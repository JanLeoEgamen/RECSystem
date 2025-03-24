<x-app-layout>
    <x-slot name="header">
    <div class="flex justify-between"> 
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Article / Create
            </h2>
            <a href="{{ route('articles.index') }}" class="bg-slate-700 text-sm text-white rounded-md px-3 py-3">Back</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{route('articles.store')}} " method="post">
                        @csrf
                        <div>
                            <label for="" class="text-sm font-medium">Title</label>
                            <div class = "my-3">    
                                <input value="{{ old('title') }}" name="title" placeholder="Title" type="text" class="border-gray-300 shadow-sm w-1/2 rounded-lg  ">
                                @error('title')
                                <p class="text-red-400 font-medium"> {{ $message }} </p>
                                @enderror
                            </div>

                            <label for="" class="text-sm font-medium">Content</label>
                            <div class = "my-3">    
                            <textarea name="text" id="text" placeholder="Content" class="border-gray-300 shadow-sm w-1/2 rounded-lg" cols="30" rows="30" >{{ old('text') }}</textarea>
                            </div>

                            <label for="" class="text-sm font-medium">Author</label>
                            <div class = "my-3">    
                                <input value="{{ old('author') }}" name="author" placeholder="Author" type="text" class="border-gray-300 shadow-sm w-1/2 rounded-lg  ">
                                @error('author')
                                    <p class="text-red-400 font-medium"> {{ $message }} </p>
                                @enderror
                            </div>

                            <button class="bg-slate-700 text-sm text-white rounded-md px-5 py-3">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
