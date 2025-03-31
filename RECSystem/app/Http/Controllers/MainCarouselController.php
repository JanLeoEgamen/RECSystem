<?php

namespace App\Http\Controllers;

use App\Models\MainCarousel;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class MainCarouselController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view main carousels', only: ['index']),
            new Middleware('permission:edit main carousels', only: ['edit']),
            new Middleware('permission:create main carousels', only: ['create']),
            new Middleware('permission:delete main carousels', only: ['destroy']),
        ];
    }

    public function index()
    {
        $mainCarousels = MainCarousel::with('user')->latest()->paginate(10);
        return view('main-carousels.list', [
            'mainCarousels' => $mainCarousels
        ]);
    }

    public function create()
    {
        return view('main-carousels.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'title' => 'required|min:3',
            'content' => 'required|min:10',
            'status' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route('main-carousels.create')->withInput()->withErrors($validator);
        }

        $mainCarousel = new MainCarousel();
        $mainCarousel->title = $request->title;
        $mainCarousel->content = $request->content;
        $mainCarousel->user_id = $request->user()->id;
        $mainCarousel->status = $request->status ?? true;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('main-carousels', 'public');
            $mainCarousel->image = $imagePath;
        }

        $mainCarousel->save();

        return redirect()->route('main-carousels.index')->with('success', 'Main carousel item added successfully');
    }

    public function edit(string $id)
    {
        $mainCarousel = MainCarousel::findOrFail($id);
        return view('main-carousels.edit', [
            'mainCarousel' => $mainCarousel
        ]);
    }

    public function update(Request $request, string $id)
    {
        $mainCarousel = MainCarousel::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'title' => 'required|min:3',
            'content' => 'required|min:10',
            'status' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route('main-carousels.edit', $id)->withInput()->withErrors($validator);
        }

        $mainCarousel->title = $request->title;
        $mainCarousel->content = $request->content;
        $mainCarousel->status = $request->status ?? $mainCarousel->status;

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($mainCarousel->image) {
                Storage::disk('public')->delete($mainCarousel->image);
            }
            
            $imagePath = $request->file('image')->store('main-carousels', 'public');
            $mainCarousel->image = $imagePath;
        }

        $mainCarousel->save();

        return redirect()->route('main-carousels.index')->with('success', 'Main carousel item updated successfully');
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        $mainCarousel = MainCarousel::findOrFail($id);

        if ($mainCarousel == null) {
            session()->flash('error', 'Main carousel item not found.');
            return response()->json([
                'status' => false
            ]);
        }

        // Delete image if exists
        if ($mainCarousel->image) {
            Storage::disk('public')->delete($mainCarousel->image);
        }

        $mainCarousel->delete();

        session()->flash('success', 'Main carousel item deleted successfully.');
        return response()->json([
            'status' => true
        ]);
    }
}
