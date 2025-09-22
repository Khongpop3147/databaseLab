<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Social Media Link') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="p-6">
                   <form action="{{ route('social_media_links.update', $socialMediaLink) }}" method="POST">
    @csrf
    @method('PUT')

    <!-- Platform input -->
    <div>
        <label for="platform" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            Platform
        </label>
        <input type="text" name="platform" id="platform"
               class="mt-1 block w-full rounded border-gray-300"
               value="{{ old('platform', $socialMediaLink->platform) }}" required>
        @error('platform')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            URL
        </label>
        <input type="url" name="url" id="url"
               class="mt-1 block w-full rounded border-gray-300"
               value="{{ old('url', $socialMediaLink->url) }}" required>
        @error('url')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center gap-3">
        <button type="submit"
                class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">
            Update
        </button>
        <a href="{{ route('social_media_links.index') }}" class="text-gray-600 hover:underline">
            Cancel
        </a>
    </div>
</form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
