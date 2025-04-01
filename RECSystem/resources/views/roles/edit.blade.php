<x-app-layout>
    <x-slot name="header">
    <div class="flex justify-between"> 
            <h2 class="font-semibold text-4xl text-white dark:text-gray-200 leading-tight">
                Roles / Edit
            </h2>
            <a href="{{ route('roles.index') }}" class="inline-block px-5 py-2 text-white hover:text-[#101966] hover:border-[#101966] bg-[#101966] hover:bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#101966] border border-white border font-medium dark:border-[#3E3E3A] dark:hover:bg-black dark:hover:border-[#3F53E8] rounded-lg text-xl leading-normal">Back</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{route('roles.update', $role->id)}} " method="post">
                        @csrf
                        <div>
                            <label for="" class="text-sm font-medium"> Name</label>
                            <div class = "my-3">    
                                <input value="{{ old('name', $role->name) }}" name="name" placeholder="Enter name" type="text" class="border-gray-300 shadow-sm w-1/2 rounded-lg  ">
                                @error('name')
                                <p class="text-red-400 font-medium"> {{ $message }} </p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-4 mb-3" >
                                    @if ($permissions->isNotEmpty())
                                        @foreach($permissions as $permission)
                                            <div class="mt-3">
                                            <input {{ $hasPermissions->contains($permission->name) ? 'checked' : '' }}  type="checkbox" id="permission-{{ $permission->id}}" class="rounded" name="permission[]"
                                            value="{{ $permission->name }}">
                                            <label for="permission-{{ $permission->id}}">{{ $permission->name }}</label>

                                            </div>
                                        @endforeach
                                    @endif
                            </div>

                            <button class="inline-block px-5 py-2 text-white hover:text-[#101966] hover:border-[#101966] bg-[#101966] hover:bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#101966] border border-white border font-medium dark:border-[#3E3E3A] dark:hover:bg-black dark:hover:border-[#3F53E8] rounded-lg text-xl leading-normal">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
