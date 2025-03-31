<?php

namespace App\Http\Controllers;

use App\Models\EventAnnouncement;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

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

    public function index()
    {
        $eventAnnouncements = EventAnnouncement::with('user')->latest()->paginate(10);
        return view('event-announcements.list', [
            'eventAnnouncements' => $eventAnnouncements
        ]);
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
