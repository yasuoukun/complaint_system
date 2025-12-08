@if($items->count() > 0)
<table class="min-w-full divide-y divide-slate-100 table-fixed">
    <thead class="bg-slate-50">
        <tr>
            <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase w-32">วัน-เวลา</th>
            <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase w-[40%]">รายละเอียด</th>
            <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase w-20">เมนู</th>
            <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase w-[30%]">จัดการ</th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-slate-100">
        @foreach($items as $item)
            <tr class="hover:bg-slate-50 transition">
                <td class="px-4 py-3 text-center align-top">
                    @php $d = ($type == 'history') ? $item->updated_at : $item->created_at; @endphp
                    <div class="text-sm font-bold text-slate-700">{{ $d->format('d/m/Y') }}</div>
                    <div class="text-[10px] text-slate-400 bg-slate-100 rounded px-1 mt-1 inline-block">⏰ {{ $d->format('H:i') }}</div>
                </td>
                <td class="px-4 py-3 align-top">
                    <div class="flex flex-wrap gap-1 mb-1">
                        @if($item->zone) <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-teal-100 text-teal-800">📍 เขต {{ $item->zone }}</span> 
                        @else <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-600 animate-pulse">⚠️ ระบุเขต</span> @endif
                        
                        @if(!$item->user_id) 
                            <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-pink-100 text-pink-700">💳 {{ $item->citizen_id }}</span> 
                        @endif
                    </div>
                    <div class="text-sm font-bold text-slate-800">{{ $item->subject }}</div>
                    <div class="text-xs text-slate-500">👤 {{ $item->first_name }} {{ $item->last_name }} ({{ $item->phone_number }})</div>
                    <p class="text-xs text-slate-600 bg-slate-50 p-2 rounded mt-1 border border-slate-100">{{ Str::limit($item->details, 100) }}</p>
                    @if($item->admin_notes) <div class="mt-1 text-[10px] text-slate-500 bg-yellow-50 p-1 rounded border border-yellow-100">📝 {{ $item->admin_notes }}</div> @endif
                </td>
                <td class="px-4 py-3 text-center align-top">
                    <div class="flex flex-col gap-1">
                        <a href="{{ route('complaints.show', $item->id) }}" class="text-[10px] bg-white border px-2 py-1 rounded hover:bg-gray-50">🔎 ดู</a>
                        <a href="{{ route('admin.complaints.download', $item->id) }}" class="text-[10px] bg-blue-50 text-blue-600 border border-blue-100 px-2 py-1 rounded hover:bg-blue-100">📥 Word</a>
                        <a href="{{ route('complaints.edit', $item->id) }}" class="text-[10px] bg-yellow-50 text-yellow-700 border border-yellow-200 px-2 py-1 rounded hover:bg-yellow-100 font-bold">✏️ แก้</a>
                    </div>
                </td>
                <td class="px-4 py-3 align-top">
                    @if($item->user_edit_note) <div class="text-[10px] text-red-600 mb-1 animate-pulse">⚠️ มีการแก้ไข</div> @endif
                    
                    @if($type === 'history')
                         <form action="{{ route('admin.complaints.destroy', $item->id) }}" method="POST" onsubmit="return confirm('ลบ?');">
                            @csrf @method('DELETE')
                            <button class="w-full bg-red-50 text-red-600 border border-red-200 text-[10px] py-1 rounded hover:bg-red-100">🗑️ ลบ</button>
                        </form>
                    @else
                        <form action="{{ route('admin.complaints.process', $item->id) }}" method="POST" class="flex flex-col gap-1">
                            @csrf
                            <select name="zone" class="text-[10px] w-full border-slate-200 rounded p-1 bg-white h-7">
                                <option value="" {{ !$item->zone ? 'selected' : '' }}>-- เขต --</option>
                                <option value="1" {{ $item->zone == '1' ? 'selected' : '' }}>เขต 1</option>
                                <option value="2" {{ $item->zone == '2' ? 'selected' : '' }}>เขต 2</option>
                                <option value="3" {{ $item->zone == '3' ? 'selected' : '' }}>เขต 3</option>
                            </select>
                            <textarea name="admin_notes" rows="1" class="text-[11px] w-full border-slate-200 rounded p-1" placeholder="บันทึก..."></textarea>
                            <div class="flex gap-1">
                                @if($type === 'pending')
                                    <button type="submit" name="status" value="waiting" class="flex-1 bg-green-500 text-white text-[10px] py-1 rounded font-bold hover:bg-green-600">✅ รับ</button>
                                    <button type="submit" name="status" value="rejected" class="bg-red-100 text-red-600 text-[10px] px-2 py-1 rounded font-bold hover:bg-red-200">❌</button>
                                @elseif($type === 'waiting')
                                    <button type="submit" name="status" value="in_progress" class="w-full bg-blue-600 text-white text-[10px] py-1 rounded font-bold hover:bg-blue-700">🚀 เริ่มงาน</button>
                                @elseif($type === 'in_progress')
                                    <button type="submit" name="status" value="completed" class="flex-1 bg-green-500 text-white text-[10px] py-1 rounded font-bold hover:bg-green-600">✅ เสร็จ</button>
                                    <button type="submit" name="status" value="unsuccessful" class="bg-purple-100 text-purple-700 text-[10px] px-2 py-1 rounded font-bold hover:bg-purple-200">⚠️</button>
                                @endif
                            </div>
                            <button type="submit" name="status" value="{{ $item->status }}" class="w-full bg-gray-100 text-gray-600 text-[10px] py-1 rounded hover:bg-gray-200">💾 บันทึก</button>
                        </form>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@else
    <div class="py-8 text-center text-slate-400 text-sm">ไม่มีข้อมูล</div>
@endif