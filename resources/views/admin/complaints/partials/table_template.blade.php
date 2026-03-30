{{-- ✅ CSS บังคับการแสดงผล แยก Mobile/PC เด็ดขาด (ห้ามลบ) --}}
<style>
    .mobile-only-view { display: block; }
    .pc-only-view { display: none; }
    @media (min-width: 768px) {
        .mobile-only-view { display: none !important; }
        .pc-only-view { display: block !important; }
    }
</style>

@if($items->count() > 0)

    {{-- ========================================================= --}}
    {{-- 📱 ส่วนที่ 1: MOBILE VIEW (Compact + แก้ไขเบอร์โทร) --}}
    {{-- ========================================================= --}}
    <div class="mobile-only-view space-y-3 p-2 pb-8">
        @foreach($items as $item)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 relative overflow-hidden">
                {{-- แถบสีด้านซ้าย --}}
                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-{{ $color ?? 'blue' }}-500"></div>
                
                <div class="p-3 pl-5">
                    {{-- 1. ส่วนหัว (Header) --}}
                    <div class="flex justify-between items-center mb-2 border-b border-gray-100 pb-2">
                        <span class="bg-gray-100 text-gray-600 text-[10px] px-2 py-0.5 rounded-full font-bold">
                            #{{ $item->id }}
                        </span>
                        <div class="text-[10px] text-gray-400">
                            🕒 <span class="text-gray-600 font-bold">{{ $item->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>

                    {{-- 2. หัวข้อเรื่อง --}}
                    <h4 class="text-sm font-bold text-blue-900 leading-tight mb-2 line-clamp-2">
                        {{ $item->subject }}
                    </h4>

                    {{-- 3. ข้อมูลผู้แจ้ง --}}
                    <div class="text-xs text-gray-600 space-y-1.5 mb-2 pl-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <span>👤 {{ $item->first_name }} {{ $item->last_name }}</span>
                            
                            {{-- เลขบัตร (สีเหมือน PC) --}}
                            @if(!$item->user_id && $item->citizen_id)
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-pink-100 text-pink-800 border border-pink-200">
                                    💳 บัตร: {{ $item->citizen_id }}
                                </span>
                            @elseif($item->user && $item->user->role == 'council_member')
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-100 text-indigo-800 border border-indigo-200">
                                    สท. {{ $item->user->first_name }}
                                </span>
                            @endif
                        </div>
                        
                        {{-- ✅ แก้ไข: เบอร์โทรเป็นตัวหนังสือธรรมดา (เอาลิงก์ออก) --}}
                        <div>
                             📞 {{ $item->phone_number }}
                        </div>

                        @if($item->zone)
                            <div class="pt-0.5">
                                <span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-bold bg-teal-50 text-teal-700 border border-teal-100">
                                    📍 เขต {{ $item->zone }}
                                </span>
                            </div>
                        @endif
                    </div>

                    {{-- 4. รายละเอียด --}}
                    <div class="bg-gray-50 p-2 rounded-md text-xs text-gray-500 border border-gray-100 leading-snug mb-3">
                        <span class="font-bold text-gray-700 mr-1">รายละเอียด:</span>
                        {{ Str::limit($item->details, 80) }}
                    </div>

                    {{-- 5. ปุ่มเมนูจัดการ --}}
                    <div class="flex gap-2 mb-3">
                        <a href="{{ route('complaints.show', $item->id) }}" class="flex-1 text-center bg-white border border-gray-300 text-gray-700 text-[11px] py-1 rounded hover:bg-gray-50">🔎 ดู</a>
                        <a href="{{ route('admin.complaints.download', $item->id) }}" class="flex-1 text-center bg-blue-50 border border-blue-200 text-blue-600 text-[11px] py-1 rounded hover:bg-blue-100 font-medium">📥 Word</a>
                        <a href="{{ route('complaints.edit', $item->id) }}" class="flex-1 text-center bg-yellow-50 border border-yellow-200 text-yellow-700 text-[11px] py-1 rounded hover:bg-yellow-100 font-medium">✏️ แก้ไข</a>
                    </div>

                    {{-- 6. ฟอร์มจัดการสถานะ --}}
                    <div class="bg-slate-50 -mx-3 -mb-3 px-3 py-3 border-t border-slate-200">
                        @if($type === 'history')
                            <form action="{{ route('admin.complaints.destroy', $item->id) }}" method="POST" onsubmit="return confirm('ยืนยันการลบ?');">
                                @csrf @method('DELETE')
                                <button class="w-full bg-white border border-red-200 text-red-600 text-xs py-2 rounded-md font-bold hover:bg-red-50 flex items-center justify-center gap-1">
                                    🗑️ ลบข้อมูลถาวร
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.complaints.process', $item->id) }}" method="POST" class="space-y-2">
                                @csrf
                                <div class="flex gap-2">
                                    <select name="zone" class="w-1/3 text-[11px] border-gray-300 rounded h-8 bg-white p-0 pl-1 font-medium text-gray-700">
                                        <option value="" {{ !$item->zone ? 'selected' : '' }}>- เลือก -</option>
                                        <option value="1" {{ $item->zone == '1' ? 'selected' : '' }}>เขต 1</option>
                                        <option value="2" {{ $item->zone == '2' ? 'selected' : '' }}>เขต 2</option>
                                        <option value="3" {{ $item->zone == '3' ? 'selected' : '' }}>เขต 3</option>
                                    </select>
                                    
                                    <input type="text" name="admin_notes" class="w-2/3 text-xs border-gray-300 rounded h-8 px-2 bg-white" placeholder="บันทึกผล..." value="{{ $item->admin_notes }}">
                                </div>

                                <input type="text" name="responsible_dept" class="w-full text-xs border-gray-300 rounded h-8 px-2 bg-white mt-2" placeholder="ระบุกองที่รับผิดชอบ (เช่น กองช่าง, กองสาธารณสุข...)" value="{{ $item->responsible_dept }}">

                                <div class="flex gap-2 pt-1">
                                    @if($type === 'pending')
                                        <button type="submit" name="status" value="waiting" class="flex-1 bg-green-500 text-white text-xs py-1.5 rounded font-bold hover:bg-green-600">✅ รับเรื่อง</button>
                                        <button type="submit" name="status" value="rejected" class="bg-red-100 text-red-600 border border-red-200 text-xs px-3 py-1.5 rounded font-bold hover:bg-red-50">❌ ปฏิเสธ</button>
                                    @elseif($type === 'waiting')
                                        <button type="submit" name="status" value="in_progress" class="flex-1 bg-blue-600 text-white text-xs py-1.5 rounded font-bold hover:bg-blue-700">🚀 เริ่มดำเนินการ</button>
                                    @elseif($type === 'in_progress')
                                        <button type="submit" name="status" value="completed" class="flex-1 bg-green-500 text-white text-xs py-1.5 rounded font-bold hover:bg-green-600">✅ เสร็จสิ้น</button>
                                        <button type="submit" name="status" value="unsuccessful" class="bg-purple-100 text-purple-700 border border-purple-200 text-xs px-3 py-1.5 rounded font-bold hover:bg-purple-50">⚠️</button>
                                    @endif
                                </div>
                                
                                <button type="submit" name="status" value="{{ $item->status }}" 
    class="w-full bg-slate-700 text-white text-xs font-bold py-2 rounded shadow-sm hover:bg-slate-800 transition mt-2 border border-slate-600">
    💾 บันทึก (คงสถานะเดิม)
</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
        
        @if(method_exists($items, 'links'))
            <div class="pt-2 px-1">{{ $items->links() }}</div>
        @endif
    </div>


    {{-- ========================================================= --}}
    {{-- 🖥️ ส่วนที่ 2: PC VIEW (คงเดิม 100% ไม่เปลี่ยนแปลง) --}}
    {{-- ========================================================= --}}
    <div class="pc-only-view">
        <div class="overflow-x-auto">
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

                                    <textarea name="admin_notes" rows="1" class="w-full text-[11px] border-gray-300 rounded p-1" placeholder="บันทึกผล...">{{ $item->admin_notes }}</textarea>

                                    <input type="text" name="responsible_dept" class="w-full text-[11px] border-gray-300 rounded p-1 mb-2 mt-1" placeholder="ระบุกองที่รับผิดชอบ (เช่น กองช่าง...)" value="{{ $item->responsible_dept }}">
                                    
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
        </div>
    </div>

@else
    <div class="py-8 text-center text-gray-400 text-sm border-2 border-dashed border-gray-200 rounded-lg bg-gray-50 m-2">ไม่มีข้อมูลในส่วนนี้</div>
@endif