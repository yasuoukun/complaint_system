<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-blue-900 rounded-lg shadow-md text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <div>
                    <h2 class="font-bold text-2xl text-blue-900 leading-tight">
                        จัดการสมาชิก
                    </h2>
                    <p class="text-sm text-gray-500">ตั้งค่าผู้ใช้งานและสิทธิ์การเข้าถึง</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                @if (Route::has('admin.users.create'))
                <a href="{{ route('admin.users.create') }}" class="bg-blue-600 text-white px-4 py-1.5 rounded-full text-sm font-bold shadow hover:bg-blue-700 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    เพิ่มสมาชิก
                </a>
                @endif
                <div class="text-sm text-gray-500 bg-white px-3 py-1 rounded-full border shadow-sm">📅 {{ date('d/m/Y') }}</div>
            </div>
        </div>
    </x-slot>

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <div class="py-12 bg-gray-50 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            @if(session('success')) 
                <div class="p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r shadow-sm flex items-center animate-fade-in-down">
                    <div class="bg-green-100 p-1 rounded-full mr-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></div>
                    {{ session('success') }}
                </div> 
            @endif
            @if(session('error')) 
                <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r shadow-sm flex items-center animate-fade-in-down">
                    <div class="bg-red-100 p-1 rounded-full mr-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                    {{ session('error') }}
                </div> 
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-purple-500 flex justify-between items-center">
                    <div>
                        <p class="text-xs text-gray-500">ผู้ดูแลระบบ (Admin)</p>
                        <p class="text-2xl font-bold">{{ $users->where('role', 'admin')->count() }}</p>
                    </div>
                    <div class="text-purple-500"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg></div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-blue-500 flex justify-between items-center">
                    <div>
                        <p class="text-xs text-gray-500">สมาชิกสภา (สท.)</p>
                        <p class="text-2xl font-bold">{{ $users->where('role', 'council_member')->count() }}</p>
                    </div>
                    <div class="text-blue-500"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0c0 .884-.95 2-2.5 2H15"></path></svg></div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-green-500 flex justify-between items-center">
                    <div>
                        <p class="text-xs text-gray-500">ประชาชนทั่วไป</p>
                        <p class="text-2xl font-bold">{{ $users->where('role', 'complainant')->count() }}</p>
                    </div>
                    <div class="text-green-500"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg></div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                <div class="flex flex-col md:flex-row gap-4 items-end">
                    <form action="{{ route('admin.users.index') }}" method="GET" class="w-full flex flex-col md:flex-row gap-4">
                        <div class="flex-1">
                            <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">ค้นหาข้อมูล</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </span>
                                <input type="text" name="search" value="{{ request('search') }}" class="w-full pl-10 text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="ชื่อ, อีเมล, เบอร์โทร...">
                            </div>
                        </div>
                        <div class="w-full md:w-1/4">
                            <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">ตำแหน่ง</label>
                            <select name="role" class="w-full text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- ทั้งหมด --</option>
                                <option value="complainant" {{ request('role') == 'complainant' ? 'selected' : '' }}>👤 ประชาชน</option>
                                <option value="council_member" {{ request('role') == 'council_member' ? 'selected' : '' }}>🏅 สท.</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>👮 Admin</option>
                            </select>
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg text-sm font-bold shadow hover:bg-blue-700 transition h-[38px] self-end">ค้นหา</button>
                            @if(request('search') || request('role'))
                                <a href="{{ route('admin.users.index') }}" class="bg-gray-200 text-gray-600 px-4 py-2 rounded-lg text-sm font-bold hover:bg-gray-300 transition h-[38px] self-end flex items-center">ล้างค่า</a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-blue-200">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4 flex justify-between items-center">
                    <h3 class="text-base font-bold text-white flex items-center gap-2">
                        <span class="bg-white/20 p-1 rounded"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg></span>
                        รายชื่อสมาชิกทั้งหมด
                    </h3>
                    <span class="bg-white text-blue-600 text-xs font-bold px-3 py-1 rounded-full shadow-sm">{{ $users->count() }} คน</span>
                </div>
                
                <div class="p-0 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-blue-50 text-blue-900">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-bold uppercase w-16 text-center">สถานะ</th>
                                <th class="px-4 py-3 text-left text-xs font-bold uppercase">ชื่อ - สกุล / ข้อมูลติดต่อ</th>
                                <th class="px-4 py-3 text-center text-xs font-bold uppercase">บทบาทปัจจุบัน</th>
                                <th class="px-4 py-3 text-left text-xs font-bold uppercase w-1/3">จัดการ / เปลี่ยนสถานะ</th>
                                <th class="px-4 py-3 text-center text-xs font-bold uppercase w-16">ลบ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($users as $index => $user)
                                <tr class="hover:bg-blue-50/30 transition">
                                    <td class="px-4 py-4 align-middle text-center">
                                        <div class="mx-auto flex items-center justify-center w-10 h-10 rounded-full 
                                            @if($user->role == 'admin') bg-purple-100 text-purple-600
                                            @elseif($user->role == 'council_member') bg-blue-100 text-blue-600
                                            @else bg-green-100 text-green-600 @endif">
                                            @if($user->role == 'admin') <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                            @elseif($user->role == 'council_member') <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0c0 .884-.95 2-2.5 2H15"></path></svg>
                                            @else <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                            @endif
                                        </div>
                                    </td>

                                    <td class="px-4 py-4 align-top">
                                        <div class="text-sm font-bold text-gray-800">{{ $user->first_name }} {{ $user->last_name }}</div>
                                        <div class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                            {{ $user->email }}
                                        </div>
                                        <div class="text-xs text-gray-500 mt-0.5 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                            {{ $user->phone_number ?? '-' }}
                                        </div>
                                        <div class="mt-1 inline-block px-2 py-0.5 bg-gray-100 text-gray-500 rounded text-[10px]">สมัครเมื่อ: {{ $user->created_at->format('d/m/Y') }}</div>
                                    </td>

                                    <td class="px-4 py-4 text-center align-middle">
                                        @if($user->role === 'admin')
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800 border border-purple-200">
                                                👮 แอดมิน
                                            </span>
                                        @elseif($user->role === 'council_member')
                                            <div class="flex flex-col items-center gap-1">
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 border border-blue-200">
                                                    👔 สมาชิกสภา
                                                </span>
                                                <span class="text-[10px] text-gray-500 bg-gray-50 px-1.5 rounded border">📍 {{ $user->zone ?? 'ไม่ระบุ' }}</span>
                                            </div>
                                        @else
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">
                                                👤 ประชาชน
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-4 align-top">
                                        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="bg-gray-50 p-2 rounded border border-gray-200 flex flex-col gap-2">
                                            @csrf
                                            @method('PUT')
                                            
                                            <div class="flex gap-1">
                                                <select name="role" class="w-1/2 text-[10px] border-gray-300 rounded py-0 px-1 h-7 bg-white">
                                                    <option value="complainant" {{ $user->role == 'complainant' ? 'selected' : '' }}>👤 ประชาชน</option>
                                                    <option value="council_member" {{ $user->role == 'council_member' ? 'selected' : '' }}>👔 สท.</option>
                                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>👮 Admin</option>
                                                </select>
                                                <select name="zone" class="w-1/2 text-[10px] border-gray-300 rounded py-0 px-1 h-7 bg-white">
                                                    <option value="">- เขต -</option>
                                                    @foreach(['เขต 1', 'เขต 2', 'เขต 3'] as $zone)
                                                        <option value="{{ $zone }}" {{ $user->zone == $zone ? 'selected' : '' }}>{{ $zone }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <button type="submit" class="w-full bg-green-500 text-white px-2 py-1 rounded text-[10px] font-bold hover:bg-green-600 shadow-sm transition">
                                                💾 บันทึกการเปลี่ยนแปลง
                                            </button>
                                        </form>
                                    </td>

                                    <td class="px-4 py-4 text-center align-middle">
                                        @if(Auth::id() !== $user->id)
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('ยืนยันลบ?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 bg-white border border-red-200 hover:bg-red-50 p-1.5 rounded transition shadow-sm" title="ลบ">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-400 text-sm">
                                        ไม่พบข้อมูลสมาชิก
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if(method_exists($users, 'hasPages') && $users->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>