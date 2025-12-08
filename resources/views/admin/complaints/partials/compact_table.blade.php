@if($items->count() > 0)
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-slate-100 table-fixed text-xs">
        <thead class="bg-slate-50 text-slate-500">
            <tr>
                <th class="px-3 py-2 text-left w-24 font-bold">วันที่</th>
                <th class="px-3 py-2 text-left w-[35%] font-bold">รายละเอียด</th>
                <th class="px-3 py-2 text-center w-20 font-bold">เมนู</th>
                <th class="px-3 py-2 text-left w-[30%] font-bold">จัดการสถานะ</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-slate-100">
            @foreach($items as $item)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-3 py-3 text-center align-top">
                        @php $d = ($type == 'history') ? $item->updated_at : $item->created_at; @endphp
                        <div class="font-bold text-slate-700">{{ $d->format('d/m/y') }}</div>
                        <div class="text-[10px] text-slate-400">{{ $d->format('H:i') }}</div>
                    </td>

                    <td class="px-3 py-3 align-top">
                        <div class="flex flex-wrap gap-1 mb-1">
                            <span class="px-1.5 py-0.5 rounded text-[9px] font-bold border {{ $item->zone ? 'bg-teal-50 text-teal-700 border-teal-200' : 'bg-red-50 text-red-600 border-red-200 animate-pulse' }}">
                                {{ $item->zone ? '📍 เขต '.$item->zone : '⚠️ ระบุเขต' }}
                            </span>

                            @if($item->user && $item->user->role == 'council_member')
                                <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">สท. {{ $item->user->first_name }}</span>
                            @elseif(!$item->user_id)
                                <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-pink-50 text-pink-700 border border-pink-200">💳 {{ $item->citizen_id }}</span>
                            @endif
                        </div>
                        
                        <div class="font-bold text-slate-800 truncate w-full">{{ $item->subject }}</div>
                        <div class="text-[10px] text-slate-500 mb-1">👤 {{ $item->first_name }} {{ $item->last_name }}</div>
                        <p class="text-slate-600 bg-slate-50 p-1.5 rounded border border-slate-100 line-clamp-2 leading-relaxed">
                            {{ Str::limit($item->details, 80) }}
                        </p>
                        
                        @if($item->admin_notes)
                            <div class="mt-1 text-[10px] text-orange-600 bg-orange-50 p-1 rounded border border-orange-100 truncate">
                                📝 {{ $item->admin_notes }}
                            </div>
                        @endif
                    </td>

                    <td class="px-3 py-3 text-center align-top">
                        <div class="flex flex-col gap-1">
                            <a href="{{ route('complaints.show', $item->id) }}" class="px-2 py-0.5 rounded border border-slate-200 text-slate-600 hover:bg-slate-100">🔎</a>
                            <a href="{{ route('admin.complaints.download', $item->id) }}" class="px-2 py-0.5 rounded border border-blue-200 text-blue-600 hover:bg-blue-50">📥</a>
                            <a href="{{ route('complaints.edit', $item->id) }}" class="px-2 py-0.5 rounded border border-yellow-300 bg-yellow-50 text-yellow-700 hover:bg-yellow-100">✏️</a>
                        </div>
                    </td>

                    <td class="px-3 py-3 align-top">
                        @if($item->user_edit_note) 
                            <div class="mb-1 text-[9px] text-red-600 font-bold animate-pulse">⚠️ มีการแก้ไข</div> 
                        @endif

                        @if($type === 'history')
                            <form action="{{ route('admin.complaints.destroy', $item->id) }}" method="POST" onsubmit="return confirm('ลบ?');">
                                @csrf @method('DELETE')
                                <button class="w-full py-1 bg-white border border-red-200 text-red-600 rounded hover:bg-red-50 text-[10px]">🗑️ ลบ</button>
                            </form>
                        @else
                            <form action="{{ route('admin.complaints.process', $item->id) }}" method="POST" class="flex flex-col gap-1">
                                @csrf
                                <div class="flex gap-1">
                                    <select name="zone" class="w-1/2 text-[10px] p-0.5 border-slate-300 rounded">
                                        <option value="" {{ !$item->zone ? 'selected' : '' }}>เขต?</option>
                                        <option value="1" {{ $item->zone == '1' ? 'selected' : '' }}>1</option>
                                        <option value="2" {{ $item->zone == '2' ? 'selected' : '' }}>2</option>
                                        <option value="3" {{ $item->zone == '3' ? 'selected' : '' }}>3</option>
                                    </select>
                                    <button type="submit" name="status" value="{{ $item->status }}" class="w-1/2 bg-slate-100 border border-slate-300 text-slate-600 rounded hover:bg-slate-200 text-[9px]">💾 บันทึก</button>
                                </div>
                                <textarea name="admin_notes" rows="1" class="w-full text-[10px] border-slate-300 rounded p-1" placeholder="บันทึก..."></textarea>
                                
                                <div class="flex gap-1 mt-0.5">
                                    @if($type === 'pending')
                                        <button type="submit" name="status" value="waiting" class="flex-1 bg-green-500 text-white py-1 rounded hover:bg-green-600">รับเรื่อง</button>
                                        <button type="submit" name="status" value="rejected" class="px-2 bg-red-100 text-red-600 border border-red-200 rounded hover:bg-red-200">❌</button>
                                    @elseif($type === 'waiting')
                                        <button type="submit" name="status" value="in_progress" class="w-full bg-blue-600 text-white py-1 rounded hover:bg-blue-700">🚀 เริ่มงาน</button>
                                    @elseif($type === 'in_progress')
                                        <button type="submit" name="status" value="completed" class="flex-1 bg-green-500 text-white py-1 rounded hover:bg-green-600">✅ เสร็จ</button>
                                        <button type="submit" name="status" value="unsuccessful" class="px-2 bg-purple-100 text-purple-700 border border-purple-200 rounded hover:bg-purple-200">⚠️</button>
                                    @endif
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
    <div class="py-8 text-center text-slate-400 text-xs">ไม่มีรายการ</div>
@endif