<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between"> 
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Main Carousel Items / Create
            </h2>
            <a href="{{ route('main-carousels.index') }}" class="bg-slate-700 text-sm text-white rounded-md px-3 py-3">Back</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('main-carousels.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div>
                            <label for="title" class="text-sm font-medium">Title</label>
                            <div class="my-3">    
                                <input value="{{ old('title') }}" name="title" placeholder="Enter title" type="text" class="border-gray-300 shadow-sm w-1/2 rounded-lg">
                                @error('title')
                                <p class="text-red-400 font-medium"> {{ $message }} </p>
                                @enderror
                            </div>

                            <label for="content" class="text-sm font-medium">Content</label>
                            <div class="my-3">    
                                <textarea name="content" placeholder="Enter content" class="border-gray-300 shadow-sm w-1/2 rounded-lg">{{ old('content') }}</textarea>
                                @error('content')
                                <p class="text-red-400 font-medium"> {{ $message }} </p>
                                @enderror
                            </div>

                            <label for="image" class="text-sm font-medium">Image</label>
                            <div class="my-3">    
                                <input name="image" type="file" class="border-gray-300 shadow-sm w-1/2 rounded-lg" required>
                                @error('image')
                                <p class="text-red-400 font-medium"> {{ $message }} </p>
                                @enderror
                            </div>

                            <div class="my-3 flex items-center">
                                <input type="hidden" name="status" value="0">
                                <input type="checkbox" name="status" id="status" class="rounded" value="1" {{ old('status', true) ? 'checked' : '' }}>
                                <label for="status" class="ml-2">Active</label>
                            </div>

                            <button class="bg-slate-700 hover:bg-slate-500 text-sm text-white rounded-md px-5 py-3">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>