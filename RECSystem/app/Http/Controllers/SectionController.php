<?php

namespace App\Http\Controllers;

use App\Models\Bureau;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Yajra\DataTables\Facades\DataTables;

class SectionController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view sections', only: ['index']),
            new Middleware('permission:edit sections', only: ['edit']),
            new Middleware('permission:create sections', only: ['create']),
            new Middleware('permission:delete sections', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Section::with('bureau')->select('*');
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $editBtn = '';
                    $deleteBtn = '';
                    
                    if (request()->user()->can('edit sections')) {
                        $editBtn = '<a href="'.route('sections.edit', $row->id).'" class="inline-block mb-2 px-5 py-2 text-white hover:text-[#101966] hover:border-[#101966] bg-[#101966] hover:bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#101966] border border-white border font-medium dark:border-[#3E3E3A] dark:hover:bg-black dark:hover:border-[#3F53E8] rounded-lg text-md leading-normal">Edit</a>';
                    }
                    
                    if (request()->user()->can('delete sections')) {
                        $deleteBtn = '<a href="javascript:void(0)" onclick="deleteSection('.$row->id.')" class="inline-block px-3 py-2 text-white hover:text-[#a10303] hover:border-[#a10303] bg-[#a10303] hover:bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#a10303] border border-white border font-medium dark:border-[#3E3E3A] dark:hover:bg-black dark:hover:border-[#3F53E8] rounded-lg text-md leading-normal">Delete</a>';
                    }
                    
                    return $editBtn.' '.$deleteBtn;
                })
                ->addColumn('bureau_name', function($row) {
                    return $row->bureau->bureau_name ?? 'N/A';
                })
                ->editColumn('created_at', function($row) {
                    return $row->created_at->format('d M, y');
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        
        return view('sections.list');
    }

    public function create()
    {
        $bureaus = Bureau::orderBy('bureau_name', 'ASC')->get();
        return view('sections.create', ['bureaus' => $bureaus]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'section_name' => [
                'required',
                'min:2',
                'max:255',
                'unique:sections,section_name',
                'regex:/^[\pL\s\-]+$/u'
            ],
            'bureau_id' => 'required|exists:bureaus,id'
        ], [
            'section_name.regex' => 'Section name may only contain letters, spaces and hyphens'
        ]);

        if ($validator->fails()) {
            return redirect()->route('sections.create')
                ->withInput()
                ->withErrors($validator);
        }

        $section = new Section();
        $section->section_name = $request->section_name;
        $section->bureau_id = $request->bureau_id;
        $section->user_id = $request->user()->id;
        $section->save();

        return redirect()->route('sections.index')
            ->with('success', 'Section added successfully');
    }

    public function edit(string $id)
    {
        $section = Section::findOrFail($id);
        $bureaus = Bureau::orderBy('bureau_name', 'ASC')->get();
        return view('sections.edit', [
            'section' => $section,
            'bureaus' => $bureaus
        ]);
    }

    public function update(Request $request, string $id)
    {
        $section = Section::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'section_name' => [
                'required',
                'min:2',
                'max:255',
                'unique:sections,section_name,' . $id,
                'regex:/^[\pL\s\-]+$/u'
            ],
            'bureau_id' => 'required|exists:bureaus,id'
        ], [
            'section_name.regex' => 'Section name may only contain letters, spaces and hyphens'
        ]);

        if ($validator->fails()) {
            return redirect()->route('sections.edit', $id)
                ->withInput()
                ->withErrors($validator);
        }

        $section->section_name = $request->section_name;
        $section->bureau_id = $request->bureau_id;
        $section->save();

        return redirect()->route('sections.index')
            ->with('success', 'Section updated successfully');
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        $section = Section::findOrFail($id);

        if ($section == null) {
            session()->flash('error', 'Section not found.');
            return response()->json(['status' => false]);
        }

        $section->delete();

        session()->flash('success', 'Section deleted successfully.');
        return response()->json(['status' => true]);
    }

}
