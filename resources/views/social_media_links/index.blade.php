<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Social Media Links') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('status'))
                <div class="mb-4 p-3 rounded bg-green-100 text-green-700">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="p-6">
                    <!-- Add New Link Button with Gradient Color -->
                    <a href="{{ route('social_media_links.create') }}"
                       class="inline-block mb-4 px-6 py-3 rounded text-white bg-gradient-to-r from-blue-500 via-blue-400 to-blue-300 hover:bg-gradient-to-l transform transition duration-300 ease-in-out hover:scale-105">
                        + Add New Link
                    </a>

                    @if($links && $links->count() > 0)
                        <ul class="space-y-6">
                            @foreach ($links as $link)
                                <li class="flex items-center justify-between py-4 px-6 border-b border-gray-200 rounded-md hover:bg-gray-100 transition duration-300 ease-in-out">
                                    <div class="space-x-2">
                                        <strong class="text-xl font-semibold bg-white">{{ $link->platform }}</strong>
                                        <a href="{{ $link->url }}" target="_blank" class="text-blue-500 hover:text-blue-700 underline">
                                            {{ $link->url }}
                                        </a>
                                    </div>

                                    <div class="space-x-3">
                                        <a href="{{ route('social_media_links.edit', $link) }}"
                                           class="px-4 py-2 text-sm bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transform transition duration-300 ease-in-out">
                                           Edit
                                        </a>

                                        <form action="{{ route('social_media_links.destroy', $link) }}"
                                              method="POST" class="inline"
                                              onsubmit="return confirm('Delete this link?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-4 py-2 text-sm bg-red-500 text-white rounded-lg hover:bg-red-600 transform transition duration-300 ease-in-out">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-500">No social media links found.
                           <a href="{{ route('social_media_links.create') }}" class="text-blue-500 hover:text-blue-700 underline">
                               Add one
                           </a>
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
