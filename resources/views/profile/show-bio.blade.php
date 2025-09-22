{{-- resources/views/profile/show-bio.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Edit Bio') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Outer container (light grey background like screenshot) -->
            <div class="bg-gray-50 dark:bg-gray-900 p-6 rounded-lg shadow-sm">

                <!-- White card -->
                <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-lg p-6 sm:p-8">
                    <form method="post" action="{{ route('profile.update-bio') }}" class="space-y-6">
                        @csrf
                        @method('patch')

                        <div>
                            <label for="bio" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Bio') }}
                            </label>

                            <textarea id="bio" name="bio" rows="6"
                                class="block w-full rounded-md border border-gray-200 dark:border-gray-700 shadow-sm focus:ring-0 focus:ring-offset-0 focus:border-gray-300 dark:focus:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 p-4 resize-y"
                                required>{{ old('bio', $bio->bio ?? '') }}</textarea>

                            @error('bio')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-start">
                            <button type="submit"
                                class="inline-flex items-center px-5 py-2 bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 transition">
                                {{ __('Update Bio') }}
                            </button>
                        </div>
                    </form>
                </div>
                <!-- end white card -->

            </div>
        </div>
    </div>

    <!-- SweetAlert2 for nice success dialog (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if (session('status') === 'Bio updated successfully!' || session('status') === 'profile-updated')
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: "{{ session('status') }}",
                    confirmButtonText: 'OK'
                });
            @endif
        });
    </script>
</x-app-layout>
