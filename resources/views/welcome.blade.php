<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Log in - {{ config('app.name', 'Login Admin') }}</title>

        @fonts

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="bg-white text-slate-900 antialiased flex min-h-screen flex-col items-center justify-center p-6 lg:p-8">

        <div class="w-full max-w-[400px]">

            {{-- Logo / brand mark --}}
            <div class="mb-8 flex items-center justify-center">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2">
                    <span class="text-[2rem] font-extrabold tracking-tight text-blue-800 leading-none">
                        Sistem Klinik Perusahaan
                    </span>
                </a>
            </div>

            {{-- Session status (e.g. "password reset link sent") --}}
            @if (session('status'))
                <div class="mb-4 rounded-lg border border-blue-100 bg-blue-50 px-4 py-3 text-sm text-blue-900">
                    {{ session('status') }}
                </div>
            @endif

            <div class="rounded-xl border border-blue-100 bg-white p-6 shadow-xl shadow-blue-100/60 lg:p-8">

                <h1 class="mb-1 text-lg font-semibold text-slate-900">Log in admin account</h1>
                <p class="mb-6 text-[13px] leading-[20px] text-slate-500">
                    Enter your username and password to access the admin dashboard.
                </p>

                <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-4">
                    @csrf

                    {{-- Username --}}
                    <div class="flex flex-col gap-1.5">
                        <label for="username" class="text-[13px] font-medium text-slate-700">
                            Username
                        </label>
                        <input
                            id="username"
                            type="text"
                            name="username"
                            value="{{ old('username') }}"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="admin"
                            class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-[13px] text-slate-900 placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring focus:ring-blue-100 {{ $errors->has('username') ? 'border-red-500' : '' }}"
                        >
                        @error('username')
                            <p class="text-[13px] text-[#F53003] dark:text-[#FF4433]">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="flex flex-col gap-1.5">
                        <div class="flex items-center justify-between">
                            <label for="password" class="text-[13px] font-medium text-slate-700">
                                Password
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-[13px] font-medium text-blue-700 underline underline-offset-4 hover:text-blue-900">
                                    Forgot password?
                                </a>
                            @endif
                        </div>
                        <div class="relative">
                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="••••••••"
                                class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 pr-10 text-[13px] text-slate-900 placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring focus:ring-blue-100 {{ $errors->has('password') ? 'border-red-500' : '' }}"
                            >
                            <button
                                type="button"
                                data-password-toggle
                                aria-label="Tampilkan password"
                                class="absolute right-1 top-1/2 -translate-y-1/2 flex h-7 w-7 items-center justify-center rounded-md text-slate-400 hover:text-blue-700"
                                onclick="togglePasswordVisibility()"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12Z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-[13px] text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Remember me --}}
                    <label for="remember" class="flex items-center gap-2 text-[13px] text-slate-500">
                        <input
                            id="remember"
                            type="checkbox"
                            name="remember"
                            class="h-3.5 w-3.5 rounded border-slate-300 text-blue-700 focus:outline-none focus:ring focus:ring-blue-100"
                        >
                        Remember me
                    </label>

                    <button
                        type="submit"
                        class="mt-2 inline-flex w-full items-center justify-center rounded-lg border border-blue-700 bg-blue-700 px-5 py-2 text-sm font-semibold leading-normal text-white shadow-sm transition-all hover:border-blue-800 hover:bg-blue-800"
                    >
                        Log in
                    </button>
                </form>

            </div>

        </div>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const toggleButton = document.querySelector('[data-password-toggle]');

            if (!passwordInput || !toggleButton) return;

            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            toggleButton.setAttribute('aria-label', isPassword ? 'Sembunyikan password' : 'Tampilkan password');
        }
    </script>

    </body>
</html>