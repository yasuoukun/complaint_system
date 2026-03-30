<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            ข้อมูลสมาชิกและประวัติการร้องเรียน
        </h2>
    </x-slot>

    <div class="py-6 md:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4">
            
            {{-- 1. การ์ดข้อมูลส่วนตัว (ปรับให้เรียงแนวตั้งบนมือถือ) --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl mb-6 border border-gray-100">
                <div class="p-6 flex flex-col md:flex-row items-center gap-4 md:gap-6 text-center md:text-left">
                    <div class="bg-blue-100 p-4 rounded-full flex-shrink-0">
                        <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xl md:text-2xl font-bold text-gray-800 break-words">{{ $user->first_name }} {{ $user->last_name }}</h3>
                        <p class="text-gray-500 break-all">{{ $user->email }}</p>
                        <div class="mt-3 flex flex-wrap justify-center md:justify-start gap-3 text-sm text-gray-600">
                            <span class="bg-gray-100 px-3 py-1 rounded-full flex items-center gap-1">
                                📞 {{ $user->phone_number ?? '-' }}
                            </span>
                            <span class="bg-gray-100 px-3 py-1 rounded-full flex items-center gap-1">
                                🆔 {{ $user->citizen_id ?? '-' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. ประวัติการร้องเรียน --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                <div class="p-6">
                    <h3 class="text-lg font-bold mb-4 text-blue-800 flex items-center gap-2">
                        📜 ประวัติการร้องเรียน 
                        <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">{{ $history->count() }} รายการ</span>
                    </h3>

                    @if($history->count() > 0)
                        
                        {{-- ========================================== --}}
                        {{--  📱 ส่วนแสดงผลสำหรับมือถือ (Card View)    --}}
                        {{-- ========================================== --}}
                        <div class="block md:hidden space-y-4">
                            @foreach($history as $item)
                                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 shadow-sm">
                                    <div class="flex justify-between items-start mb-2">
                                        <span class="text-xs text-gray-500 flex items-center gap-1">
                                            📅 {{ $item->created_at->format('d/m/Y') }}
                                        </span>
                                        @if($item->status == 'pending') <span class="px-2 py-1 text-[10px] bg-yellow-100 text-yellow-800 rounded-full font-bold">รอตรวจสอบ</span>
                                        @elseif($item->status == 'waiting') <span class="px-2 py-1 text-[10px] bg-orange-100 text-orange-800 rounded-full font-bold">รับเรื่องแล้ว</span>
                                        @elseif($item->status == 'in_progress') <span class="px-2 py-1 text-[10px] bg-blue-100 text-blue-800 rounded-full font-bold">กำลังดำเนินการ</span>
                                        @elseif($item->status == 'completed') <span class="px-2 py-1 text-[10px] bg-green-100 text-green-800 rounded-full font-bold">เสร็จสิ้น</span>
                                        @else <span class="px-2 py-1 text-[10px] bg-red-100 text-red-800 rounded-full font-bold">ยกเลิก</span>
                                        @endif
                                    </div>
                                    
                                    <h4 class="font-bold text-gray-800 mb-1 line-clamp-2">{{ $item->subject }}</h4>
                                    <p class="text-xs text-gray-500 mb-3 line-clamp-2">{{ $item->details }}</p>
                                    
                                    <a href="{{ route('admin.complaints.show', $item->id) }}" class="w-full bg-white border border-blue-200 text-blue-600 text-sm font-bold py-2 rounded-lg flex items-center justify-center gap-1 hover:bg-blue-50 transition">
                                        ดูรายละเอียด
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    </a>
                                </div>
                            @endforeach
                        </div>

                        {{-- ========================================== --}}
                        {{--  💻 ส่วนแสดงผลสำหรับ Desktop (Table)      --}}
                        {{-- ========================================== --}}
                        <div class="hidden md:block overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">วันที่</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">เรื่อง</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">สถานะ</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($history as $item)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $item->created_at->format('d/m/Y') }}
                                            </td>
                                            <td class="px-6 py-4 text-sm font-medium text-gray-900 max-w-xs truncate">
                                                {{ $item->subject }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($item->status == 'pending') <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full">รอตรวจสอบ</span>
                                                @elseif($item->status == 'waiting') <span class="px-2 py-1 text-xs bg-orange-100 text-orange-800 rounded-full">รับเรื่องแล้ว</span>
                                                @elseif($item->status == 'in_progress') <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">กำลังดำเนินการ</span>
                                                @elseif($item->status == 'completed') <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">เสร็จสิ้น</span>
                                                @else <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">ยกเลิก/ไม่ผ่าน</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('admin.complaints.show', $item->id) }}" class="text-blue-600 hover:text-blue-900 font-bold hover:underline">
                                                    ดูรายละเอียด
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    @else
                        <div class="text-center py-10">
                            <div class="bg-gray-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-3">
                                <span class="text-2xl">📭</span>
                            </div>
                            <p class="text-gray-500">ยังไม่มีประวัติการร้องเรียน</p>
                        </div>
                    @endif
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>