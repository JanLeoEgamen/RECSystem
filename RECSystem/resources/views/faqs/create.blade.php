<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between"> 
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                FAQs / Create
            </h2>
            <a href="{{ route('faqs.index') }}" class="bg-slate-700 text-sm text-white rounded-md px-3 py-3">Back</a>
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


                            <button class="bg-slate-700 hover:bg-slate-500 text-sm text-white rounded-md px-5 py-3">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>