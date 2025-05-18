<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between"> 
            <h2 class="font-semibold text-4xl text-white dark:text-gray-200 leading-tight">
                {{ __('Members') }}
            </h2>
            @can('create members')
            <a href="{{ route('members.create') }}" class="inline-block px-5 py-2 text-white hover:text-[#101966] hover:border-[#101966] bg-[#101966] hover:bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#101966] border border-white border font-medium dark:border-[#3E3E3A] dark:hover:bg-black dark:hover:border-[#3F53E8] rounded-lg text-xl leading-normal">Create</a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-message></x-message>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Column Selector Button -->
                    <button id="columnToggle" class="mb-4 inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg dark:bg-gray-700 dark:hover:bg-gray-600">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                        </svg>
                        Configure Columns
                    </button>
                    
                    <!-- Column Selector Dropdown -->
                    <div id="columnSelector" class="hidden mb-6 p-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg">
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            <!-- Personal Information -->
                            <div class="space-y-2">
                                <h4 class="font-medium text-gray-700 dark:text-gray-300">Personal Information</h4>
                                <div class="flex items-center">
                                    <input type="checkbox" id="col-first_name" class="column-toggle" data-column="1" checked>
                                    <label for="col-first_name" class="ml-2">First Name</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="col-middle_name" class="column-toggle" data-column="2">
                                    <label for="col-middle_name" class="ml-2">Middle Name</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="col-last_name" class="column-toggle" data-column="3" checked>
                                    <label for="col-last_name" class="ml-2">Last Name</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="col-suffix" class="column-toggle" data-column="4">
                                    <label for="col-suffix" class="ml-2">Suffix</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="col-sex" class="column-toggle" data-column="5">
                                    <label for="col-sex" class="ml-2">Sex</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="col-birthdate" class="column-toggle" data-column="6">
                                    <label for="col-birthdate" class="ml-2">Birthdate</label>
                                </div>
                            </div>
                            
                            <!-- Contact Information -->
                            <div class="space-y-2">
                                <h4 class="font-medium text-gray-700 dark:text-gray-300">Contact Information</h4>
                                <div class="flex items-center">
                                    <input type="checkbox" id="col-cellphone_no" class="column-toggle" data-column="7">
                                    <label for="col-cellphone_no" class="ml-2">Cellphone</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="col-telephone_no" class="column-toggle" data-column="8">
                                    <label for="col-telephone_no" class="ml-2">Telephone</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="col-email_address" class="column-toggle" data-column="9">
                                    <label for="col-email_address" class="ml-2">Email</label>
                                </div>
                            </div>
                            
                            <!-- Membership Information -->
                            <div class="space-y-2">
                                <h4 class="font-medium text-gray-700 dark:text-gray-300">Membership</h4>
                                <div class="flex items-center">
                                    <input type="checkbox" id="col-rec_number" class="column-toggle" data-column="10" checked>
                                    <label for="col-rec_number" class="ml-2">Record No.</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="col-membership_type" class="column-toggle" data-column="11" checked>
                                    <label for="col-membership_type" class="ml-2">Membership Type</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="col-section" class="column-toggle" data-column="12">
                                    <label for="col-section" class="ml-2">Section</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="col-membership_start" class="column-toggle" data-column="13" checked>
                                    <label for="col-membership_start" class="ml-2">Start Date</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="col-membership_end" class="column-toggle" data-column="14" checked>
                                    <label for="col-membership_end" class="ml-2">End Date</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="col-is_lifetime_member" class="column-toggle" data-column="15" checked>
                                    <label for="col-is_lifetime_member" class="ml-2">Lifetime</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="col-status" class="column-toggle" data-column="16" checked>
                                    <label for="col-status" class="ml-2">Status</label>
                                </div>

                            </div>
                            
                            <!-- Address Information -->
                            <div class="space-y-2">
                                <h4 class="font-medium text-gray-700 dark:text-gray-300">Address</h4>
                                <div class="flex items-center">
                                    <input type="checkbox" id="col-street_address" class="column-toggle" data-column="17">
                                    <label for="col-street_address" class="ml-2">Street Address</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="col-region" class="column-toggle" data-column="18">
                                    <label for="col-region" class="ml-2">Region</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="col-province" class="column-toggle" data-column="19">
                                    <label for="col-province" class="ml-2">Province</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="col-municipality" class="column-toggle" data-column="20">
                                    <label for="col-municipality" class="ml-2">Municipality</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="col-barangay" class="column-toggle" data-column="21">
                                    <label for="col-barangay" class="ml-2">Barangay</label>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-between">
                            <button id="resetColumns" class="px-3 py-1 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-gray-100">
                                Reset to Default
                            </button>
                            <button id="saveColumns" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                                Save Preferences
                            </button>
                        </div>
                    </div>
                    
                    <table id="membersTable" class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr class="border-b">
                                <th class="px-6 py-3 text-left">#</th>
                                <th class="px-6 py-3 text-left">First Name</th>
                                <th class="px-6 py-3 text-left">Middle Name</th>
                                <th class="px-6 py-3 text-left">Last Name</th>
                                <th class="px-6 py-3 text-left">Suffix</th>
                                <th class="px-6 py-3 text-left">Sex</th>
                                <th class="px-6 py-3 text-left">Birthdate</th>
                                <th class="px-6 py-3 text-left">Cellphone</th>
                                <th class="px-6 py-3 text-left">Telephone</th>
                                <th class="px-6 py-3 text-left">Email</th>
                                <th class="px-6 py-3 text-left">Record No.</th>
                                <th class="px-6 py-3 text-left">Membership Type</th>
                                <th class="px-6 py-3 text-left">Section</th>
                                <th class="px-6 py-3 text-left">Start Date</th>
                                <th class="px-6 py-3 text-left">End Date</th>
                                <th class="px-6 py-3 text-left">Lifetime</th>
                                <th class="px-6 py-3 text-left">Status</th>
                                <th class="px-6 py-3 text-left">Street Address</th>
                                <th class="px-6 py-3 text-left">Region</th>
                                <th class="px-6 py-3 text-left">Province</th>
                                <th class="px-6 py-3 text-left">Municipality</th>
                                <th class="px-6 py-3 text-left">Barangay</th>
                                <th class="px-6 py-3 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="script">
        <!-- Include DataTables CSS -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
        <!-- Include DataTables JS -->
        <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
        
        <script type="text/javascript">
            $(document).ready(function() {
                // Initialize DataTable with all columns
                var table = $('#membersTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('members.index') }}",
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'first_name', name: 'first_name' },
                        { data: 'middle_name', name: 'middle_name' },
                        { data: 'last_name', name: 'last_name' },
                        { data: 'suffix', name: 'suffix' },
                        { data: 'sex', name: 'sex' },
                        { data: 'birthdate', name: 'birthdate' },
                        { data: 'cellphone_no', name: 'cellphone_no' },
                        { data: 'telephone_no', name: 'telephone_no' },
                        { data: 'email_address', name: 'email_address' },
                        { data: 'rec_number', name: 'rec_number' },
                        { data: 'membership_type.type_name', name: 'membershipType.type_name' },
                        { data: 'section.section_name', name: 'section.section_name' },
                        { data: 'membership_start', name: 'membership_start' },
                        { data: 'membership_end', name: 'membership_end' },
                        { data: 'is_lifetime_member', name: 'is_lifetime_member' },
                        { data: 'status', name: 'status' }, 
                        { data: 'street_address', name: 'street_address' },
                        { data: 'region', name: 'region' },
                        { data: 'province', name: 'province' },
                        { data: 'municipality', name: 'municipality' },
                        { data: 'barangay', name: 'barangay' },
                        { data: 'action', name: 'action', orderable: false, searchable: false }
                    ],
                    responsive: true,
                    autoWidth: false,
                    lengthMenu: [10, 25, 50, 100],
                    pageLength: 10,
                    order: [[1, 'asc']],
                    initComplete: function() {
                        // Load saved preferences after table initialization
                        loadColumnPreferences();
                    }
                });

                // Toggle column selector visibility
                $('#columnToggle').click(function() {
                    $('#columnSelector').toggleClass('hidden');
                });

                // Handle column visibility toggling
                $('.column-toggle').on('change', function() {
                    var columnIndex = $(this).data('column');
                    var column = table.column(columnIndex);
                    column.visible(!column.visible());
                });

                // Save column preferences
                $('#saveColumns').click(function() {
                    saveColumnPreferences();
                    $('#columnSelector').addClass('hidden');
                    showToast('Column preferences saved!');
                });

                // Reset to default columns
                $('#resetColumns').click(function() {
                    resetToDefaultColumns();
                    showToast('Columns reset to default');
                });

                // Load saved column preferences
                function loadColumnPreferences() {
                    var preferences = JSON.parse(localStorage.getItem('memberTableColumns')) || {};
                    
                    table.columns().every(function(index) {
                        // Skip index column (0) and action column (last column)
                        if (index > 0 && index < table.columns().count() - 1) {
                            var colName = this.header().textContent.trim().toLowerCase().replace(/[^a-z0-9]+/g, '_');
                            var visible = preferences[colName] !== undefined ? preferences[colName] : getDefaultVisibility(index);
                            
                            this.visible(visible);
                            $(`.column-toggle[data-column="${index}"]`).prop('checked', visible);
                        }
                    });
                }
                
                // Get default visibility for a column
                function getDefaultVisibility(index) {
                    // Define which columns should be visible by default
                    var defaultVisible = [1, 3, 10, 11, 13, 14, 15, 16]; // First Name, Last Name, Rec No., etc.
                    return defaultVisible.includes(index);
                }
                
                // Save column preferences to localStorage
                function saveColumnPreferences() {
                    var preferences = {};
                    
                    table.columns().every(function(index) {
                        // Skip index column (0) and action column (last column)
                        if (index > 0 && index < table.columns().count() - 1) {
                            var colName = this.header().textContent.trim().toLowerCase().replace(/[^a-z0-9]+/g, '_');
                            preferences[colName] = this.visible();
                        }
                    });
                    
                    localStorage.setItem('memberTableColumns', JSON.stringify(preferences));
                }
                
                // Reset to default columns
                function resetToDefaultColumns() {
                    table.columns().every(function(index) {
                        // Skip index column (0) and action column (last column)
                        if (index > 0 && index < table.columns().count() - 1) {
                            var visible = getDefaultVisibility(index);
                            this.visible(visible);
                            $(`.column-toggle[data-column="${index}"]`).prop('checked', visible);
                        }
                    });
                    
                    localStorage.removeItem('memberTableColumns');
                }
                
                // Show toast notification
                function showToast(message) {
                    // You can replace this with your preferred toast implementation
                    alert(message); // Simple alert for demonstration
                }
                
                // Existing delete function
                function deleteMember(id) {
                    if (confirm("Are you sure you want to delete?")) {
                        $.ajax({
                            url: '{{ route("members.destroy") }}',
                            type: 'delete',
                            data: {id: id},
                            dataType: 'json',
                            headers: {
                                'x-csrf-token': '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                table.ajax.reload();
                            }
                        });
                    }
                }
            });
        </script>
    </x-slot>
</x-app-layout>