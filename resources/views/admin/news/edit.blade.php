<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">แก้ไขข่าวสาร</h2>
    </x-slot>

    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-md overflow-hidden p-8 border border-gray-200">
                <form action="{{ route('admin.news.update', $news->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">หัวข้อข่าว</label>
                        <input type="text" name="title" value="{{ old('title', $news->title) }}" class="w-full border-gray-300 rounded-lg shadow-sm" required>
                    </div>

                    <div class="bg-yellow-50 p-4 rounded border border-yellow-200">
                        <label class="block text-sm font-bold text-gray-700 mb-1">🔗 ลิงก์ข่าวภายนอก</label>
                        <input type="url" name="link_url" value="{{ old('link_url', $news->link_url) }}" class="w-full border-gray-300 rounded-lg shadow-sm" placeholder="https://...">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">รูปภาพปัจจุบัน</label>
                            @if($news->image_path)
                                <img src="{{ asset('storage/' . $news->image_path) }}" class="w-full h-auto rounded border">
                            @else
                                <div class="bg-gray-100 h-40 flex items-center justify-center text-gray-400">ไม่มีรูปภาพ</div>
                            @endif
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">เปลี่ยนรูปภาพใหม่</label>
                            <input type="file" name="image_path" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:bg-yellow-50 file:text-yellow-700" accept="image/*">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">เนื้อหาข่าวละเอียด</label>
                        <textarea id="summernote" name="content">{{ old('content', $news->content) }}</textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('admin.news.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg">ยกเลิก</a>
                        <button type="submit" class="px-6 py-2.5 bg-yellow-600 text-white rounded-lg font-bold">บันทึกการเปลี่ยนแปลง</button>
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