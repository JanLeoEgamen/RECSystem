<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-4xl text-white dark:text-gray-200 leading-tight">
                {{ __('Renew Membership') }}
            </h2>
                    <a href="{{ route('members.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md flex items-center">
                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Members
                    </a>                
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-message></x-message>
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('members.renew', $member->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 gap-6">
                            <!-- Member Information -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-medium">Member Information</h3>
                                <div>
                                    <p class="text-sm font-medium">Name:</p>
                                    <p class="mt-1">{{ $member->first_name }} {{ $member->last_name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium">Current Membership Type:</p>
                                    <p class="mt-1">{{ $member->membershipType->type_name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium">Current Expiration:</p>
                                    <p class="mt-1">
                                        @if($member->is_lifetime_member)
                                            Lifetime Membership
                                        @else
                                            {{ $member->membership_end ? \Carbon\Carbon::parse($member->membership_end)->format('M d, Y') : 'Not set' }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Renewal Options -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-medium">Renewal Options</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label for="years" class="block text-sm font-medium">Years</label>
                                        <input type="number" name="years" id="years" 
                                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm" 
                                               min="0" value="0">
                                    </div>
                                    
                                    <div>
                                        <label for="months" class="block text-sm font-medium">Months</label>
                                        <input type="number" name="months" id="months" 
                                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm" 
                                               min="0" max="11" value="0">
                                    </div>
                                    
                                    <div>
                                        <label for="days" class="block text-sm font-medium">Days</label>
                                        <input type="number" name="days" id="days" 
                                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm" 
                                               min="0" max="30" value="0">
                                    </div>
                                </div>
                                
                                <div class="mt-4">
                                    <label class="inline-flex items-center">
                                    <input type="checkbox" name="make_lifetime" id="make_lifetime" value="1" class="rounded">
                                        <span class="ml-2">Make this a lifetime membership</span>
                                    </label>
                                </div>
                                
                                <div class="mt-4 bg-blue-50 dark:bg-blue-900 p-4 rounded-lg">
                                    <p class="text-sm font-medium">New expiration date will be:</p>
                                    <p id="new_expiration_date" class="font-semibold mt-1">
                                        {{ $member->is_lifetime_member ? 'Lifetime Membership' : 
                                           ($member->membership_end ? \Carbon\Carbon::parse($member->membership_end)->format('M d, Y') : 'Not calculated') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="flex items-center px-4 py-2 text-sm text-green-600 hover:text-white hover:bg-green-600 rounded-md transition-colors duration-200 border border-green-100 hover:border-green-600 font-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Process Renewal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const yearsInput = document.getElementById('years');
            const monthsInput = document.getElementById('months');
            const daysInput = document.getElementById('days');
            const lifetimeCheckbox = document.getElementById('make_lifetime');
            const expirationDisplay = document.getElementById('new_expiration_date');
            
            // Current expiration date or today if none
            const currentExpiration = '{{ $member->membership_end ? $member->membership_end : now() }}';
            let baseDate = currentExpiration ? new Date(currentExpiration) : new Date();
            
            // Function to calculate and display new expiration date
            function updateExpirationDate() {
                if (lifetimeCheckbox.checked) {
                    expirationDisplay.textContent = 'Lifetime Membership';
                    yearsInput.disabled = monthsInput.disabled = daysInput.disabled = true;
                    return;
                }
                
                yearsInput.disabled = monthsInput.disabled = daysInput.disabled = false;
                
                const years = parseInt(yearsInput.value) || 0;
                const months = parseInt(monthsInput.value) || 0;
                const days = parseInt(daysInput.value) || 0;
                
                if (years === 0 && months === 0 && days === 0) {
                    expirationDisplay.textContent = 'Please enter a duration';
                    return;
                }
                
                // Calculate new date
                const newDate = new Date(baseDate);
                newDate.setFullYear(newDate.getFullYear() + years);
                newDate.setMonth(newDate.getMonth() + months);
                newDate.setDate(newDate.getDate() + days);
                
                // Format the date for display
                const options = { year: 'numeric', month: 'short', day: 'numeric' };
                expirationDisplay.textContent = newDate.toLocaleDateString('en-US', options);
            }
            
            // Add event listeners
            [yearsInput, monthsInput, daysInput, lifetimeCheckbox].forEach(element => {
                element.addEventListener('change', updateExpirationDate);
                element.addEventListener('input', updateExpirationDate);
            });
            
            // Initialize
            updateExpirationDate();
        });
    </script>
</x-app-layout>