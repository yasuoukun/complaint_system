<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-blue-900 rounded-lg shadow-md text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <div>
                    <h2 class="font-bold text-xl md:text-2xl text-blue-900 leading-tight">จัดการสมาชิก</h2>
                    <p class="text-xs md:text-sm text-gray-500">ตั้งค่าผู้ใช้งานและสิทธิ์การเข้าถึง</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                @if (Route::has('admin.users.create'))
                <a href="{{ route('admin.users.create') }}" class="bg-blue-600 text-white px-3 md:px-4 py-1.5 rounded-full text-xs md:text-sm font-bold shadow hover:bg-blue-700 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span class="hidden sm:inline">เพิ่มสมาชิก</span> <span class="sm:hidden">เพิ่ม</span>
                </a>
                @endif
                <div class="hidden sm:block text-sm text-gray-500 bg-white px-3 py-1 rounded-full border shadow-sm">📅 {{ date('d/m/Y') }}</div>
            </div>
        </div>
    </x-slot>

    {{-- ✅ CSS บังคับการแสดงผล --}}
    <style>
        .mobile-view { display: block; }
        .pc-view { display: none; }
        @media (min-width: 768px) {
            .mobile-view { display: none !important; }
            .pc-view { display: block !important; }
        }
    </style>

    <div class="py-6 md:py-12 bg-gray-50 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            {{-- Alert Messages --}}
            @if(session('success')) 
                <div class="p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded shadow-sm flex items-center mb-4">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ session('success') }}
                </div> 
            @endif

            {{-- 
               ✅ แก้ไขโดยใช้ style="..." (Inline CSS) 
               เพื่อบังคับให้เป็น 3 ช่องแน่นอน 100% แม้จะไม่มี class grid-cols-3 ในระบบ
            --}}
            <div class="mb-6" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.5rem;">
                
                <div class="bg-white p-2 md:p-4 rounded-xl shadow-sm border-l-4 border-purple-500 flex flex-col md:flex-row justify-between items-start md:items-center overflow-hidden">
                    <div class="w-full">
                        <p class="text-xs text-gray-500 truncate" style="font-size: 10px; line-height: 1.2;">ผู้ดูแลระบบ</p>
                        <p class="text-lg md:text-2xl font-bold text-purple-700 mt-1 md:mt-0 leading-none">{{ $users->where('role', 'admin')->count() }}</p>
                    </div>
                    <div class="text-purple-100 bg-purple-50 p-2 rounded-full hidden md:block">
                        <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                </div>

                <div class="bg-white p-2 md:p-4 rounded-xl shadow-sm border-l-4 border-blue-500 flex flex-col md:flex-row justify-between items-start md:items-center overflow-hidden">
                    <div class="w-full">
                        <p class="text-xs text-gray-500 truncate" style="font-size: 10px; line-height: 1.2;">สมาชิกสภา</p>
                        <p class="text-lg md:text-2xl font-bold text-blue-700 mt-1 md:mt-0 leading-none">{{ $users->where('role', 'council_member')->count() }}</p>
                    </div>
                    <div class="text-blue-100 bg-blue-50 p-2 rounded-full hidden md:block">
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0c0 .884-.95 2-2.5 2H15"></path></svg>
                    </div>
                </div>

                <div class="bg-white p-2 md:p-4 rounded-xl shadow-sm border-l-4 border-green-500 flex flex-col md:flex-row justify-between items-start md:items-center overflow-hidden">
                    <div class="w-full">
                        <p class="text-xs text-gray-500 truncate" style="font-size: 10px; line-height: 1.2;">ประชาชน</p>
                        <p class="text-lg md:text-2xl font-bold text-green-700 mt-1 md:mt-0 leading-none">{{ $users->where('role', 'complainant')->count() }}</p>
                    </div>
                    <div class="text-green-100 bg-green-50 p-2 rounded-full hidden md:block">
                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                </div>

            </div>

            {{-- Search Form --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
                <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-col md:flex-row gap-3">
                    <div class="flex-1 relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">🔍</span>
                        <input type="text" name="search" value="{{ request('search') }}" class="w-full pl-10 text-sm border-gray-300 rounded-lg h-10 focus:ring-blue-500" placeholder="ค้นหาชื่อ, อีเมล, เบอร์โทร...">
                    </div>
                    <div class="flex gap-2">
                        <select name="role" class="flex-1 md:w-40 text-sm border-gray-300 rounded-lg h-10">
                            <option value="">ทุกตำแหน่ง</option>
                            <option value="complainant" {{ request('role') == 'complainant' ? 'selected' : '' }}>ประชาชน</option>
                            <option value="council_member" {{ request('role') == 'council_member' ? 'selected' : '' }}>สท.</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        <button type="submit" class="bg-blue-600 text-white px-4 rounded-lg text-sm font-bold shadow hover:bg-blue-700 h-10">ค้นหา</button>
                        @if(request()->has('search') || request()->has('role'))
                            <a href="{{ route('admin.users.index') }}" class="bg-gray-100 text-gray-600 px-4 rounded-lg text-sm font-bold flex items-center justify-center h-10 hover:bg-gray-200">ล้าง</a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-blue-200">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-4 py-3 md:px-6 md:py-4 flex justify-between items-center">
                    <h3 class="text-sm md:text-base font-bold text-white flex items-center gap-2">
                        <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        รายชื่อสมาชิก
                    </h3>
                    <span class="bg-white/20 text-white px-3 py-0.5 rounded-full text-xs">{{ $users->count() }} คน</span>
                </div>

                {{-- ========================================================= --}}
                {{-- 📱 MOBILE VIEW: Full Detail Card (รายละเอียดครบ) --}}
                {{-- ========================================================= --}}
                <div class="mobile-view bg-gray-50 p-3 space-y-4">
                    @forelse($users as $user)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden relative">
                            {{-- แถบสีด้านซ้ายตาม Role --}}
                            <div class="absolute left-0 top-0 bottom-0 w-1.5 
                                @if($user->role == 'admin') bg-purple-500 
                                @elseif($user->role == 'council_member') bg-blue-500 
                                @else bg-green-500 @endif">
                            </div>

                            <div class="p-4 pl-5">
                                {{-- ส่วนหัว: ชื่อ + สถานะ --}}
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 shadow-sm
                                            @if($user->role == 'admin') bg-purple-100 text-purple-600
                                            @elseif($user->role == 'council_member') bg-blue-100 text-blue-600
                                            @else bg-green-100 text-green-600 @endif">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        </div>
                                        <div>
                                            <h4 class="text-base font-bold text-gray-800 leading-tight">
                                                {{ $user->first_name }} {{ $user->last_name }}
                                            </h4>
                                            {{-- แสดง Role แบบ Badge --}}
                                            <div class="mt-1">
                                                @if($user->role == 'admin')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-purple-100 text-purple-800 border border-purple-200">
                                                        👮 ผู้ดูแลระบบ (Admin)
                                                    </span>
                                                @elseif($user->role == 'council_member')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-800 border border-blue-200">
                                                        👔 สมาชิกสภา (สท.)
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-800 border border-green-200">
                                                        👤 ประชาชนทั่วไป
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    {{-- ปุ่มลบ --}}
                                    @if(Auth::id() !== $user->id)
                                        <button type="button" onclick="if(confirm('ยืนยันลบผู้ใช้นี้?')) document.getElementById('del-m-{{ $user->id }}').submit()" class="text-red-400 hover:text-red-600 p-1">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                        <form id="del-m-{{ $user->id }}" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="hidden">@csrf @method('DELETE')</form>
                                    @endif
                                </div>

                                {{-- ส่วนเนื้อหา: รายละเอียดครบ (อีเมล, เบอร์, วันที่, โซน) --}}
                                <div class="bg-gray-50 rounded-lg p-3 space-y-2 text-xs text-gray-600 border border-gray-100 mb-3">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        <span class="break-all">{{ $user->email }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                        <span>{{ $user->phone_number ?? '-' }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span>สมัครเมื่อ: {{ $user->created_at->format('d/m/Y') }}</span>
                                    </div>
                                    @if($user->zone)
                                    <div class="flex items-center gap-2 pt-1">
                                        <span class="bg-white border border-gray-200 px-2 py-0.5 rounded text-[10px] font-bold text-gray-600">
                                            📍 {{ $user->zone }}
                                        </span>
                                    </div>
                                    @endif
                                </div>

                                {{-- ส่วนฟอร์มจัดการ (เปลี่ยน Role/Zone) --}}
                                <div class="pt-2 border-t border-gray-100">
                                    <p class="text-[10px] font-bold text-gray-400 mb-1">จัดการสถานะ / เปลี่ยนแปลงข้อมูล</p>
                                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-2">
                                        @csrf @method('PUT')
                                        <div class="grid grid-cols-2 gap-2">
                                            <select name="role" class="w-full text-xs border-gray-300 rounded h-9 bg-white">
                                                <option value="complainant" {{ $user->role == 'complainant' ? 'selected' : '' }}>👤 ประชาชน</option>
                                                <option value="council_member" {{ $user->role == 'council_member' ? 'selected' : '' }}>👔 สท.</option>
                                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>👮 Admin</option>
                                            </select>
                                            <select name="zone" class="w-full text-xs border-gray-300 rounded h-9 bg-white">
                                                <option value="">- เลือกเขต -</option>
                                                <option value="เขต 1" {{ $user->zone == 'เขต 1' ? 'selected' : '' }}>เขต 1</option>
                                                <option value="เขต 2" {{ $user->zone == 'เขต 2' ? 'selected' : '' }}>เขต 2</option>
                                                <option value="เขต 3" {{ $user->zone == 'เขต 3' ? 'selected' : '' }}>เขต 3</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="w-full bg-blue-600 text-white text-xs py-2 rounded-lg font-bold shadow-sm hover:bg-blue-700 transition">
                                            💾 บันทึกการเปลี่ยนแปลง
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-400 bg-white rounded-lg border border-dashed">
                            ไม่พบข้อมูลสมาชิก
                        </div>
                    @endforelse

                    {{-- Pagination --}}
                    @if(method_exists($users, 'hasPages') && $users->hasPages())
                        <div class="pt-2 px-1">{{ $users->links() }}</div>
                    @endif
                </div>

                {{-- ========================================================= --}}
                {{-- 🖥️ PC VIEW: TABLE เดิม 100% (ไม่แตะต้อง) --}}
                {{-- ========================================================= --}}
                <div class="pc-view p-0 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-blue-50 text-blue-900">
                            <tr>
                                <th class="px-4 py-3 text-center text-xs font-bold uppercase w-16">สถานะ</th>
                                <th class="px-4 py-3 text-left text-xs font-bold uppercase">ชื่อ - สกุล / ข้อมูลติดต่อ</th>
                                <th class="px-4 py-3 text-center text-xs font-bold uppercase">บทบาทปัจจุบัน</th>
                                <th class="px-4 py-3 text-left text-xs font-bold uppercase w-1/3">จัดการ / เปลี่ยนสถานะ</th>
                                <th class="px-4 py-3 text-center text-xs font-bold uppercase w-16">ลบ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($users as $user)
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
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800 border border-purple-200">👮 แอดมิน</span>
                                        @elseif($user->role === 'council_member')
                                            <div class="flex flex-col items-center gap-1">
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 border border-blue-200">👔 สมาชิกสภา</span>
                                                <span class="text-[10px] text-gray-500 bg-gray-50 px-1.5 rounded border">📍 {{ $user->zone ?? 'ไม่ระบุ' }}</span>
                                            </div>
                                        @else
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">👤 ประชาชน</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 align-top">
                                        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="bg-gray-50 p-2 rounded border border-gray-200 flex flex-col gap-2">
                                            @csrf @method('PUT')
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
                                            <button type="submit" class="w-full bg-green-500 text-white px-2 py-1 rounded text-[10px] font-bold hover:bg-green-600 shadow-sm transition">💾 บันทึกการเปลี่ยนแปลง</button>
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
                                <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400 text-sm">ไม่พบข้อมูลสมาชิก</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    
                    @if(method_exists($users, 'hasPages') && $users->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">{{ $users->links() }}</div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>