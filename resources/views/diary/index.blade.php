<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Diary') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-4">
                        <a href="{{ route('diary.create') }}">
                            <button type="button"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                                {{ __('Add New Entry') }}
                            </button>
                        </a>
                    </div>

                    @if (session('status'))
                        <div class="mb-4 p-3 rounded bg-green-100 text-green-800">
                            {{ session('status') }}
                        </div>
                    @endif

                    @forelse ($diaryEntries as $entry)
                        <div class="mb-6 p-4 bg-gray-100 dark:bg-gray-700 rounded-lg shadow-md">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl font-bold">{{ $entry->date->format('F j, Y') }}</h3>
                                <div class="space-x-2">
                                    <a href="{{ route('diary.show', $entry) }}" class="px-3 py-1 rounded border">
                                        {{ __('View') }}
                                    </a>
                                    <a href="{{ route('diary.edit', $entry) }}" class="px-3 py-1 rounded border">
                                        {{ __('Edit') }}
                                    </a>
                                    <form method="POST" action="{{ route('diary.destroy', $entry) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Delete this entry?')"
                                                class="px-3 py-1 rounded border text-red-600">
                                            {{ __('Delete') }}
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <p class="mt-2">{{ $entry->content }}</p>

                            {{-- Emotions list --}}
                            @if ($entry->emotions->isNotEmpty())
                                <div class="mt-3">
                                    <h4 class="text-sm font-semibold mb-1">{{ __('Emotions') }}</h4>
                                    <ul class="list-disc ml-5 space-y-0.5">
                                        @foreach ($entry->emotions as $emotion)
                                            <li>
                                                {{ $emotion->name }}
                                                @if(!is_null($emotion->pivot->intensity))
                                                    ({{ __('Intensity') }}: {{ $emotion->pivot->intensity }})
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            {{-- Tags list --}}
                            @if ($entry->tags->isNotEmpty())
                                <div class="mt-3 text-sm">
                                    <h4 class="text-sm font-semibold mb-1">{{ __('Tags') }}</h4>
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach ($entry->tags as $tag)
                                            <span class="inline-block px-2 py-0.5 border rounded">{{ $tag->name }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="text-gray-500">{{ __('No entries yet.') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
