<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between"> 
            <h2 class="font-semibold text-4xl text-white dark:text-gray-200 leading-tight">
                Members / Edit
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
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('members.update', $member->id) }}" method="post">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Personal Information -->
                            <div class="col-span-2">
                                <h3 class="text-xl font-semibold mb-4">Personal Information</h3>
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                    <div>
                                        <label for="first_name" class="block text-sm font-medium">First Name *</label>
                                        <input value="{{ old('first_name', $member->first_name) }}" name="first_name" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        @error('first_name')
                                        <p class="text-red-400 font-medium text-sm">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="middle_name" class="block text-sm font-medium">Middle Name</label>
                                        <input value="{{ old('middle_name', $member->middle_name) }}" name="middle_name" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        @error('middle_name')
                                        <p class="text-red-400 font-medium text-sm">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="last_name" class="block text-sm font-medium">Last Name *</label>
                                        <input value="{{ old('last_name', $member->last_name) }}" name="last_name" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        @error('last_name')
                                        <p class="text-red-400 font-medium text-sm">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="suffix" class="block text-sm font-medium">Suffix</label>
                                        <input value="{{ old('suffix', $member->suffix) }}" name="suffix" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        @error('suffix')
                                        <p class="text-red-400 font-medium text-sm">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
                                    <div>
                                        <label for="sex" class="block text-sm font-medium">Sex *</label>
                                        <select name="sex" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                            <option value="">Select</option>
                                            <option value="Male" {{ old('sex', $member->sex) == 'Male' ? 'selected' : '' }}>Male</option>
                                            <option value="Female" {{ old('sex', $member->sex) == 'Female' ? 'selected' : '' }}>Female</option>
                                        </select>
                                        @error('sex')
                                        <p class="text-red-400 font-medium text-sm">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="birthdate" class="block text-sm font-medium">Birthdate *</label>
                                        <input value="{{ old('birthdate', $member->birthdate ? \Carbon\Carbon::parse($member->birthdate)->format('Y-m-d') : '') }}" name="birthdate" type="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        @error('birthdate')
                                        <p class="text-red-400 font-medium text-sm">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="civil_status" class="block text-sm font-medium">Civil Status *</label>
                                        <select name="civil_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                            <option value="">Select</option>
                                            <option value="Single" {{ old('civil_status', $member->civil_status) == 'Single' ? 'selected' : '' }}>Single</option>
                                            <option value="Married" {{ old('civil_status', $member->civil_status) == 'Married' ? 'selected' : '' }}>Married</option>
                                            <option value="Widowed" {{ old('civil_status', $member->civil_status) == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                            <option value="Separated" {{ old('civil_status', $member->civil_status) == 'Separated' ? 'selected' : '' }}>Separated</option>
                                        </select>
                                        @error('civil_status')
                                        <p class="text-red-400 font-medium text-sm">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="citizenship" class="block text-sm font-medium">Citizenship *</label>
                                        <input value="{{ old('citizenship', $member->citizenship) }}" name="citizenship" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        @error('citizenship')
                                        <p class="text-red-400 font-medium text-sm">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
                                    <div>
                                        <label for="blood_type" class="block text-sm font-medium">Blood Type</label>
                                        <select name="blood_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                            <option value="">Select</option>
                                            <option value="A+" {{ old('blood_type', $member->blood_type) == 'A+' ? 'selected' : '' }}>A+</option>
                                            <option value="A-" {{ old('blood_type', $member->blood_type) == 'A-' ? 'selected' : '' }}>A-</option>
                                            <option value="B+" {{ old('blood_type', $member->blood_type) == 'B+' ? 'selected' : '' }}>B+</option>
                                            <option value="B-" {{ old('blood_type', $member->blood_type) == 'B-' ? 'selected' : '' }}>B-</option>
                                            <option value="AB+" {{ old('blood_type', $member->blood_type) == 'AB+' ? 'selected' : '' }}>AB+</option>
                                            <option value="AB-" {{ old('blood_type', $member->blood_type) == 'AB-' ? 'selected' : '' }}>AB-</option>
                                            <option value="O+" {{ old('blood_type', $member->blood_type) == 'O+' ? 'selected' : '' }}>O+</option>
                                            <option value="O-" {{ old('blood_type', $member->blood_type) == 'O-' ? 'selected' : '' }}>O-</option>
                                        </select>
                                        @error('blood_type')
                                        <p class="text-red-400 font-medium text-sm">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <div>
                                <h3 class="text-xl font-semibold mb-4">Contact Information</h3>
                                <div class="space-y-4">
                                    <div>
                                        <label for="cellphone_no" class="block text-sm font-medium">Cellphone No. *</label>
                                        <input value="{{ old('cellphone_no', $member->cellphone_no) }}" name="cellphone_no" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        @error('cellphone_no')
                                        <p class="text-red-400 font-medium text-sm">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="telephone_no" class="block text-sm font-medium">Telephone No.</label>
                                        <input value="{{ old('telephone_no', $member->telephone_no) }}" name="telephone_no" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        @error('telephone_no')
                                        <p class="text-red-400 font-medium text-sm">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="email_address" class="block text-sm font-medium">Email Address *</label>
                                        <input value="{{ old('email_address', $member->email_address) }}" name="email_address" type="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        @error('email_address')
                                        <p class="text-red-400 font-medium text-sm">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Emergency Contact -->
                            <div>
                                <h3 class="text-xl font-semibold mb-4">Emergency Contact</h3>
                                <div class="space-y-4">
                                    <div>
                                        <label for="emergency_contact" class="block text-sm font-medium">Name *</label>
                                        <input value="{{ old('emergency_contact', $member->emergency_contact) }}" name="emergency_contact" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        @error('emergency_contact')
                                        <p class="text-red-400 font-medium text-sm">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="emergency_contact_number" class="block text-sm font-medium">Contact No. *</label>
                                        <input value="{{ old('emergency_contact_number', $member->emergency_contact_number) }}" name="emergency_contact_number" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        @error('emergency_contact_number')
                                        <p class="text-red-400 font-medium text-sm">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="relationship" class="block text-sm font-medium">Relationship *</label>
                                        <input value="{{ old('relationship', $member->relationship) }}" name="relationship" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        @error('relationship')
                                        <p class="text-red-400 font-medium text-sm">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Membership Information -->
                            <div class="col-span-2">
                                <h3 class="text-xl font-semibold mb-4">Membership Information</h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label for="rec_number" class="block text-sm font-medium">Record Number *</label>
                                        <input value="{{ old('rec_number', $member->rec_number) }}" name="rec_number" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        @error('rec_number')
                                        <p class="text-red-400 font-medium text-sm">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="membership_type_id" class="block text-sm font-medium">Membership Type *</label>
                                        <select name="membership_type_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                            <option value="">Select Type</option>
                                            @foreach($membershipTypes as $type)
                                                <option value="{{ $type->id }}" {{ old('membership_type_id', $member->membership_type_id) == $type->id ? 'selected' : '' }}>
                                                    {{ $type->type_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('membership_type_id')
                                        <p class="text-red-400 font-medium text-sm">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="section_id" class="block text-sm font-medium">Section</label>
                                        <select name="section_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                            <option value="">Select Section</option>
                                            @foreach($sections as $section)
                                                <option value="{{ $section->id }}" {{ old('section_id', $member->section_id) == $section->id ? 'selected' : '' }}>
                                                    {{ $section->section_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('section_id')
                                        <p class="text-red-400 font-medium text-sm">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                                    <div>
                                        <label for="membership_start" class="block text-sm font-medium">Membership Start *</label>
                                        <input value="{{ old('membership_start', $member->membership_start ? \Carbon\Carbon::parse($member->membership_start)->format('Y-m-d') : '') }}" name="membership_start" type="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        @error('membership_start')
                                        <p class="text-red-400 font-medium text-sm">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="membership_end" class="block text-sm font-medium">Membership End</label>
                                        <input value="{{ old('membership_end', $member->membership_end ? \Carbon\Carbon::parse($member->membership_end)->format('Y-m-d') : '') }}" name="membership_end" type="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" {{ $member->is_lifetime_member ? 'disabled' : '' }}>
                                        @error('membership_end')
                                        <p class="text-red-400 font-medium text-sm">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" id="is_lifetime_member" name="is_lifetime_member" value="1" {{ old('is_lifetime_member', $member->is_lifetime_member) ? 'checked' : '' }} class="rounded">
                                        <label for="is_lifetime_member" class="ml-2 block text-sm font-medium">Lifetime Member</label>
                                        @error('is_lifetime_member')
                                        <p class="text-red-400 font-medium text-sm">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <label for="last_renewal_date" class="block text-sm font-medium">Last Renewal Date</label>
                                    <input value="{{ old('last_renewal_date', $member->last_renewal_date ? \Carbon\Carbon::parse($member->last_renewal_date)->format('Y-m-d') : '') }}" name="last_renewal_date" type="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    @error('last_renewal_date')
                                    <p class="text-red-400 font-medium text-sm">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mt-4">
                                    <label class="block text-sm font-medium mb-2">Status *</label>
                                    <div class="flex items-center space-x-6">
                                        <label class="inline-flex items-centmer">
                                            <input type="radio" name="status" value="Active" {{ old('status', $member->status) == 'Active' ? 'checked' : '' }} class="form-radio text-[#101966]">
                                            <span class="ml-2">Active</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="status" value="Inactive" {{ old('status', $member->status) == 'Inactive' ? 'checked' : '' }} class="form-radio text-[#101966]">
                                            <span class="ml-2">Inactive</span>
                                        </label>
                                    </div>
                                    @error('status')
                                        <p class="text-red-400 font-medium text-sm">{{ $message }}</p>
                                    @enderror
                                </div>

                            </div>

                            <!-- License Information -->
                            <div class="col-span-2">
                                <h3 class="text-xl font-semibold mb-4">License Information</h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label for="licence_class" class="block text-sm font-medium">License Class</label>
                                        <input value="{{ old('licence_class', $member->licence_class) }}" name="licence_class" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        @error('licence_class')
                                        <p class="text-red-400 font-medium text-sm">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="license_number" class="block text-sm font-medium">License Number</label>
                                        <input value="{{ old('license_number', $member->license_number) }}" name="license_number" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        @error('license_number')
                                        <p class="text-red-400 font-medium text-sm">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="license_expiration_date" class="block text-sm font-medium">Expiration Date</label>
                                        <input value="{{ old('license_expiration_date', $member->license_expiration_date ? \Carbon\Carbon::parse($member->license_expiration_date)->format('Y-m-d') : '') }}" name="license_expiration_date" type="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        @error('license_expiration_date')
                                        <p class="text-red-400 font-medium text-sm">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Address Information -->
                            <div class="col-span-2">
                                <h3 class="text-xl font-semibold mb-4">Address Information</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="house_building_number_name" class="block text-sm font-medium">House/Building No./Name *</label>
                                        <input value="{{ old('house_building_number_name', $member->house_building_number_name) }}" name="house_building_number_name" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        @error('house_building_number_name')
                                        <p class="text-red-400 font-medium text-sm">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="street_address" class="block text-sm font-medium">Street Address *</label>
                                        <input value="{{ old('street_address', $member->street_address) }}" name="street_address" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        @error('street_address')
                                        <p class="text-red-400 font-medium text-sm">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
                                    <div>
                                        <label for="region" class="block text-sm font-medium">Region *</label>
                                        <input value="{{ old('region', $member->region) }}" name="region" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        @error('region')
                                        <p class="text-red-400 font-medium text-sm">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="province" class="block text-sm font-medium">Province *</label>
                                        <input value="{{ old('province', $member->province) }}" name="province" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        @error('province')
                                        <p class="text-red-400 font-medium text-sm">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="municipality" class="block text-sm font-medium">Municipality *</label>
                                        <input value="{{ old('municipality', $member->municipality) }}" name="municipality" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        @error('municipality')
                                        <p class="text-red-400 font-medium text-sm">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="barangay" class="block text-sm font-medium">Barangay *</label>
                                        <input value="{{ old('barangay', $member->barangay) }}" name="barangay" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        @error('barangay')
                                        <p class="text-red-400 font-medium text-sm">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <label for="zip_code" class="block text-sm font-medium">Zip Code *</label>
                                    <input value="{{ old('zip_code', $member->zip_code) }}" name="zip_code" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    @error('zip_code')
                                    <p class="text-red-400 font-medium text-sm">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>



                        <div class="mt-6">
                            <button type="submit" class="flex items-center px-4 py-2 text-sm text-blue-600 hover:text-white hover:bg-blue-600 rounded-md transition-colors duration-200 border border-blue-100 hover:border-blue-600 font-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Update
                            </button>
                        </div>


                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const lifetimeCheckbox = document.getElementById('is_lifetime_member');
            const membershipEndInput = document.querySelector('input[name="membership_end"]');
            
            lifetimeCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    membershipEndInput.disabled = true;
                    membershipEndInput.value = '';
                } else {
                    membershipEndInput.disabled = false;
                }
            });
        });
    </script>
</x-app-layout>