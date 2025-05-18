<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="font-semibold text-3xl text-white dark:text-white leading-tight">
                {{ __('Applicant Report') }}
            </h1>
            <div class="flex space-x-4">
                @can('generate applicant reports')
                <a href="{{ route('reports.applicants', ['export' => 'pdf']) }}" 
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
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Applicant Summary</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <!-- Total Applicants -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-indigo-500 rounded-md p-2">
                                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Total Applicants</p>
                                    <p class="text-2xl font-semibold text-gray-700 dark:text-gray-200">{{ $totalApplicants }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Approved -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-green-500 rounded-md p-2">
                                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Approved</p>
                                    <p class="text-2xl font-semibold text-gray-700 dark:text-gray-200">{{ $approvedApplicants }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Pending -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-yellow-500 rounded-md p-2">
                                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Pending</p>
                                    <p class="text-2xl font-semibold text-gray-700 dark:text-gray-200">{{ $pendingApplicants }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Rejected -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-red-500 rounded-md p-2">
                                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Rejected</p>
                                    <p class="text-2xl font-semibold text-gray-700 dark:text-gray-200">{{ $rejectedApplicants }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Gender Breakdown -->
                    <div class="mb-6">
                        <h4 class="text-md font-medium text-gray-900 dark:text-white mb-2">Gender Breakdown</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                <p class="text-sm text-gray-500 dark:text-gray-300">Male</p>
                                <p class="text-xl font-semibold text-gray-700 dark:text-gray-200">{{ $genderCounts['Male'] ?? 0 }}</p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                <p class="text-sm text-gray-500 dark:text-gray-300">Female</p>
                                <p class="text-xl font-semibold text-gray-700 dark:text-gray-200">{{ $genderCounts['Female'] ?? 0 }}</p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                <p class="text-sm text-gray-500 dark:text-gray-300">Other</p>
                                <p class="text-xl font-semibold text-gray-700 dark:text-gray-200">{{ $genderCounts['other'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Age Statistics -->
                    <div>
                        <h4 class="text-md font-medium text-gray-900 dark:text-white mb-2">Age Statistics</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                <p class="text-sm text-gray-500 dark:text-gray-300">Youngest</p>
                                <p class="text-xl font-semibold text-gray-700 dark:text-gray-200">
                                    @if($youngest)
                                        {{ \Carbon\Carbon::parse($youngest->birthdate)->age }} years
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                <p class="text-sm text-gray-500 dark:text-gray-300">Oldest</p>
                                <p class="text-xl font-semibold text-gray-700 dark:text-gray-200">
                                    @if($oldest)
                                        {{ \Carbon\Carbon::parse($oldest->birthdate)->age }} years
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                <p class="text-sm text-gray-500 dark:text-gray-300">Average Age</p>
                                <p class="text-xl font-semibold text-gray-700 dark:text-gray-200">
                                    @if($averageAge)
                                        {{ round($averageAge) }} years
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Approved Applicants Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg mb-6">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Approved Applicants ({{ $approvedApplicants }})</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Full Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Sex</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Age</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date Applied</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date Approved</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Section</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Bureau</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Membership Type</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($approved as $applicant)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                        {{ $applicant->last_name }}, {{ $applicant->first_name }} {{ $applicant->middle_name ? $applicant->middle_name[0].'.' : '' }} {{ $applicant->suffix ?? '' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ ucfirst($applicant->sex) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        @if($applicant->birthdate)
                                            {{ \Carbon\Carbon::parse($applicant->birthdate)->age }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ $applicant->created_at->format('m/d/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        @if($applicant->member && $applicant->member->created_at)
                                            {{ $applicant->member->created_at->format('m/d/Y') }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ $applicant->member->section->section_name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ $applicant->member->section->bureau->bureau_name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ $applicant->member->membershipType->type_name ?? 'N/A' }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                        No approved applicants found
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pending Applicants Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg mb-6">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Pending Applicants ({{ $pendingApplicants }})</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Full Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Sex</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Age</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date Applied</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($pending as $applicant)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                        {{ $applicant->last_name }}, {{ $applicant->first_name }} {{ $applicant->middle_name ? $applicant->middle_name[0].'.' : '' }} {{ $applicant->suffix ?? '' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ ucfirst($applicant->sex) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        @if($applicant->birthdate)
                                            {{ \Carbon\Carbon::parse($applicant->birthdate)->age }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ $applicant->created_at->format('m/d/Y') }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                        No pending applicants found
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Rejected Applicants Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Rejected Applicants ({{ $rejectedApplicants }})</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Full Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Sex</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Age</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date Applied</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date Rejected</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($rejected as $applicant)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                        {{ $applicant->last_name }}, {{ $applicant->first_name }} {{ $applicant->middle_name ? $applicant->middle_name[0].'.' : '' }} {{ $applicant->suffix ?? '' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ ucfirst($applicant->sex) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        @if($applicant->birthdate)
                                            {{ \Carbon\Carbon::parse($applicant->birthdate)->age }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ $applicant->created_at->format('m/d/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ $applicant->updated_at->format('m/d/Y') }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                        No rejected applicants found
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>w