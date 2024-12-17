<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
        <img src="img/logo.png" alt="" style="width: 100PX">
        </x-slot>

        <x-validation-errors class="mb-4" />

        @session('status')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ $value }}
            </div>
        @endsession
<H1 style="text-align: center;  margin-bottom:10px;">INICIAR CESIÓN</H1>
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            </div>

        

            <div class="flex items-center justify-end mt-4">
               

                <x-button class="ms-4">
                    {{ __('acceder') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
