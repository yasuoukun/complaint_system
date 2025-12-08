<div class="overflow-x-auto">
    @if($items->count() > 0)
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider w-28">อัปเดตล่าสุด</th>
                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">รายละเอียดคำร้อง</th>
                <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider w-24">เมนู</th>
                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-[35%]">การจัดการ</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($items as $item)
            <tr class="hover:bg-gray-50 transition">
                
                <td class="px-4 py-4 text-center align-top">
                    <div class="text-sm font-bold text-gray-700">{{ $item->updated_at->format('d/m/Y') }}</div>
                    <div class="text-[10px] text-gray-500 bg-gray-100 rounded px-1 inline-block mt-1">⏰ {{ $item->updated_at->format('H:i') }}</div>
                </td>

                <td class="px-4 py-4 align-top">
                    <div class="flex flex-wrap gap-2 mb-2">
                        @if($item->zone) 
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-teal-100 text-teal-800 border border-teal-200">📍 เขต {{ $item->zone }}</span>
                        @else 
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-700 border border-red-200 animate-pulse">⚠️ ระบุเขต</span>
                        @endif

                        @if($item->user && $item->user->role == 'council_member')
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-100 text-indigo-800 border border-indigo-200">สท. {{ $item->user->first_name }}</span>
                        @elseif(!$item->user_id)
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-pink-100 text-pink-800 border border-pink-200">
                                💳 บัตร: {{ $item->citizen_id }}
                            </span>
                        @endif
                    </div>

                    <div class="text-sm font-bold text-blue-900 mb-1">{{ $item->subject }}</div>
                    <div class="text-xs text-gray-500 mb-2">
                        👤 {{ $item->first_name }} {{ $item->last_name }} | 📞 {{ $item->phone_number }}
                    </div>
                    
                    <div class="text-[10px] text-gray-400 mb-1">📅 แจ้งเมื่อ: {{ $item->created_at->format('d/m/Y H:i') }}</div>

                    <div class="text-xs text-gray-600 bg-gray-50 p-2 rounded border border-gray-200">
                        {{ Str::limit($item->details, 100) }}
                    </div>
                    
                    @if($item->admin_notes)
                        <div class="mt-2 text-[10px] text-gray-500">
                            <span class="font-bold text-orange-600">Note:</span> {{ $item->admin_notes }}
                        </div>
                    @endif
                </td>

                <td class="px-4 py-4 text-center align-top">
                    <div class="flex flex-col gap-1">
                        <a href="{{ route('complaints.show', $item->id) }}" class="text-[10px] bg-white border border-gray-300 px-2 py-1 rounded hover:bg-gray-100">🔎 ดู</a>
                        <a href="{{ route('admin.complaints.download', $item->id) }}" class="text-[10px] bg-blue-50 border border-blue-200 text-blue-600 px-2 py-1 rounded hover:bg-blue-100">📥 Word</a>
                        <a href="{{ route('complaints.edit', $item->id) }}" class="text-[10px] bg-yellow-50 border border-yellow-200 text-yellow-700 px-2 py-1 rounded hover:bg-yellow-100 font-bold">✏️ แก้ไข</a>
                    </div>
                </td>

                <td class="px-4 py-4 align-top">
                    @if($item->user_edit_note) 
                        <div class="mb-2 text-[10px] text-red-600 bg-red-50 p-1 rounded border border-red-100 animate-pulse">⚠️ มีการแก้ไขข้อมูล</div> 
                    @endif

                    @if($type === 'history')
                        <form action="{{ route('admin.complaints.destroy', $item->id) }}" method="POST" onsubmit="return confirm('ลบ?');">
                            @csrf @method('DELETE')
                            <button class="w-full bg-white border border-red-200 text-red-600 text-[10px] py-1.5 rounded hover:bg-red-50">🗑️ ลบ</button>
                        </form>
                    @else
                        <form action="{{ route('admin.complaints.process', $item->id) }}" method="POST" class="flex flex-col gap-2">
                            @csrf
                            <select name="zone" class="text-[10px] w-full border-gray-300 rounded py-0.5 px-1 bg-white h-7 focus:ring-blue-500">
                                <option value="" {{ !$item->zone ? 'selected' : '' }}>-- เลือกเขต --</option>
                                <option value="1" {{ $item->zone == '1' ? 'selected' : '' }}>เขต 1</option>
                                <option value="2" {{ $item->zone == '2' ? 'selected' : '' }}>เขต 2</option>
                                <option value="3" {{ $item->zone == '3' ? 'selected' : '' }}>เขต 3</option>
                            </select>

                            <textarea name="admin_notes" rows="1" class="w-full text-[11px] border-gray-300 rounded p-1" placeholder="บันทึกผล..."></textarea>
                            
                            <div class="flex gap-1">
                                @if($type === 'pending')
                                    <button type="submit" name="status" value="waiting" class="flex-1 bg-green-500 text-white text-[10px] py-1 rounded font-bold hover:bg-green-600">✅ รับ</button>
                                    <button type="submit" name="status" value="rejected" class="bg-red-100 text-red-600 text-[10px] px-2 py-1 rounded font-bold border border-red-200 hover:bg-red-200">❌</button>
                                @elseif($type === 'waiting')
                                    <button type="submit" name="status" value="in_progress" class="w-full bg-blue-600 text-white text-[10px] py-1 rounded font-bold hover:bg-blue-700">🚀 เริ่มงาน</button>
                                @elseif($type === 'in_progress')
                                    <button type="submit" name="status" value="completed" class="flex-1 bg-green-500 text-white text-[10px] py-1 rounded font-bold hover:bg-green-600">✅ เสร็จ</button>
                                    <button type="submit" name="status" value="unsuccessful" class="bg-purple-100 text-purple-700 text-[10px] px-2 py-1 rounded font-bold hover:bg-purple-200">⚠️</button>
                                @endif
                            </div>
                            <button type="submit" name="status" value="{{ $item->status }}" class="w-full bg-gray-100 text-gray-600 text-[10px] py-1 rounded hover:bg-gray-200 border border-gray-300">💾 บันทึกข้อมูล</button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
        <div class="py-8 text-center text-gray-400 text-sm">ไม่มีข้อมูลในส่วนนี้</div>
    @endif
</div>