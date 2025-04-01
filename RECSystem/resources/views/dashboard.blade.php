<x-app-layout>
    <x-slot name="header">
        <h1 class="font-semibold text-3xl text-white dark:text-white leading-tight">
            {{ __('Dashboard') }}
        </h1>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#7fe67c] dark:bg-[#63f542] overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 text-2xl font-bold text-[#026300] dark:text-black">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>





