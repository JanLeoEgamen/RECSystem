<?php

namespace App\Http\Controllers;

use App\Models\Bureau;
use App\Models\Section;
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
            $data = User::with(['roles', 'assignedBureaus', 'assignedSections'])->select('*');
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    $buttons = '';
                    
                    if (request()->user()->can('edit users')) {
                        $buttons .= '<a href="'.route('users.edit', $row->id).'" class="p-2 text-indigo-600 hover:text-white hover:bg-indigo-600 rounded-full transition-colors duration-200" title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </a>';
                    }

                    if (request()->user()->can('delete users')) {
                        $buttons .= '<a href="javascript:void(0)" onclick="deleteUser('.$row->id.')" class="p-2 text-red-600 hover:text-white hover:bg-red-600 rounded-full transition-colors duration-200" title="Delete">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </a>';
                    }

                    return '<div class="flex space-x-2">'.$buttons.'</div>';
                })
                ->addColumn('name', function($row) {
                    return $row->first_name . ' ' . $row->last_name;
                })
                ->addColumn('roles', function($row) {
                    return $row->roles->pluck('name')->implode(', ');
                })
                ->addColumn('assignments', function($row) {
                    $assignments = [];
                    
                    // Get bureau assignments
                    foreach ($row->assignedBureaus as $bureau) {
                        $assignments[] = 'Bureau: ' . $bureau->bureau_name;
                    }
                    
                    // Get section assignments
                    foreach ($row->assignedSections as $section) {
                        $assignments[] = 'Section: ' . $section->section_name . ' (' . $section->bureau->bureau_name . ')';
                    }
                    
                    return implode('<br>', $assignments) ?: 'No assignments';
                })
                ->editColumn('created_at', function($row) {
                    return $row->created_at->format('d M, y');
                })
                ->rawColumns(['action', 'assignments'])
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
    $bureaus = Bureau::orderBy('bureau_name', 'ASC')->get();
    $sections = Section::with('bureau')->orderBy('section_name', 'ASC')->get();
    
    return view('users.create', [
        'roles' => $roles,
        'bureaus' => $bureaus,
        'sections' => $sections
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

            // Assign bureaus and sections
        if ($request->has('bureaus')) {
            $bureauAssignments = [];
            foreach ($request->bureaus as $bureauId) {
                $bureauAssignments[$bureauId] = ['section_id' => null];
            }
            $user->assignedBureaus()->sync($bureauAssignments);
        }

        if ($request->has('sections')) {
            $sectionAssignments = [];
            foreach ($request->sections as $sectionId) {
                $section = Section::find($sectionId);
                $sectionAssignments[$sectionId] = ['bureau_id' => $section->bureau_id];
            }
            $user->assignedSections()->sync($sectionAssignments);
        }


        return redirect()->route('users.index')->with('success', 'User added successfully');

    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    { 
    $user = User::findOrFail($id);
    $roles = Role::orderBy('name', 'ASC')->get();
    $bureaus = Bureau::orderBy('bureau_name', 'ASC')->get();
    $sections = Section::with('bureau')->orderBy('section_name', 'ASC')->get();

    $hasRoles = $user->roles->pluck('id');
    
    return view('users.edit', [
        'user' => $user,
        'roles' => $roles,
        'hasRoles' => $hasRoles,
        'bureaus' => $bureaus,
        'sections' => $sections
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

            // Update bureau and section assignments
        $bureauAssignments = [];
        if ($request->has('bureaus')) {
            foreach ($request->bureaus as $bureauId) {
                $bureauAssignments[$bureauId] = ['section_id' => null];
            }
        }
        $user->assignedBureaus()->sync($bureauAssignments);

        $sectionAssignments = [];
        if ($request->has('sections')) {
            foreach ($request->sections as $sectionId) {
                $section = Section::find($sectionId);
                $sectionAssignments[$sectionId] = ['bureau_id' => $section->bureau_id];
            }
        }
        $user->assignedSections()->sync($sectionAssignments);


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
