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
                ->addColumn('action', function($row) {
                    $buttons = '';
                    
                    if (request()->user()->can('edit roles')) {
                        $buttons .= '<a href="'.route('roles.edit', $row->id).'" class="p-2 text-indigo-600 hover:text-white hover:bg-indigo-600 rounded-full transition-colors duration-200" title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </a>';
                    }

                    if (request()->user()->can('delete roles')) {
                        $buttons .= '<a href="javascript:void(0)" onclick="deleteRole('.$row->id.')" class="p-2 text-red-600 hover:text-white hover:bg-red-600 rounded-full transition-colors duration-200" title="Delete">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </a>';
                    }

                    return '<div class="flex space-x-2">'.$buttons.'</div>';
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
     public function create() {
        $permissions = Permission::orderBy('name', 'ASC')->get();
    
        // Group permissions by object (last word in permission name)
        $groupedPermissions = $permissions->groupBy(function ($permission) {
            // Example: from 'create applicants', get 'applicants'
            $words = explode(' ', $permission->name);
            return end($words); // last word as group key
        });
    
        return view('roles.create', [
            'groupedPermissions' => $groupedPermissions
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
        $permissions = Permission::orderBy('name', 'ASC')->get();
        $hasPermissions = $role->permissions;
    
        $groupedPermissions = $permissions->groupBy(function ($permission) {
            $words = explode(' ', $permission->name);
            return end($words);
        });
    
        return view('roles.edit', compact('role', 'groupedPermissions', 'hasPermissions'));

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
