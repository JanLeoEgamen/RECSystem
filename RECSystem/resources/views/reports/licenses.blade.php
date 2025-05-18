<x-app-layout>
<x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="font-semibold text-3xl text-white dark:text-white leading-tight">
                {{ __('License Report') }}
            </h1>
            <div class="flex space-x-4">
                @can('generate license reports')
                <a href="{{ route('reports.licenses', ['export' => 'pdf']) }}" 
                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md flex items-center">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Export PDF
                </a>
                @endcan

                <a href="{{ route('reports.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md flex items-center">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Reports
                </a>
            </div>

        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Summary Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg mb-6">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">License Summary</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                        <!-- Total Members -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-indigo-500 rounded-md p-2">
                                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Total Members</p>
                                    <p class="text-xl font-semibold text-gray-700 dark:text-gray-200">{{ $totalMembers }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Licensed Members -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-green-500 rounded-md p-2">
                                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Licensed Members</p>
                                    <p class="text-xl font-semibold text-gray-700 dark:text-gray-200">{{ $licensedMembers }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Unlicensed Members -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-red-500 rounded-md p-2">
                                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-12.728 12.728M5.636 5.636l12.728 12.728" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Unlicensed Members</p>
                                    <p class="text-xl font-semibold text-gray-700 dark:text-gray-200">{{ $unlicensedMembers }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- License Status Sub-Summary -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Active Licenses -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-blue-500 rounded-md p-2">
                                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Active Licenses</p>
                                    <p class="text-xl font-semibold text-gray-700 dark:text-gray-200">{{ $activeLicenses }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Expired Licenses -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-yellow-500 rounded-md p-2">
                                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Expired Licenses</p>
                                    <p class="text-xl font-semibold text-gray-700 dark:text-gray-200">{{ $expiredLicenses }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Near Expiry -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-orange-500 rounded-md p-2">
                                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Near Expiry (60 days)</p>
                                    <p class="text-xl font-semibold text-gray-700 dark:text-gray-200">{{ $nearExpiry }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bureau Breakdown -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Membership by Bureau</h3>
                    
                    @foreach($bureaus as $bureau)
                    <div class="mb-8">
                        <!-- Bureau Header -->
                        <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg mb-4">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $bureau->bureau_name }}</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-300">
                                        {{ $bureau->bureau_members_count }} members • 
                                        <span class="text-green-600 dark:text-green-400">{{ $bureau->bureau_licensed_count }} licensed</span> • 
                                        <span class="text-red-600 dark:text-red-400">{{ $bureau->bureau_unlicensed_count }} unlicensed</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Sections under this Bureau -->
                        @foreach($bureau->sections as $section)
                        <div class="ml-6 mb-6">
                            <!-- Section Header -->
                            <div class="bg-gray-50 dark:bg-gray-600 p-3 rounded-lg mb-2">
                                <div class="flex justify-between items-center">
                                    <h5 class="font-medium text-gray-900 dark:text-white">
                                        {{ $section->section_name }} • 
                                        <span class="text-green-600 dark:text-green-400">{{ $section->licensed_members_count }} licensed</span> • 
                                        <span class="text-red-600 dark:text-red-400">{{ $section->unlicensed_members_count }} unlicensed</span>
                                    </h5>
                                    <div class="flex space-x-4">
                                        @if($section->licensed_members_count > 0)
                                        <span class="text-xs bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 px-2 py-1 rounded-full">
                                            Active: {{ $section->active_licenses_count }}
                                        </span>
                                        <span class="text-xs bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 px-2 py-1 rounded-full">
                                            Expired: {{ $section->expired_licenses_count }}
                                        </span>
                                        <span class="text-xs bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200 px-2 py-1 rounded-full">
                                            Near Expiry: {{ $section->near_expiry_count }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Members in this Section -->
                            <div class="ml-6">
<!-- Licensed Members Grouped by Class -->
@if($section->licensed_members_count > 0)
<div class="mb-6">
    <h6 class="text-md font-medium text-gray-800 dark:text-gray-300 mb-2">
        Licensed Members ({{ $section->licensed_members_count }})
    </h6>
    

@foreach($section->groupedLicensedMembers as $licenseClass => $members)
    <div class="mb-6 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
        <!-- License Class Header -->
        <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3">
            <h4 class="font-medium text-gray-800 dark:text-gray-200">
                Class: {{ $licenseClass ?? 'N/A' }} ({{ $members->count() }} members)
            </h4>
        </div>
        
        <!-- Members Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-100 dark:bg-gray-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Member</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">License #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Expiration</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($members as $member)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                            {{ $member->last_name }}, {{ $member->first_name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($member->license_expiration_date > now())
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    Active
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                    Expired
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                            {{ $member->license_number }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                            {{ \Carbon\Carbon::parse($member->license_expiration_date)->format('m/d/Y') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endforeach
</div>
@endif
<!-- Unlicensed Members List -->
@if($section->unlicensed_members_count > 0)
<div class="mb-6">
    <h6 class="text-md font-medium text-gray-800 dark:text-gray-300 mb-3">
        Unlicensed Members ({{ $section->unlicensed_members_count }})
    </h6>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
        @foreach($section->members()->whereNull('license_number')->orderBy('last_name')->orderBy('first_name')->get() as $member)
        <div class="flex items-start">
            <span class="text-gray-500 dark:text-gray-400 mr-2">•</span>
            <span class="text-sm text-gray-700 dark:text-gray-300">
                {{ $member->last_name }}, {{ $member->first_name }}
                <span class="text-xs text-gray-500 dark:text-gray-400 ml-1">({{ $section->section_name }})</span>
            </span>
        </div>
        @endforeach
    </div>
</div>
@endif                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>