<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between"> 
            <h2 class="font-semibold text-4xl text-white dark:text-gray-200 leading-tight">
                {{ __('Approved Applicants') }}
            </h2>
            <div class="flex space-x-4">
                    <a href="{{ route('applicants.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md flex items-center">
                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Applicants
                    </a>        
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-message></x-message>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table id="approvedApplicantsTable" class="w-full">
                        <thead class="bg-gray-50">
                            <tr class="border-b">
                                <th class="px-6 py-3 text-left">#</th>
                                <th class="px-6 py-3 text-left">Full Name</th>
                                <th class="px-6 py-3 text-left">Sex</th>
                                <th class="px-6 py-3 text-left">Birthdate</th>
                                <th class="px-6 py-3 text-left">Contact No.</th>
                                <th class="px-6 py-3 text-left">Approved On</th>
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
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
        <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
        
        <script type="text/javascript">
            $(document).ready(function() {
                $('#approvedApplicantsTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('applicants.approved') }}",
                    columns: [
                        { 
                            data: 'DT_RowIndex', 
                            name: 'DT_RowIndex', 
                            orderable: false, 
                            searchable: false 
                        },
                        { 
                            data: 'full_name', 
                            name: 'first_name' 
                        },
                        { 
                            data: 'sex', 
                            name: 'sex' 
                        },
                        { 
                            data: 'birthdate', 
                            name: 'birthdate' 
                        },
                        { 
                            data: 'cellphone_no', 
                            name: 'cellphone_no' 
                        },
                        { 
                            data: 'updated_at', 
                            name: 'updated_at' 
                        },
                        { 
                            data: 'action', 
                            name: 'action', 
                            orderable: false, 
                            searchable: false 
                        }
                    ],
                    responsive: true,
                    autoWidth: false,
                    lengthMenu: [10, 25, 50, 100],
                    pageLength: 10,
                    order: [[5, 'desc']] // Sort by approval date
                });
            });
        </script>
    </x-slot>
</x-app-layout>