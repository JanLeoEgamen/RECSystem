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

            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr class="border-b">
                        <th class="px-6 py-3 text-left" width="60">#</th>
                        <th class="px-6 py-3 text-left">Event Name</th>
                        <th class="px-6 py-3 text-left">Date</th>
                        <th class="px-6 py-3 text-left">Image</th>
                        <th class="px-6 py-3 text-left">Author</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left" width="180">Created</th>
                        <th class="px-6 py-3 text-center" width="180">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @if ($eventAnnouncements->isNotEmpty())
                    @foreach ($eventAnnouncements as $eventAnnouncement)
                    <tr class="border-b">
                        <td class="px-6 py-3 text-left">
                            {{ $eventAnnouncement->id }}
                        </td>
                        <td class="px-6 py-3 text-left">
                            {{ $eventAnnouncement->event_name }}
                        </td>
                        <td class="px-6 py-3 text-left">
                            {{ \Carbon\Carbon::parse($eventAnnouncement->event_date)->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-3 text-left">
                            <div class="relative w-40" style="aspect-ratio: 16/9;">
                                @if($eventAnnouncement->image)
                                    <img src="{{ asset('storage/' . $eventAnnouncement->image) }}" alt="Event Image" class="h-20 w-20 object-cover">
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-3 text-left">
                            {{ $eventAnnouncement->user->name }}
                        </td>
                        <td class="px-6 py-3 text-left">
                            @if($eventAnnouncement->status)
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Active</span>
                            @else
                                <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-3 text-left">
                            {{ \Carbon\Carbon::parse($eventAnnouncement->created_at)->format('d M, y')}}
                        </td>
                        <td class="px-6 py-3 text-center">
                            @can('edit event announcements')
                            <a href="{{ route('event-announcements.edit', $eventAnnouncement->id) }}" class="inline-block mb-2 px-5 py-2 text-white hover:text-[#101966] hover:border-[#101966] bg-[#101966] hover:bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#101966] border border-white border font-medium dark:border-[#3E3E3A] dark:hover:bg-black dark:hover:border-[#3F53E8] rounded-lg text-md leading-normal">Edit</a>
                            @endcan

                            @can('delete event announcements')
                            <a href="javascript:void(0)" onclick="deleteEventAnnouncement({{ $eventAnnouncement->id }})" class="inline-block px-3 py-2 text-white hover:text-[#a10303] hover:border-[#a10303] bg-[#a10303] hover:bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#a10303] border border-white border font-medium dark:border-[#3E3E3A] dark:hover:bg-black dark:hover:border-[#3F53E8] rounded-lg text-md leading-normal">Delete</a>
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="8" class="px-6 py-3 text-center">No event announcements found</td>
                    </tr>
                    @endif
                </tbody>
            </table>

            <div class="my-3">
                {{ $eventAnnouncements->links() }}
            </div>
        </div>
    </div>

    <x-slot name="script">
        <script type="text/javascript">
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
                            window.location.href = '{{ route("event-announcements.index")}}'
                        }
                    });
                }
            }
        </script>
    </x-slot>
</x-app-layout>