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
                ->addColumn('action', function($row) {
                    $buttons = '';
                    
                    if (request()->user()->can('edit bureaus')) {
                        $buttons .= '<a href="'.route('bureaus.edit', $row->id).'" class="p-2 text-indigo-600 hover:text-white hover:bg-indigo-600 rounded-full transition-colors duration-200" title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </a>';
                    }

                    if (request()->user()->can('delete bureaus')) {
                        $buttons .= '<a href="javascript:void(0)" onclick="deleteBureau('.$row->id.')" class="p-2 text-red-600 hover:text-white hover:bg-red-600 rounded-full transition-colors duration-200" title="Delete">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </a>';
                    }

                    return '<div class="flex space-x-2">'.$buttons.'</div>';
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
