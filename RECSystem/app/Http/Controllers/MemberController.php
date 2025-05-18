<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Applicant;
use App\Models\MembershipType;
use App\Models\Section;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view members', only: ['index', 'show']),
            new Middleware('permission:edit members', only: ['edit']),    
            new Middleware('permission:create members', only: ['create']),
            new Middleware('permission:delete members', only: ['destroy']),
            new Middleware('permission:renew members', only: ['showRenewalForm', 'processRenewal']),

        ];
    }

    /**
     * Display a listing of the resource.
     */
public function index(Request $request)
{
    if ($request->ajax()) {
        $user = $request->user(); 
                
        $data = Member::with(['membershipType', 'section'])
            ->when(!$user->can('view all members'), function($query) use ($user) {
                // Get section IDs from the pivot table where user is assigned
                $sectionIds = DB::table('user_bureau_section')
                    ->where('user_id', $user->id)
                    ->whereNotNull('section_id')
                    ->pluck('section_id');
                
                // Get bureau IDs from the pivot table where user is assigned (without specific section)
                $bureauIds = DB::table('user_bureau_section')
                    ->where('user_id', $user->id)
                    ->whereNull('section_id')
                    ->pluck('bureau_id');
                
                // Get all section IDs from these bureaus
                $bureauSectionIds = Section::whereIn('bureau_id', $bureauIds)->pluck('id');
                
                // Combine both specific section assignments and bureau-wide assignments
                $allSectionIds = $sectionIds->merge($bureauSectionIds)->unique();
                
                // Filter members by these section IDs
                $query->whereIn('section_id', $allSectionIds);
            })
            ->select('*');
            
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row) {
                $buttons = '';
                
                if (request()->user()->can('view members')) {
                    $buttons .= '<a href="'.route('members.show', $row->id).'" class="p-2 text-blue-600 hover:text-white hover:bg-blue-600 rounded-full transition-colors duration-200" title="View">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </a>';
                }

                if (request()->user()->can('edit members')) {
                    $buttons .= '<a href="'.route('members.edit', $row->id).'" class="p-2 text-indigo-600 hover:text-white hover:bg-indigo-600 rounded-full transition-colors duration-200" title="Edit">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </a>';
                }

                if (request()->user()->can('renew members')) {
                    $buttons .= '<a href="'.route('members.renew.show', $row->id).'" class="p-2 text-green-600 hover:text-white hover:bg-green-600 rounded-full transition-colors duration-200" title="Renew">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </a>';
                }

                if (request()->user()->can('delete members')) {
                    $buttons .= '<a href="javascript:void(0)" onclick="deleteMember('.$row->id.')" class="p-2 text-red-600 hover:text-white hover:bg-red-600 rounded-full transition-colors duration-200" title="Delete">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </a>';
                }

                return '<div class="flex space-x-2">'.$buttons.'</div>';
            })
            ->editColumn('membership_start', function($row) {
                return $row->membership_start ? \Carbon\Carbon::parse($row->membership_start)->format('d M, Y') : '';
            })
            ->editColumn('membership_end', function($row) {
                return $row->membership_end ? \Carbon\Carbon::parse($row->membership_end)->format('d M, Y') : '';
            })
            ->editColumn('birthdate', function($row) {
                return $row->birthdate ? \Carbon\Carbon::parse($row->birthdate)->format('d M, Y') : '';
            })
            ->editColumn('is_lifetime_member', function($row) {
                return $row->is_lifetime_member ? 'Yes' : 'No';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    
    return view('members.list');
}
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $applicants = Applicant::whereDoesntHave('member')->get();
        $membershipTypes = MembershipType::all();
        $sections = Section::all();
        
        return view('members.create', [
            'applicants' => $applicants,
            'membershipTypes' => $membershipTypes,
            'sections' => $sections
        ]);
    }

    public function show(string $id)
    {
        $member = Member::with(['membershipType', 'section'])->findOrFail($id);
        return view('members.view', ['member' => $member]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'applicant_id' => 'required|exists:applicants,id',
            'rec_number' => 'required|unique:members,rec_number',
            'membership_type_id' => 'required|exists:membership_types,id',
            'section_id' => 'nullable|exists:sections,id',
            
            // Personal Information
            'first_name' => 'required|min:2',
            'last_name' => 'required|min:2',
            'middle_name' => 'nullable',
            'suffix' => 'nullable',
            'sex' => 'required',
            'birthdate' => 'required|date',
            'civil_status' => 'required',
            'citizenship' => 'required',
            'blood_type' => 'nullable',
            
            // Contact Information
            'cellphone_no' => 'required',
            'telephone_no' => 'nullable',
            'email_address' => 'required|email',
            
            // Emergency Contact
            'emergency_contact' => 'required',
            'emergency_contact_number' => 'required',
            'relationship' => 'required',
            
            // Membership Information
            'membership_start' => 'required|date',
            'membership_end' => 'required_if:is_lifetime_member,0|date|after:membership_start',
            'is_lifetime_member' => 'boolean',
            'last_renewal_date' => 'nullable|date',
            'status' => 'required|in:Active,Inactive',

            // License Information
            'licence_class' => 'nullable',
            'license_number' => 'nullable',
            'license_expiration_date' => 'nullable|date',
            
            // Address Information
            'house_building_number_name' => 'required',
            'street_address' => 'required',
            'zip_code' => 'required',
            'region' => 'required',
            'province' => 'required',
            'municipality' => 'required',
            'barangay' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('members.create')->withInput()->withErrors($validator);
        }

        $member = new Member();
        $this->saveMemberData($member, $request);

        return redirect()->route('members.index')->with('success', 'Member added successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $member = Member::findOrFail($id);
        $membershipTypes = MembershipType::all();
        $sections = Section::all();
        
        return view('members.edit', [
            'member' => $member,
            'membershipTypes' => $membershipTypes,
            'sections' => $sections
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $member = Member::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'rec_number' => 'required|unique:members,rec_number,'.$id,
            'membership_type_id' => 'required|exists:membership_types,id',
            'section_id' => 'nullable|exists:sections,id',
            
            // Personal Information
            'first_name' => 'required|min:2',
            'last_name' => 'required|min:2',
            'middle_name' => 'nullable',
            'suffix' => 'nullable',
            'sex' => 'required',
            'birthdate' => 'required|date',
            'civil_status' => 'required',
            'citizenship' => 'required',
            'blood_type' => 'nullable',
            
            // Contact Information
            'cellphone_no' => 'required',
            'telephone_no' => 'nullable',
            'email_address' => 'required|email',
            
            // Emergency Contact
            'emergency_contact' => 'required',
            'emergency_contact_number' => 'required',
            'relationship' => 'required',
            
            // Membership Information
            'membership_start' => 'required|date',
            'membership_end' => 'required_if:is_lifetime_member,0|date|after:membership_start',
            'is_lifetime_member' => 'boolean',
            'last_renewal_date' => 'nullable|date',
            'status' => 'required|in:Active,Inactive',

            // License Information
            'licence_class' => 'nullable',
            'license_number' => 'nullable',
            'license_expiration_date' => 'nullable|date',
            
            // Address Information
            'house_building_number_name' => 'required',
            'street_address' => 'required',
            'zip_code' => 'required',
            'region' => 'required',
            'province' => 'required',
            'municipality' => 'required',
            'barangay' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('members.edit', $id)->withInput()->withErrors($validator);
        }

        $this->saveMemberData($member, $request);

        return redirect()->route('members.index')->with('success', 'Member updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->id;
        $member = Member::findOrFail($id);

        if ($member == null) {
            session()->flash('error', 'Member not found.');
            return response()->json(['status' => false]);
        }

        $member->delete();

        session()->flash('success', 'Member deleted successfully.');
        return response()->json(['status' => true]);
    }

    /**
     * Save member data (shared between store and update)
     */
    private function saveMemberData($member, $request)
    {
        // Personal Information
        $member->first_name = $request->first_name;
        $member->last_name = $request->last_name;
        $member->middle_name = $request->middle_name;
        $member->suffix = $request->suffix;
        $member->sex = $request->sex;
        $member->birthdate = $request->birthdate;
        $member->civil_status = $request->civil_status;
        $member->citizenship = $request->citizenship;
        $member->blood_type = $request->blood_type;

        // Contact Information
        $member->cellphone_no = $request->cellphone_no;
        $member->telephone_no = $request->telephone_no;
        $member->email_address = $request->email_address;

        // Emergency Contact
        $member->emergency_contact = $request->emergency_contact;
        $member->emergency_contact_number = $request->emergency_contact_number;
        $member->relationship = $request->relationship;

        // Membership Information
        $member->rec_number = $request->rec_number;
        $member->membership_type_id = $request->membership_type_id;
        $member->section_id = $request->section_id;
        $member->membership_start = $request->membership_start;
        $member->membership_end = $request->is_lifetime_member ? null : $request->membership_end;
        $member->is_lifetime_member = $request->is_lifetime_member ?? false;
        $member->last_renewal_date = $request->last_renewal_date ?? now();
        $member->status = $request->status;

        // License Information
        $member->licence_class = $request->licence_class;
        $member->license_number = $request->license_number;
        $member->license_expiration_date = $request->license_expiration_date;

        // Address Information
        $member->house_building_number_name = $request->house_building_number_name;
        $member->street_address = $request->street_address;
        $member->zip_code = $request->zip_code;
        $member->region = $request->region;
        $member->province = $request->province;
        $member->municipality = $request->municipality;
        $member->barangay = $request->barangay;

        // Only set applicant_id on create
        if (!$member->exists && $request->applicant_id) {
            $member->applicant_id = $request->applicant_id;
        }

        $member->save();
    }


    public function membershipValidity(Request $request)
    {
        if ($request->ajax()) {
            $data = Member::with(['membershipType'])
                ->select('id', 'first_name', 'last_name', 'membership_end', 'is_lifetime_member', 'membership_type_id');
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('full_name', function($row) {
                    return $row->first_name . ' ' . $row->last_name;
                })
                ->addColumn('status', function($row) {
                    if ($row->is_lifetime_member) {
                        return '<span class="px-2 py-1 text-xs font-semibold leading-tight text-green-700 bg-green-100 rounded-full">Lifetime</span>';
                    }
                    
                    $now = now();
                    $end = Carbon::parse($row->membership_end);
                    
                    if ($now->gt($end)) {
                        return '<span class="px-2 py-1 text-xs font-semibold leading-tight text-red-700 bg-red-100 rounded-full">Expired</span>';
                    } else {
                        return '<span class="px-2 py-1 text-xs font-semibold leading-tight text-blue-700 bg-blue-100 rounded-full">Active</span>';
                    }
                })
                ->addColumn('days_left', function($row) {
                    if ($row->is_lifetime_member) {
                        return '∞';
                    }
                    
                    $now = now();
                    $end = Carbon::parse($row->membership_end);
                    
                    if ($now->gt($end)) {
                        return 'Expired ' . $now->diffInDays($end) . ' days ago';
                    } else {
                        return $now->diffInDays($end) . ' days';
                    }
                })
                ->editColumn('membershipType.name', function($row) {
                    return $row->membershipType->name;
                })
                ->rawColumns(['status'])
                ->make(true);
        }
        
        return view('members.validity');
    }

public function showRenewalForm(Member $member)
{
    return view('members.renew', [
        'member' => $member,
        // Calculate default values for the form
        'default_years' => 1,
        'default_months' => 0,
        'default_days' => 0
    ]);
}

public function processRenewal(Request $request, Member $member)
{
    // Validate request input
    $validated = $request->validate([
        'years' => 'required|integer|min:0',
        'months' => 'required|integer|min:0|max:11',
        'days' => 'required|integer|min:0|max:31',
    ]);




    // Cast values to integers to ensure Carbon receives correct types
    $years = (int) $validated['years'];
    $months = (int) $validated['months'];
    $days = (int) $validated['days'];

    // Check if at least one duration is provided
    $totalDays = ($years * 365) + ($months * 30) + $days;
    if ($totalDays <= 0) {
        return redirect()->back()
            ->with('error', 'Please specify a valid renewal duration.')
            ->withInput();
    }

    // Determine the base start date: future expiration or today
    $startDate = $member->membership_end && Carbon::parse($member->membership_end)->isFuture()
        ? Carbon::parse($member->membership_end)
        : now();

    // Compute the new expiration date
    $newExpiration = $startDate->copy()
        ->addYears($years)
        ->addMonths($months)
        ->addDays($days);

    // Update the member record
    $member->update([
        'is_lifetime_member' => false,
        'membership_end' => $newExpiration,
        'last_renewal_date' => now()
    ]);

    return redirect()->route('members.index', $member->id)
        ->with('success', 'Membership successfully renewed until ' . $newExpiration->format('M d, Y'));
}

}