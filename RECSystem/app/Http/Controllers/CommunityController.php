<?php

namespace App\Http\Controllers;

use App\Models\Community;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class CommunityController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view communities', only: ['index']),
            new Middleware('permission:edit communities', only: ['edit']),
            new Middleware('permission:create communities', only: ['create']),
            new Middleware('permission:delete communities', only: ['destroy']),
        ];
    }

    public function index()
    {
        $communities = Community::with('user')->latest()->paginate(10);
        return view('communities.list', [
            'communities' => $communities
        ]);
    }

    public function create()
    {
        return view('communities.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|min:10',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route('communities.create')->withInput()->withErrors($validator);
        }

        $community = new Community();
        $community->content = $request->content;
        $community->user_id = $request->user()->id;
        $community->status = $request->status ?? true;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('communities', 'public');
            $community->image = $imagePath;
        }

        $community->save();

        return redirect()->route('communities.index')->with('success', 'Community content added successfully');
    }

    public function edit(string $id)
    {
        $community = Community::findOrFail($id);
        return view('communities.edit', [
            'community' => $community
        ]);
    }

    public function update(Request $request, string $id)
    {
        $community = Community::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'content' => 'required|min:10',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route('communities.edit', $id)->withInput()->withErrors($validator);
        }

        $community->content = $request->content;
        $community->status = $request->status ?? $community->status;

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($community->image) {
                Storage::disk('public')->delete($community->image);
            }
            
            $imagePath = $request->file('image')->store('communities', 'public');
            $community->image = $imagePath;
        }

        $community->save();

        return redirect()->route('communities.index')->with('success', 'Community content updated successfully');
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        $community = Community::findOrFail($id);

        if ($community == null) {
            session()->flash('error', 'Community content not found.');
            return response()->json([
                'status' => false
            ]);
        }

        // Delete image if exists
        if ($community->image) {
            Storage::disk('public')->delete($community->image);
        }

        $community->delete();

        session()->flash('success', 'Community content deleted successfully.');
        return response()->json([
            'status' => true
        ]);
    }
}
