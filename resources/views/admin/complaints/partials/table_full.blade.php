@if($items->count() > 0)
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200 table-fixed">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase w-[15%]">วัน-เวลา แจ้ง</th>
                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase w-[45%]">รายละเอียด</th>
                <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase w-[10%]">ไฟล์/แก้ไข</th>
                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase w-[30%]">จัดการ</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($items as $item)
                <tr class="transition {{ !$item->zone ? 'bg-red-50' : 'hover:bg-gray-50' }}">
                    
                    <td class="px-2 py-4 align-top">
                        @php
                            $dateObj = ($type === 'history') ? $item->updated_at : $item->created_at;
                        @endphp
                        <div class="flex flex-col items-center justify-center bg-white border border-slate-200 rounded-lg p-1 shadow-sm">
                            <div class="text-sm font-black text-slate-700">{{ $dateObj->format('d/m/Y') }}</div>
                            <div class="text-[10px] text-slate-500 bg-slate-100 px-1.5 rounded mt-1">
                                ⏰ {{ $dateObj->format('H:i') }}
                            </div>
                        </div>
                    </td>

                    <td class="px-2 py-4 align-top">
                        <div class="flex flex-wrap gap-1 mb-1">
                            @if($item->zone)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-teal-100 text-teal-800 border border-teal-200">
                                    📍 เขต {{ $item->zone }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-red-600 text-white animate-bounce">
                                    ⚠️ ระบุเขต
                                </span>
                            @endif

                            @if($item->user && $item->user->role === 'council_member')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-100 text-indigo-700 border border-indigo-200">
                                    สท. {{ $item->user->first_name }}
                                </span>
                            @elseif(!$item->user_id)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-orange-100 text-orange-700 border border-orange-200">
                                    บุคคลทั่วไป (บัตร: {{ $item->citizen_id }})
                                </span>
                            @endif
                        </div>

                        <div class="text-sm font-bold text-blue-900 truncate">{{ $item->subject }}</div>
                        <div class="text-xs text-gray-500 mb-1 truncate">
                            โดย: {{ $item->first_name }} {{ $item->last_name }} ({{ $item->phone_number }})
                        </div>
                        
                        <p class="text-xs text-gray-600 bg-gray-50 p-2 rounded border border-gray-100 leading-relaxed break-words">
                            {{ Str::limit($item->details, 100) }}
                        </p>
                        
                        @if($item->admin_notes)
                            <div class="mt-1 text-[10px] text-gray-500 bg-yellow-50 p-1 rounded border border-yellow-100 truncate">
                                <span class="font-bold text-yellow-700">📝 บันทึก:</span> {{ $item->admin_notes }}
                            </div>
                        @endif
                    </td>

                    <td class="px-2 py-4 text-center align-top">
                        <div class="flex flex-col gap-1 w-full">
                            <a href="{{ route('complaints.show', $item->id) }}" class="block w-full text-center px-2 py-1 border border-gray-200 text-[10px] font-medium rounded text-slate-600 bg-white hover:bg-slate-50 transition">
                                🔎 ดู
                            </a>
                            <a href="{{ route('admin.complaints.download', $item->id) }}" class="block w-full text-center px-2 py-1 border border-blue-200 text-[10px] font-medium rounded text-blue-600 bg-blue-50 hover:bg-blue-100 transition">
                                📥 Word
                            </a>
                            <a href="{{ route('complaints.edit', $item->id) }}" class="block w-full text-center px-2 py-1 border border-yellow-300 text-[10px] font-bold rounded text-yellow-700 bg-yellow-50 hover:bg-yellow-100 transition">
                                ✏️ แก้ไข
                            </a>
                        </div>
                    </td>

                    <td class="px-2 py-4 align-top">
                        @if($item->user_edit_note)
                            <div class="mb-2 p-1 bg-red-50 border border-red-200 rounded text-[10px] text-red-600 animate-pulse">
                                <strong>⚠️ มีการแก้ไข:</strong> {{ Str::limit($item->user_edit_note, 30) }}
                            </div>
                        @endif

                        @if($type === 'history')
                            <form action="{{ route('admin.complaints.destroy', $item->id) }}" method="POST" onsubmit="return confirm('ยืนยันการลบรายการนี้?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-full px-3 py-1.5 border border-red-200 text-xs font-medium rounded text-red-600 bg-white hover:bg-red-50 transition">
                                    🗑️ ลบ
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.complaints.process', $item->id) }}" method="POST" class="space-y-2">
                                @csrf
                                
                                <div class="relative">
                                    <select name="zone" class="block w-full py-1 px-2 text-[10px] border-gray-300 rounded shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                        <option value="" {{ !$item->zone ? 'selected' : '' }}>-- เขต --</option>
                                        <option value="1" {{ $item->zone == '1' ? 'selected' : '' }}>เขต 1</option>
                                        <option value="2" {{ $item->zone == '2' ? 'selected' : '' }}>เขต 2</option>
                                        <option value="3" {{ $item->zone == '3' ? 'selected' : '' }}>เขต 3</option>
                                    </select>
                                </div>

                                <textarea name="admin_notes" rows="1" class="block w-full text-xs border-gray-300 rounded shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="บันทึก..."></textarea>
                                
                                <div class="flex flex-col gap-1">
                                    <button type="submit" name="status" value="{{ $item->status }}" class="w-full py-1 px-2 border border-gray-300 rounded text-[10px] font-bold text-gray-600 bg-gray-50 hover:bg-gray-100 transition">
                                        💾 บันทึกข้อมูล
                                    </button>

                                    <div class="flex gap-1">
                                        @if($type === 'pending')
                                            <button type="submit" name="status" value="waiting" class="flex-1 bg-green-600 text-white py-1 px-1 rounded text-[10px] font-bold hover:bg-green-700 shadow-sm transition">✅ รับ</button>
                                            <button type="submit" name="status" value="rejected" class="bg-red-100 text-red-600 py-1 px-2 rounded text-[10px] font-bold border border-red-200 hover:bg-red-200 transition">❌</button>
                                        @elseif($type === 'waiting')
                                            <button type="submit" name="status" value="in_progress" class="w-full bg-blue-600 text-white py-1 px-1 rounded text-[10px] font-bold hover:bg-blue-700 shadow-sm transition">🚀 เริ่มงาน</button>
                                        @elseif($type === 'in_progress')
                                            <button type="submit" name="status" value="completed" class="flex-1 bg-green-500 text-white py-1 px-1 rounded text-[10px] font-bold hover:bg-green-600 shadow-sm transition">✅ เสร็จ</button>
                                            <button type="submit" name="status" value="unsuccessful" class="px-2 bg-purple-100 text-purple-700 py-1 rounded text-[10px] font-bold border border-purple-200 hover:bg-purple-200 transition">⚠️</button>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
    <div class="py-8 text-center text-slate-400 text-sm">ไม่มีข้อมูลในส่วนนี้</div>
@endif