<x-guest-layout>
    <a href="/" style="position: fixed; top: 30px; left: 30px; z-index: 9999; color: white; text-decoration: none; display: flex; align-items: center; font-weight: 500; font-size: 16px;">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16" style="margin-right: 8px;">
            <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
        </svg>
        กลับหน้าหลัก
    </a>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <label for="email" class="block font-medium text-sm text-gray-700">อีเมล</label>
            <input id="email" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-blue-900 focus:ring-blue-900" 
                   type="email" name="email" :value="old('email')" required autofocus autocomplete="username" 
                   placeholder="กรอกอีเมลของคุณ" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <label for="password" class="block font-medium text-sm text-gray-700">รหัสผ่าน</label>
            <input id="password" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-blue-900 focus:ring-blue-900" 
                   type="password" name="password" required autocomplete="current-password" 
                   placeholder="กรอกรหัสผ่าน" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-900 shadow-sm focus:ring-blue-900" name="remember">
                <span class="ms-2 text-sm text-gray-600">จดจำฉัน</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-blue-600 hover:text-blue-900 hover:underline" href="{{ route('password.request') }}">
                    ลืมรหัสผ่าน?
                </a>
            @endif
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-blue-900 hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-900 transition duration-150">
                เข้าสู่ระบบ
            </button>
        </div>
    </form>

    <div class="relative flex py-5 items-center">
        <div class="flex-grow border-t border-gray-300"></div>
        <span class="flex-shrink-0 mx-4 text-gray-400 text-xs">หรือ</span>
        <div class="flex-grow border-t border-gray-300"></div>
    </div>

    <div class="text-center">
        <p class="text-sm text-gray-600 mb-3">ยังไม่มีบัญชีสมาชิก?</p>
        <a href="{{ route('register') }}" class="w-full flex justify-center items-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-blue-900 bg-yellow-400 hover:bg-yellow-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition duration-150 transform hover:-translate-y-0.5">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
            สมัครสมาชิกใหม่
        </a>
    </div>
</x-guest-layout>