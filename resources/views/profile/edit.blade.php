<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile Edit') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Form for updating profile information -->
            @include('profile.partials.update-profile-information-form')

            <!-- Form for updating profile photo -->
            @include('profile.partials.update-profile-photo-form')

            <!-- Form for updating password -->
            @include('profile.partials.update-password-form')

            <!-- Form for deleting user account (if necessary) -->
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</x-app-layout>
