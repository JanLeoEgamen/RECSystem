<x-app-layout>
    <x-slot name="header">
    <div class="flex justify-between"> 
            <h2 class="font-semibold text-4xl text-white dark:text-gray-200 leading-tight">
                Permissions / Edit
            </h2>
                    <a href="{{ route('permissions.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md flex items-center">
                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Permissions
                    </a>                
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{route('permissions.update', $permission->id )}} " method="post">
                        @csrf
                        <div>
                            <label for="" class="text-sm font-medium"> Name</label>
                            <div class = "my-3">    
                                <input value="{{ old('name', $permission->name) }}" name="name" placeholder="Enter name" type="text" class="border-gray-300 shadow-sm w-1/2 rounded-lg  ">
                                @error('name')
                                <p class="text-red-400 font-medium"> {{ $message }} </p>
                                @enderror
                            </div>


                        <div class="mt-6">
                            <button type="submit" class="flex items-center px-4 py-2 text-sm text-blue-600 hover:text-white hover:bg-blue-600 rounded-md transition-colors duration-200 border border-blue-100 hover:border-blue-600 font-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Update
                            </button>
                        </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
