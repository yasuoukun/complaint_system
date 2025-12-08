@if($items->count() > 0)
<table class="min-w-full divide-y divide-slate-200 table-fixed">
    <thead class="{{ $header_bg }} {{ $header_text }}">
        <tr>
            <th class="px-6 py-3 text-left text-xs font-bold uppercase w-36">วัน-เวลา แจ้ง</th>
            <th class="px-6 py-3 text-left text-xs font-bold uppercase min-w-[300px]">รายละเอียด</th>
            <th class="px-6 py-3 text-center text-xs font-bold uppercase w-24">ไฟล์/แก้ไข</th>
            <th class="px-6 py-3 text-left text-xs font-bold uppercase w-[35%]">จัดการ</th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-slate-100">
        @foreach($items as $item)
            <tr class="transition duration-200 {{ !$item->zone ? 'bg-red-50' : 'hover:bg-slate-50' }}">
                
                <td class="px-6 py-4 align-top text-center border-r border-slate-50">
                    @php $d = ($type == 'history') ? $item->updated_at : $item->created_at; @endphp
                    <div class="inline-block bg-white border border-slate-200 rounded-xl p-2 shadow-sm">
                        <div class="text-lg font-black text-slate-700 leading-none">{{ $d->format('d') }}</div>
                        <div class="text-[10px] font-bold text-slate-400 uppercase">{{ $d->format('M') }}</div>
                    </div>
                    <div class="mt-1 text-[10px] text-slate-500 font-medium bg-slate-100 rounded-full px-2 py-0.5 inline-block">
                        ⏰ {{ $d->format('H:i') }}
                    </div>
                </td>

                <td class="px-6 py-4 align-top">
                    <div class="flex flex-wrap gap-2 mb-2">
                        @if($item->zone) 
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-teal-100 text-teal-700 border border-teal-200 shadow-sm">📍 เขต {{ $item->zone }}</span> 
                        @else 
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-600 text-white animate-pulse shadow-md">⚠️ ระบุเขต</span> 
                        @endif

                        @if($item->user && $item->user->role == 'council_member')
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-100 text-indigo-700 border border-indigo-200">👔 สท. {{ $item->user->first_name }}</span>
                        @elseif(!$item->user_id)
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-700 border border-rose-200 shadow-sm">
                                💳 บัตร: {{ $item->citizen_id }}
                            </span>
                        @endif
                    </div>

                    <div class="text-sm font-bold text-slate-800 mb-1 truncate">{{ $item->subject }}</div>
                    <div class="text-xs text-slate-500 mb-2 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        {{ $item->first_name }} {{ $item->last_name }}
                        <span class="text-slate-300">|</span>
                        📞 {{ $item->phone_number }}
                    </div>
                    
                    <div class="text-xs text-slate-600 bg-slate-50 p-3 rounded-lg border border-slate-100 leading-relaxed">
                        {{ Str::limit($item->details, 120) }}
                    </div>

                    @if($item->admin_notes)
                        <div class="mt-2 text-[10px] text-slate-500 bg-amber-50 p-2 rounded border border-amber-100">
                            <span class="font-bold text-amber-700">📝 บันทึก:</span> {{ $item->admin_notes }}
                        </div>
                    @endif
                </td>

                <td class="px-6 py-4 text-center align-top border-l border-r border-slate-50">
                    <div class="flex flex-col gap-2 w-full">
                        <a href="{{ route('complaints.show', $item->id) }}" class="w-full flex items-center justify-center gap-1 bg-white border border-slate-200 text-slate-600 px-2 py-1 rounded-md hover:bg-slate-50 hover:text-blue-600 text-[10px] font-bold transition">
                            🔎 ดู
                        </a>
                        <a href="{{ route('admin.complaints.download', $item->id) }}" class="w-full flex items-center justify-center gap-1 bg-blue-50 border border-blue-100 text-blue-600 px-2 py-1 rounded-md hover:bg-blue-100 text-[10px] font-bold transition">
                            📥 Word
                        </a>
                        <a href="{{ route('complaints.edit', $item->id) }}" class="w-full flex items-center justify-center gap-1 bg-yellow-50 border border-yellow-200 text-yellow-700 px-2 py-1 rounded-md hover:bg-yellow-100 text-[10px] font-bold transition">
                            ✏️ แก้ไข
                        </a>
                    </div>
                </td>

                <td class="px-6 py-4 align-top">
                    @if($item->user_edit_note) 
                        <div class="mb-2 p-1.5 bg-red-50 border border-red-100 rounded text-[10px] text-red-600 animate-pulse shadow-sm">
                            <strong>⚠️ แก้ไข:</strong> {{ Str::limit($item->user_edit_note, 30) }}
                        </div> 
                    @endif

                    @if($type === 'history')
                        <form action="{{ route('admin.complaints.destroy', $item->id) }}" method="POST" onsubmit="return confirm('ลบ?');">
                            @csrf @method('DELETE')
                            <button class="w-8 h-8 flex items-center justify-center bg-white border border-red-200 text-red-500 rounded hover:bg-red-50 hover:text-red-700 transition" title="ลบรายการนี้">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    @else
                        <form action="{{ route('admin.complaints.process', $item->id) }}" method="POST" class="space-y-2">
                            @csrf
                            <div class="relative">
                                <select name="zone" class="w-full text-[10px] border-slate-300 rounded py-1 px-2 h-7 bg-white focus:ring-blue-500 focus:border-blue-500 shadow-sm cursor-pointer">
                                    <option value="" {{ !$item->zone ? 'selected' : '' }}>-- เลือกเขต --</option>
                                    <option value="1" {{ $item->zone == '1' ? 'selected' : '' }}>เขต 1</option>
                                    <option value="2" {{ $item->zone == '2' ? 'selected' : '' }}>เขต 2</option>
                                    <option value="3" {{ $item->zone == '3' ? 'selected' : '' }}>เขต 3</option>
                                </select>
                            </div>

                            <textarea name="admin_notes" rows="1" class="w-full text-[11px] border-slate-300 rounded p-1.5 shadow-sm focus:ring-blue-500" placeholder="บันทึก..."></textarea>
                            
                            <div class="flex flex-col gap-2">
                                <button type="submit" name="status" value="{{ $item->status }}" class="w-full py-1.5 border border-slate-300 rounded text-[10px] font-bold text-slate-600 bg-slate-50 hover:bg-white hover:shadow-sm transition">
                                    💾 บันทึกข้อมูล
                                </button>

                                <div class="flex gap-2">
                                    @if($type === 'pending')
                                        <button type="submit" name="status" value="waiting" class="flex-1 bg-green-500 text-white text-[10px] py-1.5 rounded font-bold hover:bg-green-600 shadow-sm transition">✅ รับเรื่อง</button>
                                        <button type="submit" name="status" value="rejected" class="bg-red-100 text-red-600 text-[10px] px-2 py-1.5 rounded font-bold border border-red-200 hover:bg-red-200 transition">❌</button>
                                    @elseif($type === 'waiting')
                                        <button type="submit" name="status" value="in_progress" class="w-full bg-blue-600 text-white text-[10px] py-1.5 rounded font-bold hover:bg-blue-700 shadow-md transition">🚀 เริ่มงาน</button>
                                    @elseif($type === 'in_progress')
                                        <button type="submit" name="status" value="completed" class="flex-1 bg-green-500 text-white text-[10px] py-1.5 rounded font-bold hover:bg-green-600 shadow-sm transition">✅ เสร็จ</button>
                                        <button type="submit" name="status" value="unsuccessful" class="bg-purple-100 text-purple-700 text-[10px] px-2 py-1.5 rounded font-bold border border-purple-200 hover:bg-purple-200 transition">⚠️</button>
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
@else
    <div class="py-12 text-center flex flex-col items-center justify-center">
        <div class="bg-slate-50 p-3 rounded-full mb-2">
            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
        </div>
        <span class="text-slate-400 text-sm">ไม่มีรายการ</span>
    </div>
@endif