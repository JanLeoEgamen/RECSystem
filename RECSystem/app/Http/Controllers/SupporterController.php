<?php

namespace App\Http\Controllers;

use App\Models\Supporter;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;


class SupporterController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view supporters', only: ['index']),
            new Middleware('permission:edit supporters', only: ['edit']),
            new Middleware('permission:create supporters', only: ['create']),
            new Middleware('permission:delete supporters', only: ['destroy']),
        ];
    }

    public function index()
    {
        $supporters = Supporter::with('user')->latest()->paginate(10);
        return view('supporters.list', [
            'supporters' => $supporters
        ]);
    }

    public function create()
    {
        return view('supporters.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'supporter_name' => 'required|min:3',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route('supporters.create')->withInput()->withErrors($validator);
        }

        $supporter = new Supporter();
        $supporter->supporter_name = $request->supporter_name;
        $supporter->user_id = $request->user()->id;
        $supporter->status = $request->status ?? true;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('supporters', 'public');
            $supporter->image = $imagePath;
        }

        $supporter->save();

        return redirect()->route('supporters.index')->with('success', 'Supporter added successfully');
    }

    public function edit(string $id)
    {
        $supporter = Supporter::findOrFail($id);
        return view('supporters.edit', [
            'supporter' => $supporter
        ]);
    }

    public function update(Request $request, string $id)
    {
        $supporter = Supporter::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'supporter_name' => 'required|min:3',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route('supporters.edit', $id)->withInput()->withErrors($validator);
        }

        $supporter->supporter_name = $request->supporter_name;
        $supporter->status = $request->status ?? $supporter->status;

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($supporter->image) {
                Storage::disk('public')->delete($supporter->image);
            }
            
            $imagePath = $request->file('image')->store('supporters', 'public');
            $supporter->image = $imagePath;
        }

        $supporter->save();

        return redirect()->route('supporters.index')->with('success', 'Supporter updated successfully');
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        $supporter = Supporter::findOrFail($id);

        if ($supporter == null) {
            session()->flash('error', 'Supporter not found.');
            return response()->json([
                'status' => false
            ]);
        }

        // Delete image if exists
        if ($supporter->image) {
            Storage::disk('public')->delete($supporter->image);
        }

        $supporter->delete();

        session()->flash('success', 'Supporter deleted successfully.');
        return response()->json([
            'status' => true
        ]);
    }

}
