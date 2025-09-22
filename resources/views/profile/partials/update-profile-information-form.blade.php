<!-- resources/views/profile/partials/update-profile-information-form.blade.php -->

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    {{-- form สำหรับส่ง verification --}}
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    {{-- ฟอร์มหลัก: UPDATE PROFILE (รวม bio) --}}
    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        {{-- Name --}}
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        {{-- Email --}}
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification"
                            class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

    

    {{-- แสดง Bio ปัจจุบัน + ปุ่มไปหน้า Manage (ถ้าต้องการหน้าแยก) --}}
    <div class="mt-6">
        <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">{{ __('Current Bio') }}</h3>
        <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-lg p-4">
            <p class="text-sm text-gray-700 dark:text-gray-200">
                {!! nl2br(e($bio->bio ?? __('No bio available'))) !!}
            </p>

            {{-- ถ้าคุณยังอยากมีหน้าจัดการแยกไว้ --}}
            <div class="mt-4">
                <a href="{{ route('profile.show-bio') }}"
                   class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-md transition">
                    {{ __('Manage Bio') }}
                </a>
            </div>
        </div>
    </div>
</section>
