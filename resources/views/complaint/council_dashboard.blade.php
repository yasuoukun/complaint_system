<x-app-layout>
    {{-- ✅ CSS บังคับการแสดงผล (ทำงานทันที ไม่ต้อง Run Build) --}}
    <style>
        .mobile-show { display: block !important; }
        .pc-show { display: none !important; }
        .pc-flex-show { display: none !important; }

        @media (min-width: 1024px) { 
            .mobile-show { display: none !important; }
            .pc-show { display: block !important; }
            .pc-flex-show { display: flex !important; }
        }
    </style>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-yellow-500 rounded-lg shadow-sm text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <div>
                    <h2 class="font-bold text-xl text-blue-900 leading-tight">
                        ติดตามงาน สท.
                    </h2>
                    <span class="text-xs font-bold text-white bg-blue-600 px-2 py-0.5 rounded-full">
                        เขต: {{ Auth::user()->zone ?? '-' }}
                    </span>
                </div>
            </div>
            
            {{-- ปุ่ม PC --}}
            <a href="{{ route('complaints.create') }}" class="pc-flex-show items-center gap-2 bg-blue-900 text-white px-5 py-2.5 rounded-full font-bold text-sm hover:bg-blue-800 shadow-md transition transform hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                เขียนคำร้องแทนชาวบ้าน
            </a>
        </div>
        
        {{-- ปุ่ม Mobile --}}
        <div class="mobile-show mt-4">
            <a href="{{ route('complaints.create') }}" class="flex w-full justify-center items-center gap-2 bg-blue-900 text-white px-5 py-3 rounded-xl font-bold text-sm shadow-md active:scale-95 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                เขียนคำร้องแทนชาวบ้าน
            </a>
        </div>
    </x-slot>

    <div class="py-6 md:py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 md:gap-8">
                
                {{-- ========================================== --}}
                {{-- 🟦 กล่องที่ 1: เรื่องที่ท่านยื่นเอง (ปรับให้ครบถ้วน) --}}
                {{-- ========================================== --}}
                <div class="bg-white rounded-xl shadow-md overflow-hidden border-t-4 border-blue-600 flex flex-col h-full">
                    <div class="bg-blue-50 px-4 md:px-6 py-4 border-b border-blue-100 flex justify-between items-center">
                        <h3 class="font-bold text-blue-900 flex items-center gap-2 text-base md:text-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            เรื่องที่ท่านยื่นเอง
                        </h3>
                        <span class="bg-blue-200 text-blue-800 text-xs font-bold px-2 py-1 rounded-full">{{ $mySubmissions->count() }} เรื่อง</span>
                    </div>
                    
                    {{-- 📱 Mobile View (My Submissions - Full Detail) --}}
                    <div class="mobile-show bg-gray-50 p-2 space-y-3">
                        @forelse($mySubmissions as $item)
                            @php
                                $expireTime = $item->created_at->addMinutes(10);
                                $canEdit = $item->status == 'pending' && $expireTime->isFuture();
                            @endphp

                            <div class="bg-white rounded-lg shadow-sm border border-gray-200 relative overflow-hidden">
                                <div class="absolute left-0 top-0 bottom-0 w-1.5 
                                    @if($item->status == 'pending') bg-yellow-400
                                    @elseif($item->status == 'waiting') bg-orange-400
                                    @elseif($item->status == 'in_progress') bg-blue-500
                                    @elseif($item->status == 'completed') bg-green-500
                                    @else bg-red-500 @endif">
                                </div>
                                <div class="p-3 pl-5">
                                    {{-- Header --}}
                                    <div class="mb-2 border-b border-gray-100 pb-2">
                                        <div class="flex justify-between items-start">
                                            <h4 class="font-bold text-blue-900 text-sm line-clamp-2">{{ $item->subject }}</h4>
                                            <span class="text-[10px] text-gray-400 whitespace-nowrap ml-2">
                                                {{ $item->created_at->format('d/m/y H:i') }}
                                            </span>
                                        </div>
                                        @if($item->created_at != $item->updated_at)
                                            <div class="text-[10px] text-orange-500 text-right mt-0.5">
                                                แก้ไข: {{ $item->updated_at->format('d/m H:i') }}
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Details --}}
                                    <div class="text-xs text-gray-600 space-y-1 mb-3">
                                        {{-- ชื่อผู้แจ้ง --}}
                                        <div class="flex items-center gap-1 font-bold text-gray-700">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                            {{ $item->first_name }} {{ $item->last_name }}
                                        </div>
                                        @if($item->citizen_id)
                                            <div class="bg-blue-50 text-blue-800 border border-blue-100 px-2 py-1 rounded inline-block text-[10px]">
                                                💳 บัตร: {{ $item->citizen_id }}
                                            </div>
                                        @endif
                                        <div>📞 {{ $item->phone_number }}</div>
                                        <div>📍 ชุมชน: {{ $item->community }}</div>

                                        <div class="flex items-center gap-2 mt-2 pt-2 border-t border-gray-100">
                                            <span class="font-bold">สถานะ:</span>
                                            @if($item->status == 'pending') <span class="text-yellow-600 font-bold text-[10px]">⏳ รอตรวจสอบ</span>
                                            @elseif($item->status == 'waiting') <span class="text-orange-600 font-bold text-[10px]">📋 รับเรื่องแล้ว</span>
                                            @elseif($item->status == 'in_progress') <span class="text-blue-600 font-bold text-[10px]">🔧 กำลังดำเนินการ</span>
                                            @elseif($item->status == 'completed') <span class="text-green-600 font-bold text-[10px]">✅ เสร็จสิ้น</span>
                                            @else <span class="text-red-600 font-bold text-[10px]">❌ ไม่อนุมัติ</span>
                                            @endif
                                        </div>
                                        
                                        @if($item->admin_notes)
                                            <div class="bg-gray-50 p-2 rounded border border-gray-100 mt-2 text-[10px]">
                                                <span class="font-bold text-gray-700">จนท:</span> {{ $item->admin_notes }}
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Actions --}}
                                    <div class="grid {{ $canEdit ? 'grid-cols-2' : 'grid-cols-1' }} gap-2 pt-2 border-t border-gray-100">
                                        <a href="{{ route('complaints.show', $item->id) }}" class="text-center bg-blue-50 text-blue-600 px-3 py-1.5 rounded-lg border border-blue-100 text-xs hover:bg-blue-100">
                                            🔎 ดูรายละเอียด
                                        </a>
                                        @if($canEdit)
                                            <a href="{{ route('complaints.edit', $item->id) }}" class="flex flex-col justify-center items-center bg-yellow-50 text-yellow-700 px-3 py-1.5 rounded-lg border border-yellow-200 text-xs hover:bg-yellow-100">
                                                <span>✏️ แก้ไข</span>
                                                <span class="live-timer text-[9px] text-red-600 font-extrabold leading-none" data-expire="{{ $expireTime->timestamp * 1000 }}"></span>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-400 text-xs bg-white rounded-lg border border-dashed">ไม่มีข้อมูล</div>
                        @endforelse
                    </div>

                    {{-- 🖥️ PC View (My Submissions - Full Detail) --}}
                    <div class="pc-show p-0 overflow-x-auto flex-grow">
                        @if($mySubmissions->count() > 0)
                            <table class="min-w-full text-sm text-left">
                                <thead class="bg-gray-50 text-gray-500 border-b">
                                    <tr>
                                        <th class="px-4 py-3 font-medium w-32 text-center">เวลาส่ง / แก้ไข</th>
                                        <th class="px-4 py-3 font-medium">รายละเอียด / สถานะ</th>
                                        <th class="px-4 py-3 font-medium text-center w-32">จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($mySubmissions as $item)
                                        @php
                                            $expireTime = $item->created_at->addMinutes(10);
                                            $canEdit = $item->status == 'pending' && $expireTime->isFuture();
                                        @endphp
                                        <tr class="hover:bg-blue-50/50 transition">
                                            <td class="px-4 py-3 text-gray-500 text-xs align-top text-center">
                                                <div class="font-bold">{{ $item->created_at->format('d/m/Y') }}</div>
                                                <div class="text-[10px] mt-1 bg-gray-100 px-1 rounded inline-block">ส่ง: {{ $item->created_at->format('H:i') }} น.</div>
                                                <div class="text-[10px] mt-1 text-orange-500">แก้: {{ $item->updated_at->format('d/m H:i') }}</div>
                                            </td>
                                            <td class="px-4 py-3 align-top">
                                                <div class="font-bold text-blue-900 mb-1 text-base">{{ $item->subject }}</div>
                                                
                                                {{-- ข้อมูลผู้แจ้ง (เพิ่มเติม) --}}
                                                <div class="text-xs text-gray-600 mb-1 flex items-center gap-1 flex-wrap">
                                                    <span class="font-bold">{{ $item->first_name }} {{ $item->last_name }}</span>
                                                    @if($item->citizen_id)
                                                        <span class="bg-blue-50 text-blue-800 border border-blue-100 px-1.5 py-0.5 rounded text-[10px]">💳 {{ $item->citizen_id }}</span>
                                                    @endif
                                                    <span class="text-gray-300">|</span> 
                                                    <span>📞 {{ $item->phone_number }}</span>
                                                </div>
                                                <div class="text-xs text-gray-500 mb-2">📍 ชุมชน: {{ $item->community }}</div>

                                                <div class="mb-2">
                                                    @if($item->status == 'pending') <span class="inline-block bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded text-[10px]">⏳ รอตรวจสอบ</span>
                                                    @elseif($item->status == 'waiting') <span class="inline-block bg-orange-100 text-orange-800 px-2 py-0.5 rounded text-[10px]">📋 รับเรื่องแล้ว</span>
                                                    @elseif($item->status == 'in_progress') <span class="inline-block bg-blue-100 text-blue-800 px-2 py-0.5 rounded text-[10px]">🔧 กำลังดำเนินการ</span>
                                                    @elseif($item->status == 'completed') <span class="inline-block bg-green-100 text-green-800 px-2 py-0.5 rounded text-[10px]">✅ เสร็จสิ้น</span>
                                                    @else <span class="inline-block bg-red-100 text-red-800 px-2 py-0.5 rounded text-[10px]">❌ ไม่อนุมัติ</span>
                                                    @endif
                                                </div>
                                                @if($item->admin_notes)
                                                    <div class="bg-gray-50 p-2 rounded border border-gray-100 text-xs text-gray-600">
                                                        <span class="font-bold">จนท:</span> {{ $item->admin_notes }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-center align-top">
                                                <div class="flex flex-col gap-2">
                                                    <a href="{{ route('complaints.show', $item->id) }}" class="inline-block bg-blue-50 text-blue-600 border border-blue-200 px-2 py-1 rounded text-xs hover:bg-blue-100 transition">🔎 รายละเอียด</a>
                                                    @if($canEdit)
                                                        <a href="{{ route('complaints.edit', $item->id) }}" class="inline-block bg-yellow-50 text-yellow-700 border border-yellow-200 px-2 py-1 rounded text-xs hover:bg-yellow-100 transition">
                                                            ✏️ แก้ไข 
                                                            <span class="live-timer text-[10px] text-red-600 font-bold block" data-expire="{{ $expireTime->timestamp * 1000 }}"></span>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="flex flex-col items-center justify-center h-48 text-gray-400"><p class="text-sm">ยังไม่มีรายการที่ท่านยื่น</p></div>
                        @endif
                    </div>
                </div>

                {{-- ========================================== --}}
                {{-- 🟩 กล่องที่ 2: เรื่องจากชาวบ้านในเขต --}}
                {{-- ========================================== --}}
                <div class="bg-white rounded-xl shadow-md overflow-hidden border-t-4 border-green-500 flex flex-col h-full">
                    <div class="bg-green-50 px-4 md:px-6 py-4 border-b border-green-100 flex justify-between items-center">
                        <h3 class="font-bold text-green-900 flex items-center gap-2 text-base md:text-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            เรื่องจากชาวบ้านใน {{ Auth::user()->zone }}
                        </h3>
                        <span class="bg-green-200 text-green-800 text-xs font-bold px-2 py-1 rounded-full">{{ $zoneComplaints->count() }} เรื่อง</span>
                    </div>
                    
                    {{-- 📱 Mobile View (Zone Complaints) --}}
                    <div class="mobile-show bg-gray-50 p-2 space-y-3">
                        @forelse($zoneComplaints as $item)
                            <div class="bg-white rounded-lg shadow-sm border border-gray-200 relative overflow-hidden">
                                <div class="absolute left-0 top-0 bottom-0 w-1.5 
                                    @if($item->status == 'pending') bg-yellow-400
                                    @elseif($item->status == 'waiting') bg-orange-400
                                    @elseif($item->status == 'in_progress') bg-blue-500
                                    @elseif($item->status == 'completed') bg-green-500
                                    @else bg-red-500 @endif">
                                </div>
                                <div class="p-3 pl-5">
                                    <div class="mb-2 border-b border-gray-100 pb-2">
                                        <div class="flex justify-between items-start">
                                            <h4 class="font-bold text-green-800 text-sm line-clamp-2">{{ $item->subject }}</h4>
                                            <span class="text-[10px] text-gray-400 whitespace-nowrap ml-2">
                                                {{ $item->created_at->format('d/m/y H:i') }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="text-xs text-gray-600 space-y-1 mb-3">
                                        <div class="flex items-center gap-1 font-bold text-gray-700">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                            {{ $item->first_name }} {{ $item->last_name }}
                                        </div>
                                        @if(!$item->user_id)
                                            <div class="bg-orange-50 text-orange-800 border border-orange-100 px-2 py-1 rounded inline-block text-[10px]">
                                                💳 บุคคลทั่วไป (บัตร: {{ $item->citizen_id }})
                                            </div>
                                        @endif
                                        <div>📞 เบอร์โทร: {{ $item->phone_number }}</div>
                                        <div>📍 ชุมชน: {{ $item->community }}</div>
                                        <div class="flex items-center gap-2 mt-2 pt-2 border-t border-gray-100">
                                            <span class="font-bold">สถานะ:</span>
                                            @if($item->status == 'pending') <span class="text-yellow-600 font-bold text-[10px]">⏳ รอตรวจสอบ</span>
                                            @elseif($item->status == 'waiting') <span class="text-orange-600 font-bold text-[10px]">📋 รับเรื่องแล้ว</span>
                                            @elseif($item->status == 'in_progress') <span class="text-blue-600 font-bold text-[10px]">🔧 กำลังดำเนินการ</span>
                                            @elseif($item->status == 'completed') <span class="text-green-600 font-bold text-[10px]">✅ เสร็จสิ้น</span>
                                            @else <span class="text-red-600 font-bold text-[10px]">❌ ไม่อนุมัติ</span>
                                            @endif
                                        </div>
                                    </div>
                                    <a href="{{ route('complaints.show', $item->id) }}" class="block w-full text-center bg-green-50 text-green-700 px-3 py-2 rounded-lg border border-green-100 text-xs hover:bg-green-100 font-bold">
                                        🔎 ดูรายละเอียด
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-400 text-xs bg-white rounded-lg border border-dashed">ไม่มีข้อมูล</div>
                        @endforelse
                    </div>

                    {{-- 🖥️ PC View (Zone Complaints) --}}
                    <div class="pc-show p-0 overflow-x-auto flex-grow">
                        @if($zoneComplaints->count() > 0)
                            <table class="min-w-full text-sm text-left">
                                <thead class="bg-gray-50 text-gray-500 border-b">
                                    <tr>
                                        <th class="px-4 py-3 font-medium w-32 text-center">เวลาส่ง / แก้ไข</th>
                                        <th class="px-4 py-3 font-medium">ผู้แจ้ง / รายละเอียด</th>
                                        <th class="px-4 py-3 font-medium text-center w-24">สถานะ</th>
                                        <th class="px-4 py-3 font-medium text-center w-16">ดู</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($zoneComplaints as $item)
                                        <tr class="hover:bg-green-50/50 transition">
                                            <td class="px-4 py-3 text-gray-600 text-xs align-top text-center">
                                                <div class="font-bold">{{ $item->created_at->format('d/m/Y') }}</div>
                                                <div class="text-[10px] mt-1 bg-gray-100 px-1 rounded inline-block">ส่ง: {{ $item->created_at->format('H:i') }} น.</div>
                                                <div class="text-[10px] mt-1 text-orange-500">แก้: {{ $item->updated_at->format('d/m H:i') }}</div>
                                            </td>
                                            <td class="px-4 py-3 align-top">
                                                <div class="font-bold text-green-800 mb-1 text-base">{{ $item->subject }}</div>
                                                @if(!$item->user_id)
                                                    <div class="mb-1 inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-orange-100 text-orange-700 border border-orange-200">
                                                        บุคคลทั่วไป (เลขบัตร: {{ $item->citizen_id }})
                                                    </div>
                                                @endif
                                                <div class="text-xs text-gray-600 mb-1 flex items-center gap-1 flex-wrap">
                                                    {{ $item->first_name }} {{ $item->last_name }} <span class="text-gray-300">|</span> <span class="text-blue-600 font-bold">📞 {{ $item->phone_number }}</span>
                                                </div>
                                                <div class="text-xs text-gray-500">{{ $item->community }}</div>
                                            </td>
                                            <td class="px-4 py-3 text-center align-top">
                                                @if($item->status == 'pending') <span class="block bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-[10px] font-bold border border-yellow-200">รอตรวจสอบ</span>
                                                @elseif($item->status == 'waiting') <span class="block bg-orange-100 text-orange-800 px-2 py-1 rounded text-[10px] font-bold border border-orange-200">รับเรื่องแล้ว</span>
                                                @elseif($item->status == 'in_progress') <span class="block bg-blue-100 text-blue-800 px-2 py-1 rounded text-[10px] font-bold border border-blue-200">กำลังดำเนินการ</span>
                                                @elseif($item->status == 'completed') <span class="block bg-green-100 text-green-800 px-2 py-1 rounded text-[10px] font-bold border border-green-200">เสร็จสิ้น</span>
                                                @else <span class="block bg-red-100 text-red-800 px-2 py-1 rounded text-[10px] font-bold border border-red-200">อื่นๆ</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-center align-top">
                                                <a href="{{ route('complaints.show', $item->id) }}" class="inline-block bg-white border border-green-200 text-green-600 hover:bg-green-600 hover:text-white px-3 py-1.5 rounded-lg transition shadow-sm text-xs">🔎 ดู</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="flex flex-col items-center justify-center h-48 text-gray-400"><p class="text-sm">ยังไม่มีรายการแจ้งเหตุในเขตของท่าน</p></div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- ✅ JS นับถอยหลัง --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function updateTimers() {
                const now = new Date().getTime();
                
                document.querySelectorAll('.live-timer').forEach(element => {
                    const expireTime = parseInt(element.getAttribute('data-expire'));
                    const distance = expireTime - now;

                    if (distance < 0) {
                        element.innerHTML = "(หมดเวลา)";
                        const editLink = element.closest('a');
                        if(editLink) {
                            editLink.style.opacity = '0.5';
                            editLink.style.pointerEvents = 'none';
                            editLink.innerHTML = '🔒 หมดเวลา';
                        }
                    } else {
                        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                        const minStr = minutes < 10 ? "0" + minutes : minutes;
                        const secStr = seconds < 10 ? "0" + seconds : seconds;
                        element.innerHTML = `(${minStr}:${secStr})`;
                    }
                });
            }
            setInterval(updateTimers, 1000);
            updateTimers();
        });
    </script>
</x-app-layout>