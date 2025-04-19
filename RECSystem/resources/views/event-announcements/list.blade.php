<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between"> 
            <h2 class="font-semibold text-4xl text-white dark:text-gray-200 leading-tight">
                {{ __('Event Announcements') }}
            </h2>
            @can('create event announcements')
            <a href="{{ route('event-announcements.create') }}" class="inline-block px-5 py-2 text-white hover:text-[#101966] hover:border-[#101966] bg-[#101966] hover:bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#101966] border border-white border font-medium dark:border-[#3E3E3A] dark:hover:bg-black dark:hover:border-[#3F53E8] rounded-lg text-xl leading-normal">Create</a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-message></x-message>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table id="eventAnnouncementsTable" class="w-full">
                        <thead class="bg-gray-50">
                            <tr class="border-b">
                                <th class="px-6 py-3 text-left">#</th>
                                <th class="px-6 py-3 text-left">Event Name</th>
                                <th class="px-6 py-3 text-left">Date</th>
                                <th class="px-6 py-3 text-left">Image</th>
                                <th class="px-6 py-3 text-left">Author</th>
                                <th class="px-6 py-3 text-left">Status</th>
                                <th class="px-6 py-3 text-left">Created</th>
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
                $('#eventAnnouncementsTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('event-announcements.index') }}",
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'event_name', name: 'event_name' },
                        { data: 'event_date', name: 'event_date' },
                        { data: 'image', name: 'image', orderable: false, searchable: false },
                        { data: 'author', name: 'user.first_name' },
                        { data: 'status', name: 'status' },
                        { data: 'created_at', name: 'created_at' },
                        { data: 'action', name: 'action', orderable: false, searchable: false }
                    ],
                    responsive: true,
                    autoWidth: false,
                    lengthMenu: [10, 25, 50, 100],
                    pageLength: 10,
                    order: [[1, 'asc']] // Default sort by event name
                });
            });

            function deleteEventAnnouncement(id) {
                if (confirm("Are you sure you want to delete?")) {
                    $.ajax({
                        url: '{{ route("event-announcements.destroy") }}',
                        type: 'delete',
                        data: {id: id},
                        dataType: 'json',
                        headers: {
                            'x-csrf-token': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            $('#eventAnnouncementsTable').DataTable().ajax.reload();
                        }
                    });
                }
            }
        </script>
    </x-slot>
</x-app-layout>