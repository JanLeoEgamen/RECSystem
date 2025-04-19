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
                ->addColumn('action', function($row){
                    $editBtn = '';
                    $deleteBtn = '';
                    
                    if (request()->user()->can('edit event announcements')) {
                        $editBtn = '<a href="'.route('event-announcements.edit', $row->id).'" class="inline-block mb-2 px-5 py-2 text-white hover:text-[#101966] hover:border-[#101966] bg-[#101966] hover:bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#101966] border border-white border font-medium dark:border-[#3E3E3A] dark:hover:bg-black dark:hover:border-[#3F53E8] rounded-lg text-md leading-normal">Edit</a>';
                    }
                    
                    if (request()->user()->can('delete event announcements')) {
                        $deleteBtn = '<a href="javascript:void(0)" onclick="deleteEventAnnouncement('.$row->id.')" class="inline-block px-3 py-2 text-white hover:text-[#a10303] hover:border-[#a10303] bg-[#a10303] hover:bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#a10303] border border-white border font-medium dark:border-[#3E3E3A] dark:hover:bg-black dark:hover:border-[#3F53E8] rounded-lg text-md leading-normal">Delete</a>';
                    }
                    
                    return $editBtn.' '.$deleteBtn;
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
