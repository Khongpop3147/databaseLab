<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">{{ $reminder->title }}</h2>
    </x-slot>

    <div class="py-8 max-w-3xl mx-auto">
        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="text-sm text-gray-500">
                    {{ $reminder->remind_at?->format('Y-m-d H:i') ?? '—' }}
                    · {{ ucfirst($reminder->status) }}
                </div>

                @if($reminder->notes)
                    <p class="mt-4 whitespace-pre-line">{{ $reminder->notes }}</p>
                @endif

                @if($reminder->tags->isNotEmpty())
                    <div class="mt-4 flex flex-wrap gap-1.5">
                        @foreach ($reminder->tags as $tag)
                            <span class="inline-block px-2 py-0.5 border rounded text-sm">{{ $tag->name }}</span>
                        @endforeach
                    </div>
                @endif

                <div class="mt-6 flex gap-2">
                    <a href="{{ route('reminders.edit', $reminder) }}" class="px-3 py-1 rounded border">{{ __('Edit') }}</a>
                    <a href="{{ route('reminders.index') }}" class="px-3 py-1 rounded border">{{ __('Back') }}</a>

                    <form class="inline" method="POST" action="{{ route('reminders.destroy', $reminder) }}"
                          onsubmit="return confirm('{{ __('Delete this reminder?') }}')">
                        @csrf @method('DELETE')
                        <button class="px-3 py-1 rounded border text-red-600">{{ __('Delete') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
