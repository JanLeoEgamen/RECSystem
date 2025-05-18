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
                ->addColumn('action', function($row) {
                    $buttons = '';
                    
                    if (request()->user()->can('edit supporters')) {
                        $buttons .= '<a href="'.route('supporters.edit', $row->id).'" class="p-2 text-indigo-600 hover:text-white hover:bg-indigo-600 rounded-full transition-colors duration-200" title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </a>';
                    }

                    if (request()->user()->can('delete supporters')) {
                        $buttons .= '<a href="javascript:void(0)" onclick="deleteSupporter('.$row->id.')" class="p-2 text-red-600 hover:text-white hover:bg-red-600 rounded-full transition-colors duration-200" title="Delete">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </a>';
                    }

                    return '<div class="flex space-x-2">'.$buttons.'</div>';
                })
                ->addColumn('author', function($row) {
                    return $row->user->first_name . ' ' . $row->user->last_name;
                })
                ->editColumn('image', function($row) {
                    if ($row->image) {
                        return '<img src="'.asset('images/'.$row->image).'" alt="Supporter Image" class="h-20 w-20 object-cover">';
                    }
                    return 'No Image';
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
