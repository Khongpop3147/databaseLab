<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">{{ __('Reminders') }}</h2>
    </x-slot>

    <div class="py-8 max-w-6xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="mb-4">
                    <a href="{{ route('reminders.create') }}"
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md">
                        + {{ __('New Reminder') }}
                    </a>
                </div>

                @if (session('status'))
                    <div class="mb-4 p-3 rounded bg-green-100 text-green-800">{{ session('status') }}</div>
                @endif

                @forelse ($reminders as $reminder)
                    <div class="mb-4 p-4 bg-gray-100 dark:bg-gray-700 rounded">
                        <div class="flex justify-between items-start">
                            <div>
                                <a href="{{ route('reminders.show', $reminder) }}" class="font-semibold">
                                    {{ $reminder->title }}
                                </a>
                                <div class="text-sm text-gray-500">
                                    {{ $reminder->remind_at?->format('Y-m-d H:i') ?? '—' }}
                                    · {{ ucfirst($reminder->status) }}
                                </div>
                            </div>
                            <div class="space-x-2">
                                <a href="{{ route('reminders.edit', $reminder) }}" class="px-3 py-1 rounded border">
                                    {{ __('Edit') }}
                                </a>
                                <form class="inline" method="POST" action="{{ route('reminders.destroy', $reminder) }}"
                                      onsubmit="return confirm('{{ __('Delete this reminder?') }}')">
                                    @csrf @method('DELETE')
                                    <button class="px-3 py-1 rounded border text-red-600">{{ __('Delete') }}</button>
                                </form>
                            </div>
                        </div>

                        {{-- Tags --}}
                        @if($reminder->tags->isNotEmpty())
                            <div class="mt-2 flex flex-wrap gap-1.5">
                                @foreach ($reminder->tags as $tag)
                                    <span class="inline-block px-2 py-0.5 border rounded text-sm">{{ $tag->name }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @empty
                    <p class="text-gray-500">{{ __('No reminders yet.') }}</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
