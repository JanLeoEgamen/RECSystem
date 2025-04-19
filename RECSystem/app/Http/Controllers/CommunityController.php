<?php

namespace App\Http\Controllers;

use App\Models\Community;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;


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

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Community::with('user')->select('*');
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $editBtn = '';
                    $deleteBtn = '';
                    
                    if (request()->user()->can('edit communities')) {
                        $editBtn = '<a href="'.route('communities.edit', $row->id).'" class="inline-block mb-2 px-5 py-2 text-white hover:text-[#101966] hover:border-[#101966] bg-[#101966] hover:bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#101966] border border-white border font-medium dark:border-[#3E3E3A] dark:hover:bg-black dark:hover:border-[#3F53E8] rounded-lg text-md leading-normal">Edit</a>';
                    }
                    
                    if (request()->user()->can('delete communities')) {
                        $deleteBtn = '<a href="javascript:void(0)" onclick="deleteCommunity('.$row->id.')" class="inline-block px-3 py-2 text-white hover:text-[#a10303] hover:border-[#a10303] bg-[#a10303] hover:bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#a10303] border border-white border font-medium dark:border-[#3E3E3A] dark:hover:bg-black dark:hover:border-[#3F53E8] rounded-lg text-md leading-normal">Delete</a>';
                    }
                    
                    return $editBtn.' '.$deleteBtn;
                })
                ->editColumn('content', function($row) {
                    return Str::limit($row->content, 50);
                })
                ->editColumn('image', function($row) {
                    if ($row->image) {
                        return '<img src="'.asset('images/'.$row->image).'" alt="Community Image" class="h-20 w-20 object-cover">';
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
        
        return view('communities.list');
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
