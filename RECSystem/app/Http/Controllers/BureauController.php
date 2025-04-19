<?php

namespace App\Http\Controllers;

use App\Models\Bureau;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Yajra\DataTables\Facades\DataTables;

class BureauController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view bureaus', only: ['index']),
            new Middleware('permission:edit bureaus', only: ['edit']),
            new Middleware('permission:create bureaus', only: ['create']),
            new Middleware('permission:delete bureaus', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Bureau::select('*');
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $editBtn = '';
                    $deleteBtn = '';
                    
                    if (request()->user()->can('edit bureaus')) {
                        $editBtn = '<a href="'.route('bureaus.edit', $row->id).'" class="inline-block mb-2 px-5 py-2 text-white hover:text-[#101966] hover:border-[#101966] bg-[#101966] hover:bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#101966] border border-white border font-medium dark:border-[#3E3E3A] dark:hover:bg-black dark:hover:border-[#3F53E8] rounded-lg text-md leading-normal">Edit</a>';
                    }
                    
                    if (request()->user()->can('delete bureaus')) {
                        $deleteBtn = '<a href="javascript:void(0)" onclick="deleteBureau('.$row->id.')" class="inline-block px-3 py-2 text-white hover:text-[#a10303] hover:border-[#a10303] bg-[#a10303] hover:bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#a10303] border border-white border font-medium dark:border-[#3E3E3A] dark:hover:bg-black dark:hover:border-[#3F53E8] rounded-lg text-md leading-normal">Delete</a>';
                    }
                    
                    return $editBtn.' '.$deleteBtn;
                })
                ->editColumn('created_at', function($row) {
                    return $row->created_at->format('d M, y');
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        
        return view('bureaus.list');
    }

    public function create()
    {
        return view('bureaus.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bureau_name' => [
                'required',
                'min:2',
                'max:255',
                'unique:bureaus,bureau_name',
                'regex:/^[\pL\s\-]+$/u'
            ]
        ], [
            'bureau_name.regex' => 'Bureau name may only contain letters, spaces and hyphens'
        ]);

        if ($validator->fails()) {
            return redirect()->route('bureaus.create')
                ->withInput()
                ->withErrors($validator);
        }

        $bureau = new Bureau();
        $bureau->bureau_name = $request->bureau_name;
        $bureau->user_id = $request->user()->id;
        $bureau->save();

        return redirect()->route('bureaus.index')
            ->with('success', 'Bureau added successfully');
    }

    public function edit(string $id)
    {
        $bureau = Bureau::findOrFail($id);
        return view('bureaus.edit', ['bureau' => $bureau]);
    }

    public function update(Request $request, string $id)
    {
        $bureau = Bureau::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'bureau_name' => [
                'required',
                'min:2',
                'max:255',
                'unique:bureaus,bureau_name,' . $id,
                'regex:/^[\pL\s\-]+$/u'
            ]
        ], [
            'bureau_name.regex' => 'Bureau name may only contain letters, spaces and hyphens'
        ]);

        if ($validator->fails()) {
            return redirect()->route('bureaus.edit', $id)
                ->withInput()
                ->withErrors($validator);
        }

        $bureau->bureau_name = $request->bureau_name;
        $bureau->save();

        return redirect()->route('bureaus.index')
            ->with('success', 'Bureau updated successfully');
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        $bureau = Bureau::findOrFail($id);

        if ($bureau == null) {
            session()->flash('error', 'Bureau not found.');
            return response()->json(['status' => false]);
        }

        $bureau->delete();

        session()->flash('success', 'Bureau deleted successfully.');
        return response()->json(['status' => true]);
    }

}
