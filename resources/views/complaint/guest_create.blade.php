<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>แจ้งเรื่องร้องเรียน (บุคคลทั่วไป)</title>

    <link rel="icon" type="image/png" href="{{ asset('storage/system/logo.png') }}?v={{ time() }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('storage/system/logo.png') }}?v={{ time() }}">

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
                <span class="text-blue-200 text-sm font-medium bg-blue-800 px-3 py-1 rounded-full">ระบบรับเรื่องร้องเรียนออนไลน์ (บุคคลทั่วไป)</span>
            </div>
        </div>
    </nav>

    <div class="py-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center mb-8">
                <h1 class="text-3xl font-extrabold text-blue-900">แบบฟอร์มแจ้งเรื่องร้องเรียน</h1>
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

            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r shadow-sm">
                    <div class="flex items-center gap-2 font-bold mb-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        สำเร็จ
                    </div>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <form id="complaintForm" action="{{ route('guest.complaint.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- 1. ข้อมูลผู้แจ้ง --}}
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

                {{-- 2. ที่อยู่และสถานที่เกิดเหตุ --}}
                <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6 border border-gray-200">
                    <div class="bg-blue-900 px-6 py-4 border-b border-blue-800">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-yellow-400 text-blue-900 text-xs">2</span>
                            ที่อยู่และสถานที่เกิดเหตุ
                        </h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-12 gap-6">
                        
                        <div class="md:col-span-3">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">บ้านเลขที่</label>
                            <input type="text" name="house_no" value="{{ old('house_no') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400" placeholder="ถ้ามี">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">หมู่ที่</label>
                            <input type="text" name="moo" value="{{ old('moo') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400" placeholder="-">
                        </div>
                        <div class="md:col-span-7">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">ถนน / ซอย</label>
                            <input type="text" name="road" value="{{ old('road') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400" placeholder="-">
                        </div>

                        <div class="col-span-12 my-2 border-t border-gray-100"></div>

                        <div class="md:col-span-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">
                                ชุมชนของผู้แจ้ง <span class="text-red-500">*</span> 
                                <span class="text-xs font-normal text-gray-500">(สำหรับลงเอกสารราชการ)</span>
                            </label>
                            <select name="community" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 cursor-pointer" required>
                                <option value="" disabled {{ old('community') ? '' : 'selected' }}>-- เลือกชุมชนที่ท่านอาศัยอยู่ --</option>
                                @foreach(["หนองปลาเฒ่า", "เมืองพญาแล", "หนองหลอด", "ขี้เหล็กใหญ่", "ขี้เหล็กน้อย-มิตรภาพ", "ทานตะวัน", "โนนตาปาน", "หนองสังข์", "คลองเรียง", "ขี้เหล็กน้อย-ปรางค์กู่", "เมืองเก่า", "โนนไฮ", "หนองบัว", "ตลาด", "หินตั้ง-โพนงาม", "ราษฎร์เจริญสุข", "ใหม่พัฒนา", "โนนสาทร", "โนนสมอ", "อาทร ทวีสุข", "คลองลี่", "สนามบิน", "หนองบ่อ", "กุดแคน-ฝั่งถนน", "โคกน้อย", "เมืองน้อยเหนือ", "เมืองน้อยใต้"] as $comm)
                                    <option value="{{ $comm }}" {{ old('community') == $comm ? 'selected' : '' }}>{{ $comm }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">
                                📍 ชุมชน/พื้นที่ ที่เกิดเหตุ <span class="text-red-500">*</span>
                                <span class="text-xs font-normal text-gray-500">(เพื่อระบุพิกัดเจ้าหน้าที่)</span>
                            </label>
                            <select name="incident_community" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 cursor-pointer bg-blue-50" required>
                                <option value="" disabled {{ old('incident_community') ? '' : 'selected' }}>-- เลือกพื้นที่ที่เกิดปัญหา --</option>
                                @foreach(["หนองปลาเฒ่า", "เมืองพญาแล", "หนองหลอด", "ขี้เหล็กใหญ่", "ขี้เหล็กน้อย-มิตรภาพ", "ทานตะวัน", "โนนตาปาน", "หนองสังข์", "คลองเรียง", "ขี้เหล็กน้อย-ปรางค์กู่", "เมืองเก่า", "โนนไฮ", "หนองบัว", "ตลาด", "หินตั้ง-โพนงาม", "ราษฎร์เจริญสุข", "ใหม่พัฒนา", "โนนสาทร", "โนนสมอ", "อาทร ทวีสุข", "คลองลี่", "สนามบิน", "หนองบ่อ", "กุดแคน-ฝั่งถนน", "โคกน้อย", "เมืองน้อยเหนือ", "เมืองน้อยใต้"] as $comm)
                                    <option value="{{ $comm }}" {{ old('incident_community') == $comm ? 'selected' : '' }}>{{ $comm }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- 3. รายละเอียดและพิกัด --}}
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
                            <div class="flex flex-col md:flex-row justify-between items-center mb-2">
                                <label class="block text-sm font-bold text-gray-700">📍 พิกัดจุดเกิดเหตุ (ระบบค้นหาอัตโนมัติ)</label>
                                
                                <button type="button" onclick="resetLocation()" class="mt-2 md:mt-0 bg-white border border-gray-300 text-gray-600 px-3 py-1 rounded-lg text-xs font-bold shadow-sm hover:bg-gray-100 hover:text-red-500 transition flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    รีเซ็ต / ระบุตำแหน่งปัจจุบัน
                                </button>
                            </div>
                            
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
                                <div id="map" class="rounded-lg shadow-md border-2 border-white ring-1 ring-gray-200" style="width: 100%; max-width: 350px; height: 300px; z-index: 1;"></div>
                            </div>
                            
                            <p class="text-xs text-center text-gray-500 mt-2 flex justify-center items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                สามารถใช้นิ้วเลื่อนหมุดสีแดง เพื่อปรับตำแหน่งให้แม่นยำยิ่งขึ้น
                            </p>
                            <input type="hidden" name="map_capture" id="map_capture">
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">รูปภาพประกอบ (สูงสุด 4 รูป)</label>
                            
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:bg-gray-50 transition relative group cursor-pointer bg-white">
                                <div class="space-y-1 text-center pointer-events-none">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-blue-500 transition" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600 justify-center">
                                        <span class="relative rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                            คลิกเพื่อเลือกไฟล์
                                        </span>
                                        <p class="pl-1">หรือลากไฟล์มาวางที่นี่</p>
                                    </div>
                                    <p class="text-xs text-red-500 font-bold mt-1">* เลือกได้ไม่เกิน 4 รูป</p>
                                </div>
                                
                                <input type="file" name="images[]" multiple accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="checkAndPreview(this)">
                            </div>
                            
                            <div id="image-preview-container" class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-4"></div>
                        </div>

                    </div>
                </div>

                <div class="flex justify-center pb-12">
                    <button type="submit" id="btnSubmit" class="w-full md:w-auto bg-gradient-to-r from-blue-600 to-blue-800 text-white text-lg font-bold px-10 py-4 rounded-full shadow-lg hover:shadow-xl hover:scale-105 transform transition duration-200 flex items-center justify-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        ส่งเรื่องร้องเรียน
                    </button>
                </div>

            </form>
        </div>
    </div>

    <div id="pdpaModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 text-center sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" onclick="closePdpaModal()" aria-hidden="true"></div>

            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-10">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-100">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">
                                นโยบายคุ้มครองข้อมูลส่วนบุคคล (PDPA)
                            </h3>
                            <div class="mt-3 text-sm text-gray-600">
                                <p>เทศบาลเมืองชัยภูมิ มีความจำเป็นต้องเก็บรวบรวมข้อมูลส่วนบุคคลของท่าน ได้แก่ เลขบัตรประชาชน, ชื่อ-นามสกุล, เบอร์โทรศัพท์ และพิกัดสถานที่เกิดเหตุ เพื่อประโยชน์ในการดำเนินงานดังนี้:</p>
                                <ul class="list-disc pl-5 mt-2 space-y-1">
                                    <li>เพื่อใช้ในการติดต่อประสานงาน และแจ้งความคืบหน้าการแก้ไขปัญหา</li>
                                    <li>เพื่อตรวจสอบการแจ้งข้อมูลเท็จ และป้องกันผู้ไม่ประสงค์ดี</li>
                                </ul>
                                <p class="mt-4 p-3 bg-red-50 text-red-600 text-xs font-semibold rounded-lg border border-red-100">
                                    * ข้อมูลของท่านจะถูกจัดเก็บด้วยความปลอดภัยขั้นสูงสุด และไม่มีการเผยแพร่ต่อสาธารณะ
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3">
                    <button type="button" onclick="closePdpaModal()" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-100 sm:mt-0 sm:w-auto sm:text-sm">
                        ยกเลิก (ยังไม่ส่ง)
                    </button>
                    <button type="button" onclick="acceptAndSubmit()" class="w-full inline-flex justify-center items-center gap-1 rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 sm:w-auto sm:text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        ข้าพเจ้ายินยอม และส่งคำร้อง
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // =====================================
        // ดักจับการกด Submit ฟอร์ม เพื่อเปิด PDPA Modal
        // =====================================
        document.getElementById('complaintForm').addEventListener('submit', function(event) {
            // ถ้ายังไม่ได้อนุญาตให้ส่ง (allowSubmit เป็น false) ให้เบรกไว้ก่อน
            if (!window.allowSubmit) {
                event.preventDefault(); // สั่งหยุดส่ง
                document.getElementById('pdpaModal').style.display = 'block'; // โชว์ Modal ขึ้นมา
            }
        });

        function closePdpaModal() {
            document.getElementById('pdpaModal').style.display = 'none';
        }

        // เมื่อกด "ยินยอม" ใน Modal
        function acceptAndSubmit() {
            closePdpaModal(); // ปิด Modal ก่อน
            
            // เปลี่ยนสถานะปุ่มหลัก
            var submitBtn = document.getElementById('btnSubmit');
            submitBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> กำลังส่งข้อมูล...';
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-75', 'cursor-not-allowed');

            // เริ่มกระบวนการแคปแผนที่
            html2canvas(document.getElementById('map'), { useCORS: true, allowTaint: true }).then(function(canvas) {
                document.getElementById('map_capture').value = canvas.toDataURL("image/png");
                
                window.allowSubmit = true; // อนุญาตให้ฟอร์มส่งข้อมูลได้แล้ว
                document.getElementById('complaintForm').submit(); // สั่งยิงข้อมูลไปหา Controller
            }).catch(function(err) {
                window.allowSubmit = true; 
                document.getElementById('complaintForm').submit(); // ถ้าแคปไม่ติด ก็ยังให้ข้อมูลถูกส่งไป
            });
        }

        // =====================================
        // โค้ดระบบแผนที่ GPS และรูปภาพ (ของเดิม)
        // =====================================
        var map, marker;
        var defaultLat = 15.8065; 
        var defaultLng = 102.0315;

        function updateInputs(lat, lng) {
            document.getElementById('latitude').value = lat.toFixed(7);
            document.getElementById('longitude').value = lng.toFixed(7);
        }

        function resetLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        var lat = position.coords.latitude;
                        var lng = position.coords.longitude;
                        if (map && marker) {
                            map.setView([lat, lng], 18);
                            marker.setLatLng([lat, lng]);
                        }
                        updateInputs(lat, lng);
                    },
                    function(error) {
                        navigator.geolocation.getCurrentPosition(
                            function(posLow) {
                                var lat = posLow.coords.latitude;
                                var lng = posLow.coords.longitude;
                                if (map && marker) {
                                    map.setView([lat, lng], 16);
                                    marker.setLatLng([lat, lng]);
                                }
                                updateInputs(lat, lng);
                            },
                            function(errLow) {
                                alert("ไม่สามารถระบุตำแหน่ง GPS ได้ ระบบจะใช้ตำแหน่งเริ่มต้น");
                                if (map && marker) {
                                    map.setView([defaultLat, defaultLng], 15);
                                    marker.setLatLng([defaultLat, defaultLng]);
                                }
                                updateInputs(defaultLat, defaultLng);
                            },
                            { enableHighAccuracy: false, timeout: 4000, maximumAge: 300000 }
                        );
                    },
                    { enableHighAccuracy: true, timeout: 5000, maximumAge: 300000 }
                );
            } else {
                alert("เบราว์เซอร์ของคุณไม่รองรับการระบุตำแหน่ง");
            }
        }

        function checkAndPreview(input) {
            var container = document.getElementById('image-preview-container');
            container.innerHTML = ''; 

            if (input.files.length > 4) {
                alert("คุณสามารถอัปโหลดรูปภาพได้สูงสุด 4 รูปเท่านั้นครับ\nกรุณาเลือกใหม่");
                input.value = ""; 
                return; 
            }

            if (input.files) {
                Array.from(input.files).forEach(file => {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var div = document.createElement('div');
                        div.className = 'relative group aspect-square rounded-lg overflow-hidden border border-gray-200 shadow-sm';
                        div.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                        container.appendChild(div);
                    }
                    reader.readAsDataURL(file);
                });
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            window.allowSubmit = false; // ตัวแปรนี้เป็นตัวเบรกการส่งฟอร์มในตอนแรก

            var oldLat = "{{ old('latitude') }}";
            var oldLng = "{{ old('longitude') }}";
            
            var startLat = oldLat ? parseFloat(oldLat) : defaultLat;
            var startLng = oldLng ? parseFloat(oldLng) : defaultLng;
            
            map = L.map('map').setView([startLat, startLng], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' }).addTo(map);
            marker = L.marker([startLat, startLng], {draggable: true}).addTo(map);

            if(oldLat && oldLng) { updateInputs(startLat, startLng); }

            marker.on('dragend', function(e) {
                var position = marker.getLatLng();
                updateInputs(position.lat, position.lng);
            });

            if(!oldLat) {
                resetLocation();
            }
        });
    </script>
</body>
</html>