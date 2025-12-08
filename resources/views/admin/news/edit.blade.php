<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.news.index') }}" class="p-2 bg-gray-200 rounded-lg text-gray-600 hover:bg-gray-300 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-bold text-2xl text-blue-900 leading-tight">
                แก้ไขข่าวสาร
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
                <div class="bg-blue-900 px-6 py-4 border-b border-blue-800">
                    <h3 class="font-bold text-white">📝 แบบฟอร์มแก้ไขข้อมูล</h3>
                </div>
                <div class="p-8">
                    <form action="{{ route('admin.news.update', $news->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">รูปภาพปัจจุบัน</label>
                            <div class="rounded-lg overflow-hidden border border-gray-300 w-full max-w-md shadow-sm">
                                <img src="{{ asset('storage/' . $news->image_path) }}" class="w-full h-auto object-cover">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">เปลี่ยนรูปภาพใหม่ (ถ้าต้องการ)</label>
                            <input type="file" name="image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition border border-gray-300 rounded-lg cursor-pointer" accept="image/*">
                            <p class="text-xs text-gray-400 mt-1">* หากไม่ต้องการเปลี่ยนรูป ให้เว้นว่างไว้</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">หัวข้อ / คำอธิบาย</label>
                            <input type="text" name="title" value="{{ old('title', $news->title) }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">ลิ้งค์ปลายทาง</label>
                            <input type="url" name="link_url" value="{{ old('link_url', $news->link_url) }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm" placeholder="https://...">
                        </div>

                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                            <a href="{{ route('admin.news.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition">ยกเลิก</a>
                            <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg font-bold shadow-md hover:bg-blue-700 transition transform hover:scale-105">
                                บันทึกการเปลี่ยนแปลง
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>