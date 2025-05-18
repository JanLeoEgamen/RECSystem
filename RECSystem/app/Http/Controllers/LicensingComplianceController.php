<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class LicensingComplianceController extends Controller
{
    public function index(Request $request)
    {
        // Card Metrics
        $licensedMembers = Member::whereNotNull('license_number')->count();
        $expiringSoon = Member::whereBetween('license_expiration_date', [now(), now()->addDays(30)])->count();
        $topLicenseClass = Member::groupBy('licence_class')
            ->orderByDesc(DB::raw('COUNT(*)'))
            ->pluck('licence_class')
            ->first();
        
        if ($request->ajax()) {
            $data = Member::with(['section.bureau'])->whereNotNull('license_number')->select('*');
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    $buttons = '';
                    
                    if (request()->user()->can('edit members')) {
                        $buttons .= '<a href="'.route('members.edit', $row->id).'" class="p-2 text-indigo-600 hover:text-white hover:bg-indigo-600 rounded-full transition-colors duration-200" title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </a>';
                    }

                    return '<div class="flex space-x-2">'.$buttons.'</div>';
                })
                ->addColumn('full_name', function($row) {
                    return $row->first_name . ' ' . $row->last_name;
                })
                ->addColumn('bureau_section', function($row) {
                    return ($row->section->bureau->bureau_name ?? 'N/A') . ' / ' . ($row->section->section_name ?? 'N/A');
                })
                ->editColumn('license_expiration_date', function($row) {
                    $isExpiringSoon = $row->license_expiration_date && 
                                     $row->license_expiration_date->between(now(), now()->addDays(30));
                                     
                    $dateFormatted = $row->license_expiration_date ? 
                        $row->license_expiration_date->format('d M, Y') : 'N/A';
                    
                    if ($isExpiringSoon) {
                        return '<span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full">'.$dateFormatted.'</span>';
                    } elseif ($row->license_expiration_date && $row->license_expiration_date->lt(now())) {
                        return '<span class="px-2 py-1 bg-red-100 text-red-800 rounded-full">'.$dateFormatted.'</span>';
                    }
                    return $dateFormatted;
                })
                ->rawColumns(['action', 'license_expiration_date'])
                ->make(true);
        }
        
        return view('dashboard.licensing-compliance', compact(
            'licensedMembers',
            'expiringSoon',
            'topLicenseClass'
        ));
    }
}