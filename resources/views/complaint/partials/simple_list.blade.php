@if($items->count() > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm border-collapse">
            <thead class="bg-gray-50 text-gray-500 font-bold">
                <tr>
                    <th class="px-4 py-2 text-left w-24">วันที่</th>
                    <th class="px-4 py-2 text-left">เรื่อง / ผู้แจ้ง</th>
                    <th class="px-4 py-2 text-center w-24">สถานะ</th>
                    <th class="px-4 py-2 text-center w-16">ดู</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($items as $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 text-gray-600 text-xs">
                            {{ $item->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="font-bold text-blue-900 truncate w-40 md:w-64">{{ $item->subject }}</div>
                            <div class="text-xs text-gray-500 mt-1">
                                @if($item->user_id == auth()->id())
                                    <span class="text-blue-600">(ท่านแจ้งเอง)</span>
                                @else
                                    จาก: {{ $item->first_name }} {{ $item->last_name }}
                                    <br>ชุมชน: {{ $item->community }}
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center align-middle">
                            @if($item->status == 'pending') <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-[10px] font-bold border border-yellow-200">รอตรวจ</span>
                            @elseif($item->status == 'waiting') <span class="bg-orange-100 text-orange-800 px-2 py-1 rounded-full text-[10px] font-bold border border-orange-200">รับเรื่อง</span>
                            @elseif($item->status == 'in_progress') <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-[10px] font-bold border border-blue-200">กำลังทำ</span>
                            @elseif($item->status == 'completed') <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-[10px] font-bold border border-green-200">เสร็จสิ้น</span>
                            @else <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-[10px] font-bold border border-red-200">อื่นๆ</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center align-middle">
                            <a href="{{ route('complaints.show', $item->id) }}" class="bg-white border border-gray-300 text-gray-500 hover:text-blue-600 hover:border-blue-400 px-2 py-1 rounded text-xs transition shadow-sm">
                                🔎
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="flex flex-col items-center justify-center py-8 text-center text-gray-400">
        <svg class="w-10 h-10 mb-2 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
        <p class="text-sm">- ยังไม่มีรายการ -</p>
    </div>
@endif