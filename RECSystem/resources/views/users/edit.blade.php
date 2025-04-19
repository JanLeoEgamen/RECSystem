<x-app-layout>
    <x-slot name="header">
    <div class="flex justify-between"> 
            <h2 class="font-semibold text-4xl text-white dark:text-gray-200 leading-tight">
                Users / Edit
            </h2>
            <a href="{{ route('users.index') }}" class="inline-block px-5 py-2 text-white hover:text-[#101966] hover:border-[#101966] bg-[#101966] hover:bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#101966] border border-white border font-medium dark:border-[#3E3E3A] dark:hover:bg-black dark:hover:border-[#3F53E8] rounded-lg text-xl leading-normal">Back</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{route('users.update', $user->id)}}" method="post">
                        @csrf
                        <div>
                            <label for="first_name" class="text-sm font-medium">First Name</label>
                            <div class="my-3">    
                                <input value="{{ old('first_name', $user->first_name) }}" name="first_name" placeholder="Enter first name" type="text" class="border-gray-300 shadow-sm w-1/2 rounded-lg">
                                @error('first_name')
                                <p class="text-red-400 font-medium"> {{ $message }} </p>
                                @enderror
                            </div>

                            <label for="last_name" class="text-sm font-medium">Last Name</label>
                            <div class="my-3">    
                                <input value="{{ old('last_name', $user->last_name) }}" name="last_name" placeholder="Enter last name" type="text" class="border-gray-300 shadow-sm w-1/2 rounded-lg">
                                @error('last_name')
                                <p class="text-red-400 font-medium"> {{ $message }} </p>
                                @enderror
                            </div>

                            <label for="birthdate" class="text-sm font-medium">Birthdate</label>
                            <div class="my-3">    
                                <input value="{{ old('birthdate', $user->birthdate ? (\Carbon\Carbon::parse($user->birthdate)->format('Y-m-d')) : '') }}" name="birthdate" type="date" class="border-gray-300 shadow-sm w-1/2 rounded-lg">
                                @error('birthdate')
                                <p class="text-red-400 font-medium"> {{ $message }} </p>
                                @enderror
                            </div>

                            <label for="" class="text-sm font-medium"> Email</label>
                            <div class = "my-3">    
                                <input value="{{ old('email', $user->email) }}" name="email" placeholder="Enter Email" type="text" class="border-gray-300 shadow-sm w-1/2 rounded-lg">
                                @error('email')
                                <p class="text-red-400 font-medium"> {{ $message }} </p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-4 mb-3">
                                @if ($roles->isNotEmpty())
                                    @foreach($roles as $role)
                                        <div class="mt-3">
                                            <input {{ $hasRoles->contains($role->id) ? 'checked' : '' }} type="checkbox" id="role-{{ $role->id}}" class="rounded" name="role[]" value="{{ $role->name }}">
                                            <label for="role-{{ $role->id}}">{{ $role->name }}</label>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            <button class="inline-block px-5 py-2 text-white hover:text-[#101966] hover:border-[#101966] bg-[#101966] hover:bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#101966] border border-white border font-medium dark:border-[#3E3E3A] dark:hover:bg-black dark:hover:border-[#3F53E8] rounded-lg text-xl leading-normal">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>