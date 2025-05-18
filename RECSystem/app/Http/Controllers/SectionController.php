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
                ->addColumn('action', function($row) {
                    $buttons = '';
                    
                    if (request()->user()->can('edit sections')) {
                        $buttons .= '<a href="'.route('sections.edit', $row->id).'" class="p-2 text-indigo-600 hover:text-white hover:bg-indigo-600 rounded-full transition-colors duration-200" title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </a>';
                    }

                    if (request()->user()->can('delete sections')) {
                        $buttons .= '<a href="javascript:void(0)" onclick="deleteSection('.$row->id.')" class="p-2 text-red-600 hover:text-white hover:bg-red-600 rounded-full transition-colors duration-200" title="Delete">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </a>';
                    }

                    return '<div class="flex space-x-2">'.$buttons.'</div>';
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
