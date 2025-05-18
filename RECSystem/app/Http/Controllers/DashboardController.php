<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Applicant;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $fullName = $user->first_name . ' ' . $user->last_name;

        // Get section and bureau-based access
        $sectionIds = DB::table('user_bureau_section')
            ->where('user_id', $user->id)
            ->whereNotNull('section_id')
            ->pluck('section_id');

        $bureauIds = DB::table('user_bureau_section')
            ->where('user_id', $user->id)
            ->whereNull('section_id')
            ->pluck('bureau_id');

        $bureauSectionIds = Section::whereIn('bureau_id', $bureauIds)->pluck('id');
        $accessibleSectionIds = $sectionIds->merge($bureauSectionIds)->unique();

        // Member statistics
        $memberQuery = Member::whereIn('section_id', $accessibleSectionIds);

        $totalMembers = (clone $memberQuery)->count();

        $activeMembers = (clone $memberQuery)->where(function ($query) {
            $query->where('is_lifetime_member', true)
                  ->orWhere('membership_end', '>=', now());
        })->count();

        $expiringSoon = (clone $memberQuery)->where('is_lifetime_member', false)
            ->whereBetween('membership_end', [now(), now()->addDays(30)])
            ->count();

        $expiringSoonMembers = (clone $memberQuery)->where('is_lifetime_member', false)
            ->whereBetween('membership_end', [now(), now()->addDays(30)])
            ->orderBy('membership_end')
            ->take(5)
            ->get();

        $recentMembers = (clone $memberQuery)->latest()->take(5)->get();




        return view('dashboard', [
            'fullName' => $fullName,  // Pass full name to the view
            'totalMembers' => $totalMembers,
            'activeMembers' => $activeMembers,
            'expiringSoon' => $expiringSoon,
            'recentMembers' => $recentMembers,
            'expiringSoonMembers' => $expiringSoonMembers,
            
        ]);
    }
}
