<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">{{ __('Edit Reminder') }}</h2>
    </x-slot>

    <div class="py-8 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <form method="POST" action="{{ route('reminders.update', $reminder) }}">
                    @csrf @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium">{{ __('Title') }}</label>
                        <input type="text" name="title" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700"
                               value="{{ old('title', $reminder->title) }}" required>
                        @error('title')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium">{{ __('Notes') }}</label>
                        <textarea name="notes" rows="4" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700">{{ old('notes', $reminder->notes) }}</textarea>
                        @error('notes')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium">{{ __('Remind At') }}</label>
                        <input type="datetime-local" name="remind_at"
                               value="{{ old('remind_at', $reminder->remind_at?->format('Y-m-d\TH:i')) }}"
                               class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700">
                        @error('remind_at')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium">{{ __('Status') }}</label>
                        <select name="status" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700">
                            <option value="new"  @selected(old('status', $reminder->status)==='new')>New</option>
                            <option value="done" @selected(old('status', $reminder->status)==='done')>Done</option>
                        </select>
                        @error('status')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                    </div>

                    {{-- Tags --}}
                    @php
                        $checked = old('tags', $reminder->tags->pluck('id')->toArray());
                    @endphp
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">{{ __('Select Tags') }}</label>
                        <div class="flex flex-wrap gap-3">
                            @foreach($tags as $tag)
                                <label class="inline-flex items-center gap-2">
                                    <input type="checkbox" name="tags[]"
                                           value="{{ $tag->id }}"
                                           @checked(in_array($tag->id, $checked))>
                                    <span>{{ $tag->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('tags')<div class="text-red-500 text-sm mt-2">{{ $message }}</div>@enderror
                    </div>

                    <x-primary-button>{{ __('Update') }}</x-primary-button>
                    <a href="{{ route('reminders.index') }}" class="ml-2 px-4 py-2 rounded border">{{ __('Cancel') }}</a>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
