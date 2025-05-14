<x-guest-layout>
    <!-- Bubble animation elements -->
    <div class="bubbles">
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
    </div>

    <div class="w-full max-w-md bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Header with gradient -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-6 text-center text-white">
            <h1 class="text-2xl font-bold">Create Account</h1>
            <p class="text-indigo-100 mt-1">Join LaundryCare today</p>
        </div>

        <!-- Form content -->
        <div class="p-6">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div>
                    <x-input-label for="name" :value="__('Name')" class="text-indigo-800" />
                    <x-text-input id="name" class="block mt-1 w-full border-gray-300 text-gray-700 focus:border-indigo-400 focus:ring-indigo-300 rounded-lg" 
                                type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-600" />
                </div>

                <!-- Email Address -->
                <div class="mt-4">
                    <x-input-label for="email" :value="__('Email')" class="text-indigo-800" />
                    <x-text-input id="email" class="block mt-1 w-full border-gray-300 text-gray-700 focus:border-indigo-400 focus:ring-indigo-300 rounded-lg" 
                                type="email" name="email" :value="old('email')" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" class="text-indigo-800" />
                    <x-text-input id="password" class="block mt-1 w-full border-gray-300 text-gray-700 focus:border-indigo-400 focus:ring-indigo-300 rounded-lg"
                                type="password"
                                name="password"
                                required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600" />
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-indigo-800" />
                    <x-text-input id="password_confirmation" class="block mt-1 w-full border-gray-300 text-gray-700 focus:border-indigo-400 focus:ring-indigo-300 rounded-lg"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-600" />
                </div>

                <div class="flex items-center justify-between mt-6">
                    <a class="underline text-sm text-indigo-600 hover:text-indigo-800 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-300" 
                       href="{{ route('login') }}">
                        {{ __('Already registered?') }}
                    </a>

                    <x-primary-button class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white">
                        {{ __('Register') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>