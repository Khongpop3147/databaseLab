<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg flex flex-col items-center text-center">
                @php
                    // ensure $user exists
                    $user = $user ?? auth()->user();

                    // default image (place a default-photo.png under public/images/)
                    $defaultImg = asset('images/default-photo.png');

                    // determine photo path and URL
                    $photoUrl = null;
                    if ($user && !empty($user->profile_photo)) {
                        $photoPath = $user->profile_photo;

                        // if DB stores only filename like "abc.jpg", prefix the folder
                        if (strpos($photoPath, '/') === false) {
                            $photoPath = 'profile_photos/' . $photoPath;
                        }

                        // if file exists in storage/app/public/<photoPath>, use asset('storage/...')
                        if (file_exists(storage_path('app/public/' . $photoPath))) {
                            $photoUrl = asset('storage/' . $photoPath);
                        }
                    }
                @endphp

                <h1 class="text-2xl font-bold">Hello, {{ $user->name ?? 'guest' }}!</h1>

                @if($user && $user->birthdate)
                    <p class="text-blue-500 text-lg mt-2">Birthdate is {{ $user->birthdate->format('Y-m-d') }}!</p>
                @else
                    <p class="text-gray-500 mt-2">Your birthdate is not set.</p>
                @endif

                <!-- Adjust size of profile photo -->
                <div class="mt-6 mx-auto w-20 h-20 rounded-full overflow-hidden">
                    @if($photoUrl)
                        <img src="{{ $photoUrl }}" alt="Profile photo of {{ $user->name ?? 'user' }}" class="w-full h-full object-cover">
                    @else
                        <img src="{{ $defaultImg }}" alt="Default profile" class="w-full h-full object-cover">
                    @endif
                </div>

                <p class="mt-6 text-sm text-gray-700">I LOVE DATABASE NA KUB</p>
                <p class="mt-2 text-sm text-gray-500">You're logged in!</p>

                <!-- Social Media Links Button -->
                <div class="mt-4">
                    <a href="{{ route('social_media_links.index') }}" class="text-blue-500">Go to Social Media Links</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
