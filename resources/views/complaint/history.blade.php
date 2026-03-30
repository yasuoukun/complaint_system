@if(optional(Auth::user())->role == 'ems')
    <script>
        window.location.href = "https://requestion-chaiyaphum-municipality.banyongservice.com/login"; 
        // ⚠️ เดี๋ยวก่อน! ตรงนี้มีปัญหาเรื่อง Token (อ่านคำเตือนด้านล่าง)
    </script>
    <p>กำลังนำท่านเข้าสู่ระบบ... กรุณารอสักครู่</p>

@else
   <x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('ประวัติการยื่นคำร้อง') }}
            </h2>
            
            <a href="{{ route('complaints.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 text-sm font-bold shadow-sm transition transform hover:scale-105">
                เขียนคำร้องใหม่
            </a>
        </div>
    </x-slot>

    {{-- ✅ CSS บังคับการแสดงผล --}}
    <style>
        .mobile-view { display: block !important; }
        .pc-view { display: none !important; }
        @media (min-width: 1024px) { 
            .mobile-view { display: none !important; }
            .pc-view { display: block !important; }
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded border border-green-200 shadow-sm flex items-center gap-2 mx-4 sm:mx-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded border border-red-200 shadow-sm flex items-center gap-2 mx-4 sm:mx-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-blue-500 mx-4 sm:mx-0">
                <div class="p-4 sm:p-6 text-gray-900">
                    
                    @if($complaints->count() > 0)
                        
                        {{-- ========================================================= --}}
                        {{-- 📱 MOBILE VIEW --}}
                        {{-- ========================================================= --}}
                        <div class="mobile-view space-y-4">
                            @foreach($complaints as $item)
                                @php
                                    $expireTime = $item->created_at->addMinutes(10);
                                    $canEdit = $item->status == 'pending' && $expireTime->isFuture();
                                @endphp

                                <div class="bg-white border border-gray-200 rounded-lg shadow-sm relative overflow-hidden">
                                    <div class="absolute left-0 top-0 bottom-0 w-2 
                                        @if($item->status == 'pending') bg-yellow-400
                                        @elseif($item->status == 'waiting') bg-orange-400
                                        @elseif($item->status == 'in_progress') bg-blue-500
                                        @elseif($item->status == 'completed') bg-green-500
                                        @else bg-red-500 @endif">
                                    </div>

                                    <div class="p-4 pl-5">
                                        {{-- หัวข้อ --}}
                                        <div class="flex justify-between items-start mb-3 border-b border-gray-100 pb-2">
                                            <div>
                                                <div class="text-xs text-gray-500 mb-1">
                                                    📅 {{ $item->created_at->format('d/m/Y') }} 
                                                    ⏰ {{ $item->created_at->format('H:i') }} น.
                                                </div>
                                                <h3 class="font-bold text-lg text-blue-900 leading-tight">
                                                    {{ $item->subject }}
                                                </h3>
                                            </div>
                                        </div>

                                        {{-- รายละเอียด --}}
                                        <div class="mb-3 text-sm text-gray-600 bg-gray-50 p-3 rounded border border-gray-100">
                                            <span class="font-bold text-gray-800">รายละเอียด:</span> 
                                            {{ Str::limit($item->details, 100) }}
                                        </div>

                                        {{-- สถานะ --}}
                                        <div class="mb-3 flex items-center flex-wrap gap-2">
                                            @if($item->status == 'pending') 
                                                <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded font-bold border border-yellow-200">⏳ รอตรวจสอบ</span>
                                            @elseif($item->status == 'waiting') 
                                                <span class="bg-orange-100 text-orange-800 text-xs px-2 py-1 rounded font-bold border border-orange-200">📋 รับเรื่องแล้ว</span>
                                            @elseif($item->status == 'in_progress') 
                                                <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded font-bold border border-blue-200">🔧 กำลังดำเนินการ</span>
                                            @elseif($item->status == 'completed') 
                                                <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded font-bold border border-green-200">✅ เสร็จสิ้น</span>
                                            @else 
                                                <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded font-bold border border-red-200">❌ ไม่อนุมัติ</span>
                                            @endif
                                        </div>

                                        {{-- ความเห็น จนท. --}}
                                        @if($item->admin_notes)
                                            <div class="mb-3 p-3 bg-yellow-50 rounded border border-yellow-100 text-sm text-yellow-900">
                                                <div class="font-bold text-xs text-yellow-700 mb-1">📢 ความเห็นเจ้าหน้าที่:</div>
                                                {{ $item->admin_notes }}
                                            </div>
                                        @endif

                                        {{-- ปุ่มดำเนินการ --}}
                                        <div class="grid {{ $canEdit ? 'grid-cols-2' : 'grid-cols-1' }} gap-2">
                                            <a href="{{ route('complaints.show', $item->id) }}" class="text-center bg-white border border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white py-2 rounded-lg text-sm font-bold transition shadow-sm">
                                                🔎 ดูรายละเอียด
                                            </a>
                                            
                                            @if($canEdit)
                                                <a href="{{ route('complaints.edit', $item->id) }}" class="flex flex-col justify-center items-center bg-yellow-50 border border-yellow-400 text-yellow-700 hover:bg-yellow-100 py-1 rounded-lg text-sm font-bold transition shadow-sm">
                                                    <span>✏️ แก้ไข</span>
                                                    {{-- 🕒 ตัวนับเวลาถอยหลัง (Mobile) --}}
                                                    <span class="live-timer text-[10px] text-red-600 font-extrabold" data-expire="{{ $expireTime->timestamp * 1000 }}"></span>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            
                            @if(method_exists($complaints, 'links'))
                                <div class="mt-4">{{ $complaints->links() }}</div>
                            @endif
                        </div>

                        {{-- ========================================================= --}}
                        {{-- 🖥️ PC VIEW: ตาราง --}}
                        {{-- ========================================================= --}}
                        <div class="pc-view overflow-x-auto">
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
                                        @php
                                            $expireTime = $item->created_at->addMinutes(10);
                                            $canEdit = $item->status == 'pending' && $expireTime->isFuture();
                                        @endphp
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
                                                    
                                                    @if($canEdit)
                                                        <a href="{{ route('complaints.edit', $item->id) }}" class="inline-flex items-center gap-1 text-xs bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full border border-yellow-300 hover:bg-yellow-200 transition">
                                                            ✏️ แก้ไข 
                                                            {{-- 🕒 ตัวนับเวลาถอยหลัง (PC) --}}
                                                            <span class="live-timer font-bold text-red-600 ml-1" data-expire="{{ $expireTime->timestamp * 1000 }}"></span>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>

                                            <td class="py-3 px-4 text-center">
                                                @if($item->status == 'pending') <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-bold border border-yellow-200 inline-block">⏳ รอตรวจสอบ</span>
                                                @elseif($item->status == 'waiting') <span class="px-3 py-1 bg-orange-100 text-orange-800 rounded-full text-xs font-bold border border-orange-200 inline-block">📋 รับเรื่องแล้ว</span>
                                                @elseif($item->status == 'in_progress') <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-bold border border-blue-200 inline-block animate-pulse">🔧 กำลังดำเนินการ</span>
                                                @elseif($item->status == 'completed') <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold border border-green-200 inline-block">✅ เสร็จสิ้น</span>
                                                @else <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-bold border border-red-200 inline-block">❌ ไม่อนุมัติ</span>
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
                            
                            @if(method_exists($complaints, 'links'))
                                <div class="mt-4">{{ $complaints->links() }}</div>
                            @endif
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

    {{-- ✅ Javascript นับถอยหลัง (Real-time Timer) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function updateTimers() {
                const now = new Date().getTime();
                
                document.querySelectorAll('.live-timer').forEach(element => {
                    const expireTime = parseInt(element.getAttribute('data-expire'));
                    const distance = expireTime - now;

                    if (distance < 0) {
                        element.innerHTML = "(หมดเวลา)";
                        // ถ้าอยากให้ปุ่มหายไปเลยเมื่อหมดเวลา ให้เอา comment ด้านล่างออก
                        // element.closest('a').style.display = 'none'; 
                    } else {
                        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                        
                        // เติมเลข 0 ข้างหน้าถ้าหลักเดียว เช่น 09:05
                        const minStr = minutes < 10 ? "0" + minutes : minutes;
                        const secStr = seconds < 10 ? "0" + seconds : seconds;
                        
                        element.innerHTML = `(${minStr}:${secStr})`;
                    }
                });
            }

            // อัปเดตทุก 1 วินาที
            setInterval(updateTimers, 1000);
            updateTimers(); // เรียกทำงานครั้งแรกทันที
        });
    </script>
</x-app-layout>
@endif
