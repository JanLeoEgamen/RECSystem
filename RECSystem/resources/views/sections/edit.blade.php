<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between"> 
            <h2 class="font-semibold text-4xl text-white dark:text-gray-200 leading-tight">
                Sections / Edit
            </h2>
            <a href="{{ route('sections.index') }}" class="inline-block px-5 py-2 text-white hover:text-[#101966] hover:border-[#101966] bg-[#101966] hover:bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#101966] border border-white border font-medium dark:border-[#3E3E3A] dark:hover:bg-black dark:hover:border-[#3F53E8] rounded-lg text-xl leading-normal">Back</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('sections.update', $section->id) }}" method="post">
                        @csrf
                        <div>
                            <label for="section_name" class="text-sm font-medium">Section Name</label>
                            <div class="my-3">    
                                <input value="{{ old('section_name', $section->section_name) }}" name="section_name" placeholder="Enter section name" type="text" class="border-gray-300 shadow-sm w-1/2 rounded-lg">
                                @error('section_name')
                                <p class="text-red-400 font-medium"> {{ $message }} </p>
                                @enderror
                            </div>

                            <label for="bureau_id" class="text-sm font-medium">Bureau</label>
                            <div class="my-3">
                                <select name="bureau_id" class="border-gray-300 shadow-sm w-1/2 rounded-lg">
                                    <option value="">Select Bureau</option>
                                    @foreach($bureaus as $bureau)
                                    <option value="{{ $bureau->id }}" {{ (old('bureau_id', $section->bureau_id) == $bureau->id) ? 'selected' : '' }}>
                                        {{ $bureau->bureau_name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('bureau_id')
                                <p class="text-red-400 font-medium"> {{ $message }} </p>
                                @enderror
                            </div>

                            <button class="inline-block px-5 py-2 text-white hover:text-[#101966] hover:border-[#101966] bg-[#101966] hover:bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#101966] border border-white border font-medium dark:border-[#3E3E3A] dark:hover:bg-black dark:hover:border-[#3F53E8] rounded-lg text-xl leading-normal">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>