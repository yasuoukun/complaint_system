<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-yellow-500 rounded-lg shadow-sm text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <div>
                    <h2 class="font-bold text-xl text-blue-900 leading-tight">
                        ระบบติดตามงานสมาชิกสภาเทศบาล
                    </h2>
                    <span class="text-xs font-bold text-white bg-blue-600 px-2 py-0.5 rounded-full">
                        เขตพื้นที่รับผิดชอบ: {{ Auth::user()->zone ?? 'ไม่ระบุ' }}
                    </span>
                </div>
            </div>
            
            <a href="{{ route('complaints.create') }}" class="flex items-center gap-2 bg-blue-900 text-white px-5 py-2.5 rounded-full font-bold text-sm hover:bg-blue-800 shadow-md transition transform hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                เขียนคำร้องแทนชาวบ้าน
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                
                <div class="bg-white rounded-xl shadow-md overflow-hidden border-t-4 border-blue-600 flex flex-col h-full">
                    <div class="bg-blue-50 px-6 py-4 border-b border-blue-100 flex justify-between items-center">
                        <h3 class="font-bold text-blue-900 flex items-center gap-2 text-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            เรื่องที่ท่านยื่นเอง
                        </h3>
                        <span class="bg-blue-200 text-blue-800 text-xs font-bold px-2 py-1 rounded-full">{{ $mySubmissions->count() }} เรื่อง</span>
                    </div>
                    
                    <div class="p-0 overflow-x-auto flex-grow">
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
                                        <tr class="hover:bg-blue-50/50 transition">
                                            <td class="px-4 py-3 text-gray-500 text-xs align-top text-center">
                                                <div class="font-bold">{{ $item->created_at->format('d/m/Y') }}</div>
                                                <div class="text-[10px] mt-1 bg-gray-100 px-1 rounded inline-block">ส่ง: {{ $item->created_at->format('H:i') }} น.</div>
                                                <div class="text-[10px] mt-1 text-orange-500">แก้: {{ $item->updated_at->format('d/m H:i') }}</div>
                                            </td>
                                            <td class="px-4 py-3 align-top">
                                                <a href="{{ route('complaints.show', $item->id) }}" class="font-bold text-blue-800 hover:underline block mb-1 text-base">
                                                    {{ $item->subject }}
                                                </a>

                                                <div class="text-xs text-gray-600 mb-1">📞 {{ $item->phone_number }}</div>
                                                
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
                                                    <a href="{{ route('complaints.show', $item->id) }}" class="inline-block bg-blue-50 text-blue-600 border border-blue-200 px-2 py-1 rounded text-xs hover:bg-blue-100 transition">
                                                        🔎 รายละเอียด
                                                    </a>
                                                    @if($item->status == 'pending' && $item->created_at->addMinutes(10)->isFuture())
                                                        <a href="{{ route('complaints.edit', $item->id) }}" class="inline-block bg-yellow-50 text-yellow-700 border border-yellow-200 px-2 py-1 rounded text-xs hover:bg-yellow-100 transition">
                                                            ✏️ แก้ไข
                                                        </a>
                                                    @else
                                                        <span class="inline-block bg-gray-100 text-gray-400 border border-gray-200 px-2 py-1 rounded text-xs cursor-not-allowed">
                                                            🔒 ล็อค
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="flex flex-col items-center justify-center h-48 text-gray-400">
                                <p class="text-sm">ยังไม่มีรายการที่ท่านยื่น</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md overflow-hidden border-t-4 border-green-500 flex flex-col h-full">
                    <div class="bg-green-50 px-6 py-4 border-b border-green-100 flex justify-between items-center">
                        <h3 class="font-bold text-green-900 flex items-center gap-2 text-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            เรื่องจากชาวบ้านใน {{ Auth::user()->zone }}
                        </h3>
                        <span class="bg-green-200 text-green-800 text-xs font-bold px-2 py-1 rounded-full">{{ $zoneComplaints->count() }} เรื่อง</span>
                    </div>
                    
                    <div class="p-0 overflow-x-auto flex-grow">
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
                                                <div class="font-bold text-green-800 mb-1 text-base">
                                                    {{ $item->subject }}
                                                </div>
                                                
                                                @if(!$item->user_id)
                                                    <div class="mb-1 inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-orange-100 text-orange-700 border border-orange-200">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0c0 .884-.95 2-2.5 2H15"></path></svg>
                                                        บุคคลทั่วไป (เลขบัตร: {{ $item->citizen_id }})
                                                    </div>
                                                @endif

                                                <div class="text-xs text-gray-600 mb-1 flex items-center gap-1 flex-wrap">
                                                    <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                                    {{ $item->first_name }} {{ $item->last_name }}
                                                    <span class="text-gray-300 ml-1">|</span>
                                                    <span class="text-blue-600 font-bold ml-1">📞 {{ $item->phone_number }}</span>
                                                </div>
                                                <div class="text-xs text-gray-500 flex items-center gap-1">
                                                    <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                                    {{ $item->community }}
                                                </div>
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
                                                <a href="{{ route('complaints.show', $item->id) }}" class="inline-block bg-white border border-green-200 text-green-600 hover:bg-green-600 hover:text-white px-3 py-1.5 rounded-lg transition shadow-sm text-xs">
                                                    🔎 ดู
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="flex flex-col items-center justify-center h-48 text-gray-400">
                                <p class="text-sm">ยังไม่มีรายการแจ้งเหตุในเขตของท่าน</p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>