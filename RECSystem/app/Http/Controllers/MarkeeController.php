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
                ->addColumn('action', function($row) {
                    $buttons = '';
                    
                    if (request()->user()->can('edit markees')) {
                        $buttons .= '<a href="'.route('markees.edit', $row->id).'" class="p-2 text-indigo-600 hover:text-white hover:bg-indigo-600 rounded-full transition-colors duration-200" title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </a>';
                    }

                    if (request()->user()->can('delete markees')) {
                        $buttons .= '<a href="javascript:void(0)" onclick="deleteMarkee('.$row->id.')" class="p-2 text-red-600 hover:text-white hover:bg-red-600 rounded-full transition-colors duration-200" title="Delete">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </a>';
                    }

                    return '<div class="flex space-x-2">'.$buttons.'</div>';
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
