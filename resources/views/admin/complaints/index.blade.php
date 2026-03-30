<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-blue-900 rounded-lg shadow-sm text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                </div>
                <h2 class="font-bold text-xl text-blue-900 leading-tight">Admin Dashboard</h2>
            </div>
            <div class="text-sm text-gray-500 bg-white px-3 py-1 rounded-full border shadow-sm"> {{ date('d/m/Y') }}</div>
        </div>
    </x-slot>

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <div class="py-8 bg-gray-100 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-yellow-400 flex justify-between items-center">
                    <div><p class="text-xs text-gray-500">รอตรวจสอบ</p><p class="text-2xl font-bold">{{ $pendingComplaints->count() }}</p></div>
                    <div class="text-yellow-400"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-orange-400 flex justify-between items-center">
                    <div><p class="text-xs text-gray-500">รับเรื่องแล้ว</p><p class="text-2xl font-bold">{{ $waitingComplaints->count() }}</p></div>
                    <div class="text-orange-400"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg></div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-blue-500 flex justify-between items-center">
                    <div><p class="text-xs text-gray-500">กำลังทำ</p><p class="text-2xl font-bold">{{ $inProgressComplaints->count() }}</p></div>
                    <div class="text-blue-500"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg></div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-gray-400 flex justify-between items-center">
                    <div><p class="text-xs text-gray-500">จบงาน</p><p class="text-2xl font-bold">{{ $historyComplaints->count() }}</p></div>
                    <div class="text-gray-400"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg></div>
                </div>
            </div>

            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <form action="{{ route('admin.complaints.index') }}" method="GET" class="flex flex-col md:flex-row gap-3 items-end">
                    
                    <div class="flex-1 w-full">
                        <label class="block text-xs font-bold text-gray-500 mb-1">ค้นหาข้อมูล</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}" class="w-full pl-9 text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="เรื่อง, ชื่อ, เบอร์โทร, เลขบัตร...">
                        </div>
                    </div>
                    
                    <div class="w-full md:w-32">
                        <label class="block text-xs font-bold text-gray-500 mb-1">เขตพื้นที่</label>
                        <select name="filter_zone" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 cursor-pointer">
                            <option value="">-- ทุกเขต --</option>
                            <option value="1" {{ request('filter_zone') == '1' ? 'selected' : '' }}>เขต 1</option>
                            <option value="2" {{ request('filter_zone') == '2' ? 'selected' : '' }}>เขต 2</option>
                            <option value="3" {{ request('filter_zone') == '3' ? 'selected' : '' }}>เขต 3</option>
                        </select>
                    </div>

                    <div class="w-full md:w-40">
                        <label class="block text-xs font-bold text-gray-500 mb-1">วันที่แจ้ง</label>
                        <input type="date" name="filter_date" value="{{ request('filter_date') }}" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-bold shadow-sm hover:bg-blue-700 transition flex items-center gap-1">
                            ค้นหา
                        </button>
                        @if(request('search') || request('filter_zone') || request('filter_date'))
                            <a href="{{ route('admin.complaints.index') }}" class="bg-gray-200 text-gray-600 px-3 py-2 rounded-md text-sm font-bold hover:bg-gray-300 transition">
                                ล้างค่า
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            @if(session('success')) <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">{{ session('success') }}</div> @endif
            @if(session('error')) <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm">{{ session('error') }}</div> @endif

            <div class="bg-white rounded-lg shadow overflow-hidden border border-yellow-200">
                <div class="px-6 py-3 bg-yellow-100 border-b border-yellow-200 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-yellow-800">1. คำร้องใหม่ (รอตรวจสอบ)</h3>
                    <span class="bg-white text-yellow-800 px-3 py-1 rounded-full text-xs font-bold shadow-sm">{{ $pendingComplaints->count() }}</span>
                </div>
                @include('admin.complaints.partials.table_template', ['items' => $pendingComplaints, 'type' => 'pending', 'color' => 'yellow'])
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-lg shadow overflow-hidden border border-orange-200">
                    <div class="px-6 py-3 bg-orange-100 border-b border-orange-200 flex justify-between items-center">
                        <h3 class="text-base font-bold text-orange-800">2. รับเรื่องแล้ว</h3>
                        <span class="bg-white text-orange-800 px-2 py-0.5 rounded-full text-xs font-bold shadow-sm">{{ $waitingComplaints->count() }}</span>
                    </div>
                    @include('admin.complaints.partials.table_template', ['items' => $waitingComplaints, 'type' => 'waiting', 'color' => 'orange'])
                </div>

                <div class="bg-white rounded-lg shadow overflow-hidden border border-blue-200">
                    <div class="px-6 py-3 bg-blue-100 border-b border-blue-200 flex justify-between items-center">
                        <h3 class="text-base font-bold text-blue-800">3. กำลังดำเนินการ</h3>
                        <span class="bg-white text-blue-800 px-2 py-0.5 rounded-full text-xs font-bold shadow-sm">{{ $inProgressComplaints->count() }}</span>
                    </div>
                    @include('admin.complaints.partials.table_template', ['items' => $inProgressComplaints, 'type' => 'in_progress', 'color' => 'blue'])
                </div>
            </div>

            <div class="bg-white rounded-lg shadow overflow-hidden border border-gray-200" x-data="{ showDelete: false, mode: 'month' }">
                <div class="px-6 py-3 bg-gray-100 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-base font-bold text-gray-700">4. ประวัติงานที่จบแล้ว</h3>
                    <button @click="showDelete = !showDelete" class="text-xs bg-white border border-red-300 text-red-600 px-3 py-1 rounded hover:bg-red-50">🗑️ ล้างประวัติ</button>
                </div>
                
                <div x-show="showDelete" class="bg-red-50 p-3 border-b border-red-100 flex flex-wrap gap-3 items-center" style="display: none;">
                    
                    <form action="{{ route('admin.complaints.bulk_destroy') }}" method="POST" onsubmit="return confirm('ยืนยันการลบ?');" class="flex flex-wrap gap-2 items-center">
                        @csrf <input type="hidden" name="type" value="month">
                        <div class="flex items-center gap-2">
                            <input type="month" name="select_month" required class="text-xs border-gray-300 rounded">
                            <button class="bg-red-600 text-white px-3 py-1 rounded text-xs hover:bg-red-700 whitespace-nowrap">ลบเดือนนี้</button>
                        </div>
                    </form>

                    <span class="text-gray-300 hidden md:inline">|</span>

                    <form action="{{ route('admin.complaints.bulk_destroy') }}" method="POST" onsubmit="return confirm('ลบ?');" class="flex flex-wrap gap-2 items-center">
                        @csrf <input type="hidden" name="type" value="range">
                        <div class="flex flex-wrap items-center gap-2">
                            <input type="date" name="start_date" required class="text-xs border-gray-300 rounded"> 
                            <span>-</span> 
                            <input type="date" name="end_date" required class="text-xs border-gray-300 rounded">
                            <button class="bg-red-600 text-white px-3 py-1 rounded text-xs hover:bg-red-700 whitespace-nowrap">ลบช่วงเวลา</button>
                        </div>
                    </form>
                    
                </div>

                @include('admin.complaints.partials.table_template', ['items' => $historyComplaints, 'type' => 'history', 'color' => 'gray'])
            </div>

        </div>
    </div>
</x-app-layout>