<x-guest-layout>
    <a href="/" style="position: fixed; top: 30px; left: 30px; z-index: 9999; color: white; text-decoration: none; display: flex; align-items: center; font-weight: 500; font-size: 16px;">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16" style="margin-right: 8px;">
            <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
        </svg>
        กลับหน้าหลัก
    </a>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('ลืมรหัสผ่านใช่ไหม? ไม่ต้องกังวล เพียงแจ้งที่อยู่อีเมลของคุณให้เราทราบ แล้วเราจะส่งลิงก์สำหรับรีเซ็ตรหัสผ่านไปให้ทางอีเมล เพื่อให้คุณสามารถเลือกรหัสผ่านใหม่ได้') }}
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    @if ($errors->any())
        <div class="mb-4 rounded-md bg-red-50 p-4 border border-red-200">
            <div class="flex">
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">พบข้อผิดพลาด:</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('รีเซ็ตรหัสผ่านอีเมล') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>