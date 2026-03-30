<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-indigo-100 rounded-lg text-indigo-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
            </div>
            <h2 class="font-bold text-xl text-gray-800 leading-tight">
                เขียนข่าวประชาสัมพันธ์ <span class="text-indigo-600">(Internal News)</span>
            </h2>
        </div>
    </x-slot>

    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="news_type" value="content">

                <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                    
                    <div class="bg-gradient-to-r from-indigo-600 to-blue-600 px-8 py-6 text-white">
                        <h3 class="text-lg font-bold flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            เขียนบทความใหม่
                        </h3>
                        <p class="text-indigo-100 text-sm mt-1">สร้างเนื้อหาข่าว กิจกรรม หรือประชาสัมพันธ์ภายในเว็บไซต์</p>
                    </div>

                    <div class="p-8 space-y-8">
                        
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            <div class="lg:col-span-2 space-y-6">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">หัวข้อข่าว <span class="text-red-500">*</span></label>
                                    <input type="text" name="title" class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition py-3 px-4 text-lg font-medium" required placeholder="ใส่หัวข้อข่าวที่น่าสนใจ..." value="{{ old('title') }}">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">เนื้อหาข่าวละเอียด <span class="text-red-500">*</span></label>
                                    <textarea id="summernote" name="content">{{ old('content') }}</textarea>
                                </div>
                            </div>

                            <div class="lg:col-span-1">
                                <label class="block text-sm font-bold text-gray-700 mb-2">รูปภาพปก (Cover)</label>
                                <div class="border-2 border-dashed border-gray-300 rounded-2xl bg-gray-50 hover:bg-indigo-50 transition p-4 text-center cursor-pointer relative h-64 flex flex-col items-center justify-center group overflow-hidden">
                                    
                                    <div class="space-y-2 relative z-10">
                                        <div class="w-16 h-16 bg-white rounded-full shadow-sm flex items-center justify-center mx-auto text-indigo-500 group-hover:scale-110 transition">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <p class="text-sm font-bold text-gray-600">คลิกเพื่ออัปโหลด</p>
                                        <p class="text-xs text-gray-400">รองรับ JPG, PNG</p>
                                    </div>
                                    
                                    <input type="file" name="image_path" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" accept="image/*" onchange="previewCover(this)">
                                    
                                    <img id="cover-preview" class="absolute inset-0 w-full h-full object-cover hidden z-0">
                                </div>
                                <p class="text-xs text-gray-400 mt-2 text-center">* รูปนี้จะแสดงหน้าแรกและด้านบนสุดของเนื้อหา</p>
                            </div>
                        </div>

                    </div>

                    <div class="bg-gray-50 px-8 py-5 flex items-center justify-end gap-3 border-t border-gray-100">
                        <a href="{{ route('admin.news.index') }}" class="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 font-bold rounded-xl hover:bg-gray-50 transition shadow-sm">
                            ยกเลิก
                        </a>
                        <button type="submit" class="px-8 py-2.5 bg-gradient-to-r from-indigo-600 to-blue-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                            เผยแพร่บทความ
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script>
      // ตั้งค่า Summernote (Editor ใหญ่)
      $('#summernote').summernote({
        placeholder: 'เขียนเนื้อหาข่าว ใส่รูปภาพ จัดรูปแบบข้อความได้ที่นี่...',
        tabsize: 2,
        height: 500,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link', 'picture', 'video']],
          ['view', ['fullscreen', 'codeview', 'help']]
        ]
      });

      // ฟังก์ชันแสดงตัวอย่างรูปปก
      function previewCover(input) {
          if (input.files && input.files[0]) {
              var reader = new FileReader();
              reader.onload = function (e) {
                  $('#cover-preview').attr('src', e.target.result).removeClass('hidden');
              }
              reader.readAsDataURL(input.files[0]);
          }
      }
    </script>
</x-app-layout>