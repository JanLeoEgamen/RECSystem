<?php

namespace App\Http\Controllers;

use App\Models\EventAnnouncement;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class EventAnnouncementController extends Controller implements HasMiddleware

{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view event announcements', only: ['index']),
            new Middleware('permission:edit event announcements', only: ['edit']),
            new Middleware('permission:create event announcements', only: ['create']),
            new Middleware('permission:delete event announcements', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = EventAnnouncement::with('user')->select('*');
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    $buttons = '';
                    
                    if (request()->user()->can('edit event announcements')) {
                        $buttons .= '<a href="'.route('event-announcements.edit', $row->id).'" class="p-2 text-indigo-600 hover:text-white hover:bg-indigo-600 rounded-full transition-colors duration-200" title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </a>';
                    }

                    if (request()->user()->can('delete event announcements')) {
                        $buttons .= '<a href="javascript:void(0)" onclick="deleteEventAnnouncement('.$row->id.')" class="p-2 text-red-600 hover:text-white hover:bg-red-600 rounded-full transition-colors duration-200" title="Delete">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </a>';
                    }

                    return '<div class="flex space-x-2">'.$buttons.'</div>';
                })
                ->editColumn('event_date', function($row) {
                    return \Carbon\Carbon::parse($row->event_date)->format('M d, Y');
                })
                ->editColumn('image', function($row) {
                    if ($row->image) {
                        return '<img src="'.asset('images/'.$row->image).'" alt="Event Image" class="h-20 w-20 object-cover">';
                    }
                    return 'No Image';
                })
                ->addColumn('author', function($row) {
                    return $row->user->first_name . ' ' . $row->user->last_name;
                })
                ->editColumn('status', function($row) {
                    return $row->status 
                        ? '<span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Active</span>'
                        : '<span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">Inactive</span>';
                })
                ->editColumn('created_at', function($row) {
                    return $row->created_at->format('d M, y');
                })
                ->rawColumns(['action', 'status', 'image'])
                ->make(true);
        }
        
        return view('event-announcements.list');
    }
    public function create()
    {
        return view('event-announcements.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_name' => 'required|min:3',
            'event_date' => 'required|date',
            'year' => 'required|numeric',
            'caption' => 'required|min:10',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route('event-announcements.create')->withInput()->withErrors($validator);
        }

        $eventAnnouncement = new EventAnnouncement();
        $eventAnnouncement->event_name = $request->event_name;
        $eventAnnouncement->event_date = \Carbon\Carbon::parse($request->event_date);
        $eventAnnouncement->year = $request->year;
        $eventAnnouncement->caption = $request->caption;
        $eventAnnouncement->user_id = $request->user()->id;
        $eventAnnouncement->status = $request->status ?? true;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('event-announcements', 'public');
            $eventAnnouncement->image = $imagePath;
        }

        $eventAnnouncement->save();

        return redirect()->route('event-announcements.index')->with('success', 'Event announcement added successfully');
    }

    public function edit(string $id)
    {
        
        $eventAnnouncement = EventAnnouncement::findOrFail($id);
            // Convert event_date to Carbon instance if it's a string
        if (is_string($eventAnnouncement->event_date)) {
            $eventAnnouncement->event_date = \Carbon\Carbon::parse($eventAnnouncement->event_date);
        }

        return view('event-announcements.edit', [
            'eventAnnouncement' => $eventAnnouncement
        ]);
    }

    public function update(Request $request, string $id)
    {
        $eventAnnouncement = EventAnnouncement::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'event_name' => 'required|min:3',
            'event_date' => 'required|date',
            'year' => 'required|numeric',
            'caption' => 'required|min:10',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route('event-announcements.edit', $id)->withInput()->withErrors($validator);
        }

        $eventAnnouncement->event_name = $request->event_name;
        $eventAnnouncement->event_date = \Carbon\Carbon::parse($request->event_date);
        $eventAnnouncement->year = $request->year;
        $eventAnnouncement->caption = $request->caption;
        $eventAnnouncement->status = $request->status ?? $eventAnnouncement->status;

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($eventAnnouncement->image) {
                Storage::disk('public')->delete($eventAnnouncement->image);
            }
            
            $imagePath = $request->file('image')->store('event-announcements', 'public');
            $eventAnnouncement->image = $imagePath;
        }

        $eventAnnouncement->save();

        return redirect()->route('event-announcements.index')->with('success', 'Event announcement updated successfully');
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        $eventAnnouncement = EventAnnouncement::findOrFail($id);

        if ($eventAnnouncement == null) {
            session()->flash('error', 'Event announcement not found.');
            return response()->json([
                'status' => false
            ]);
        }

        // Delete image if exists
        if ($eventAnnouncement->image) {
            Storage::disk('public')->delete($eventAnnouncement->image);
        }

        $eventAnnouncement->delete();

        session()->flash('success', 'Event announcement deleted successfully.');
        return response()->json([
            'status' => true
        ]);
    }

}
