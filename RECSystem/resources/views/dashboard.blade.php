<x-app-layout>
    <x-slot name="header">
        <h1 class="font-semibold text-3xl text-white dark:text-white leading-tight">
            {{ __('Dashboard') }}
        </h1>
    </x-slot>

    <!-- Welcome Card -->
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gradient-to-r from-[#63f542] to-[#7fe67c] dark:from-[#63f542] dark:to-[#7fe67c] overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 text-2xl font-bold text-[#026300] dark:text-[#3e3e3a]">
                    <div class="flex items-center space-x-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-[#026300] dark:text-[#3e3e3a]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 12c2.28 0 4.5-2.22 4.5-5s-2.22-5-4.5-5S7.5 4.22 7.5 7s2.22 5 4.5 5zM4 20c0-4.418 4.5-8 8-8s8 3.582 8 8"></path>
                        </svg>
                        <div>
                            <p class="text-lg text-[#026300] font-semibold">{{ __("Hello,") }} <span class="text-[#026300] dark:text-[#3e3e3a]">{{ $fullName }}</span>!</p>
                            <p class="mt-1 text-xl text-[#026300]">{{ __("You're logged in!") }}</p>
                            
                        </div>
                        <div>
                            <p class="mt-1 text-md text-[#026300] dark:text-[#3e3e3a]">
                                {{ \Carbon\Carbon::now()->format('l, F j, Y') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Stats Cards Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Total Members Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-300 truncate">
                                    Total Members
                                </dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-gray-900 dark:text-white">
                                        {{ $totalMembers }}
                                    </div>
                                </dd>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Active Members Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-300 truncate">
                                    Active Members
                                </dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-gray-900 dark:text-white">
                                        {{ $activeMembers }}
                                    </div>
                                </dd>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Expiring Soon Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-300 truncate">
                                    Memberships Expiring Soon
                                </dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-gray-900 dark:text-white">
                                        {{ $expiringSoon }}
                                    </div>
                                </dd>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Tables Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Memberships Expiring Soon Table -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                            Memberships Expiring Soon
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Membership End</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Type</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse($expiringSoonMembers as $member)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $member->first_name }} {{ $member->last_name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                {{ \Carbon\Carbon::parse($member->membership_end)->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                {{ $member->membershipType->type_name ?? 'N/A' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-300">
                                                No memberships expiring soon.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Recent Members Table -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                            Recent Members
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Membership Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Joined</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($recentMembers as $member)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $member->first_name }} {{ $member->last_name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                {{ $member->membershipType->type_name ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                {{ $member->created_at->format('M d, Y') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Quick Actions Row -->
            @canany(['view applicants', 'view members', 'view event announcements', 'view communities', 'view articles', 'view supporters'])
            <div class="mb-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Membership Management Quick Actions</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                    @can('view applicants')
                        <a href="{{ route('applicants.create') }}" class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow flex flex-col items-center justify-center hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300 text-center">New Applicant</span>
                        </a>
                    @endcan

                    @can('create members')
                        <a href="{{ route('members.index') }}" class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow flex flex-col items-center justify-center hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <svg class="h-6 w-6 text-green-600 dark:text-green-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300 text-center">New Member</span>
                        </a>
                    @endcan

                    @can('view applicants')
                        <a href="{{ route('applicants.index') }}" class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow flex flex-col items-center justify-center hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300 text-center">Assess Applicants</span>
                        </a>
                    @endcan

                    @can('renew members')
                        <a href="{{ route('members.index') }}" class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow flex flex-col items-center justify-center hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <svg class="h-6 w-6 text-blue-600 dark:text-blue-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300 text-center">Renew Members</span>
                        </a>
                    @endcan
                </div>
            </div>
            @endcan

            <!-- Quick Actions Row -->
            @canany(['view faqs', 'view main carousels', 'view event announcements', 'view communities', 'view articles', 'view supporters'])
            <div class="mb-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Website Content Management Quick Actions</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                        @can('view event-announcements')
                            <a href="{{ route('event-announcements.index') }}" class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow flex flex-col items-center justify-center hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <svg class="h-6 w-6 text-purple-600 dark:text-purple-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 text-center">New Event</span>
                            </a>
                        @endcan

                        @can('view articles')
                            <a href="{{ route('articles.index') }}" class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow flex flex-col items-center justify-center hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <svg class="h-6 w-6 text-red-600 dark:text-red-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                </svg>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 text-center">New Article</span>
                            </a>
                        @endcan

                        @can('view communities')
                            <a href="{{ route('communities.index') }}" class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow flex flex-col items-center justify-center hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <svg class="h-6 w-6 text-green-600 dark:text-green-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5V4H2v16h5m5-9h4m-2 2v-4" />
                                </svg>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 text-center">New Community</span>
                            </a>
                        @endcan

                        @can('view faqs')
                            <a href="{{ route('faqs.index') }}" class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow flex flex-col items-center justify-center hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16h6" />
                                </svg>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 text-center">New FAQ</span>
                            </a>
                        @endcan

                        @can('view markees')
                            <a href="{{ route('markees.index') }}" class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow flex flex-col items-center justify-center hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <svg class="h-6 w-6 text-blue-600 dark:text-blue-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m4 0h-1V9h-1M12 20c4.418 0 8-3.582 8-8s-3.582-8-8-8-8 3.582-8 8 3.582 8 8 8z" />
                                </svg>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 text-center">New Markee</span>
                            </a>
                        @endcan

                        @can('view main-carousels')
                            <a href="{{ route('main-carousels.index') }}" class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow flex flex-col items-center justify-center hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <svg class="h-6 w-6 text-pink-600 dark:text-pink-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 text-center">New Carousel</span>
                            </a>
                        @endcan

                        @can('view supporters')
                            <a href="{{ route('supporters.index') }}" class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow flex flex-col items-center justify-center hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 text-center">New Supporter</span>
                            </a>
                        @endcan

                </div>
            </div>
            @endcan


            @canany(['view bureaus', 'view sections', 'view users', 'view roles', 'view permissions', 'view supporters'])
            <div class="mb-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Maintenance Quick Actions</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">

                    @can('view bureaus')
                    <a href="{{ route('bureaus.index') }}" class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow flex flex-col items-center justify-center hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M9 21V3m6 18V3" />
                        </svg>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300 text-center">New Bureau</span>
                    </a>
                    @endcan

                    @can('view sections')
                    <a href="{{ route('sections.index') }}" class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow flex flex-col items-center justify-center hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <svg class="h-6 w-6 text-green-600 dark:text-green-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300 text-center">New Section</span>
                    </a>
                    @endcan

                    @can('view users')
                    <a href="{{ route('users.index') }}" class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow flex flex-col items-center justify-center hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <svg class="h-6 w-6 text-blue-600 dark:text-blue-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A4 4 0 0112 15a4 4 0 016.879 2.804M12 7a4 4 0 110-8 4 4 0 010 8z" />
                        </svg>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300 text-center">New User</span>
                    </a>
                    @endcan

                    @can('view roles')
                    <a href="{{ route('roles.index') }}" class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow flex flex-col items-center justify-center hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 00-8 0v4H5l7 7 7-7h-3V7z" />
                        </svg>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300 text-center">New Role</span>
                    </a>
                    @endcan

                    @can('view permissions')
                    <a href="{{ route('permissions.index') }}" class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow flex flex-col items-center justify-center hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <svg class="h-6 w-6 text-red-600 dark:text-red-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m2 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300 text-center">New Permission</span>
                    </a>
                    @endcan

                </div>
            </div>
            @endcan





            
        </div>
    </div>
</x-app-layout>