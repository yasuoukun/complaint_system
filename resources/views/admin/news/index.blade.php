<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-yellow-500 rounded-lg shadow-md">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
            </div>
            <h2 class="font-bold text-2xl text-blue-900 leading-tight">
                จัดการข่าวประชาสัมพันธ์
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            @if(session('success'))
                <div class="p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r shadow-sm flex items-center animate-fade-in-down">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 sticky top-24">
                        <div class="bg-blue-900 px-6 py-4 border-b border-blue-800">
                            <h3 class="font-bold text-white flex items-center gap-2">
                                <span class="bg-yellow-400 text-blue-900 text-xs px-2 py-0.5 rounded-full">New</span> ลงประกาศใหม่
                            </h3>
                        </div>
                        <div class="p-6">
                            <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">รูปภาพ (Banner) <span class="text-red-500">*</span></label>
                                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:bg-gray-50 cursor-pointer relative">
                                        <div class="space-y-1 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="flex text-sm text-gray-600 justify-center">
                                                <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                                    <span>อัปโหลดไฟล์</span>
                                                    <input id="file-upload" name="image" type="file" class="sr-only" accept="image/*" required onchange="document.getElementById('preview-text').innerText = 'เลือกไฟล์: ' + this.files[0].name">
                                                </label>
                                            </div>
                                            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 5MB</p>
                                            <p id="preview-text" class="text-sm text-green-600 font-bold mt-2"></p>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">หัวข้อ / คำอธิบายสั้นๆ</label>
                                    <input type="text" name="title" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="เช่น กิจกรรมวันแม่...">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">ลิ้งค์ปลายทาง (ถ้ามี)</label>
                                    <input type="url" name="link_url" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm" placeholder="https://www.facebook.com/...">
                                    <p class="text-xs text-gray-400 mt-1">ใส่เมื่อต้องการให้คนกดที่รูปแล้วเด้งไปเว็บอื่น</p>
                                </div>

                                <button type="submit" class="w-full bg-blue-900 text-white py-2.5 rounded-lg font-bold shadow hover:bg-blue-800 transition transform hover:scale-105">
                                    + โพสต์ข่าวสาร
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <h3 class="text-lg font-bold text-gray-700 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                        รายการข่าวที่แสดงอยู่ ({{ $news->count() }})
                    </h3>

                    @if($news->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($news as $item)
                                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden group relative hover:shadow-lg transition flex flex-col">
                                    <div class="h-48 w-full overflow-hidden relative">
                                        <img src="{{ asset('storage/' . $item->image_path) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                        @if($item->link_url)
                                            <div class="absolute top-2 right-2 bg-black/50 text-white text-xs px-2 py-1 rounded backdrop-blur-sm">
                                                🔗 มีลิ้งค์
                                            </div>
                                        @endif
                                    </div>

                                    <div class="p-4 flex-1 flex flex-col justify-between">
                                        <div>
                                            <h4 class="font-bold text-gray-800 truncate mb-1">{{ $item->title ?? '(ไม่มีหัวข้อ)' }}</h4>
                                            <p class="text-xs text-gray-400 mb-3">โพสต์เมื่อ: {{ $item->created_at->format('d/m/Y H:i') }}</p>
                                            
                                            @if($item->link_url)
                                                <a href="{{ $item->link_url }}" target="_blank" class="text-blue-600 text-xs hover:underline truncate block w-full mb-3">
                                                    {{ $item->link_url }}
                                                </a>
                                            @else
                                                <p class="text-xs text-gray-300 mb-3">- ไม่มีลิ้งค์ -</p>
                                            @endif
                                        </div>

                                        <div class="flex gap-2 pt-3 border-t border-gray-100 mt-auto">
                                            <a href="{{ route('admin.news.edit', $item->id) }}" class="flex-1 bg-yellow-100 text-yellow-700 hover:bg-yellow-200 text-xs font-bold py-2 rounded-lg text-center transition">
                                                ✏️ แก้ไข
                                            </a>

                                            <form action="{{ route('admin.news.destroy', $item->id) }}" method="POST" onsubmit="return confirm('ยืนยันการลบข่าวนี้?');" class="flex-1">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="w-full bg-red-100 text-red-600 hover:bg-red-200 text-xs font-bold py-2 rounded-lg transition">
                                                    🗑️ ลบ
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-white rounded-xl border-2 border-dashed border-gray-300 p-10 text-center">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <p class="text-gray-400">ยังไม่มีข่าวประชาสัมพันธ์</p>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>