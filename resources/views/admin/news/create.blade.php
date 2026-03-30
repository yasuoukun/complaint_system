<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">เขียนข่าวประชาสัมพันธ์</h2>
    </x-slot>

    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">หัวข้อข่าว <span class="text-red-500">*</span></label>
                        <input type="text" name="title" class="w-full border-gray-300 rounded-md shadow-sm" required placeholder="ใส่หัวข้อข่าว..." value="{{ old('title') }}">
                    </div>

                    <div class="mb-4 bg-yellow-50 p-4 rounded border border-yellow-200">
                        <label class="block text-gray-700 text-sm font-bold mb-2">🔗 ลิงก์ข่าวภายนอก (Facebook / เว็บไซต์อื่น)</label>
                        <input type="url" name="link_url" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="https://www.facebook.com/..." value="{{ old('link_url') }}">
                        <p class="text-xs text-gray-500 mt-1">* หากใส่ลิงก์นี้ ระบบจะพาผู้ใช้ไปยังเว็บอื่นทันทีที่คลิก (ไม่ต้องเขียนเนื้อหาด้านล่างก็ได้)</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">รูปภาพปก</label>
                        <input type="file" name="image_path" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-blue-50 file:text-blue-700" accept="image/*">
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">เนื้อหาข่าวละเอียด (สำหรับข่าวภายในเว็บ)</label>
                        <textarea id="summernote" name="content">{{ old('content') }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">* ถ้ามีลิงก์ภายนอกแล้ว สามารถเว้นว่างช่องนี้ได้</p>
                    </div>

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('admin.news.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">ยกเลิก</a>
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded font-bold">บันทึกข่าว</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script>
      $('#summernote').summernote({
        placeholder: 'เขียนเนื้อหาข่าว...',
        tabsize: 2,
        height: 300,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['insert', ['picture', 'link']],
          ['view', ['fullscreen', 'codeview']]
        ]
      });
    </script>
</x-app-layout>