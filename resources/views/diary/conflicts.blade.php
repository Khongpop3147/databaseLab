<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('Conflicting Emotions') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
          <h3 class="text-2xl font-bold mb-4">{{ __('Summary') }}</h3>

          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
              <thead class="bg-gray-50 dark:bg-gray-700/50">
                <tr>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Content</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Emotion</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Intensity</th>
                </tr>
              </thead>

              <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($conflicts as $row)
                  <tr class="hover:bg-gray-50/70 dark:hover:bg-gray-700/30 transition">
                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                      {{ $row->id }}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                      {{ \Illuminate\Support\Carbon::parse($row->date)->translatedFormat('M j, Y') }}
                    </td>
                    <td class="px-4 py-3 text-sm">
                      {{ \Illuminate\Support\Str::limit($row->content, 70) }}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                      <span class="text-indigo-600 dark:text-indigo-400 font-semibold">
                        {{ $row->emotion_name }}
                      </span>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                      <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-rose-100 text-rose-600 dark:bg-rose-900/30 dark:text-rose-300 font-bold">
                        {{ $row->intensity ?? '-' }}
                      </span>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                      {{ __('No conflicting entries found.') }}
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          {{-- Pagination --}}
          <div class="mt-6">
            {{ $conflicts->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
