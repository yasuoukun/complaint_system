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
                
                {{-- คอลัมน์ซ้าย: รายละเอียด และ ข้อมูลผู้แจ้ง --}}
                <div class="lg:col-span-2 space-y-6">
                    
                    {{-- 1. กล่องรายละเอียดคำร้อง --}}
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

                            {{-- พื้นที่เกิดเหตุ --}}
                            <div class="bg-orange-50 border-l-4 border-orange-400 p-4 rounded-r-lg">
                                <label class="block text-xs font-bold text-orange-800 uppercase mb-1 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    พื้นที่เกิดเหตุ / พิกัดชุมชน
                                </label>
                                <div class="text-lg font-bold text-orange-900">
                                    {{ $complaint->incident_community ?? 'ไม่ได้ระบุพื้นที่' }}
                                </div>
                                <div class="text-xs text-orange-600 mt-1">* ใช้สำหรับระบุโซนและลงพื้นที่ตรวจสอบ</div>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">รายละเอียด</label>
                                <div class="bg-gray-50 p-4 rounded-lg text-gray-700 border border-gray-100 leading-relaxed whitespace-pre-wrap">{{ $complaint->details }}</div>
                            </div>

                            {{-- ส่วนแสดงรูปภาพ --}}
                            @if(
                                ($complaint->images && $complaint->images->count() > 0) || 
                                $complaint->photo_image_path || 
                                $complaint->map_image_path
                            )
                                <div class="mt-6 border-t pt-6">
                                    <label class="block text-xs font-bold text-gray-400 uppercase mb-3">
                                        หลักฐานและรูปภาพประกอบ
                                    </label>
                                    
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                        {{-- 1. วนลูปรูปภาพ --}}
                                        @if($complaint->images && $complaint->images->count() > 0)
                                            @foreach($complaint->images as $img)
                                                <div class="relative group aspect-video bg-gray-100 rounded-lg overflow-hidden border border-gray-200 cursor-pointer">
                                                    <a href="{{ asset('storage/' . $img->image_path) }}" target="_blank">
                                                        <img src="{{ asset('storage/' . $img->image_path) }}" class="w-full h-full object-cover hover:scale-110 transition-transform duration-500">
                                                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 flex items-center justify-center transition-all opacity-0 group-hover:opacity-100">
                                                            <span class="text-white text-xs bg-black/50 px-2 py-1 rounded">คลิกเพื่อขยาย</span>
                                                        </div>
                                                    </a>
                                                </div>
                                            @endforeach
                                        @endif

                                        {{-- 2. รูปภาพระบบเก่า --}}
                                        @if($complaint->photo_image_path)
                                            <div class="relative group aspect-video bg-gray-100 rounded-lg overflow-hidden border border-gray-200">
                                                <a href="{{ asset('storage/' . $complaint->photo_image_path) }}" target="_blank">
                                                    <img src="{{ asset('storage/' . $complaint->photo_image_path) }}" class="w-full h-full object-cover hover:scale-110 transition-transform duration-500">
                                                    <div class="absolute bottom-0 left-0 right-0 bg-gray-800/70 text-white text-xs p-1 text-center">รูปภาพเดิม</div>
                                                </a>
                                            </div>
                                        @endif

                                        {{-- 3. รูปแผนที่ --}}
                                        @if($complaint->map_image_path)
                                            <div class="relative group aspect-video bg-gray-100 rounded-lg overflow-hidden border border-blue-200">
                                                @if(Str::startsWith($complaint->map_image_path, 'data:image'))
                                                    <a href="{{ $complaint->map_image_path }}" target="_blank">
                                                        <img src="{{ $complaint->map_image_path }}" class="w-full h-full object-cover hover:scale-110 transition">
                                                    </a>
                                                @else
                                                    <a href="{{ asset('storage/' . $complaint->map_image_path) }}" target="_blank">
                                                        <img src="{{ asset('storage/' . $complaint->map_image_path) }}" class="w-full h-full object-cover hover:scale-110 transition">
                                                    </a>
                                                @endif
                                                <div class="absolute top-0 right-0 bg-blue-600 text-white text-xs px-2 py-1 rounded-bl-lg font-bold shadow">📍 แผนที่</div>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    {{-- ปุ่ม Google Maps Link --}}
                                    @if($complaint->latitude && $complaint->longitude)
                                        <div class="mt-4">
                                            <a href="https://www.google.com/maps/search/?api=1&query={{ $complaint->latitude }},{{ $complaint->longitude }}" target="_blank" class="text-blue-600 hover:underline text-sm flex items-center gap-1 font-bold">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                                เปิดพิกัดใน Google Maps
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- 2. กล่องข้อมูลผู้ร้องเรียน --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-3 border-b border-gray-100 bg-blue-50">
                            <h3 class="font-bold text-base text-blue-800">ข้อมูลผู้ร้องเรียน</h3>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">ชื่อ-นามสกุล</label>
                                <div class="flex items-center gap-2 text-slate-700 font-medium text-lg">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    {{ $complaint->title }}{{ $complaint->first_name }} {{ $complaint->last_name }}

                                    {{-- ✅ [จุดที่แก้] เพิ่มลิงก์ดูประวัติ หรือ ป้ายบุคคลทั่วไป --}}
                                    @if($complaint->user_id)
                                        <a href="{{ route('admin.users.show', $complaint->user_id) }}" target="_blank" class="ml-2 text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full hover:bg-blue-200 transition flex items-center gap-1 border border-blue-200" title="ดูประวัติการร้องเรียน">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                            ดูประวัติ
                                        </a>
                                    @else
                                        <span class="ml-2 text-xs bg-gray-100 text-gray-500 px-2 py-1 rounded-full border border-gray-200">
                                            บุคคลทั่วไป
                                        </span>
                                    @endif
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
                            
                            <div class="col-span-1 md:col-span-2">
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">ที่อยู่ผู้แจ้ง (สำหรับออกเอกสาร)</label>
                                <div class="text-gray-800 bg-gray-50 p-4 rounded-lg border border-gray-100">
                                    <p class="mb-2 text-lg">
                                        <span class="mr-2">🏠</span> 
                                        <b>บ้านเลขที่:</b> {{ $complaint->house_no ?? $complaint->house_number ?? '-' }} 
                                        <span class="mx-2">|</span>
                                        <b>หมู่ที่:</b> {{ $complaint->moo ?? $complaint->village_no ?? '-' }}
                                    </p>
                                    
                                    <p class="mb-2 text-sm ml-8">
                                        @if(!empty($complaint->soi)) <b>ซอย:</b> {{ $complaint->soi }} @endif
                                        @if(!empty($complaint->road)) <span class="ml-2"><b>ถนน:</b> {{ $complaint->road }}</span>@endif
                                    </p>

                                    <p class="mb-2 text-sm ml-8">
                                        <b>ต.</b>{{ $complaint->sub_district == 'ในเขตเทศบาล' ? 'ในเมือง' : $complaint->sub_district }}
                                        <b>อ.</b>{{ $complaint->district }} 
                                        <b>จ.</b>{{ $complaint->province }} 
                                        {{ $complaint->zip_code }}
                                    </p>

                                    <div class="mt-3 pt-3 border-t border-gray-200 ml-8">
                                        <span class="font-bold text-gray-700 bg-gray-200 px-3 py-1 rounded-full text-xs">
                                            ชุมชนผู้แจ้ง: {{ $complaint->community ?? '-' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- คอลัมน์ขวา: ส่วนจัดการสถานะ --}}
                <div class="lg:col-span-1 space-y-6">
                    
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">สถานะปัจจุบัน</label>
                        @if($complaint->status == 'pending')
                            <div class="inline-flex flex-col items-center justify-center p-4 bg-yellow-50 rounded-xl border border-yellow-100 w-full">
                                <span class="text-3xl mb-2">⏳</span>
                                <span class="text-yellow-700 font-bold text-lg">รอตรวจสอบ</span>
                            </div>
                        @elseif($complaint->status == 'waiting')
                            <div class="inline-flex flex-col items-center justify-center p-4 bg-orange-50 rounded-xl border border-orange-100 w-full">
                                <span class="text-3xl mb-2">📋</span>
                                <span class="text-orange-700 font-bold text-lg">รับเรื่องแล้ว</span>
                            </div>
                        @elseif($complaint->status == 'in_progress')
                            <div class="inline-flex flex-col items-center justify-center p-4 bg-blue-50 rounded-xl border border-blue-100 w-full">
                                <span class="text-3xl mb-2">🔧</span>
                                <span class="text-blue-700 font-bold text-lg">กำลังดำเนินการ</span>
                            </div>
                        @elseif($complaint->status == 'completed')
                            <div class="inline-flex flex-col items-center justify-center p-4 bg-green-50 rounded-xl border border-green-100 w-full">
                                <span class="text-3xl mb-2">✅</span>
                                <span class="text-green-700 font-bold text-lg">เสร็จสิ้น</span>
                            </div>
                        @else
                            <div class="inline-flex flex-col items-center justify-center p-4 bg-red-50 rounded-xl border border-red-100 w-full">
                                <span class="text-3xl mb-2">❌</span>
                                <span class="text-red-700 font-bold text-lg">ไม่ผ่าน/ยกเลิก</span>
                            </div>
                        @endif
                    </div>

                    <div class="bg-white rounded-xl shadow-lg border border-blue-100 overflow-hidden">
                        <div class="px-5 py-3 bg-blue-600 text-white font-bold flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            ส่วนจัดการของเจ้าหน้าที่
                        </div>
                        
                        <form action="{{ route('admin.complaints.process', $complaint->id) }}" method="POST" class="p-5 space-y-4">
                            @csrf
                            
                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-1">กำหนดเขตพื้นที่รับผิดชอบ</label>
                                <select name="zone" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm py-2">
                                    <option value="" {{ !$complaint->zone ? 'selected' : '' }}>-- กรุณาเลือกเขต --</option>
                                    <option value="1" {{ $complaint->zone == '1' ? 'selected' : '' }}>เขต 1</option>
                                    <option value="2" {{ $complaint->zone == '2' ? 'selected' : '' }}>เขต 2</option>
                                    <option value="3" {{ $complaint->zone == '3' ? 'selected' : '' }}>เขต 3</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-1">บันทึกผลการดำเนินงาน</label>
                                <textarea name="admin_notes" rows="4" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm" placeholder="ระบุรายละเอียด หรือเหตุผล...">{{ $complaint->admin_notes }}</textarea>
                            </div>

                            <hr class="border-gray-100">

                            <div class="space-y-3">
                                {{-- ปุ่มบันทึกแบบเดิม (สีขาว) ตามที่คุณต้องการ --}}
                                <button type="submit" name="status" value="{{ $complaint->status }}" class="w-full bg-white text-gray-700 py-2 rounded-lg font-bold shadow-sm border border-gray-300 hover:bg-gray-50 transition text-sm">
                                    บันทึกข้อมูล (คงสถานะเดิม)
                                </button>

                                <div class="grid grid-cols-2 gap-3">
                                    @if($complaint->status == 'pending')
                                        <button type="submit" name="status" value="waiting" class="bg-green-500 text-white py-2 rounded-lg font-bold shadow hover:bg-green-600 transition text-sm">
                                            ✅ รับเรื่อง
                                        </button>
                                        <button type="submit" name="status" value="rejected" class="bg-red-100 text-red-600 border border-red-200 py-2 rounded-lg font-bold shadow-sm hover:bg-red-200 transition text-sm">
                                            ❌ ปฏิเสธ
                                        </button>
                                    @elseif($complaint->status == 'waiting')
                                        <button type="submit" name="status" value="in_progress" class="col-span-2 bg-blue-600 text-white py-2 rounded-lg font-bold shadow hover:bg-blue-700 transition text-sm">
                                            🚀 เริ่มดำเนินการ
                                        </button>
                                    @elseif($complaint->status == 'in_progress')
                                        <button type="submit" name="status" value="completed" class="col-span-2 bg-green-600 text-white py-2 rounded-lg font-bold shadow hover:bg-green-700 transition text-sm">
                                            🎉 เสร็จสิ้นภารกิจ
                                        </button>
                                        <button type="submit" name="status" value="unsuccessful" class="col-span-2 bg-purple-50 text-purple-700 border border-purple-200 py-2 rounded-lg font-bold shadow-sm hover:bg-purple-100 transition mt-2 text-sm">
                                            ⚠️ ดำเนินการไม่สำเร็จ
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                         <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">เอกสารราชการ</label>
                         <a href="{{ route('admin.complaints.download', $complaint->id) }}" class="w-full flex items-center justify-center gap-2 bg-slate-700 hover:bg-slate-800 text-white font-bold py-2.5 rounded-lg transition shadow-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            ดาวน์โหลดไฟล์ Word
                         </a>
                    </div>
                    
                    <div class="text-center pt-2">
                        <form action="{{ route('admin.complaints.destroy', $complaint->id) }}" method="POST" onsubmit="return confirm('คำเตือน: การลบนี้จะกู้คืนไม่ได้ ยืนยันการลบ?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-red-400 hover:text-red-600 hover:underline transition">
                                ลบรายการนี้ออกจากระบบ
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>