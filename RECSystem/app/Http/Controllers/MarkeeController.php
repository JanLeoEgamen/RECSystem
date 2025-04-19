<?php

namespace App\Http\Controllers;

use App\Models\Markee;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class MarkeeController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view markees', only: ['index']),
            new Middleware('permission:edit markees', only: ['edit']),
            new Middleware('permission:create markees', only: ['create']),
            new Middleware('permission:delete markees', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Markee::with('user')->select('*');
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $editBtn = '';
                    $deleteBtn = '';
                    
                    if (request()->user()->can('edit markees')) {
                        $editBtn = '<a href="'.route('markees.edit', $row->id).'" class="inline-block mb-2 px-5 py-2 text-white hover:text-[#101966] hover:border-[#101966] bg-[#101966] hover:bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#101966] border border-white border font-medium dark:border-[#3E3E3A] dark:hover:bg-black dark:hover:border-[#3F53E8] rounded-lg text-md leading-normal">Edit</a>';
                    }
                    
                    if (request()->user()->can('delete markees')) {
                        $deleteBtn = '<a href="javascript:void(0)" onclick="deleteMarkee('.$row->id.')" class="inline-block px-3 py-2 text-white hover:text-[#a10303] hover:border-[#a10303] bg-[#a10303] hover:bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#a10303] border border-white border font-medium dark:border-[#3E3E3A] dark:hover:bg-black dark:hover:border-[#3F53E8] rounded-lg text-md leading-normal">Delete</a>';
                    }
                    
                    return $editBtn.' '.$deleteBtn;
                })
                ->editColumn('status', function($row) {
                    return $row->status 
                        ? '<span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Active</span>'
                        : '<span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">Inactive</span>';
                })
                ->editColumn('created_at', function($row) {
                    return $row->created_at->format('d M, y');
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        
        return view('markees.list');
    }

    public function create()
    {
        return view('markees.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'header' => 'required|min:3|max:255',
            'content' => 'required|min:10',
            'status' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route('markees.create')
                ->withInput()
                ->withErrors($validator);
        }

        $markee = new Markee();
        $markee->header = $request->header;
        $markee->content = $request->content;
        $markee->user_id = $request->user()->id;
        $markee->status = $request->status ?? true;
        $markee->save();

        return redirect()->route('markees.index')
            ->with('success', 'Markee added successfully');
    }

    public function edit(string $id)
    {
        $markee = Markee::findOrFail($id);
        return view('markees.edit', [
            'markee' => $markee
        ]);
    } 

    public function update(Request $request, string $id)
    {
        $markee = Markee::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'header' => 'required|min:3|max:255',
            'content' => 'required|min:10',
            'status' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route('markees.edit', $id)
                ->withInput()
                ->withErrors($validator);
        }

        $markee->header = $request->header;
        $markee->content = $request->content;
        $markee->status = $request->status ?? $markee->status;
        $markee->save();

        return redirect()->route('markees.index')
            ->with('success', 'Markee updated successfully');
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        $markee = Markee::findOrFail($id);

        if ($markee == null) {
            session()->flash('error', 'Markee not found.');
            return response()->json([
                'status' => false
            ]);
        }

        $markee->delete();

        session()->flash('success', 'Markee deleted successfully.');
        return response()->json([
            'status' => true
        ]);
    }

}
