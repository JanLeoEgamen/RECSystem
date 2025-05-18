<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Applicant;
use App\Models\Bureau;
use App\Models\Section;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ReportController extends Controller implements HasMiddleware
{

        public static function middleware(): array
    {
        return [
            // Require authentication for all report routes
            new Middleware('auth'),
            
            // View permissions
            new Middleware('permission:view reports', only: ['index']),
            new Middleware('permission:view membership reports', only: ['membership']),
            new Middleware('permission:view applicant reports', only: ['applicants']),
            new Middleware('permission:view license reports', only: ['licenses']),
            
            // Generate/export permissions
            new Middleware('permission:generate membership reports', only: ['membership']),
            new Middleware('permission:generate applicant reports', only: ['applicants']),
            new Middleware('permission:generate license reports', only: ['licenses']),
        ];
    }


    public function index()
    {
        $totalMembers = Member::count();
        $totalBureaus = Bureau::count();
        $totalApplicants = Applicant::count();
        $totalSections = Section::count(); 

        // Get sections count per bureau
        $bureausWithSections = Bureau::withCount('sections')->get();
        
        return view('reports.index', [
            'totalMembers' => $totalMembers,
            'totalBureaus' => $totalBureaus,
            'totalApplicants' => $totalApplicants,
            'totalSections' => $totalSections, 
            'bureausWithSections' => $bureausWithSections,
        ]);
    }

    public function membership(Request $request)
    {
        // Summary statistics
        $totalMembers = Member::count();
        $activeMembers = Member::where('status', 'active')->count();
        $inactiveMembers = $totalMembers - $activeMembers;
        $totalBureaus = Bureau::count();
        $totalSections = Section::count();

        // Get bureaus with their sections and member counts
        $bureaus = Bureau::with(['sections' => function($query) {
            $query->withCount('members')
                ->orderBy('section_name');
        }])->withCount('members', 'sections')
        ->orderBy('bureau_name')
        ->get();

        // Get all members with their relationships for the detailed list
        $members = Member::with(['section', 'membershipType'])
                        ->orderBy('last_name')
                        ->orderBy('first_name')
                        ->get();

            // Check if PDF was requested
    if ($request->has('export') && $request->export === 'pdf') {
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        
        $dompdf = new Dompdf($options);
        $html = view('reports.membership-pdf', [
            'totalMembers' => $totalMembers,
            'activeMembers' => $activeMembers,
            'inactiveMembers' => $inactiveMembers,
            'totalBureaus' => $totalBureaus,
            'totalSections' => $totalSections,
            'bureaus' => $bureaus,
            'members' => $members,
        ])->render();
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        return $dompdf->stream("membership_report_".now()->format('Y-m-d').".pdf");
    }

        return view('reports.membership', [
            'totalMembers' => $totalMembers,
            'activeMembers' => $activeMembers,
            'inactiveMembers' => $inactiveMembers,
            'totalBureaus' => $totalBureaus,
            'totalSections' => $totalSections,
            'bureaus' => $bureaus,
            'members' => $members,
        ]);
    }

    public function applicants(Request $request)
    {
        // Total counts
        $totalApplicants = Applicant::count();
        $approvedApplicants = Applicant::where('status', 'approved')->count();
        $pendingApplicants = Applicant::where('status', 'pending')->count();
        $rejectedApplicants = Applicant::where('status', 'rejected')->count();

        // Gender breakdown
        $genderCounts = Applicant::selectRaw('sex, count(*) as count')
            ->groupBy('sex')
            ->get()
            ->pluck('count', 'sex');

        // Age statistics
    $youngest = Applicant::whereNotNull('birthdate')->orderBy('birthdate', 'desc')->first();
    $oldest = Applicant::whereNotNull('birthdate')->orderBy('birthdate', 'asc')->first();
    
    // More accurate age calculation
    $averageAge = Applicant::whereNotNull('birthdate')
        ->selectRaw('AVG(DATEDIFF(CURDATE(), birthdate)/365) as avg_age')
        ->first()->avg_age;

        // Get applicants grouped by status
        $approved = Applicant::where('status', 'approved')
            ->with(['member.section.bureau', 'member.membershipType'])
            ->orderBy('created_at', 'desc')
            ->get();

        $pending = Applicant::where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        $rejected = Applicant::where('status', 'rejected')
            ->orderBy('created_at', 'desc')
            ->get();

           // PDF Export
    if ($request->has('export') && $request->export === 'pdf') {
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        
        $dompdf = new Dompdf($options);
        $html = view('reports.applicants-pdf', [
            'totalApplicants' => $totalApplicants,
            'approvedApplicants' => $approvedApplicants,
            'pendingApplicants' => $pendingApplicants,
            'rejectedApplicants' => $rejectedApplicants,
            'genderCounts' => $genderCounts,
            'youngest' => $youngest,
            'oldest' => $oldest,
            'averageAge' => $averageAge,
            'approved' => $approved,
            'pending' => $pending,
            'rejected' => $rejected,
        ])->render();
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        return $dompdf->stream("applicants_report_".now()->format('Y-m-d').".pdf");
    }

        return view('reports.applicants', [
            'totalApplicants' => $totalApplicants,
            'approvedApplicants' => $approvedApplicants,
            'pendingApplicants' => $pendingApplicants,
            'rejectedApplicants' => $rejectedApplicants,
            'genderCounts' => $genderCounts,
            'youngest' => $youngest,
            'oldest' => $oldest,
            'averageAge' => $averageAge,
            'approved' => $approved,
            'pending' => $pending,
            'rejected' => $rejected,
        ]);
    }



    public function licenses(Request $request)
    {
        // Summary statistics
        $totalMembers = Member::count();
        $licensedMembers = Member::whereNotNull('license_number')->count();
        $unlicensedMembers = $totalMembers - $licensedMembers;
        
        $activeLicenses = Member::where('license_expiration_date', '>', now())->count();
        $expiredLicenses = Member::where('license_expiration_date', '<', now())->count();
        $nearExpiry = Member::whereBetween('license_expiration_date', [now(), now()->addDays(60)])->count();

        // Get bureaus with their sections and grouped licensed members
        $bureaus = Bureau::with(['sections' => function($query) {
            $query->withCount([
                'members as total_members_count',
                'members as licensed_members_count' => function($q) {
                    $q->whereNotNull('license_number');
                },
                'members as unlicensed_members_count' => function($q) {
                    $q->whereNull('license_number');
                },
                'members as active_licenses_count' => function($q) {
                    $q->where('license_expiration_date', '>', now());
                },
                'members as expired_licenses_count' => function($q) {
                    $q->where('license_expiration_date', '<', now());
                },
                'members as near_expiry_count' => function($q) {
                    $q->whereBetween('license_expiration_date', [now(), now()->addDays(60)]);
                }
            ])
            ->with(['members' => function($q) {
                $q->whereNotNull('license_number')
                ->orderBy('licence_class')
                ->orderBy('last_name')
                ->orderBy('first_name');
            }])
            ->orderBy('section_name');
        }])
        ->withCount([
            'members as bureau_members_count',
            'members as bureau_licensed_count' => function($q) {
                $q->whereNotNull('license_number');
            },
            'members as bureau_unlicensed_count' => function($q) {
                $q->whereNull('license_number');
            }
        ])
        ->orderBy('bureau_name')
        ->get();

        // Group licensed members by class in each section
        $bureaus->each(function($bureau) {
            $bureau->sections->each(function($section) {
                $section->groupedLicensedMembers = $section->members
                    ->where('license_number', '!=', null)
                    ->groupBy('licence_class');
            });
        });

            // PDF Export
        if ($request->has('export') && $request->export === 'pdf') {
            $options = new Options();
            $options->set('isRemoteEnabled', true);
            $options->set('isHtml5ParserEnabled', true);
            
            $dompdf = new Dompdf($options);
            $html = view('reports.licenses-pdf', [
                'totalMembers' => $totalMembers,
                'licensedMembers' => $licensedMembers,
                'unlicensedMembers' => $unlicensedMembers,
                'activeLicenses' => $activeLicenses,
                'expiredLicenses' => $expiredLicenses,
                'nearExpiry' => $nearExpiry,
                'bureaus' => $bureaus,
            ])->render();
            
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'landscape');
            $dompdf->render();
            
            return $dompdf->stream("license_report_".now()->format('Y-m-d').".pdf");
        }

        return view('reports.licenses', [
            'totalMembers' => $totalMembers,
            'licensedMembers' => $licensedMembers,
            'unlicensedMembers' => $unlicensedMembers,
            'activeLicenses' => $activeLicenses,
            'expiredLicenses' => $expiredLicenses,
            'nearExpiry' => $nearExpiry,
            'bureaus' => $bureaus,
        ]);
    }
}