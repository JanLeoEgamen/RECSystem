<?php

namespace App\Http\Controllers;

use App\Models\MembershipType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Yajra\DataTables\Facades\DataTables;

class MembershipTypeController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view membership types', only: ['index']),
            new Middleware('permission:edit membership types', only: ['edit']),
            new Middleware('permission:create membership types', only: ['create']),
            new Middleware('permission:delete membership types', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = MembershipType::select('*');
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $editBtn = '';
                    $deleteBtn = '';
                    
                    if (request()->user()->can('edit membership types')) {
                        $editBtn = '<a href="'.route('membership-types.edit', $row->id).'" class="inline-block mb-2 px-5 py-2 text-white hover:text-[#101966] hover:border-[#101966] bg-[#101966] hover:bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#101966] border border-white border font-medium dark:border-[#3E3E3A] dark:hover:bg-black dark:hover:border-[#3F53E8] rounded-lg text-md leading-normal">Edit</a>';
                    }
                    
                    if (request()->user()->can('delete membership types')) {
                        $deleteBtn = '<a href="javascript:void(0)" onclick="deleteMembershipType('.$row->id.')" class="inline-block px-3 py-2 text-white hover:text-[#a10303] hover:border-[#a10303] bg-[#a10303] hover:bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#a10303] border border-white border font-medium dark:border-[#3E3E3A] dark:hover:bg-black dark:hover:border-[#3F53E8] rounded-lg text-md leading-normal">Delete</a>';
                    }
                    
                    return $editBtn.' '.$deleteBtn;
                })
                ->editColumn('created_at', function($row) {
                    return $row->created_at->format('d M, y');
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        
        return view('membership-types.list');
    }

    public function create()
    {
        return view('membership-types.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type_name' => [
                'required',
                'min:2',
                'max:255',
                'unique:membership_types,type_name',
                'regex:/^[\pL\s\-]+$/u'
            ]
        ], [
            'type_name.regex' => 'Type name may only contain letters, spaces and hyphens'
        ]);

        if ($validator->fails()) {
            return redirect()->route('membership-types.create')
                ->withInput()
                ->withErrors($validator);
        }

        $membershipType = new MembershipType();
        $membershipType->type_name = $request->type_name;
        $membershipType->user_id = $request->user()->id;
        $membershipType->save();

        return redirect()->route('membership-types.index')
            ->with('success', 'Membership type added successfully');
    }

    public function edit(string $id)
    {
        $membershipType = MembershipType::findOrFail($id);
        return view('membership-types.edit', ['membershipType' => $membershipType]);
    }

    public function update(Request $request, string $id)
    {
        $membershipType = MembershipType::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'type_name' => [
                'required',
                'min:2',
                'max:255',
                'unique:membership_types,type_name,' . $id,
                'regex:/^[\pL\s\-]+$/u'
            ]
        ], [
            'type_name.regex' => 'Type name may only contain letters, spaces and hyphens'
        ]);

        if ($validator->fails()) {
            return redirect()->route('membership-types.edit', $id)
                ->withInput()
                ->withErrors($validator);
        }

        $membershipType->type_name = $request->type_name;
        $membershipType->save();

        return redirect()->route('membership-types.index')
            ->with('success', 'Membership type updated successfully');
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        $membershipType = MembershipType::findOrFail($id);

        if ($membershipType == null) {
            session()->flash('error', 'Membership type not found.');
            return response()->json(['status' => false]);
        }

        $membershipType->delete();

        session()->flash('success', 'Membership type deleted successfully.');
        return response()->json(['status' => true]);
    }

}
