<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                    จัดการข่าวประชาสัมพันธ์
                </h2>
                <p class="text-sm text-gray-500 mt-1">บริหารจัดการข่าวสาร กิจกรรม และประกาศต่างๆ ของเทศบาล</p>
            </div>
            
            <div class="flex gap-3 w-full md:w-auto">
                <a href="{{ route('admin.news.create', ['type' => 'link']) }}" class="group relative flex-1 md:flex-none inline-flex items-center justify-center px-4 md:px-6 py-2.5 text-sm font-bold text-white transition-all duration-200 bg-emerald-500 font-pj rounded-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-600 hover:bg-emerald-600 shadow-md hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                    <span class="truncate">โพสต์ลิงก์</span>
                </a>

                <a href="{{ route('admin.news.create', ['type' => 'content']) }}" class="flex-1 md:flex-none inline-flex items-center justify-center px-4 md:px-6 py-2.5 text-sm font-bold text-white transition-all duration-200 bg-indigo-600 font-pj rounded-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600 hover:bg-indigo-700 shadow-md hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    <span class="truncate">เขียนข่าวใหม่</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 md:py-10 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 px-4">
            
            {{-- 
                ✅ ส่วนสถิติ: ใช้เทคนิคเดียวกับหน้าสมาชิก 
                ใช้ style="display: grid; grid-template-columns: repeat(3, 1fr);" เพื่อบังคับ 3 ช่องแนวนอน
            --}}
            <div class="mb-2" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.5rem;">
                
                {{-- Card 1: ข่าวทั้งหมด --}}
                <div class="bg-white p-2 md:p-4 rounded-xl shadow-sm border-l-4 border-blue-500 flex flex-col md:flex-row justify-between items-start md:items-center overflow-hidden">
                    <div class="w-full">
                        <p class="text-xs text-gray-500 truncate" style="font-size: 10px; line-height: 1.2;">ข่าวทั้งหมด</p>
                        <p class="text-lg md:text-2xl font-bold text-blue-700 mt-1 md:mt-0 leading-none">{{ $news->total() }}</p>
                    </div>
                    <div class="hidden md:block p-2 bg-blue-50 rounded-full text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                </div>

                {{-- Card 2: แบบเขียนเอง --}}
                <div class="bg-white p-2 md:p-4 rounded-xl shadow-sm border-l-4 border-indigo-500 flex flex-col md:flex-row justify-between items-start md:items-center overflow-hidden">
                    <div class="w-full">
                        <p class="text-xs text-gray-500 truncate" style="font-size: 10px; line-height: 1.2;">เขียนเอง</p>
                        <p class="text-lg md:text-2xl font-bold text-indigo-700 mt-1 md:mt-0 leading-none">
                            {{ \App\Models\News::whereNull('link_url')->count() }}
                        </p>
                    </div>
                    <div class="hidden md:block p-2 bg-indigo-50 rounded-full text-indigo-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                </div>

                {{-- Card 3: แบบแนบลิงก์ --}}
                <div class="bg-white p-2 md:p-4 rounded-xl shadow-sm border-l-4 border-emerald-500 flex flex-col md:flex-row justify-between items-start md:items-center overflow-hidden">
                    <div class="w-full">
                        <p class="text-xs text-gray-500 truncate" style="font-size: 10px; line-height: 1.2;">แนบลิงก์</p>
                        <p class="text-lg md:text-2xl font-bold text-emerald-700 mt-1 md:mt-0 leading-none">
                            {{ \App\Models\News::whereNotNull('link_url')->count() }}
                        </p>
                    </div>
                    <div class="hidden md:block p-2 bg-emerald-50 rounded-full text-emerald-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                    </div>
                </div>

            </div>

            @if(session('success'))
                <div class="p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r shadow-sm flex items-center animate-fade-in-down">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                    <h3 class="font-bold text-gray-800 text-lg">รายการข่าวล่าสุด</h3>
                    <span class="text-xs text-gray-400 hidden md:inline">เรียงตามวันที่ล่าสุด</span>
                </div>

                @if($news->count() > 0)
                    {{-- Mobile View List --}}
                    <div class="md:hidden">
                        <div class="divide-y divide-gray-100">
                            @foreach($news as $item)
                            <div class="p-4 bg-white hover:bg-gray-50 transition duration-150">
                                <div class="flex gap-4">
                                    <div class="w-20 h-20 flex-shrink-0 rounded-lg overflow-hidden border border-gray-200 bg-gray-100">
                                        @if($item->image_path)
                                            <img src="{{ asset('storage/' . $item->image_path) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="flex-1 min-w-0">
                                        <div class="flex justify-between items-start">
                                            <span class="text-xs text-gray-400 mb-1 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                {{ $item->created_at->format('d/m/Y') }}
                                            </span>
                                            
                                            @if($item->link_url)
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">Link</span>
                                            @else
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-100 text-indigo-700">Internal</span>
                                            @endif
                                        </div>

                                        <h4 class="font-bold text-gray-800 text-sm line-clamp-2 leading-snug mb-1">{{ $item->title }}</h4>
                                        
                                        @if($item->link_url)
                                            <a href="{{ $item->link_url }}" target="_blank" class="text-xs text-emerald-500 truncate block">
                                                {{ $item->link_url }}
                                            </a>
                                        @endif
                                    </div>
                                </div>

                                <div class="mt-3 pt-3 border-t border-gray-50 flex items-center justify-between">
                                    <div class="flex items-center gap-2 text-xs text-gray-500">
                                        <div class="w-5 h-5 rounded-full bg-gray-200 flex items-center justify-center font-bold text-gray-600 text-[10px]">
                                            {{ mb_substr($item->user->name ?? 'A', 0, 1) }}
                                        </div>
                                        <span>{{ $item->user->name ?? 'Unknown' }}</span>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.news.edit', $item->id) }}" class="px-3 py-1.5 bg-yellow-50 text-yellow-600 border border-yellow-200 rounded-md text-xs font-bold">
                                            แก้ไข
                                        </a>
                                        <form action="{{ route('admin.news.destroy', $item->id) }}" method="POST" onsubmit="return confirm('ยืนยันการลบ?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 bg-red-50 text-red-600 border border-red-200 rounded-md text-xs font-bold">
                                                ลบ
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- PC View Table --}}
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                                <tr>
                                    <th class="px-6 py-4 font-semibold">รูปปก</th>
                                    <th class="px-6 py-4 font-semibold w-2/5">หัวข้อข่าว / รายละเอียด</th>
                                    <th class="px-6 py-4 font-semibold text-center">ประเภท</th>
                                    <th class="px-6 py-4 font-semibold text-center">ผู้ลงข่าว</th>
                                    <th class="px-6 py-4 font-semibold text-right">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($news as $item)
                                <tr class="hover:bg-gray-50/50 transition duration-150 group">
                                    <td class="px-6 py-4">
                                        <div class="relative w-20 h-14 rounded-lg overflow-hidden border border-gray-200 shadow-sm group-hover:shadow-md transition">
                                            @if($item->image_path)
                                                <img src="{{ asset('storage/' . $item->image_path) }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-400">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="font-bold text-gray-800 text-base line-clamp-1 group-hover:text-indigo-600 transition">{{ $item->title }}</span>
                                            <span class="text-xs text-gray-400 mt-1 flex items-center gap-2">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                {{ $item->created_at->format('d/m/Y H:i') }} น.
                                            </span>
                                            @if($item->link_url)
                                                <a href="{{ $item->link_url }}" target="_blank" class="text-xs text-emerald-500 mt-1 hover:underline truncate w-full max-w-xs flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                                    {{ $item->link_url }}
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($item->link_url)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                                                Link
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-100 text-indigo-700">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                                                Internal
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600">
                                                {{ mb_substr($item->user->name ?? 'A', 0, 1) }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.news.edit', $item->id) }}" class="p-2 bg-white border border-gray-200 rounded-lg text-yellow-600 hover:bg-yellow-50 hover:border-yellow-300 transition shadow-sm" title="แก้ไข">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </a>
                                            <form action="{{ route('admin.news.destroy', $item->id) }}" method="POST" onsubmit="return confirm('ยืนยันการลบข่าวนี้?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-2 bg-white border border-gray-200 rounded-lg text-red-600 hover:bg-red-50 hover:border-red-300 transition shadow-sm" title="ลบ">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                        {{ $news->links() }}
                    </div>
                @else
                    <div class="p-12 text-center">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-100 mb-6">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">ยังไม่มีข่าวประชาสัมพันธ์</h3>
                        <p class="text-gray-500 mb-8 max-w-sm mx-auto">เริ่มต้นสร้างข่าวใหม่เพื่อประชาสัมพันธ์ข้อมูลข่าวสารให้กับประชาชน</p>
                        <div class="flex justify-center gap-4">
                            <a href="{{ route('admin.news.create', ['type' => 'content']) }}" class="px-5 py-2.5 bg-indigo-600 text-white font-bold rounded-xl shadow hover:bg-indigo-700 transition">เขียนข่าวใหม่</a>
                            <a href="{{ route('admin.news.create', ['type' => 'link']) }}" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 font-bold rounded-xl shadow-sm hover:bg-gray-50 transition">แนบลิงก์</a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>