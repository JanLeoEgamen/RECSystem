<?php

namespace App\Http\Controllers;

use App\Models\MainCarousel;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

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

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = MainCarousel::with('user')->select('*');
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $editBtn = '';
                    $deleteBtn = '';
                    
                    if (request()->user()->can('edit main carousels')) {
                        $editBtn = '<a href="'.route('main-carousels.edit', $row->id).'" class="inline-block mb-2 px-5 py-2 text-white hover:text-[#101966] hover:border-[#101966] bg-[#101966] hover:bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#101966] border border-white border font-medium dark:border-[#3E3E3A] dark:hover:bg-black dark:hover:border-[#3F53E8] rounded-lg text-md leading-normal">Edit</a>';
                    }
                    
                    if (request()->user()->can('delete main carousels')) {
                        $deleteBtn = '<a href="javascript:void(0)" onclick="deleteMainCarousel('.$row->id.')" class="inline-block px-3 py-2 text-white hover:text-[#a10303] hover:border-[#a10303] bg-[#a10303] hover:bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#a10303] border border-white border font-medium dark:border-[#3E3E3A] dark:hover:bg-black dark:hover:border-[#3F53E8] rounded-lg text-md leading-normal">Delete</a>';
                    }
                    
                    return $editBtn.' '.$deleteBtn;
                })
                ->editColumn('content', function($row) {
                    return Str::limit($row->content, 50);
                })
                ->editColumn('image', function($row) {
                    if ($row->image) {
                        return '<img src="'.asset('images/'.$row->image).'" alt="Carousel Image" class="h-20 w-20 object-cover">';
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
        
        return view('main-carousels.list');
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
