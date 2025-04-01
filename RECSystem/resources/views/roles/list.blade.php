<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between"> 
            <h2 class="font-semibold text-4xl text-white dark:text-gray-200 leading-tight">
                {{ __('Roles') }}
            </h2>
            @can('create roles')
            <a href="{{ route('roles.create') }}" class="inline-block px-5 py-2 text-white hover:text-[#101966] hover:border-[#101966] bg-[#101966] hover:bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#101966] border border-white border font-medium dark:border-[#3E3E3A] dark:hover:bg-black dark:hover:border-[#3F53E8] rounded-lg text-xl leading-normal">Create</a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-message> </x-message>


                <table class="w-full">
                <thead class="bg-gray-50">
                    <tr class="border-b">
                        <th class="px-6 py-3 text-left" width = "60">#</th>
                        <th class="px-6 py-3 text-left">Name</th>
                        <th class="px-6 py-3 text-left">Permissions</th>

                        <th class="px-6 py-3 text-left" width = "180">Created</th>
                        <th class="px-6 py-3 text-center" width = "180">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @if ($roles->isNotEmpty())
                    @foreach ($roles as $role)
                    <tr class="border-b">
                        <td class="px-6 py-3 text-left">
                            {{ $role->id }}
                        </td>
                        <td class="px-6 py-3 text-left">
                            {{ $role->name }}
                        </td>
                        <td class="px-6 py-3 text-left">
                            {{$role->permissions->pluck('name')->implode(', ')}}
                        </td> 
                        <td class="px-6 py-3 text-left">
                            {{ \Carbon\Carbon::parse($role->created_at)->format('d M, y')}}
                        </td>
                        <td class="px-6 py-3 text-center">

                        @can('edit roles')
                        <a href="{{ route('roles.edit', $role->id) }}" class="inline-block mb-2 px-5 py-2 text-white hover:text-[#101966] hover:border-[#101966] bg-[#101966] hover:bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#101966] border border-white border font-medium dark:border-[#3E3E3A] dark:hover:bg-black dark:hover:border-[#3F53E8] rounded-lg text-md leading-normal">Edit</a>
                        @endcan

                        @can('delete roles')
                        <a href="javascript:void" onclick="deleteRole({{ $role->id }})" class="inline-block px-3 py-2 text-white hover:text-[#a10303] hover:border-[#a10303] bg-[#a10303] hover:bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#a10303] border border-white border font-medium dark:border-[#3E3E3A] dark:hover:bg-black dark:hover:border-[#3F53E8] rounded-lg text-md leading-normal">Delete</a>
                        @endcan

                        </td>
                    </tr>
                    @endforeach
                    @endif

                </tbody>
            </table>

            <div class="my-3">
            {{ $roles->links() }}

            </div>
        </div>
    </div>

    <x-slot name="script">
        <script type="text/javascript">
        function deleteRole(id){
            if (confirm("Are you sure you want to delete?")){
                $.ajax({
                    url : '{{ route("roles.destroy") }}',
                    type : 'delete',
                    data : {id:id},
                    dataType : 'json',
                    headers : {
                        'x-csrf-token' : '{{ csrf_token() }}'
                    },
                    success : function(response){
                        window.location.href = '{{ route("roles.index")}}'
                    } 
                });
            }
        }
        </script>

    </x-slot>

</x-app-layout>
