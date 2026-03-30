<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <div class="p-2 bg-yellow-100 rounded-lg text-yellow-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            </div>
            <h2 class="font-bold text-2xl text-blue-900 leading-tight">
                แก้ไขคำร้อง
            </h2>
        </div>
    </x-slot>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6 p-4 bg-yellow-50 text-yellow-800 rounded-lg border-l-4 border-yellow-400 shadow-sm flex items-center gap-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                    <strong>โหมดแก้ไขข้อมูล:</strong> คุณสามารถแก้ไขรายละเอียด พิกัดแผนที่ หรือเปลี่ยนรูปภาพใหม่ได้
                </div>
            </div>

            <form id="complaintForm" action="{{ route('complaints.update', $complaint->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6 border border-gray-200">
                    <div class="bg-blue-900 px-6 py-4 border-b border-blue-800 text-white font-bold text-lg">
                        1. ข้อมูลผู้ร้องเรียน
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-12 gap-6">
                        <div class="md:col-span-12">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">เรื่องที่ต้องการแจ้ง</label>
                            <input type="text" name="subject" value="{{ old('subject', $complaint->subject) }}" class="w-full border-gray-300 rounded-lg shadow-sm" required>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">คำนำหน้า</label>
                            <select name="title" class="w-full border-gray-300 rounded-lg shadow-sm" required>
                                <option value="นาย" {{ $complaint->title == 'นาย' ? 'selected' : '' }}>นาย</option>
                                <option value="นาง" {{ $complaint->title == 'นาง' ? 'selected' : '' }}>นาง</option>
                                <option value="นางสาว" {{ $complaint->title == 'นางสาว' ? 'selected' : '' }}>นางสาว</option>
                            </select>
                        </div>
                        <div class="md:col-span-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">ชื่อจริง</label>
                            <input type="text" name="first_name" value="{{ old('first_name', $complaint->first_name) }}" class="w-full border-gray-300 rounded-lg shadow-sm" required>
                        </div>
                        <div class="md:col-span-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">นามสกุล</label>
                            <input type="text" name="last_name" value="{{ old('last_name', $complaint->last_name) }}" class="w-full border-gray-300 rounded-lg shadow-sm" required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">อายุ</label>
                            <input type="number" name="age" value="{{ old('age', $complaint->age) }}" class="w-full border-gray-300 rounded-lg shadow-sm" required>
                        </div>
                        
                        <div class="md:col-span-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">เบอร์โทรศัพท์</label>
                            <input type="text" name="phone_number" value="{{ old('phone_number', $complaint->phone_number) }}" class="w-full border-gray-300 rounded-lg shadow-sm bg-yellow-50" required>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6 border border-gray-200">
                    <div class="bg-blue-900 px-6 py-4 border-b border-blue-800 text-white font-bold text-lg">
                        2. สถานที่เกิดเหตุ
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-12 gap-6">
                        <div class="md:col-span-3">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">บ้านเลขที่</label>
                            <input type="text" name="house_no" value="{{ old('house_no', $complaint->house_no) }}" class="w-full border-gray-300 rounded-lg shadow-sm" required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">หมู่ที่</label>
                            <input type="text" name="moo" value="{{ old('moo', $complaint->moo) }}" class="w-full border-gray-300 rounded-lg shadow-sm" required>
                        </div>
                        <div class="md:col-span-7">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">ถนน / ซอย</label>
                            <input type="text" name="road" value="{{ old('road', $complaint->road) }}" class="w-full border-gray-300 rounded-lg shadow-sm">
                        </div>
                        
                        <div class="md:col-span-12">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">ชุมชน</label>
                            <select name="community" class="w-full border-gray-300 rounded-lg shadow-sm" required>
                                @foreach(["หนองปลาเฒ่า", "เมืองพญาแล", "หนองหลอด", "ขี้เหล็กใหญ่", "ขี้เหล็กน้อย-มิตรภาพ", "ทานตะวัน", "โนนตาปาน", "หนองสังข์", "คลองเรียง", "ขี้เหล็กน้อย-ปรางค์กู่", "เมืองเก่า", "โนนไฮ", "หนองบัว", "ตลาด", "หินตั้ง-โพนงาม", "ราษฎร์เจริญสุข", "ใหม่พัฒนา", "โนนสาทร", "โนนสมอ", "อาทร ทวีสุข", "คลองลี่", "สนามบิน", "หนองบ่อ", "กุดแคน-ฝั่งถนน", "โคกน้อย", "เมืองน้อยเหนือ", "เมืองน้อยใต้"] as $comm)
                                    <option value="{{ $comm }}" {{ $complaint->community == $comm ? 'selected' : '' }}>{{ $comm }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8 border border-gray-200">
                    <div class="bg-blue-900 px-6 py-4 border-b border-blue-800 text-white font-bold text-lg">
                        3. รายละเอียดและพิกัด
                    </div>
                    <div class="p-6">
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">รายละเอียดปัญหา</label>
                            <textarea name="details" rows="5" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>{{ old('details', $complaint->details) }}</textarea>
                        </div>

                        <div class="mb-6 bg-gray-50 p-4 rounded-xl border border-gray-200">
                            <label class="block text-sm font-bold text-gray-700 mb-2 text-center">📍 แก้ไขพิกัด (ลากหมุดเพื่อเปลี่ยนตำแหน่ง)</label>
                            
                            <div class="grid grid-cols-2 gap-4 mb-3">
                                <input type="text" name="latitude" id="latitude" value="{{ old('latitude', $complaint->latitude) }}" class="w-full bg-white border-gray-300 rounded-md text-xs text-gray-600 h-8 text-center" readonly>
                                <input type="text" name="longitude" id="longitude" value="{{ old('longitude', $complaint->longitude) }}" class="w-full bg-white border-gray-300 rounded-md text-xs text-gray-600 h-8 text-center" readonly>
                            </div>

                            <div class="flex justify-center">
                                <div id="map" class="rounded-lg shadow-md border-2 border-white ring-1 ring-gray-200" style="width: 100%; max-width: 400px; height: 350px; z-index: 1;"></div>
                            </div>
                            <input type="hidden" name="map_capture" id="map_capture">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">เปลี่ยนรูปภาพประกอบ (ถ้าต้องการ)</label>
                            
                            @if($complaint->photo_image_path)
                                <div class="mb-2">
                                    <p class="text-xs text-gray-500 mb-1">รูปปัจจุบัน:</p>
                                    <img src="{{ asset('storage/' . $complaint->photo_image_path) }}" class="h-32 rounded-lg border shadow-sm">
                                </div>
                            @endif

                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:bg-gray-50 transition">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600 justify-center">
                                        <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>อัปโหลดรูปใหม่</span>
                                            <input id="file-upload" name="photo_image" type="file" class="sr-only" accept="image/*" onchange="document.getElementById('file-name').innerText = this.files[0].name">
                                        </label>
                                    </div>
                                    <p class="text-xs text-gray-500">ถ้าไม่อัปโหลดใหม่ จะใช้รูปเดิม</p>
                                    <p id="file-name" class="text-sm text-green-600 font-bold mt-2"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-center gap-4 pb-12">
                    <a href="{{ route('complaints.history') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-full font-bold hover:bg-gray-300">ยกเลิก</a>
                    <button type="button" onclick="captureAndSubmit()" class="bg-gradient-to-r from-yellow-400 to-yellow-500 text-blue-900 px-10 py-3 rounded-full font-bold shadow-lg hover:scale-105 transition transform">
                        บันทึกการแก้ไข
                    </button>
                </div>

            </form>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // ใช้พิกัดเดิมของคำร้อง
            var lat = {{ $complaint->latitude ?? 15.8065 }};
            var lng = {{ $complaint->longitude ?? 102.0315 }};
            
            var map = L.map('map').setView([lat, lng], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(map);

            var marker = L.marker([lat, lng], {draggable: true}).addTo(map);

            function updateInputs(lat, lng) {
                document.getElementById('latitude').value = lat.toFixed(7);
                document.getElementById('longitude').value = lng.toFixed(7);
            }

            marker.on('dragend', function(e) {
                var position = marker.getLatLng();
                updateInputs(position.lat, position.lng);
            });
        });

        function captureAndSubmit() {
            var submitBtn = document.querySelector('button[onclick="captureAndSubmit()"]');
            submitBtn.innerText = 'กำลังบันทึก...';
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-75', 'cursor-not-allowed');

            html2canvas(document.getElementById('map'), {
                useCORS: true,
                allowTaint: true
            }).then(function(canvas) {
                var imgData = canvas.toDataURL("image/png");
                document.getElementById('map_capture').value = imgData;
                document.getElementById('complaintForm').submit();
            }).catch(function(err) {
                console.error("Capture Error:", err);
                document.getElementById('complaintForm').submit();
            });
        }
    </script>
</x-app-layout>