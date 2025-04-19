<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return[
            new Middleware('permission:view users', only: ['index']),
            new Middleware('permission:edit users', only: ['edit']),    
            new Middleware('permission:create users', only: ['create']),
            new Middleware('permission:delete users', only: ['destroy']),
        
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    if ($request->ajax()) {
        $data = User::with('roles')->select('*');
        
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $editBtn = '';
                $deleteBtn = '';
                
                if (request()->user()->can('edit users')) {
                    $editBtn = '<a href="'.route('users.edit', $row->id).'" class="inline-block mb-2 px-5 py-2 text-white hover:text-[#101966] hover:border-[#101966] bg-[#101966] hover:bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#101966] border border-white border font-medium dark:border-[#3E3E3A] dark:hover:bg-black dark:hover:border-[#3F53E8] rounded-lg text-md leading-normal">Edit</a>';
                }
                
                if (request()->user()->can('delete users')) {
                    $deleteBtn = '<a href="javascript:void(0)" onclick="deleteUser('.$row->id.')" class="inline-block px-3 py-2 text-white hover:text-[#a10303] hover:border-[#a10303] bg-[#a10303] hover:bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#a10303] border border-white border font-medium dark:border-[#3E3E3A] dark:hover:bg-black dark:hover:border-[#3F53E8] rounded-lg text-md leading-normal">Delete</a>';
                }
                
                return $editBtn.' '.$deleteBtn;
            })
            ->addColumn('name', function($row) {
                return $row->first_name . ' ' . $row->last_name;
            })
            ->addColumn('roles', function($row) {
                return $row->roles->pluck('name')->implode(', ');
            })
            ->editColumn('created_at', function($row) {
                return $row->created_at->format('d M, y');
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    
    return view('users.list');
}
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::orderBy('name', 'ASC')->get();
        return view('users.create',[
            'roles' => $roles
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(),[
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5|same:confirm_password',
            'confirm_password' => 'required',
        ]);
        
        if($validator->fails()){
            return redirect()->route('users.create')->withInput()->withErrors($validator);
        }
        
        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->birthdate = $request->birthdate;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        $user->syncRoles($request->role);

        return redirect()->route('users.index')->with('success', 'User added successfully');

    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    { 
        $user = User::findOrFail($id);
        $roles = Role::orderBy('name', 'ASC')->get();

        $hasRoles = $user->roles->pluck('id');
        
        return view('users.edit', [
            'user' => $user,
            'roles' => $roles,
            'hasRoles' =>$hasRoles
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);


        $validator = Validator::make($request->all(),[
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,'.$id.',id'
        ]);

        if($validator->fails()){
            return redirect()->route('users.edit', $id)->withInput()->withErrors($validator);
        }
        
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->birthdate = $request->birthdate;
        $user->email = $request->email;
        $user->save();

        $user->syncRoles($request->role);

        return redirect()->route('users.index')->with('success', 'User updated successfully');

        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->id;
        $user = User::findOrFail($id);

        if($user == null) {
            session()->flash('error', 'User not found.');
            return response()->json([
                'status' => false

            ]);
        }

        $user->delete();

        session()->flash('success', 'User deleted successfully.');
        return response()->json([
            'status' => true

        ]);

    }


    
}
