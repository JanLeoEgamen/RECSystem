<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center"> 
            <h2 class="font-semibold text-4xl text-white dark:text-gray-200 leading-tight">
                {{ __('Membership Validity') }}
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
                    <!-- Column Selector Button -->
                    <button id="columnToggle" class="mb-4 inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg dark:bg-gray-700 dark:hover:bg-gray-600">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                        </svg>
                        Configure Columns
                    </button>
                    
                    <!-- Column Selector Dropdown -->
                    <div id="columnSelector" class="hidden mb-6 p-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg">
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            <!-- Member Information -->
                            <div class="space-y-2">
                                <h4 class="font-medium text-gray-700 dark:text-gray-300">Member Information</h4>
                                <div class="flex items-center">
                                    <input type="checkbox" id="col-full_name" class="column-toggle" data-column="1" checked>
                                    <label for="col-full_name" class="ml-2">Member Name</label>
                                </div>
                            </div>
                            
                            <!-- Membership Information -->
                            <div class="space-y-2">
                                <h4 class="font-medium text-gray-700 dark:text-gray-300">Membership</h4>
                                <div class="flex items-center">
                                    <input type="checkbox" id="col-status" class="column-toggle" data-column="2" checked>
                                    <label for="col-status" class="ml-2">Status</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="col-membership_type" class="column-toggle" data-column="3" checked>
                                    <label for="col-membership_type" class="ml-2">Membership Type</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="col-days_left" class="column-toggle" data-column="4" checked>
                                    <label for="col-days_left" class="ml-2">Days Left</label>
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
                    
                    <table id="membershipValidityTable" class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr class="border-b">
                                <th class="px-6 py-3 text-left">#</th>
                                <th class="px-6 py-3 text-left">Member Name</th>
                                <th class="px-6 py-3 text-left">Status</th>
                                <th class="px-6 py-3 text-left">Membership Type</th>
                                <th class="px-6 py-3 text-left">Days Left</th>
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
                // Initialize DataTable
                var table = $('#membershipValidityTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('members.validity') }}",
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'full_name', name: 'full_name' },
                        { data: 'status', name: 'status' },
                        { data: 'membershipType.name', name: 'membershipType.name' },
                        { data: 'days_left', name: 'days_left' }
                    ],
                    responsive: true,
                    autoWidth: false,
                    lengthMenu: [10, 25, 50, 100],
                    pageLength: 10,
                    order: [[4, 'asc']], // Default sort by days left ascending
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
                    var preferences = JSON.parse(localStorage.getItem('validityTableColumns')) || {};
                    
                    table.columns().every(function(index) {
                        // Skip index column (0)
                        if (index > 0) {
                            var colName = this.header().textContent.trim().toLowerCase().replace(/[^a-z0-9]+/g, '_');
                            var visible = preferences[colName] !== undefined ? preferences[colName] : true;
                            
                            this.visible(visible);
                            $(`.column-toggle[data-column="${index}"]`).prop('checked', visible);
                        }
                    });
                }
                
                // Save column preferences to localStorage
                function saveColumnPreferences() {
                    var preferences = {};
                    
                    table.columns().every(function(index) {
                        if (index > 0) {
                            var colName = this.header().textContent.trim().toLowerCase().replace(/[^a-z0-9]+/g, '_');
                            preferences[colName] = this.visible();
                        }
                    });
                    
                    localStorage.setItem('validityTableColumns', JSON.stringify(preferences));
                }
                
                // Reset to default columns
                function resetToDefaultColumns() {
                    table.columns().every(function(index) {
                        if (index > 0) {
                            this.visible(true);
                            $(`.column-toggle[data-column="${index}"]`).prop('checked', true);
                        }
                    });
                    
                    localStorage.removeItem('validityTableColumns');
                }
                
                // Show toast notification
                function showToast(message) {
                    // You can replace this with your preferred toast implementation
                    alert(message); // Simple alert for demonstration
                }
            });
        </script>
    </x-slot>
</x-app-layout>