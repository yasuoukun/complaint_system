<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>แจ้งเรื่องร้องทุกข์ (บุคคลทั่วไป)</title>
    <link rel="preconnect" href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    
    <style> body { font-family: 'Sarabun', sans-serif; } </style>
</head>
<body class="bg-gray-100 font-sans antialiased">

    <nav class="bg-blue-900 shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="{{ url('/') }}" class="flex items-center gap-2 text-white font-bold text-lg hover:text-yellow-400 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    กลับหน้าหลัก
                </a>
                <span class="text-blue-200 text-sm font-medium bg-blue-800 px-3 py-1 rounded-full">ระบบรับเรื่องร้องทุกข์ออนไลน์ (บุคคลทั่วไป)</span>
            </div>
        </div>
    </nav>

    <div class="py-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center mb-8">
                <h1 class="text-3xl font-extrabold text-blue-900">แบบฟอร์มแจ้งเรื่องร้องทุกข์</h1>
                <p class="text-gray-500 mt-2 text-sm">สำหรับประชาชนทั่วไปที่ไม่ได้เป็นสมาชิก (จำกัดสิทธิ์ส่ง 3 ครั้ง/วัน)</p>
            </div>

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r shadow-sm animate-pulse">
                    <div class="flex items-center gap-2 font-bold mb-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        โปรดตรวจสอบข้อมูล:
                    </div>
                    <ul class="list-disc list-inside text-sm ml-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="complaintForm" action="{{ route('guest.complaint.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6 border border-gray-200">
                    <div class="bg-blue-900 px-6 py-4 border-b border-blue-800 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-yellow-400 text-blue-900 text-xs">1</span>
                            ข้อมูลผู้แจ้ง (สำคัญ)
                        </h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-12 gap-6">
                        
                        <div class="md:col-span-12">
                            <label class="block text-sm font-bold text-gray-700 mb-1">เลขบัตรประจำตัวประชาชน (13 หลัก) <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0c0 .884-.95 2-2.5 2H15"></path></svg>
                                </div>
                                <input type="text" name="citizen_id" value="{{ old('citizen_id') }}" class="pl-10 w-full border-gray-300 rounded-lg shadow-sm focus:ring-yellow-500 focus:border-yellow-500 bg-yellow-50 text-lg tracking-widest font-mono text-blue-900" required maxlength="13" placeholder="xxxxxxxxxxxxx" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            </div>
                            <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                <svg class="w-3 h-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                ข้อมูลถูกเก็บเป็นความลับ ใช้เพื่อยืนยันตัวตนเท่านั้น
                            </p>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">คำนำหน้า</label>
                            <select name="title" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="" disabled selected>เลือก</option>
                                <option value="นาย" {{ old('title') == 'นาย' ? 'selected' : '' }}>นาย</option>
                                <option value="นาง" {{ old('title') == 'นาง' ? 'selected' : '' }}>นาง</option>
                                <option value="นางสาว" {{ old('title') == 'นางสาว' ? 'selected' : '' }}>นางสาว</option>
                            </select>
                        </div>
                        <div class="md:col-span-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">ชื่อจริง</label>
                            <input type="text" name="first_name" value="{{ old('first_name') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        <div class="md:col-span-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">นามสกุล</label>
                            <input type="text" name="last_name" value="{{ old('last_name') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">อายุ</label>
                            <input type="number" name="age" value="{{ old('age') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        
                        <div class="md:col-span-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">เบอร์โทรศัพท์ติดต่อ <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                </div>
                                <input type="text" name="phone_number" value="{{ old('phone_number') }}" class="pl-10 w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required pattern="0[0-9]{9}" maxlength="10" minlength="10" inputmode="numeric" placeholder="08xxxxxxxx" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6 border border-gray-200">
                    <div class="bg-blue-900 px-6 py-4 border-b border-blue-800">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-yellow-400 text-blue-900 text-xs">2</span>
                            สถานที่เกิดเหตุ
                        </h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-12 gap-6">
                        <div class="md:col-span-3">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">บ้านเลขที่</label>
                            <input type="text" name="house_no" value="{{ old('house_no') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">หมู่ที่</label>
                            <input type="text" name="moo" value="{{ old('moo') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        <div class="md:col-span-7">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">ถนน / ซอย</label>
                            <input type="text" name="road" value="{{ old('road') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="md:col-span-12">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">ชุมชน <span class="text-red-500">*</span></label>
                            <select name="community" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="" disabled selected>-- กรุณาเลือกชุมชน --</option>
                                @foreach(["หนองปลาเฒ่า", "เมืองพญาแล", "หนองหลอด", "ขี้เหล็กใหญ่", "ขี้เหล็กน้อย-มิตรภาพ", "ทานตะวัน", "โนนตาปาน", "หนองสังข์", "คลองเรียง", "ขี้เหล็กน้อย-ปรางค์กู่", "เมืองเก่า", "โนนไฮ", "หนองบัว", "ตลาด", "หินตั้ง-โพนงาม", "ราษฎร์เจริญสุข", "ใหม่พัฒนา", "โนนสาทร", "โนนสมอ", "อาทร ทวีสุข", "คลองลี่", "สนามบิน", "หนองบ่อ", "กุดแคน-ฝั่งถนน", "โคกน้อย", "เมืองน้อยเหนือ", "เมืองน้อยใต้"] as $comm)
                                    <option value="{{ $comm }}" {{ old('community') == $comm ? 'selected' : '' }}>{{ $comm }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8 border border-gray-200">
                    <div class="bg-blue-900 px-6 py-4 border-b border-blue-800">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-yellow-400 text-blue-900 text-xs">3</span>
                            รายละเอียดและพิกัด
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">หัวข้อเรื่อง <span class="text-red-500">*</span></label>
                            <input type="text" name="subject" value="{{ old('subject') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="เช่น ไฟดับ, ถนนชำรุด" required>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">รายละเอียดปัญหา <span class="text-red-500">*</span></label>
                            <textarea name="details" rows="5" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required placeholder="กรุณาระบุรายละเอียดให้ชัดเจน...">{{ old('details') }}</textarea>
                        </div>

                        <div class="mb-6 bg-gray-50 p-4 rounded-xl border border-gray-200">
                            <label class="block text-sm font-bold text-gray-700 mb-2 text-center">📍 พิกัดจุดเกิดเหตุ (ระบบค้นหาอัตโนมัติ)</label>
                            
                            <div class="grid grid-cols-2 gap-4 mb-3">
                                <div class="relative">
                                    <span class="absolute left-3 top-2 text-xs text-gray-400">Lat</span>
                                    <input type="text" name="latitude" id="latitude" value="{{ old('latitude') }}" class="w-full pl-10 bg-white border-gray-300 rounded-md text-sm text-gray-600 h-8" readonly>
                                </div>
                                <div class="relative">
                                    <span class="absolute left-3 top-2 text-xs text-gray-400">Lng</span>
                                    <input type="text" name="longitude" id="longitude" value="{{ old('longitude') }}" class="w-full pl-10 bg-white border-gray-300 rounded-md text-sm text-gray-600 h-8" readonly>
                                </div>
                            </div>

                            <div class="flex justify-center">
                                <div id="map" class="rounded-lg shadow-md border-2 border-white ring-1 ring-gray-200" style="width: 100%; max-width: 400px; height: 350px; z-index: 1;"></div>
                            </div>
                            
                            <p class="text-xs text-center text-gray-500 mt-2 flex justify-center items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                สามารถใช้นิ้วเลื่อนหมุดสีแดง เพื่อปรับตำแหน่งให้แม่นยำยิ่งขึ้น
                            </p>
                            <input type="hidden" name="map_capture" id="map_capture">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">รูปภาพประกอบ (ถ้ามี)</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:bg-gray-50 transition">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600 justify-center">
                                        <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>อัปโหลดไฟล์รูปภาพ</span>
                                            <input id="file-upload" name="photo_image" type="file" class="sr-only" accept="image/*" onchange="document.getElementById('file-name').innerText = this.files[0].name">
                                        </label>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF ไม่เกิน 2MB</p>
                                    <p id="file-name" class="text-sm text-green-600 font-bold mt-2"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-center pb-12">
                    <button type="button" onclick="captureAndSubmit()" class="w-full md:w-auto bg-gradient-to-r from-blue-600 to-blue-800 text-white text-lg font-bold px-10 py-4 rounded-full shadow-lg hover:shadow-xl hover:scale-105 transform transition duration-200 flex items-center justify-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        ส่งเรื่องร้องทุกข์
                    </button>
                </div>

            </form>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var oldLat = "{{ old('latitude') }}";
            var oldLng = "{{ old('longitude') }}";
            var defaultLat = oldLat ? parseFloat(oldLat) : 15.8065;
            var defaultLng = oldLng ? parseFloat(oldLng) : 102.0315;
            
            var map = L.map('map').setView([defaultLat, defaultLng], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' }).addTo(map);
            var marker = L.marker([defaultLat, defaultLng], {draggable: true}).addTo(map);

            function updateInputs(lat, lng) {
                document.getElementById('latitude').value = lat.toFixed(7);
                document.getElementById('longitude').value = lng.toFixed(7);
            }

            if(oldLat && oldLng) { updateInputs(defaultLat, defaultLng); }

            marker.on('dragend', function(e) {
                var position = marker.getLatLng();
                updateInputs(position.lat, position.lng);
            });

            if(!oldLat) {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        var lat = position.coords.latitude;
                        var lng = position.coords.longitude;
                        map.setView([lat, lng], 18);
                        marker.setLatLng([lat, lng]);
                        updateInputs(lat, lng);
                    }, function() { updateInputs(defaultLat, defaultLng); });
                } else { updateInputs(defaultLat, defaultLng); }
            }
        });

        function captureAndSubmit() {
            var btn = document.querySelector('button[onclick="captureAndSubmit()"]');
            btn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> กำลังส่งข้อมูล...';
            btn.disabled = true;
            btn.classList.add('opacity-75', 'cursor-not-allowed');
            
            html2canvas(document.getElementById('map'), { useCORS: true, allowTaint: true }).then(function(canvas) {
                document.getElementById('map_capture').value = canvas.toDataURL("image/png");
                document.getElementById('complaintForm').submit();
            }).catch(function() { document.getElementById('complaintForm').submit(); });
        }
    </script>
</body>
</html>