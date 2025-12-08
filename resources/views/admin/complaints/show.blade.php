<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-white rounded-lg shadow-sm border border-gray-200 text-blue-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <div>
                    <h2 class="font-bold text-xl text-gray-800 leading-tight">รายละเอียดคำร้องเรียน</h2>
                    <p class="text-sm text-gray-500">รหัสเรื่อง: #{{ $complaint->id }}</p>
                </div>
            </div>
            <a href="{{ route('admin.complaints.index') }}" class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-bold hover:bg-gray-50 shadow-sm flex items-center gap-2 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                ย้อนกลับ
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-100 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-r shadow-sm font-bold flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-2 space-y-6">
                    
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                            <h3 class="font-bold text-lg text-gray-800">ข้อมูลการร้องเรียน</h3>
                            <div class="text-sm text-gray-500 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $complaint->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                        <div class="p-6 space-y-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">หัวข้อเรื่อง</label>
                                <div class="text-xl font-bold text-blue-900">{{ $complaint->subject }}</div>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">รายละเอียด</label>
                                <div class="bg-gray-50 p-4 rounded-lg text-gray-700 border border-gray-100 leading-relaxed whitespace-pre-wrap">{{ $complaint->details }}</div>
                            </div>

                            @if($complaint->image)
                                <div>
                                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2">รูปภาพประกอบ</label>
                                    <div class="rounded-lg overflow-hidden border border-gray-200 inline-block">
                                        <a href="{{ asset('storage/'.$complaint->image) }}" target="_blank">
                                            <img src="{{ asset('storage/'.$complaint->image) }}" class="max-h-64 w-auto object-cover hover:opacity-90 transition">
                                        </a>
                                    </div>
                                </div>
                            @endif
                            
                            @if($complaint->map_image_path)
                                <div>
                                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2">แผนที่จุดเกิดเหตุ</label>
                                    <div class="rounded-lg overflow-hidden border border-gray-200 inline-block">
                                        <img src="{{ asset('storage/'.$complaint->map_image_path) }}" class="max-h-64 w-auto object-cover">
                                    </div>
                                    @if($complaint->latitude && $complaint->longitude)
                                        <div class="mt-2">
                                            <a href="https://www.google.com/maps/search/?api=1&query={{ $complaint->latitude }},{{ $complaint->longitude }}" target="_blank" class="text-blue-600 hover:underline text-sm flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                                เปิดใน Google Maps
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-3 border-b border-gray-100 bg-blue-50">
                            <h3 class="font-bold text-base text-blue-800">ข้อมูลผู้ร้องเรียน</h3>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">ชื่อ-นามสกุล</label>
                                <div class="flex items-center gap-2 text-slate-700 font-medium text-lg">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    {{ $complaint->first_name }} {{ $complaint->last_name }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">เลขบัตรประชาชน</label>
                                <div class="text-slate-700 font-mono tracking-wide bg-slate-50 px-2 py-1 rounded inline-block border border-slate-200">
                                    {{ $complaint->citizen_id ?? '-' }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">เบอร์โทรศัพท์</label>
                                <div class="flex items-center gap-2 text-blue-600 font-bold text-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    {{ $complaint->phone_number }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">ที่อยู่ / ชุมชน</label>
                                <div class="text-gray-700">
                                    🏠 {{ $complaint->house_number }} ม.{{ $complaint->village_no }} 
                                    <br> ชุมชน: <span class="font-bold text-slate-800">{{ $complaint->community ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="lg:col-span-1 space-y-6">
                    
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">สถานะปัจจุบัน</label>
                        @if($complaint->status == 'pending')
                            <span class="inline-block px-4 py-2 bg-yellow-100 text-yellow-700 rounded-full font-bold text-sm border border-yellow-200">⏳ รอตรวจสอบ</span>
                        @elseif($complaint->status == 'waiting')
                            <span class="inline-block px-4 py-2 bg-orange-100 text-orange-700 rounded-full font-bold text-sm border border-orange-200">📋 รับเรื่องแล้ว</span>
                        @elseif($complaint->status == 'in_progress')
                            <span class="inline-block px-4 py-2 bg-blue-100 text-blue-700 rounded-full font-bold text-sm border border-blue-200">🔧 กำลังดำเนินการ</span>
                        @elseif($complaint->status == 'completed')
                            <span class="inline-block px-4 py-2 bg-green-100 text-green-700 rounded-full font-bold text-sm border border-green-200">✅ เสร็จสิ้น</span>
                        @else
                            <span class="inline-block px-4 py-2 bg-red-100 text-red-700 rounded-full font-bold text-sm border border-red-200">❌ ไม่ผ่าน/ยกเลิก</span>
                        @endif
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                         <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">เอกสารราชการ</label>
                         <a href="{{ route('admin.complaints.download', $complaint->id) }}" class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-lg transition shadow-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            ดาวน์โหลดไฟล์ Word
                         </a>
                    </div>

                    <div class="bg-white rounded-xl shadow-lg border border-blue-100 overflow-hidden">
                        <div class="px-5 py-3 bg-blue-600 text-white font-bold flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            ส่วนจัดการของเจ้าหน้าที่
                        </div>
                        
                        <form action="{{ route('admin.complaints.process', $complaint->id) }}" method="POST" class="p-5 space-y-4">
                            @csrf
                            
                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-1">กำหนดเขตพื้นที่</label>
                                <select name="zone" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                                    <option value="" {{ !$complaint->zone ? 'selected' : '' }}>-- กรุณาเลือกเขต --</option>
                                    <option value="1" {{ $complaint->zone == '1' ? 'selected' : '' }}>เขต 1</option>
                                    <option value="2" {{ $complaint->zone == '2' ? 'selected' : '' }}>เขต 2</option>
                                    <option value="3" {{ $complaint->zone == '3' ? 'selected' : '' }}>เขต 3</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-1">บันทึกผลการดำเนินงาน / ตอบกลับ</label>
                                <textarea name="admin_notes" rows="4" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm" placeholder="ระบุรายละเอียดการดำเนินการ...">{{ $complaint->admin_notes }}</textarea>
                            </div>

                            <hr class="border-gray-100">

                            <div class="space-y-3">
                                <button type="submit" name="status" value="{{ $complaint->status }}" class="w-full bg-gray-100 text-gray-700 py-2.5 rounded-lg font-bold shadow-sm border border-gray-300 hover:bg-white hover:border-blue-300 transition flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                    บันทึกข้อมูล (สถานะเดิม)
                                </button>

                                <div class="grid grid-cols-2 gap-3">
                                    @if($complaint->status == 'pending')
                                        <button type="submit" name="status" value="waiting" class="bg-green-500 text-white py-2 rounded-lg font-bold shadow hover:bg-green-600 transition">
                                            ✅ รับเรื่อง
                                        </button>
                                        <button type="submit" name="status" value="rejected" class="bg-red-100 text-red-600 border border-red-200 py-2 rounded-lg font-bold shadow-sm hover:bg-red-200 transition">
                                            ❌ ปฏิเสธ
                                        </button>
                                    @elseif($complaint->status == 'waiting')
                                        <button type="submit" name="status" value="in_progress" class="col-span-2 bg-blue-600 text-white py-2 rounded-lg font-bold shadow hover:bg-blue-700 transition">
                                            🚀 เริ่มดำเนินการ
                                        </button>
                                    @elseif($complaint->status == 'in_progress')
                                        <button type="submit" name="status" value="completed" class="col-span-2 bg-green-600 text-white py-2 rounded-lg font-bold shadow hover:bg-green-700 transition">
                                            🎉 เสร็จสิ้นภารกิจ
                                        </button>
                                        <button type="submit" name="status" value="unsuccessful" class="col-span-2 bg-purple-100 text-purple-700 border border-purple-200 py-2 rounded-lg font-bold shadow-sm hover:bg-purple-200 transition mt-2">
                                            ⚠️ ไม่สำเร็จ
                                        </button>
                                    @else
                                        <div class="col-span-2 text-center text-sm text-gray-400">สถานะสิ้นสุดแล้ว</div>
                                    @endif
                                </div>
                            </div>

                        </form>
                    </div>
                    
                    <form action="{{ route('admin.complaints.destroy', $complaint->id) }}" method="POST" onsubmit="return confirm('ยืนยันการลบรายการนี้?');" class="text-center">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-xs text-red-400 hover:text-red-600 hover:underline transition">
                            ลบรายการนี้ถาวร
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>