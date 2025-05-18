<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Send Email to Members
            </h2>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            @if (session('success'))
                <div 
                    x-data="{ show: true }" 
                    x-init="setTimeout(() => show = false, 3000)" 
                    x-show="show"
                    class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg shadow"
                >
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('emails.send') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Select Member</label>
                    <select id="member_select" name="member_id" class="w-full border-gray-300 rounded-lg shadow-sm mt-1">
                        <option value="" disabled selected hidden>Select a member</option>
                        <option value="all">All Members</option> <!-- Optional all option -->
                        @foreach ($members as $member)
                            <option value="{{ $member->id }}">{{ $member->first_name }} {{ $member->last_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="template" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Choose Template</label>
                    <select name="template" id="template" class="w-full border-gray-300 rounded-lg shadow-sm mt-1">
                        <option value="verification">Email Verification</option>
                        <option value="welcome">Welcome Email</option>
                        <option value="payment_due">Payment Due Notification</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="attachments" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Attach Files</label>
                    <input type="file" name="attachments[]" id="attachments" class="w-full border-gray-300 rounded-lg shadow-sm mt-1" multiple>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">You can attach multiple files.</p>
                </div>

                <div class="mb-4">
                    <label for="custom_message" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Optional Custom Message</label>
                    <textarea name="custom_message" rows="4" class="w-full border-gray-300 rounded-lg shadow-sm mt-1"></textarea>
                </div>

                <button type="submit"
                    class="px-6 py-2 bg-[#1e40af] text-white rounded-lg hover:bg-[#112244] focus:ring-2 focus:ring-offset-2 focus:ring-[#1e40af]">
                    Send Email
                </button>
            </form>
        </div>
    </div>

    {{-- Select2 Styles and Scripts --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        window.onload = function () {
            $('#member_select').select2({
                placeholder: "Select a member",
                width: '100%',
                allowClear: true
            });
        };
    </script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</x-app-layout>
