<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return[
            new Middleware('permission:view permissions', only: ['index']),
            new Middleware('permission:edit permissions', only: ['edit']),
            new Middleware('permission:create permissions', only: ['create']),
            new Middleware('permission:delete permissions', only: ['destroy']),
            
        ];
    }

    // This method is for showing permissions page
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Permission::select('*');
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    $buttons = '';
                    
                    if (request()->user()->can('edit permissions')) {
                        $buttons .= '<a href="'.route('permissions.edit', $row->id).'" class="p-2 text-indigo-600 hover:text-white hover:bg-indigo-600 rounded-full transition-colors duration-200" title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </a>';
                    }

                    if (request()->user()->can('delete permissions')) {
                        $buttons .= '<a href="javascript:void(0)" onclick="deletePermission('.$row->id.')" class="p-2 text-red-600 hover:text-white hover:bg-red-600 rounded-full transition-colors duration-200" title="Delete">
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
        
        return view('permissions.list');
    }
    // This method is for showing create permission page
    public function create(){
        return view('permissions.create');

    }

    // This method is for inserting a permission in db
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required|unique:permissions|min:3'
        ]);

        if ($validator->passes()){
            Permission::create(['name' => $request->name]);

            return redirect()->route('permissions.index')->with('success', 'Permission added, successfully');

        } else{
            return redirect()->route('permissions.create')->withInput()->withErrors($validator);
        }

    }

    // This method is for showing edit permission page
    public function edit($id){
        $permission = Permission::findorFail($id);
        return view('permissions.edit',[
            'permission' => $permission
        ]);


    }

    // This method is for updating a permission
    public function update($id, Request $request){
        $permission = Permission::findorFail($id);

        $validator = Validator::make($request->all(),[
            'name' => 'required|min:3|unique:permissions,name,'.$id.',id'
        ]);

        if ($validator->passes()){
            //Permission::create(['name' => $request->name]);
            $permission->name = $request->name;
            $permission->save();

            return redirect()->route('permissions.index')->with('success', 'Permission updated successfully');

        } else{
            return redirect()->route('permissions.edit', $id)->withInput()->withErrors($validator);
        }

    }

    // This method is for deleting permissions =in db
    public function destroy(Request $request){
        $id = $request->id;

        $permission = Permission::find($id);

        if ($permission == null){
            session()->flash('error', 'Permission not found');
            return response()->json([
                'status' => false
            ]);
        }
        $permission->delete();

        session()->flash('success', 'Permission deleted successfully');
        return response()->json([
            'status' => true
        ]);
    

    }
}
