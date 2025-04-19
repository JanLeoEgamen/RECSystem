<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return[
            new Middleware('permission:view roles', only: ['index']),
            new Middleware('permission:edit roles', only: ['edit']),
            new Middleware('permission:create roles', only: ['create']),
            new Middleware('permission:delete roles', only: ['destroy']),
            
        ];
    }

    //This method will show roles page
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Role::with('permissions')->select('*');
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $editBtn = '';
                    $deleteBtn = '';
                    
                    if (request()->user()->can('edit roles')) {
                        $editBtn = '<a href="'.route('roles.edit', $row->id).'" class="inline-block mb-2 px-5 py-2 text-white hover:text-[#101966] hover:border-[#101966] bg-[#101966] hover:bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#101966] border border-white border font-medium dark:border-[#3E3E3A] dark:hover:bg-black dark:hover:border-[#3F53E8] rounded-lg text-md leading-normal">Edit</a>';
                    }
                    
                    if (request()->user()->can('delete roles')) {
                        $deleteBtn = '<a href="javascript:void(0)" onclick="deleteRole('.$row->id.')" class="inline-block px-3 py-2 text-white hover:text-[#a10303] hover:border-[#a10303] bg-[#a10303] hover:bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#a10303] border border-white border font-medium dark:border-[#3E3E3A] dark:hover:bg-black dark:hover:border-[#3F53E8] rounded-lg text-md leading-normal">Delete</a>';
                    }
                    
                    return $editBtn.' '.$deleteBtn;
                })
                ->addColumn('permissions', function($row) {
                    return $row->permissions->pluck('name')->implode(', ');
                })
                ->editColumn('created_at', function($row) {
                    return $row->created_at->format('d M, y');
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        
        return view('roles.list');
    }
     //This method will create roles page
     public function create(){
        $permissions = Permission::orderBy('name', 'ASC')->get();

        return view('roles.create', [
            'permissions' => $permissions
        ]);
     }

      //This method will insert role in db
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required|unique:roles|min:3'
        ]);

        if ($validator->passes()){

            $role = Role::create(['name' => $request->name]);

            if(!empty($request->permission)){
                foreach($request ->permission as $name){
                    $role->givePermissionTo($name);
                }
            }

            return redirect()->route('roles.index')->with('success', 'Role added successfully');

        } else{
            return redirect()->route('roles.create')->withInput()->withErrors($validator);
        }

    }

    //This method is for editing the role
    public function edit($id){
        $role = Role::findOrFail($id);
        $hasPermissions = $role->permissions->pluck('name');
        $permissions = Permission::orderBy('name', 'ASC')->get();

        return view('roles.edit', [
            'permissions' => $permissions,
            'hasPermissions' => $hasPermissions,
            'role' => $role
        ]);
    }

    //This method is for updating the role
    public function update($id, Request $request){
        $role = Role::findOrFail($id);
        
        $validator = Validator::make($request->all(),[
            'name' => 'required|unique:roles,name,'.$id.',id'
        ]);

        if ($validator->passes()){

            //$role = Role::create(['name' => $request->name]);
            $role->name = $request->name;
            $role->save();

            if(!empty($request->permission)){
                $role->syncPermissions($request->permission);
            } else{
                $role->syncPermissions([]);

            }

            return redirect()->route('roles.index')->with('success', 'Role updated successfully');

        } else{
            return redirect()->route('roles.edit', $id)->withInput()->withErrors($validator);
        }
    }

    //method for deleting a role 
    public function destroy(Request $request){
        $id = $request->id;
        $role = Role::findOrFail($id);

        if($role == null) {
            session()->flash('error', 'Role not found.');
            return response()->json([
                'status' => false

            ]);
        }

        $role->delete();

        session()->flash('success', 'Role deleted successfully.');
        return response()->json([
            'status' => true

        ]);

    }

}
