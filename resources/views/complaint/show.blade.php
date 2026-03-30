<x-app-layout>
    {{-- ✅ CSS บังคับการแสดงผล (แก้ปัญหา Tailwind ไม่ Build) --}}
    <style>
        /* ค่าเริ่มต้น (Mobile): ซ่อน PC, แสดง Mobile */
        .status-pc { display: none !important; }
        .status-mobile { display: block !important; }

        /* หน้าจอ PC (กว้างกว่า 768px): แสดง PC, ซ่อน Mobile */
        @media (min-width: 768px) {
            .status-pc { display: flex !important; } /* กลับมาเป็น Flex เหมือนเดิม */
            .status-mobile { display: none !important; }
        }
    </style>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            {{-- ฝั่งซ้าย: ปุ่มย้อนกลับ + หัวข้อ --}}
            <div class="flex items-center gap-3">
                <a href="{{ auth()->user()->role === 'admin' ? route('admin.complaints.index') : route('complaints.history') }}" class="p-2 bg-gray-200 rounded-full hover:bg-gray-300 transition text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <h2 class="font-bold text-2xl text-blue-900 leading-tight">
                    รายละเอียดคำร้อง #{{ $complaint->id }}
                </h2>
            </div>
            
            {{-- ✅ ฝั่งขวา: สถานะสำหรับ PC (ใช้ class status-pc บังคับแสดงตำแหน่งเดิม) --}}
            <div class="status-pc items-center gap-2">
                @if($complaint->status == 'pending')
                    <span class="px-4 py-2 bg-yellow-100 text-yellow-800 rounded-full text-sm font-bold border border-yellow-200 shadow-sm">⏳ รอตรวจสอบ</span>
                @elseif($complaint->status == 'waiting')
                    <span class="px-4 py-2 bg-orange-100 text-orange-800 rounded-full text-sm font-bold border border-orange-200 shadow-sm">📋 รับเรื่องแล้ว</span>
                @elseif($complaint->status == 'in_progress')
                    <span class="px-4 py-2 bg-blue-100 text-blue-800 rounded-full text-sm font-bold border border-blue-200 shadow-sm animate-pulse">🔧 กำลังดำเนินการ</span>
                @elseif($complaint->status == 'completed')
                    <span class="px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-bold border border-green-200 shadow-sm">✅ เสร็จสิ้น</span>
                @elseif($complaint->status == 'unsuccessful')
                    <span class="px-4 py-2 bg-purple-100 text-purple-800 rounded-full text-sm font-bold border border-purple-200 shadow-sm">⚠️ ไม่สำเร็จ</span>
                @else
                    <span class="px-4 py-2 bg-red-100 text-red-800 rounded-full text-sm font-bold border border-red-200 shadow-sm">❌ ไม่อนุมัติ</span>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-6 md:py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- ✅ สถานะสำหรับ Mobile (แสดงเต็มความกว้างด้านล่าง Header) --}}
            <div class="status-mobile mb-6 px-2">
                @if($complaint->status == 'pending')
                    <div class="w-full bg-yellow-50 text-yellow-800 py-3 rounded-xl font-bold border border-yellow-200 shadow-sm text-center">
                        ⏳ สถานะ: รอตรวจสอบ
                    </div>
                @elseif($complaint->status == 'waiting')
                    <div class="w-full bg-orange-50 text-orange-800 py-3 rounded-xl font-bold border border-orange-200 shadow-sm text-center">
                        📋 สถานะ: รับเรื่องแล้ว
                    </div>
                @elseif($complaint->status == 'in_progress')
                    <div class="w-full bg-blue-50 text-blue-800 py-3 rounded-xl font-bold border border-blue-200 shadow-sm text-center animate-pulse">
                        🔧 สถานะ: กำลังดำเนินการแก้ไข
                    </div>
                @elseif($complaint->status == 'completed')
                    <div class="w-full bg-green-50 text-green-800 py-3 rounded-xl font-bold border border-green-200 shadow-sm text-center">
                        ✅ สถานะ: ดำเนินการเสร็จสิ้น
                    </div>
                @elseif($complaint->status == 'unsuccessful')
                    <div class="w-full bg-purple-50 text-purple-800 py-3 rounded-xl font-bold border border-purple-200 shadow-sm text-center">
                        ⚠️ สถานะ: ดำเนินการไม่สำเร็จ
                    </div>
                @else
                    <div class="w-full bg-red-50 text-red-800 py-3 rounded-xl font-bold border border-red-200 shadow-sm text-center">
                        ❌ สถานะ: ไม่อนุมัติคำร้อง
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- 🟦 คอลัมน์ซ้าย: ข้อมูลหลัก --}}
                <div class="lg:col-span-2 space-y-8">
                    
                    <div class="bg-white rounded-2xl shadow-md overflow-hidden border border-gray-200">
                        <div class="bg-blue-900 px-6 py-4 border-b border-blue-800 flex justify-between items-center">
                            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                                <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                ข้อมูลการร้องเรียน
                            </h3>
                            
                            {{-- แสดงเขตพื้นที่ --}}
                            @if($complaint->zone)
                                <span class="bg-blue-800 text-white text-xs px-3 py-1 rounded-full border border-blue-600 shadow-sm">
                                    📍 เขต {{ $complaint->zone }}
                                </span>
                            @endif
                        </div>
                        <div class="p-6">
                            <h1 class="text-2xl font-extrabold text-gray-800 mb-2">{{ $complaint->subject }}</h1>
                            <p class="text-sm text-gray-500 mb-6">วันที่แจ้ง: {{ $complaint->created_at->format('d/m/Y เวลา H:i น.') }}</p>
                            
                            <div class="bg-gray-50 p-6 rounded-xl border border-gray-100 text-gray-700 leading-relaxed whitespace-pre-line">
                                {{ $complaint->details }}
                            </div>

                            {{-- รูปภาพและแผนที่ --}}
                            @if(($complaint->images && $complaint->images->count() > 0) || $complaint->photo_image_path || $complaint->map_image_path)
                                <div class="mt-8">
                                    <h4 class="font-bold text-gray-700 mb-4 border-b pb-2">หลักฐานและแผนที่</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                        
                                        @if($complaint->images && $complaint->images->count() > 0)
                                            @foreach($complaint->images as $img)
                                                <div class="relative group cursor-pointer rounded-lg overflow-hidden border shadow-sm h-48">
                                                    <img src="{{ asset('storage/' . $img->image_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-300" onclick="window.open(this.src)">
                                                    <div class="absolute bottom-0 left-0 right-0 bg-black/50 text-white text-xs p-1 text-center opacity-0 group-hover:opacity-100 transition">คลิกเพื่อดูภาพใหญ่</div>
                                                </div>
                                            @endforeach
                                        @endif

                                        @if($complaint->photo_image_path)
                                            <div class="relative group cursor-pointer rounded-lg overflow-hidden border shadow-sm h-48">
                                                <img src="{{ asset('storage/' . $complaint->photo_image_path) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" onclick="window.open(this.src)">
                                                <div class="absolute bottom-0 left-0 right-0 bg-gray-800/70 text-white text-xs p-1 text-center">รูปภาพ (ระบบเก่า)</div>
                                            </div>
                                        @endif

                                        @if($complaint->map_image_path)
                                            <div class="relative group cursor-pointer rounded-lg overflow-hidden border shadow-sm h-48 border-blue-200">
                                                @if(Str::startsWith($complaint->map_image_path, 'data:image'))
                                                    <img src="{{ $complaint->map_image_path }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" onclick="window.open(this.src)">
                                                @else
                                                    <img src="{{ asset('storage/' . $complaint->map_image_path) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" onclick="window.open(this.src)">
                                                @endif
                                                <div class="absolute top-0 right-0 bg-blue-600 text-white text-xs px-2 py-1 rounded-bl-lg font-bold">📍 แผนที่</div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- ส่วนจัดการของ Admin --}}
                    @if(auth()->user()->role === 'admin')
                    <div class="bg-white rounded-2xl shadow-md overflow-hidden border border-yellow-200">
                        <div class="bg-yellow-50 px-6 py-4 border-b border-yellow-100">
                            <h3 class="text-lg font-bold text-yellow-800 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                ส่วนจัดการของเจ้าหน้าที่
                            </h3>
                        </div>
                        <div class="p-6">
                            <form action="{{ route('admin.complaints.process', $complaint->id) }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">บันทึกการดำเนินงาน / เหตุผล</label>
                                    <textarea name="admin_notes" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-yellow-500 focus:border-yellow-500">{{ $complaint->admin_notes }}</textarea>
                                </div>
                                <div class="flex flex-wrap gap-3">
                                    <button type="submit" name="status" value="waiting" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-bold shadow-sm transition">✅ อนุมัติ / รับเรื่อง</button>
                                    <button type="submit" name="status" value="in_progress" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-bold shadow-sm transition">🚀 กำลังดำเนินการ</button>
                                    <button type="submit" name="status" value="completed" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg font-bold shadow-sm transition">🏁 เสร็จสิ้น</button>
                                    <button type="submit" name="status" value="unsuccessful" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-bold shadow-sm transition">⚠️ ไม่สำเร็จ</button>
                                    <button type="submit" name="status" value="rejected" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-bold shadow-sm transition ml-auto">❌ ไม่อนุมัติ</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif

                </div>

                {{-- 🟩 คอลัมน์ขวา: ข้อมูลผู้แจ้ง --}}
                <div class="lg:col-span-1 space-y-8">
                    
                    <div class="bg-white rounded-2xl shadow-md overflow-hidden border border-gray-200">
                        <div class="p-6">
                            <h4 class="font-bold text-gray-900 mb-4 border-b pb-2">👤 ข้อมูลผู้ร้องเรียน</h4>
                            <div class="space-y-3 text-sm">
                                <p><span class="text-gray-500 block">ชื่อ-นามสกุล:</span> <span class="font-medium">{{ $complaint->title }}{{ $complaint->first_name }} {{ $complaint->last_name }}</span></p>
                                
                                {{-- แสดงเลขบัตร --}}
                                @if($complaint->citizen_id)
                                    <p>
                                        <span class="text-gray-500 block">เลขบัตรประชาชน:</span> 
                                        <span class="font-medium text-pink-600 bg-pink-50 px-2 py-0.5 rounded border border-pink-100">
                                            {{ $complaint->citizen_id }}
                                        </span>
                                    </p>
                                @endif

                                <p><span class="text-gray-500 block">อายุ:</span> <span class="font-medium">{{ $complaint->age }} ปี</span></p>
                                <p><span class="text-gray-500 block">เบอร์โทรศัพท์:</span> <span class="font-medium text-blue-600">{{ $complaint->phone_number }}</span></p>
                                
                                <p><span class="text-gray-500 block">ที่อยู่:</span> 
                                    <span class="font-medium">
                                        @if(!empty($complaint->house_number)) บ้านเลขที่ {{ $complaint->house_number }} @endif
                                        @if(!empty($complaint->village_no)) หมู่ {{ $complaint->village_no }} @endif
                                        <br>
                                        @if(!empty($complaint->soi)) ซอย {{ $complaint->soi }} @endif
                                        @if(!empty($complaint->road)) ถนน {{ $complaint->road }} @endif
                                        <br>
                                        @if(!empty($complaint->community)) ชุมชน {{ $complaint->community }} @else - @endif
                                        <br>
                                        ต.{{ $complaint->sub_district == 'ในเขตเทศบาล' ? 'ในเมือง' : $complaint->sub_district }} 
                                        อ.{{ $complaint->district }} <br>
                                        จ.{{ $complaint->province }} {{ $complaint->zip_code }}
                                    </span>
                                </p>

                                @if($complaint->latitude)
                                    <div class="pt-2">
                                        <a href="https://www.google.com/maps/search/?api=1&query={{ $complaint->latitude }},{{ $complaint->longitude }}" target="_blank" class="text-xs bg-blue-50 text-blue-600 px-3 py-2 rounded-lg border border-blue-200 flex items-center justify-center hover:bg-blue-100 transition">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                            เปิดพิกัดใน Google Maps
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-md overflow-hidden border border-gray-200">
                        <div class="p-6">
                            <h4 class="font-bold text-gray-900 mb-4 border-b pb-2">📢 บันทึกจากเจ้าหน้าที่</h4>
                            @if($complaint->admin_notes)
                                <div class="bg-gray-50 p-4 rounded-lg text-sm text-gray-700 border-l-4 border-blue-500">
                                    {{ $complaint->admin_notes }}
                                </div>
                                <p class="text-right text-xs text-gray-400 mt-2">อัปเดตเมื่อ: {{ $complaint->updated_at->diffForHumans() }}</p>
                            @else
                                <p class="text-center text-gray-400 text-sm italic py-4">- ยังไม่มีการตอบกลับ -</p>
                            @endif
                        </div>
                    </div>

                    @if(auth()->user()->role === 'admin')
                        <div class="bg-white rounded-2xl shadow-md overflow-hidden border border-gray-200">
                            <div class="p-6">
                                <h4 class="font-bold text-gray-900 mb-4 border-b pb-2">📄 เอกสารราชการ</h4>
                                <a href="{{ route('admin.complaints.download', $complaint->id) }}" class="flex items-center justify-center gap-2 w-full bg-blue-600 text-white px-4 py-3 rounded-xl shadow hover:bg-blue-700 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 011.414.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    ดาวน์โหลดไฟล์ Word
                                </a>
                            </div>
                        </div>
                    @endif

                </div>

            </div>
        </div>
    </div>
</x-app-layout>