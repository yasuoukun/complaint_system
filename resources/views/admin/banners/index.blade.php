<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-indigo-600 rounded-lg shadow-lg text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <h2 class="font-bold text-xl text-slate-800 leading-tight">
                    จัดการภาพสไลด์ (Banner)
                </h2>
                <p class="text-sm text-slate-500">เพิ่มหรือลบรูปภาพประชาสัมพันธ์หน้าเว็บไซต์</p>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-slate-50 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if(session('success'))
                <div class="p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r shadow-sm flex items-center gap-3">
                    <div class="bg-green-100 p-1 rounded-full"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></div>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-2">
                    <span class="w-2 h-8 bg-indigo-500 rounded-full"></span>
                    <h3 class="text-lg font-bold text-slate-700">อัปโหลดรูปภาพใหม่</h3>
                </div>
                
                <div class="p-6">
                    <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col md:flex-row gap-6 items-start">
                        @csrf
                        
                        <div class="flex-1 w-full">
                            <label class="block text-sm font-bold text-slate-700 mb-2">รูปภาพ (Image)</label>
                            <div class="relative">
                                <input type="file" name="image" required class="block w-full text-sm text-slate-500
                                    file:mr-4 file:py-2.5 file:px-4
                                    file:rounded-lg file:border-0
                                    file:text-sm file:font-bold
                                    file:bg-indigo-50 file:text-indigo-700
                                    hover:file:bg-indigo-100
                                    border border-slate-300 rounded-lg cursor-pointer shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                                ">
                            </div>
                            <p class="text-xs text-slate-400 mt-1">* รองรับไฟล์ jpg, png, jpeg</p>
                        </div>

                        <div class="flex-1 w-full">
                            <label class="block text-sm font-bold text-slate-700 mb-2">ลิ้งค์ปลายทาง (URL)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                                </div>
                                <input type="url" name="link_url" class="pl-10 block w-full border-slate-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2.5" placeholder="https://example.com (ไม่บังคับ)">
                            </div>
                        </div>

                        <div class="mt-1 md:mt-7">
                            <button type="submit" class="bg-indigo-600 text-white px-6 py-2.5 rounded-lg font-bold shadow-md hover:bg-indigo-700 hover:shadow-lg transition flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                เพิ่มรูปภาพ
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <h3 class="text-lg font-bold text-slate-700 pl-1 border-l-4 border-indigo-500">รายการภาพปัจจุบัน ({{ $banners->count() }})</h3>
            
            @if($banners->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($banners as $banner)
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden group hover:shadow-md transition duration-300">
                            <div class="relative h-48 w-full bg-slate-100 overflow-hidden">
                                <img src="{{ asset('storage/' . $banner->image_path) }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-105" alt="Banner Image">
                                
                                @if($banner->link_url)
                                    <div class="absolute top-2 right-2">
                                        <span class="bg-black/50 text-white text-[10px] px-2 py-1 rounded-full backdrop-blur-sm flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                            มีลิ้งค์
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <div class="p-4">
                                <div class="flex justify-between items-center">
                                    <div class="text-xs text-slate-500 truncate max-w-[60%]">
                                        @if($banner->link_url)
                                            <a href="{{ $banner->link_url }}" target="_blank" class="text-indigo-600 hover:underline truncate block">
                                                {{ $banner->link_url }}
                                            </a>
                                        @else
                                            <span class="text-slate-400">- ไม่มีลิ้งค์ -</span>
                                        @endif
                                    </div>

                                    <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" onsubmit="return confirm('ยืนยันการลบรูปภาพนี้?');">
                                        @csrf @method('DELETE')
                                        <button class="text-xs bg-white border border-red-200 text-red-500 hover:bg-red-50 hover:text-red-700 px-3 py-1.5 rounded-lg transition flex items-center gap-1 shadow-sm">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            ลบ
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-16 text-center bg-white rounded-2xl border-2 border-dashed border-slate-200">
                    <div class="bg-slate-50 p-4 rounded-full inline-block mb-3">
                        <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <p class="text-slate-500 font-medium">ยังไม่มีรูปภาพสไลด์</p>
                    <p class="text-slate-400 text-sm mt-1">เพิ่มรูปภาพเพื่อแสดงในหน้าแรก</p>
                </div>
            @endif
            
        </div>
    </div>
</x-app-layout>