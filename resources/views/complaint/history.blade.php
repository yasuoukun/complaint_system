<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('ประวัติการยื่นคำร้อง') }}
            </h2>
            
            <a href="{{ route('complaints.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 text-sm font-bold shadow-sm transition transform hover:scale-105">
                + เขียนคำร้องใหม่
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded border border-green-200 shadow-sm flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded border border-red-200 shadow-sm flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-blue-500">
                <div class="p-6 text-gray-900">
                    
                    @if($complaints->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full border-collapse">
                                <thead>
                                    <tr class="bg-gray-100 text-sm uppercase text-gray-600 border-b border-gray-200">
                                        <th class="py-3 px-4 text-center w-32">วันที่</th>
                                        <th class="py-3 px-4 text-left">เรื่องร้องเรียน / การจัดการ</th>
                                        <th class="py-3 px-4 text-center w-40">สถานะ</th>
                                        <th class="py-3 px-4 text-left">ความเห็นเจ้าหน้าที่</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($complaints as $item)
                                        <tr class="hover:bg-blue-50 transition-colors duration-150">
                                            <td class="py-3 px-4 text-center text-sm text-gray-600">
                                                <div class="font-bold">{{ $item->created_at->format('d/m/Y') }}</div>
                                                <div class="text-xs text-gray-400">{{ $item->created_at->format('H:i') }} น.</div>
                                            </td>
                                            
                                            <td class="py-3 px-4">
                                                <a href="{{ route('complaints.show', $item->id) }}" class="font-bold text-blue-900 text-lg hover:text-blue-600 hover:underline transition">
                                                    {{ $item->subject }}
                                                </a>
                                                <p class="text-sm text-gray-500 mt-1 truncate w-64 md:w-96 cursor-pointer" onclick="window.location='{{ route('complaints.show', $item->id) }}'">
                                                    {{ $item->details }}
                                                </p>
                                                
                                                <div class="mt-3 flex items-center gap-2">
                                                    <a href="{{ route('complaints.show', $item->id) }}" class="inline-flex items-center text-xs bg-blue-50 text-blue-600 px-3 py-1 rounded-full border border-blue-200 hover:bg-blue-100 transition">
                                                        🔎 รายละเอียด
                                                    </a>

                                                    @php
                                                        // เช็คว่าเวลาปัจจุบัน ยังไม่เกิน เวลาส่ง+10นาที และสถานะต้องเป็น pending
                                                        $isEditable = $item->created_at->addMinutes(10)->isFuture() && $item->status == 'pending';
                                                    @endphp

                                                    @if($isEditable)
                                                        <a href="{{ route('complaints.edit', $item->id) }}" class="inline-flex items-center gap-1 text-xs bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full border border-yellow-300 hover:bg-yellow-200 transition">
                                                            ✏️ แก้ไข
                                                        </a>
                                                    @else
                                                        <span class="inline-flex items-center gap-1 text-xs bg-gray-100 text-gray-400 px-3 py-1 rounded-full border border-gray-200 cursor-not-allowed" title="หมดเวลาแก้ไข หรือเจ้าหน้าที่รับเรื่องแล้ว">
                                                            🔒 ล็อค
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>

                                            <td class="py-3 px-4 text-center">
                                                @if($item->status == 'pending')
                                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-bold border border-yellow-200 inline-flex items-center gap-1">
                                                        ⏳ รอตรวจสอบ
                                                    </span>
                                                @elseif($item->status == 'waiting')
                                                    <span class="px-3 py-1 bg-orange-100 text-orange-800 rounded-full text-xs font-bold border border-orange-200 inline-flex items-center gap-1">
                                                        📋 รับเรื่องแล้ว
                                                    </span>
                                                @elseif($item->status == 'in_progress')
                                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-bold border border-blue-200 inline-flex items-center gap-1">
                                                        🔧 กำลังดำเนินการ
                                                    </span>
                                                @elseif($item->status == 'completed')
                                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold border border-green-200 inline-flex items-center gap-1">
                                                        ✅ เสร็จสิ้น
                                                    </span>
                                                @elseif($item->status == 'unsuccessful')
                                                    <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-bold border border-purple-200 inline-flex items-center gap-1">
                                                        ⚠️ ไม่สำเร็จ
                                                    </span>
                                                @else
                                                    <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-bold border border-red-200 inline-flex items-center gap-1">
                                                        ❌ ไม่อนุมัติ
                                                    </span>
                                                @endif
                                            </td>

                                            <td class="py-3 px-4 text-sm text-gray-600">
                                                @if($item->admin_notes)
                                                    <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm relative">
                                                        <div class="absolute top-0 left-3 -mt-1 w-2 h-2 bg-white border-t border-l border-gray-200 transform rotate-45"></div>
                                                        {{ $item->admin_notes }}
                                                    </div>
                                                @else
                                                    <span class="text-gray-300 italic">- รอการตอบกลับ -</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-16">
                            <p class="text-gray-500 text-lg font-medium">คุณยังไม่มีประวัติการส่งคำร้อง</p>
                            <a href="{{ route('complaints.create') }}" class="inline-block mt-4 bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 shadow-md transition">
                                เริ่มเขียนคำร้องแรก
                            </a>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>