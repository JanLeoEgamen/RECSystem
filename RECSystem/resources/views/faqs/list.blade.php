<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between"> 
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('FAQs') }}
            </h2>
            @can('create faqs')
            <a href="{{ route('faqs.create') }}" class="bg-slate-700 text-sm text-white rounded-md px-3 py-2">Create</a>
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
                        <th class="px-6 py-3 text-left">Question</th>
                        <th class="px-6 py-3 text-left">Answer</th>
                        <th class="px-6 py-3 text-left">Author</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left" width="180">Created</th>
                        <th class="px-6 py-3 text-center" width="180">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @if ($faqs->isNotEmpty())
                    @foreach ($faqs as $faq)
                    <tr class="border-b">
                        <td class="px-6 py-3 text-left">
                            {{ $faq->id }}
                        </td>
                        <td class="px-6 py-3 text-left">
                            {{ Str::limit($faq->question, 50) }}
                        </td>
                        <td class="px-6 py-3 text-left">
                            {{ Str::limit($faq->answer, 50) }}
                        </td>
                        <td class="px-6 py-3 text-left">
                            {{ $faq->user->name }}
                        </td>
                        <td class="px-6 py-3 text-left">
                            @if($faq->status)
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Active</span>
                            @else
                                <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-3 text-left">
                            {{ \Carbon\Carbon::parse($faq->created_at)->format('d M, y')}}
                        </td>
                        <td class="px-6 py-3 text-center">
                            @can('edit faqs')
                            <a href="{{ route('faqs.edit', $faq->id) }}" class="bg-slate-700 text-sm text-white rounded-md px-3 py-2 hover:bg-slate-600">Edit</a>
                            @endcan

                            @can('delete faqs')
                            <a href="javascript:void(0)" onclick="deleteFAQ({{ $faq->id }})" class="bg-red-600 text-sm text-white rounded-md px-3 py-2 hover:bg-red-500">Delete</a>
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="7" class="px-6 py-3 text-center">No FAQs found</td>
                    </tr>
                    @endif
                </tbody>
            </table>

            <div class="my-3">
                {{ $faqs->links() }}
            </div>
        </div>
    </div>

    <x-slot name="script">
        <script type="text/javascript">
            function deleteFAQ(id) {
                if (confirm("Are you sure you want to delete?")) {
                    $.ajax({
                        url: '{{ route("faqs.destroy") }}',
                        type: 'delete',
                        data: {id: id},
                        dataType: 'json',
                        headers: {
                            'x-csrf-token': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            window.location.href = '{{ route("faqs.index")}}'
                        }
                    });
                }
            }
        </script>
    </x-slot>
</x-app-layout>