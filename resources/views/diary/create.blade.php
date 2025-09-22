<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Diary') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form id="diary-form" method="POST" action="{{ route('diary.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date</label>
                            <input type="date" id="date" name="date"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-100"
                                   value="{{ old('date') }}" required>
                            @error('date')
                                <div class="text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Content</label>
                            <textarea id="content" name="content" rows="5"
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-100" required>{{ old('content') }}</textarea>
                            @error('content')
                                <div class="text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Select Emotions + Intensity --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Select Emotions') }}
                            </label>

                            <div class="grid grid-cols-1 gap-4">
                                @foreach ($emotions as $emotion)
                                    <div class="flex items-center">
                                        <input type="checkbox"
                                               id="emotion_{{ $emotion->id }}"
                                               name="emotions[]"
                                               value="{{ $emotion->id }}"
                                               class="h-5 w-5 text-indigo-600 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600"
                                               onchange="toggleIntensityInput({{ $emotion->id }})"
                                               @checked(in_array($emotion->id, old('emotions', []), true))>
                                        <label for="emotion_{{ $emotion->id }}" class="ml-3 text-sm font-medium">
                                            {{ $emotion->name }}
                                        </label>

                                        <div class="ml-4 @if(!in_array($emotion->id, old('emotions', []), true)) hidden @endif"
                                             id="intensity_container_{{ $emotion->id }}">
                                            <input type="number"
                                                   name="intensity[{{ $emotion->id }}]"
                                                   class="w-28 border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-100"
                                                   placeholder="1-10" min="1" max="10"
                                                   value="{{ old('intensity.'.$emotion->id) }}">
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @error('emotions')
                                <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                            @enderror
                            @if($errors->has('intensity.*'))
                                <div class="text-red-500 text-sm mt-2">{{ __('Each intensity must be between 1 and 10.') }}</div>
                            @endif
                        </div>

                        {{-- ✅ Tags (checkboxes like Reminder) --}}
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Select Tags') }}
                            </label>

                            <div class="flex flex-wrap gap-4">
                                @foreach($tags as $tag)
                                    <label class="inline-flex items-center gap-2">
                                        <input
                                            type="checkbox"
                                            name="tags[]"
                                            value="{{ $tag->id }}"
                                            class="h-5 w-5 text-indigo-600 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600"
                                            @checked(in_array($tag->id, old('tags', []), true))
                                        >
                                        <span>{{ $tag->name }}</span>
                                    </label>
                                @endforeach
                            </div>

                            @error('tags')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                            {{ __('Save Entry') }}
                        </button>
                        <a href="{{ route('diary.index') }}" class="ml-2 px-4 py-2 rounded-md border">
                            {{ __('Cancel') }}
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleIntensityInput(emotionId) {
            const checkbox = document.getElementById('emotion_' + emotionId);
            const container = document.getElementById('intensity_container_' + emotionId);
            const input = container.querySelector('input');
            if (checkbox.checked) {
                container.classList.remove('hidden');
            } else {
                container.classList.add('hidden');
                input.value = '';
            }
        }
    </script>
</x-app-layout>
