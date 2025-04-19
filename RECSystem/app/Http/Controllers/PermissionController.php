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
                ->addColumn('action', function($row){
                    $editBtn = '';
                    $deleteBtn = '';
                    
                    if (request()->user()->can('edit permissions')) {
                        $editBtn = '<a href="'.route('permissions.edit', $row->id).'" class="inline-block mb-2 px-5 py-2 text-white hover:text-[#101966] hover:border-[#101966] bg-[#101966] hover:bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#101966] border border-white border font-medium dark:border-[#3E3E3A] dark:hover:bg-black dark:hover:border-[#3F53E8] rounded-lg text-md leading-normal">Edit</a>';
                    }
                    
                    if (request()->user()->can('delete permissions')) {
                        $deleteBtn = '<a href="javascript:void(0)" onclick="deletePermission('.$row->id.')" class="inline-block px-3 py-2 text-white hover:text-[#a10303] hover:border-[#a10303] bg-[#a10303] hover:bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#a10303] border border-white border font-medium dark:border-[#3E3E3A] dark:hover:bg-black dark:hover:border-[#3F53E8] rounded-lg text-md leading-normal">Delete</a>';
                    }
                    
                    return $editBtn.' '.$deleteBtn;
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
