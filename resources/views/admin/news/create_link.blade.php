<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-emerald-100 rounded-lg text-emerald-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
            </div>
            <h2 class="font-bold text-xl text-gray-800 leading-tight">
                ลงประกาศข่าว <span class="text-emerald-600">(แบบแนบลิงก์)</span>
            </h2>
        </div>
    </x-slot>

    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="news_type" value="link">

                <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                    
                    <div class="bg-gradient-to-r from-emerald-500 to-teal-600 px-8 py-6 text-white">
                        <h3 class="text-lg font-bold flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            กรอกข้อมูลข่าวสาร
                        </h3>
                        <p class="text-emerald-100 text-sm mt-1">ใช้สำหรับข่าวที่ต้องการให้คลิกแล้วลิงก์ไปยัง Facebook หรือเว็บไซต์อื่น</p>
                    </div>

                    <div class="p-8 space-y-8">
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">หัวข้อข่าว <span class="text-red-500">*</span></label>
                                <input type="text" name="title" class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-emerald-500 focus:border-emerald-500 transition py-3 px-4" required placeholder="เช่น ประชาสัมพันธ์กิจกรรม..." value="{{ old('title') }}">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">🔗 ลิงก์ปลายทาง (URL) <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-400">https://</span>
                                    </div>
                                    <input type="url" name="link_url" class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-emerald-500 focus:border-emerald-500 pl-16 py-3 px-4" required placeholder="www.facebook.com/..." value="{{ old('link_url') }}">
                                </div>
                                <p class="text-xs text-gray-400 mt-2">เมื่อผู้ใช้คลิกที่ข่าว ระบบจะเปิดลิงก์นี้ในแท็บใหม่ทันที</p>
                            </div>
                        </div>

                        <hr class="border-dashed border-gray-200">

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">รายละเอียดเพิ่มเติม (ถ้ามี)</label>
                            <div class="prose max-w-none">
                                <textarea id="summernote" name="content">{{ old('content') }}</textarea>
                            </div>
                        </div>

                        <hr class="border-dashed border-gray-200">

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">รูปภาพประกอบ <span class="text-red-500">*</span></label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:bg-emerald-50 transition cursor-pointer relative bg-gray-50 group">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-emerald-500 transition" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600 justify-center">
                                        <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-emerald-600 hover:text-emerald-500 focus-within:outline-none px-2">
                                            <span>อัปโหลดรูปภาพ</span>
                                            <input id="file-upload" name="image_path" type="file" class="sr-only" accept="image/*" required onchange="previewImage(this)">
                                        </label>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                                </div>
                                <img id="img-preview" class="absolute inset-0 w-full h-full object-contain bg-white hidden rounded-xl p-2">
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-8 py-5 flex items-center justify-end gap-3 border-t border-gray-100">
                        <a href="{{ route('admin.news.index') }}" class="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 font-bold rounded-xl hover:bg-gray-50 transition shadow-sm">
                            ยกเลิก
                        </a>
                        <button type="submit" class="px-8 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                            บันทึกข่าว (Link)
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script>
      // ตั้งค่า Summernote
      $('#summernote').summernote({
        placeholder: 'ใส่รายละเอียดเพิ่มเติมตรงนี้ (ไม่บังคับ)...',
        tabsize: 2,
        height: 150,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['para', ['ul', 'ol']],
          ['view', ['codeview']]
        ]
      });

      // ฟังก์ชันแสดงตัวอย่างรูปภาพ
      function previewImage(input) {
          if (input.files && input.files[0]) {
              var reader = new FileReader();
              reader.onload = function (e) {
                  $('#img-preview').attr('src', e.target.result).removeClass('hidden');
              }
              reader.readAsDataURL(input.files[0]);
          }
      }
    </script>
</x-app-layout>