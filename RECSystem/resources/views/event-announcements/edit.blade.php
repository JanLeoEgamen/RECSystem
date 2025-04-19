<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between"> 
            <h2 class="font-semibold text-4xl text-white dark:text-gray-200 leading-tight">
                    Event Announcements / Edit
            </h2>
            <a href="{{ route('event-announcements.index') }}" class="inline-block px-5 py-2 text-white hover:text-[#101966] hover:border-[#101966] bg-[#101966] hover:bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#101966] border border-white border font-medium dark:border-[#3E3E3A] dark:hover:bg-black dark:hover:border-[#3F53E8] rounded-lg text-xl leading-normal">Back</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('event-announcements.update', $eventAnnouncement->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div>
                            <label for="event_name" class="text-sm font-medium">Event Name</label>
                            <div class="my-3">    
                                <input value="{{ old('event_name', $eventAnnouncement->event_name) }}" name="event_name" placeholder="Enter event name" type="text" class="border-gray-300 shadow-sm w-1/2 rounded-lg">
                                @error('event_name')
                                <p class="text-red-400 font-medium"> {{ $message }} </p>
                                @enderror
                            </div>

                            <label for="event_date" class="text-sm font-medium">Event Date</label>
                            <div class="my-3">    
                                <input value="{{ old('event_date', $eventAnnouncement->event_date->format('Y-m-d')) }}" name="event_date" type="date" class="border-gray-300 shadow-sm w-1/2 rounded-lg">
                                @error('event_date')
                                <p class="text-red-400 font-medium"> {{ $message }} </p>
                                @enderror
                            </div>

                            <label for="year" class="text-sm font-medium">Year</label>
                            <div class="my-3">    
                                <input value="{{ old('year', $eventAnnouncement->year) }}" name="year" placeholder="Enter year" type="number" min="2000" max="2100" class="border-gray-300 shadow-sm w-1/2 rounded-lg">
                                @error('year')
                                <p class="text-red-400 font-medium"> {{ $message }} </p>
                                @enderror
                            </div>

                            <label for="caption" class="text-sm font-medium">Caption</label>
                            <div class="my-3">    
                                <textarea name="caption" placeholder="Enter caption" class="border-gray-300 shadow-sm w-1/2 rounded-lg">{{ old('caption', $eventAnnouncement->caption) }}</textarea>
                                @error('caption')
                                <p class="text-red-400 font-medium"> {{ $message }} </p>
                                @enderror
                            </div>

                            <label for="image" class="text-sm font-medium">Image</label>
                            <div class="my-3">
                                @if($eventAnnouncement->image)
                                    <img src="{{ asset('images/' . $eventAnnouncement->image) }}" alt="Event Image" class="h-40 w-40 object-cover mb-2">
                                @endif
                                <input name="image" type="file" class="border-gray-300 shadow-sm w-1/2 rounded-lg">
                                @error('image')
                                <p class="text-red-400 font-medium"> {{ $message }} </p>
                                @enderror
                            </div>

                            <div class="my-3 flex items-center">
                                <input type="hidden" name="status" value="0">
                                <input type="checkbox" name="status" id="status" class="rounded" value="1" 
                                    {{ old('status', $eventAnnouncement->status) ? 'checked' : '' }}>
                                <label for="status" class="ml-2">Active</label>
                            </div>

                            <button class="inline-block px-5 py-2 text-white hover:text-[#101966] hover:border-[#101966] bg-[#101966] hover:bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#101966] border border-white border font-medium dark:border-[#3E3E3A] dark:hover:bg-black dark:hover:border-[#3F53E8] rounded-lg text-xl leading-normal">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>