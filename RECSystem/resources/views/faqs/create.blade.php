<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between"> 
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                FAQs / Create
            </h2>
                    <a href="{{ route('faqs.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md flex items-center">
                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Faqs
                    </a>                
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('faqs.store') }}" method="post">
                        @csrf
                        <div>
                            <label for="question" class="text-sm font-medium">Question</label>
                            <div class="my-3">    
                                <input value="{{ old('question') }}" name="question" placeholder="Enter question" type="text" class="border-gray-300 shadow-sm w-1/2 rounded-lg">
                                @error('question')
                                <p class="text-red-400 font-medium"> {{ $message }} </p>
                                @enderror
                            </div>

                            <label for="answer" class="text-sm font-medium">Answer</label>
                            <div class="my-3">    
                                <textarea name="answer" placeholder="Enter answer" class="border-gray-300 shadow-sm w-1/2 rounded-lg">{{ old('answer') }}</textarea>
                                @error('answer')
                                <p class="text-red-400 font-medium"> {{ $message }} </p>
                                @enderror
                            </div>

                            <div class="my-3 flex items-center">
                                <input type="hidden" name="status" value="0"> <!-- This ensures a value is always sent -->
                                <input type="checkbox" name="status" id="status" class="rounded" value="1" {{ old('status', true) ? 'checked' : '' }}>
                                <label for="status" class="ml-2">Active</label>
                            </div>


                        <div class="mt-6">
                            <button type="submit" class="flex items-center px-4 py-2 text-sm text-blue-600 hover:text-white hover:bg-blue-600 rounded-md transition-colors duration-200 border border-blue-100 hover:border-blue-600 font-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Create
                            </button>
                        </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>