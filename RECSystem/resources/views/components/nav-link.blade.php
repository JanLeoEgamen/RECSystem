@props(['active'])


@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-2 pt-1 dark:border-white text-xl font-medium leading-5 text-white dark:text-gray-100  hover:text-[#5E6FFB]  focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-2 pt-1 text-xl font-medium leading-5 text-white dark:text-gray-400 hover:text-[#5E6FFB] dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-700 focus:outline-none focus:text-white dark:focus:text-gray-300 focus:border-indigo-700 dark:focus:border-gray-700 transition duration-150 ease-in-out';
@endphp


<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>





