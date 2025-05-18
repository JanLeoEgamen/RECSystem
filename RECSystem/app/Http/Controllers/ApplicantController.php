<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\Member;
use App\Models\MembershipType;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Yajra\DataTables\Facades\DataTables;

class ApplicantController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return[
            new Middleware('permission:view applicants', only: ['index', 'show']),
            new Middleware('permission:edit applicants', only: ['edit']),    
            new Middleware('permission:create applicants', only: ['create']),
            new Middleware('permission:delete applicants', only: ['destroy']),
            new Middleware('permission:assess applicants', only: ['assess']),

        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Applicant::where('status', 'pending')->select('*');
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    $buttons = '';
                    
                    if (request()->user()->can('view applicants')) {
                        $buttons .= '<a href="'.route('applicants.show', $row->id).'" class="p-2 text-blue-600 hover:text-white hover:bg-blue-600 rounded-full transition-colors duration-200" title="View">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </a>';
                    }

                    if (request()->user()->can('edit applicants')) {
                        $buttons .= '<a href="'.route('applicants.edit', $row->id).'" class="p-2 text-indigo-600 hover:text-white hover:bg-indigo-600 rounded-full transition-colors duration-200" title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </a>';
                    }

                    if (request()->user()->can('delete applicants')) {
                        $buttons .= '<a href="javascript:void(0)" onclick="deleteApplicant('.$row->id.')" class="p-2 text-red-600 hover:text-white hover:bg-red-600 rounded-full transition-colors duration-200" title="Delete">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </a>';
                    }

                    if (request()->user()->can('assess applicants')) {
                        $buttons .= '<a href="'.route('applicants.assess', $row->id).'" class="p-2 text-green-600 hover:text-white hover:bg-green-600 rounded-full transition-colors duration-200" title="Assess">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </a>';
                    }

                    return '<div class="flex space-x-2">'.$buttons.'</div>';
                })
                ->addColumn('full_name', function($row) {
                    $name = $row->first_name . ' ' . $row->last_name;
                    if ($row->middle_name) {
                        $name .= ' ' . $row->middle_name;
                    }
                    if ($row->suffix) {
                        $name .= ' ' . $row->suffix;
                    }
                    return $name;
                })
                ->editColumn('created_at', function($row) {
                    return $row->created_at->format('d M, y');
                })
                ->editColumn('birthdate', function($row) {
                    return $row->birthdate ? \Carbon\Carbon::parse($row->birthdate)->format('d M, y') : '';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        
        return view('applicants.list');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('applicants.create');
    }


    public function show(string $id)
    {
        $applicant = Applicant::findOrFail($id);
        return view('applicants.view', ['applicant' => $applicant]);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|min:2',
            'last_name' => 'required|min:2',
            'sex' => 'required',
            'birthdate' => 'required|date',
            'civil_status' => 'required',
            'citizenship' => 'required',
            'email_address' => 'required|email',
            'cellphone_no' => 'required',
            'emergency_contact' => 'required',
            'emergency_contact_number' => 'required',
            'relationship' => 'required',
            'house_building_number_name' => 'required',
            'street_address' => 'required',
            'region' => 'required',
            'province' => 'required',
            'municipality' => 'required',
            'barangay' => 'required',
            'zip_code' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('applicants.create')->withInput()->withErrors($validator);
        }

        $applicant = new Applicant();
        $applicant->status = 'pending';
        $this->saveApplicantData($applicant, $request);

        return redirect()->route('applicants.index')->with('success', 'Applicant added successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $applicant = Applicant::findOrFail($id);
        return view('applicants.edit', ['applicant' => $applicant]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $applicant = Applicant::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|min:2',
            'last_name' => 'required|min:2',
            'sex' => 'required',
            'birthdate' => 'required|date',
            'civil_status' => 'required',
            'citizenship' => 'required',
            'email_address' => 'required|email',
            'cellphone_no' => 'required',
            'emergency_contact' => 'required',
            'emergency_contact_number' => 'required',
            'relationship' => 'required',
            'house_building_number_name' => 'required',
            'street_address' => 'required',
            'region' => 'required',
            'province' => 'required',
            'municipality' => 'required',
            'barangay' => 'required',
            'zip_code' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('applicants.edit', $id)->withInput()->withErrors($validator);
        }

        $this->saveApplicantData($applicant, $request);

        return redirect()->route('applicants.index')->with('success', 'Applicant updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->id;
        $applicant = Applicant::findOrFail($id);

        if ($applicant == null) {
            session()->flash('error', 'Applicant not found.');
            return response()->json(['status' => false]);
        }

        $applicant->delete();

        session()->flash('success', 'Applicant deleted successfully.');
        return response()->json(['status' => true]);
    }


    /**
     * Show the assessment form.
     */
    public function assess(string $id)
    {
        $applicant = Applicant::findOrFail($id);
        $membershipTypes = MembershipType::all();
        $sections = Section::all();
        
        return view('applicants.assess', [
            'applicant' => $applicant,
            'membershipTypes' => $membershipTypes,
            'sections' => $sections
        ]);
    }

    /**
     * Approve applicant and create member record.
     */
    public function approve(Request $request, string $id)
    {
        $applicant = Applicant::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'rec_number' => 'required|unique:members,rec_number',
            'membership_type_id' => 'required|exists:membership_types,id',
            'section_id' => 'nullable|exists:sections,id',
            'membership_start' => 'required|date',
            'membership_end' => 'required_if:is_lifetime_member,0|date|after:membership_start',
            'is_lifetime_member' => 'boolean',
            'licence_class' => 'nullable',
            'license_number' => 'nullable',
            'license_expiration_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return redirect()->route('applicants.assess', $id)
                            ->withErrors($validator)
                            ->withInput();
        }

        // Create member from applicant
        $member = new Member();
        $member->fill($applicant->toArray()); // Copy all applicant data
        
        // Add membership specific fields
        $member->rec_number = $request->rec_number;
        $member->membership_type_id = $request->membership_type_id;
        $member->section_id = $request->section_id;
        $member->membership_start = $request->membership_start;
        $member->membership_end = $request->is_lifetime_member ? null : $request->membership_end;
        $member->is_lifetime_member = $request->is_lifetime_member ?? false;
        $member->last_renewal_date = now();
        $member->licence_class = $request->licence_class;
        $member->license_number = $request->license_number;
        $member->license_expiration_date = $request->license_expiration_date;
        $member->status = "Active";
        $member->applicant_id = $applicant->id;
        
        $member->save();

        return redirect()->route('applicants.index')
                        ->with('success', 'Applicant approved and member created successfully');
    }

    /**
     * Save applicant data (shared between store and update)
     */
    private function saveApplicantData($applicant, $request)
    {
        // Personal Information
        $applicant->first_name = $request->first_name;
        $applicant->last_name = $request->last_name;
        $applicant->middle_name = $request->middle_name;
        $applicant->suffix = $request->suffix;
        $applicant->sex = $request->sex;
        $applicant->birthdate = $request->birthdate;
        $applicant->civil_status = $request->civil_status;
        $applicant->citizenship = $request->citizenship;
        $applicant->blood_type = $request->blood_type;

        // Contact Information
        $applicant->cellphone_no = $request->cellphone_no;
        $applicant->telephone_no = $request->telephone_no;
        $applicant->email_address = $request->email_address;

        // Emergency Contact
        $applicant->emergency_contact = $request->emergency_contact;
        $applicant->emergency_contact_number = $request->emergency_contact_number;
        $applicant->relationship = $request->relationship;

        // License Information
        $applicant->licence_class = $request->licence_class;
        $applicant->license_number = $request->license_number;
        $applicant->license_expiration_date = $request->license_expiration_date;

        // Address Information
        $applicant->house_building_number_name = $request->house_building_number_name;
        $applicant->street_address = $request->street_address;
        $applicant->zip_code = $request->zip_code;
        $applicant->region = $request->region;
        $applicant->province = $request->province;
        $applicant->municipality = $request->municipality;
        $applicant->barangay = $request->barangay;

        $applicant->save();
    }

    public function reject(string $id)
    {
        $applicant = Applicant::findOrFail($id);
        $applicant->update(['status' => 'rejected']);
        
        return redirect()->route('applicants.index')
                        ->with('success', 'Applicant has been rejected');
    }

    /**
     * Display a listing of rejected applicants.
     */
    public function rejected(Request $request)
    {
        if ($request->ajax()) {
            $data = Applicant::where('status', 'rejected')->select('*');
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    $buttons = '';
                    
                    if (request()->user()->can('view applicants')) {
                        $buttons .= '<a href="'.route('applicants.show', $row->id).'" class="p-2 text-blue-600 hover:text-white hover:bg-blue-600 rounded-full transition-colors duration-200" title="View">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </a>';
                    }

                    if (request()->user()->can('edit applicants')) {
                        $buttons .= '<a href="javascript:void(0)" onclick="restoreApplicant('.$row->id.')" class="p-2 text-green-600 hover:text-white hover:bg-green-600 rounded-full transition-colors duration-200" title="Restore">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </a>';
                    }

                    return '<div class="flex space-x-2">'.$buttons.'</div>';
                })
                ->addColumn('full_name', function($row) {
                    return $row->first_name.' '.$row->last_name.($row->middle_name ? ' '.$row->middle_name : '').($row->suffix ? ' '.$row->suffix : '');
                })
                ->editColumn('created_at', function($row) {
                    return $row->created_at->format('d M, y');
                })
                ->editColumn('updated_at', function($row) {
                    return $row->updated_at->format('d M, y');
                })
                ->editColumn('birthdate', function($row) {
                    return $row->birthdate ? \Carbon\Carbon::parse($row->birthdate)->format('d M, y') : '';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        
        return view('applicants.rejected');
    }


    public function approved(Request $request)
    {
        if ($request->ajax()) {
            $data = Applicant::where('status', 'approved')->select('*');
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $buttons = '';
                    
                    if (request()->user()->can('view applicants')) {
                        $buttons .= '<a href="'.route('applicants.show', $row->id).'" class="p-2 text-blue-600 hover:text-white hover:bg-blue-600 rounded-full transition-colors duration-200" title="View">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </a>';
                    }
                    
                    return $buttons;
                })
                ->addColumn('full_name', function($row) {
                    return $row->first_name.' '.$row->last_name.($row->middle_name ? ' '.$row->middle_name : '').($row->suffix ? ' '.$row->suffix : '');
                })
                ->editColumn('created_at', function($row) {
                    return $row->created_at->format('d M, y');
                })
                ->editColumn('updated_at', function($row) {
                    return $row->updated_at->format('d M, y');
                })
                ->editColumn('birthdate', function($row) {
                    return $row->birthdate ? \Carbon\Carbon::parse($row->birthdate)->format('d M, y') : '';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        
        return view('applicants.approved');
    }


    /**
     * Restore a rejected applicant.
     */
    public function restore(Request $request)
    {
        $id = $request->id;
        $applicant = Applicant::findOrFail($id);
    
        // Validate the applicant is currently rejected
        if (strtolower($applicant->status) !== 'rejected') {
            session()->flash('error', 'Only rejected applicants can be restored');
            return response()->json([
                'status' => false,
                'message' => 'Only rejected applicants can be restored'
            ]);

        }
    
        $applicant->status = 'pending';
        $applicant->save();
    
        session()->flash('success', 'Applicant restored to pending status successfully');
        return response()->json([
            'status' => true,
            'message' => 'Applicant restored to pending status successfully'
        ]);

    }

    
}