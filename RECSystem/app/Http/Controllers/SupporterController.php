<?php

namespace App\Http\Controllers;

use App\Models\Supporter;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

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

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Supporter::with('user')->select('*');
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $editBtn = '';
                    $deleteBtn = '';
                    
                    if (request()->user()->can('edit supporters')) {
                        $editBtn = '<a href="'.route('supporters.edit', $row->id).'" class="inline-block mb-2 px-5 py-2 text-white hover:text-[#101966] hover:border-[#101966] bg-[#101966] hover:bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#101966] border border-white border font-medium dark:border-[#3E3E3A] dark:hover:bg-black dark:hover:border-[#3F53E8] rounded-lg text-md leading-normal">Edit</a>';
                    }
                    
                    if (request()->user()->can('delete supporters')) {
                        $deleteBtn = '<a href="javascript:void(0)" onclick="deleteSupporter('.$row->id.')" class="inline-block px-3 py-2 text-white hover:text-[#a10303] hover:border-[#a10303] bg-[#a10303] hover:bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#a10303] border border-white border font-medium dark:border-[#3E3E3A] dark:hover:bg-black dark:hover:border-[#3F53E8] rounded-lg text-md leading-normal">Delete</a>';
                    }
                    
                    return $editBtn.' '.$deleteBtn;
                })
                ->editColumn('image', function($row) {
                    if ($row->image) {
                        return '<img src="'.asset('images/'.$row->image).'" alt="Supporter Image" class="h-20 w-20 object-cover">';
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
        
        return view('supporters.list');
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
