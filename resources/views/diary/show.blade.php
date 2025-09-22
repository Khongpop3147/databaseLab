<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">
            {{ $diaryEntry->date->format('F j, Y') }}
        </h2>
    </x-slot>

    <div class="py-8 max-w-3xl mx-auto">
        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                {{-- เนื้อหาไดอารี --}}
                <p class="whitespace-pre-line">{{ $diaryEntry->content }}</p>

                {{-- แสดงอารมณ์ (ถ้ามี) --}}
                @if($diaryEntry->emotions && $diaryEntry->emotions->isNotEmpty())
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold mb-2">{{ __('Emotions') }}</h3>
                        <ul class="list-disc ml-5 space-y-1">
                            @foreach($diaryEntry->emotions as $emotion)
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

                {{-- แสดงแท็ก (ถ้ามี) --}}
                @if($diaryEntry->tags && $diaryEntry->tags->isNotEmpty())
                    <div class="mt-6 text-sm">
                        <h3 class="text-lg font-semibold mb-2">{{ __('Tags') }}</h3>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach ($diaryEntry->tags as $tag)
                                <span class="inline-block px-2 py-0.5 border rounded">{{ $tag->name }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- ปุ่มแอ็กชัน --}}
                <div class="mt-6 flex gap-2">
                    <a href="{{ route('diary.edit', $diaryEntry) }}" class="px-3 py-1 rounded border">
                        {{ __('Edit') }}
                    </a>
                    <a href="{{ route('diary.index') }}" class="px-3 py-1 rounded border">
                        {{ __('Back') }}
                    </a>

                    <form method="POST" action="{{ route('diary.destroy', $diaryEntry) }}"
                          onsubmit="return confirm('{{ __('Delete this entry?') }}')" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3 py-1 rounded border text-red-600">
                            {{ __('Delete') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
