<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Diary') }}
        </h2>
    </x-slot>

    @php
        // เตรียม summary ให้ครบ emotion 1..5 เสมอ
        $summary = ($summary ?? []) + [1=>0,2=>0,3=>0,4=>0,5=>0];
        $emotionFilter = (int)($emotionFilter ?? 0);

        // เมตาของการ์ด + ลำดับ (Happy→Sad→Angry→Excited→Anxious)
        $meta = [
            1 => ['name'=>'Happy',   'emoji'=>'😊', 'bg'=>'bg-gradient-to-br from-yellow-400 to-yellow-600'],
            2 => ['name'=>'Sad',     'emoji'=>'😢', 'bg'=>'bg-gradient-to-br from-blue-400 to-blue-600'],
            3 => ['name'=>'Angry',   'emoji'=>'😡', 'bg'=>'bg-gradient-to-br from-red-400 to-red-600'],
            4 => ['name'=>'Excited', 'emoji'=>'🤩', 'bg'=>'bg-gradient-to-br from-green-400 to-green-600'],
            5 => ['name'=>'Anxious', 'emoji'=>'😰', 'bg'=>'bg-gradient-to-br from-purple-400 to-purple-600'],
        ];
        $order = [1,2,3,4,5];
    @endphp

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Action Bar --}}
            <div class="mb-4 flex justify-between items-center">
                <a href="{{ route('diary.create') }}">
                    <button type="button"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                        {{ __('Add New Entry') }}
                    </button>
                </a>
            </div>

            @if (session('status'))
                <div class="mb-6 p-3 rounded bg-green-100 text-green-800">
                    {{ session('status') }}
                </div>
            @endif

            {{-- ===== Summary Cards : Colorful & Clickable ===== --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-bold">
                            {{ __('Diary Summary by Emotions') }}
                            @if ($emotionFilter > 0 && isset($meta[$emotionFilter]))
                                <span class="ml-2 text-sm px-2 py-1 rounded bg-blue-600/20 text-blue-300">
                                    Filter: {{ $meta[$emotionFilter]['name'] }}
                                </span>
                            @endif
                        </h3>

                        @if ($emotionFilter > 0)
                            <a href="{{ route('diary.index') }}"
                               class="text-sm px-3 py-1 rounded border hover:bg-gray-100 dark:hover:bg-gray-700">
                                Show All
                            </a>
                        @endif
                    </div>

                    {{-- มือถือ/จอแคบ: แถวแนวนอนเลื่อนได้ | เดสก์ท็อป: กริด 5 ใบ --}}
                    <div class="flex lg:grid lg:grid-cols-5 gap-4 lg:gap-6 overflow-x-auto pb-2 [-ms-overflow-style:none] [scrollbar-width:none]"
                         style="-webkit-overflow-scrolling:touch;">
                        <style>.flex::-webkit-scrollbar{display:none}</style>

                        @foreach ($order as $id)
                            @php
                                $count  = $summary[$id] ?? 0;
                                $active = $emotionFilter === $id;
                                $cls = $meta[$id]['bg'].' text-white rounded-3xl shadow-xl w-56 lg:w-auto h-56
                                       flex-shrink-0 flex flex-col items-center justify-center text-center
                                       transition-transform duration-200 hover:-translate-y-1 hover:shadow-2xl
                                       focus:outline-none focus-visible:ring-4 focus-visible:ring-white/80 focus-visible:ring-offset-2';
                            @endphp

                            <a href="{{ route('diary.index', ['emotion' => $id]) }}"
                               class="block"
                               aria-label="Filter by {{ $meta[$id]['name'] }}">
                                <div class="{{ $cls }} {{ $active ? 'ring-4 ring-blue-500 ring-offset-2' : '' }}">
                                    <div class="text-5xl mb-1">{{ $meta[$id]['emoji'] }}</div>
                                    <div class="text-xl font-semibold">{{ $meta[$id]['name'] }}</div>
                                    <div class="text-5xl font-extrabold mt-3">{{ $count }}</div>
                                    <div class="mt-1 opacity-90 text-sm">Diaries</div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
            {{-- ===== End Summary Cards ===== --}}

            {{-- ===== Diary List ===== --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @forelse ($diaryEntries as $entry)
                        <div class="mb-6 p-4 bg-gray-100 dark:bg-gray-700 rounded-lg shadow-md">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl font-bold">
                                    {{ optional($entry->date)->format('F j, Y') ?? '-' }}
                                </h3>
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

                            {{-- Emotions --}}
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

                            {{-- Tags --}}
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

                    {{-- Pagination --}}
                    @if (method_exists($diaryEntries, 'links'))
                        <div class="mt-6">
                            {{ $diaryEntries->links() }}
                        </div>
                    @endif
                </div>
            </div>
            {{-- ===== End Diary List ===== --}}

        </div>
    </div>
</x-app-layout>
