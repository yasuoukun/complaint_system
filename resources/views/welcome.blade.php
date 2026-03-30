@if(optional(Auth::user())->role == 'ems')
    <script>
        window.location.href = "https://cpmcare.banyongservice.com/welcome"; 
        // ⚠️ เดี๋ยวก่อน! ตรงนี้มีปัญหาเรื่อง Token (อ่านคำเตือนด้านล่าง)
    </script>
    <p>กำลังนำท่านเข้าสู่ระบบ... กรุณารอสักครู่</p>

@else
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <meta name="color-scheme" content="light">
    <meta name="supported-color-schemes" content="light">

    <title>ศูนย์รับเรื่องร้องทุกข์ - เทศบาลเมืองชัยภูมิ</title>
    <link rel="icon" type="image/png" href="{{ asset('storage/system/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        /* ✅ บังคับ CSS ให้เป็น Light Mode เสมอ */
        :root {
            color-scheme: light;
        }
        body { 
            font-family: 'Sarabun', sans-serif;
            background-color: #f8fafc !important;
            color: #1e293b !important;
        }

        /* ✅ CSS พิเศษ: ปรับตำแหน่งกล่องชื่อนายก (แก้ที่นี่จุดเดียวจบ) */
        .mayor-popup {
            bottom: 2.5rem; /* มือถือ: อยู่ล่างปกติ (bottom-10) */
        }
        @media (min-width: 1024px) {
            .mayor-popup {
                /* 👇 PC: ปรับความสูงตรงนี้ (130px คือสูงกว่าคลื่นนิดหน่อยกำลังดี) */
                bottom: 130px !important; 
            }
        }
        
        /* --- Custom Animations --- */
        @keyframes blob-float {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob { animation: blob-float 10s infinite ease-in-out; }
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }

        @keyframes float-slow {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }
        .animate-float-slow { animation: float-slow 6s ease-in-out infinite; }

        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s cubic-bezier(0.5, 0, 0, 1);
        }
        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }
        .reveal-delay-100 { transition-delay: 0.1s; }
        .reveal-delay-200 { transition-delay: 0.2s; }
        .reveal-delay-300 { transition-delay: 0.3s; }

        .glass-effect {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .glass-card-dark {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .text-gradient-gold {
            background: linear-gradient(to right, #FCD34D, #F59E0B, #D97706);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-size: 200% auto;
            animation: gradient-shift 5s linear infinite;
        }
        @keyframes gradient-shift {
            0% { background-position: 0% center; }
            100% { background-position: 200% center; }
        }

        .slide-item { transition: opacity 0.8s ease-in-out, transform 0.8s ease-in-out; }
        .slide-item.inactive { transform: scale(1.05); }
    </style>
</head>

<body class="antialiased bg-slate-50 text-slate-800 overflow-x-hidden" 
      style="background-color: #f8fafc; color: #1e293b;"
      x-data="{ scrolled: false }" 
      @scroll.window="scrolled = (window.pageYOffset > 20)">

    <nav :class="{ 'bg-white/90 backdrop-blur-md shadow-md border-b border-gray-100 py-2': scrolled, 'bg-transparent py-4': !scrolled }" class="fixed w-full z-50 top-0 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                
                <div class="flex items-center gap-2 sm:gap-3 group shrink-0">
                    <div class="relative">
                        <div class="absolute -inset-1 bg-yellow-400 rounded-full blur opacity-0 group-hover:opacity-30 transition duration-500"></div>
                        <img src="{{ asset('storage/system/logo.png') }}" alt="Logo" class="h-10 w-auto sm:h-12 drop-shadow hover:scale-105 transition">
                    </div>
                    <div class="flex flex-col">
                        <span class="text-lg font-extrabold tracking-tight leading-none transition duration-300" :class="scrolled ? 'text-blue-900' : 'text-white'">เทศบาลเมืองชัยภูมิ</span>
                        <span class="text-[10px] font-bold text-yellow-500 uppercase tracking-widest mt-0.5">Smart Complaint</span>
                    </div>
                </div>
                
                <div class="flex items-center gap-2 sm:gap-3">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-5 py-2 text-sm font-bold text-white bg-blue-900 rounded-full hover:bg-blue-800 shadow-md transition hover:shadow-lg hover:-translate-y-0.5">
                                เข้าสู่ระบบแล้ว
                            </a>
                        @else
                            <div class="hidden sm:flex items-center gap-3">
                                <a href="{{ route('login') }}" class="text-sm font-bold transition px-4 hover:-translate-y-0.5" :class="scrolled ? 'text-gray-500 hover:text-blue-900' : 'text-blue-100 hover:text-white'">
                                    เข้าสู่ระบบ
                                </a>
                                <a href="{{ route('register') }}" class="relative group overflow-hidden px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-900 text-white text-sm font-bold rounded-full shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                                    <span class="relative z-10 flex items-center gap-2">
                                        <span>ลงทะเบียน</span>
                                        <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                    </span>
                                    <div class="absolute inset-0 h-full w-full scale-0 rounded-full transition-all duration-300 group-hover:scale-150 group-hover:bg-blue-800/30"></div>
                                </a>
                            </div>

                            <div class="sm:hidden relative" x-data="{ open: false }">
                                <button @click="open = !open" class="flex items-center gap-2 px-4 py-2 bg-blue-900 text-white rounded-full text-xs font-bold shadow-md active:scale-95 transition hover:bg-blue-800">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    <span>เมนูสมาชิก</span>
                                    <svg class="w-3 h-3 transition-transform duration-300" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>

                                <div x-show="open" 
                                     @click.away="open = false"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                     x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                                     class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden z-50 origin-top-right">
                                     
                                    <div class="py-1">
                                        <a href="{{ route('login') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-900 font-bold border-b border-gray-100">
                                            🔑 เข้าสู่ระบบ
                                        </a>
                                        <a href="{{ route('register') }}" class="block px-4 py-3 text-sm text-blue-600 hover:bg-blue-50 font-bold">
                                            📝 ลงทะเบียน / แจ้งเรื่อง
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <div class="relative min-h-[600px] lg:min-h-[800px] flex items-center pt-20 overflow-hidden bg-blue-950 z-10">
        <div class="absolute inset-0 z-0">
            <div class="absolute top-0 -left-40 w-96 h-96 bg-blue-600/30 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob"></div>
            <div class="absolute top-0 -right-40 w-96 h-96 bg-yellow-500/20 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-40 left-20 w-96 h-96 bg-indigo-600/30 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-4000"></div>

            <img src="{{ asset('images/bg-building.jpg') }}" class="absolute inset-0 w-full h-full object-cover opacity-20 mix-blend-overlay" alt="Building" style="transform: scale(1.1);">
            <div class="absolute inset-0 bg-gradient-to-b from-blue-950/80 via-blue-950/90 to-blue-900"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-30">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-0 lg:gap-12 items-center">
                
                <div class="lg:col-span-7 text-center lg:text-left pt-8 lg:pt-0">
                    <div class="reveal inline-block px-4 py-2 bg-white/10 border border-white/20 rounded-full text-blue-100 text-sm font-bold mb-6 backdrop-blur-md shadow-lg animate-float-slow">
                        <span class="mr-2">🚀</span> ยกระดับบริการเพื่อประชาชน
                    </div>
                    <h1 class="reveal reveal-delay-100 text-4xl md:text-7xl font-black text-white leading-[1.1] mb-6 drop-shadow-2xl">
                        รับฟัง <span class="text-gradient-gold">ทุกปัญหา</span><br>
                        พัฒนาเมืองชัยภูมิ
                    </h1>
                    <p class="reveal reveal-delay-200 text-lg md:text-xl text-blue-100/80 mb-8 lg:mb-12 font-light leading-relaxed max-w-2xl mx-auto lg:mx-0">
                        ระบบร้องเรียนออนไลน์โฉมใหม่ ใช้งานง่าย ติดตามผลได้จริง 
                        <span class="block mt-2 text-white/90 font-normal">โปร่งใส ฉับไว ใส่ใจทุกเสียงของคุณ</span>
                    </p>

                    <div class="reveal reveal-delay-300 flex flex-col sm:flex-row gap-4 justify-center lg:justify-start relative z-50">
                        <a href="{{ route('register') }}" class="group relative overflow-hidden px-8 py-4 bg-gradient-to-r from-yellow-400 to-yellow-500 text-blue-900 font-extrabold rounded-2xl shadow-xl transition-all duration-300 hover:scale-105 hover:shadow-yellow-500/40 flex items-center justify-center gap-3">
                            <span class="relative z-10 flex items-center gap-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                เขียนคำร้อง (สมาชิก)
                            </span>
                            <div class="absolute inset-0 h-full w-full bg-gradient-to-r from-yellow-300 to-yellow-400 scale-0 rounded-2xl transition-all duration-300 group-hover:scale-150 group-hover:opacity-50"></div>
                        </a>
                        <a href="{{ route('guest.complaint.create') }}" class="px-8 py-4 bg-white/5 border-2 border-white/20 text-white font-bold rounded-2xl backdrop-blur-md transition-all duration-300 hover:bg-white/15 hover:border-white/40 hover:scale-105 flex items-center justify-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            แจ้งเรื่องทั่วไป(ไม่ล็อกอิน)
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-5 relative h-[450px] lg:h-[800px] flex items-end justify-center pointer-events-none -mt-16 lg:-mt-36">
                    <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-full h-[60%] bg-gradient-to-t from-blue-600/60 via-blue-800/20 to-transparent blur-3xl"></div>
                    
                    <img src="{{ asset('images/mayor.png') }}?v=2" alt="นายกเทศมนตรี" class="reveal reveal-delay-200 relative z-20 w-auto h-auto max-h-[420px] lg:max-h-[750px] object-contain object-bottom drop-shadow-[0_20px_50px_rgba(0,0,0,0.5)] transition duration-1000 hover:scale-[1.02]">
                    
                    <div class="mayor-popup reveal reveal-delay-300 absolute -left-2 md:-left-4 lg:-left-20 z-30 animate-float-slow pointer-events-auto">
    
                        <div class="glass-card-dark p-3 md:p-5 rounded-2xl md:rounded-3xl shadow-2xl border-l-[3px] md:border-l-4 border-yellow-500 flex items-center gap-3 md:gap-4 pr-4 md:pr-8 transform transition hover:scale-105 hover:-rotate-2">
                            
                            <div class="h-10 w-10 md:h-14 md:w-14 shrink-0 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-full flex items-center justify-center text-white font-bold shadow-lg">
                                <svg class="w-5 h-5 md:w-7 md:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            
                            <div>
                                <p class="text-white font-bold text-sm md:text-lg leading-tight">
                                    นายบรรยงค์ <br class="md:hidden">เกียรติก้องชูชัย
                                </p>
                                <p class="text-blue-300 text-[9px] md:text-xs uppercase tracking-wide mt-0.5 md:mt-1">
                                    นายกเทศมนตรีเมืองชัยภูมิ
                                </p>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-none z-20">
            <svg class="relative block w-[calc(100%+1.3px)] h-20 md:h-32 text-gray-50 fill-current" viewBox="0 0 1440 120" preserveAspectRatio="none">
                <path d="M0,64L48,80C96,96,192,128,288,128C384,128,480,96,576,85.3C672,75,768,85,864,96C960,107,1056,117,1152,112C1248,107,1344,85,1392,74.7L1440,64V120H1392C1344,120,1248,120,1152,120C1056,120,960,120,864,120C768,120,672,120,576,120C480,120,384,120,288,120C192,120,96,120,48,120H0V64Z"></path>
            </svg>
        </div>
    </div>

    @if(isset($banners) && $banners->count() > 0)
    <div class="py-8 md:py-20 bg-gray-50 relative z-20 -mt-10 md:-mt-20 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="absolute -top-10 -left-10 w-40 h-40 bg-yellow-300/30 rounded-full blur-3xl -z-10 animate-pulse"></div>
            <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-blue-300/30 rounded-full blur-3xl -z-10 animate-pulse animation-delay-2000"></div>

            <div class="reveal relative w-full h-[280px] md:h-[500px] rounded-[2.5rem] overflow-hidden shadow-2xl hover:shadow-[0_35px_60px_-15px_rgba(0,0,0,0.3)] transition-all duration-500 group transform hover:-translate-y-2 bg-white cursor-grab active:cursor-grabbing">
                
                <div id="slider-track" class="flex w-full h-full transition-transform duration-500 ease-out" style="transform: translateX(0%);">
                    @foreach($banners as $index => $banner)
                        <div class="w-full h-full shrink-0 relative select-none">
                            <img src="{{ asset('storage/' . $banner->image_path) }}" class="w-full h-full object-cover pointer-events-none" alt="Banner" draggable="false">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent pointer-events-none"></div>
                            @if($banner->link_url)
                                <a href="{{ $banner->link_url }}" class="absolute inset-0 z-20" draggable="false"></a>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="absolute bottom-6 left-0 right-0 flex justify-center gap-3 z-30">
                    @foreach($banners as $index => $banner)
                        <div class="dot-indicator h-3 rounded-full bg-white/40 backdrop-blur-md transition-all duration-500 cursor-pointer hover:bg-white/80 {{ $index == 0 ? 'bg-yellow-400 w-10' : 'w-3' }}"></div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(isset($news) && $news->count() > 0)
    <div class="py-8 md:py-20 bg-white relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between mb-12 reveal">
                <div>
                    <span class="text-sm font-bold text-blue-600 uppercase tracking-wider">LATEST UPDATES</span>
                    <h2 class="text-4xl font-extrabold text-slate-900 mt-2">ข่าวสารและกิจกรรม</h2>
                    <div class="h-2 w-24 bg-yellow-500 mt-4 rounded-full"></div>
                </div>
                <a href="#" class="group hidden md:inline-flex items-center gap-2 text-sm font-bold text-blue-600 hover:text-blue-800 transition">
                    ดูทั้งหมด 
                    <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                @foreach($news as $index => $item)
                    <div class="reveal reveal-delay-{{ ($index + 1) * 100 }} group bg-white rounded-3xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-500 hover:-translate-y-3 border border-gray-100 cursor-pointer relative flex flex-col h-full">
                        
                        @if($item->link_url)
                            <a href="{{ $item->link_url }}" target="_blank" class="absolute inset-0 z-40"></a>
                        @else
                            <a href="{{ route('news.show', $item->id) }}" class="absolute inset-0 z-40"></a>
                        @endif

                        <div class="relative h-64 w-full overflow-hidden flex-shrink-0">
                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title }}" class="w-full h-full object-cover transition duration-700 group-hover:scale-110 group-hover:rotate-1">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent opacity-60 group-hover:opacity-80 transition duration-300"></div>
                            <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm text-slate-800 text-xs font-bold px-3 py-1.5 rounded-full shadow-sm z-30">
                                {{ $item->created_at->day }} {{ ['ม.ค.','ก.พ.','มี.ค.','เม.ย.','พ.ค.','มิ.ย.','ก.ค.','ส.ค.','ก.ย.','ต.ค.','พ.ย.','ธ.ค.'][$item->created_at->month - 1] }} {{ $item->created_at->year + 543 }}
                            </div>
                        </div>
                        <div class="p-8 flex flex-col flex-1 relative">
                            <div class="absolute -top-6 left-8 h-12 w-12 bg-yellow-500 text-white rounded-2xl flex items-center justify-center shadow-lg transform group-hover:rotate-12 transition duration-300 z-30">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                            </div>
                            <div class="mt-4 mb-4 flex-1">
                                @if($item->title)
                                    <h3 class="text-xl font-bold text-slate-900 leading-snug group-hover:text-blue-700 transition-colors duration-300 line-clamp-2">{{ $item->title }}</h3>
                                @endif
                                
                                @if($item->content)
                                    <p class="text-sm text-gray-500 mt-2 line-clamp-2">
                                        {{ Str::limit(strip_tags($item->content), 120) }}
                                    </p>
                                @endif
                            </div>
                            
                            <div class="mt-auto flex items-center text-sm font-bold text-blue-600 group-hover:text-yellow-600 transition">
                                อ่านเพิ่มเติม <svg class="w-4 h-4 ml-2 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <div class="py-28 bg-white relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
            <div class="absolute top-10 -left-20 w-72 h-72 bg-blue-300/30 rounded-full mix-blend-multiply filter blur-2xl animate-blob"></div>
            <div class="absolute bottom-10 -right-20 w-72 h-72 bg-yellow-300/30 rounded-full mix-blend-multiply filter blur-2xl animate-blob animation-delay-2000"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-green-300/30 rounded-full mix-blend-multiply filter blur-2xl animate-blob animation-delay-4000"></div>
            <div class="absolute -top-10 right-20 w-64 h-64 bg-purple-300/20 rounded-full mix-blend-multiply filter blur-2xl animate-blob animation-delay-2000"></div>
            <svg class="absolute top-0 left-0 text-gray-100 w-1/3 h-auto opacity-50 transform -translate-x-1/2 -translate-y-1/2" fill="currentColor" viewBox="0 0 200 200"><path d="M45.7,-76.3C59.5,-70.6,70.9,-58.1,79.8,-43.7C88.7,-29.3,95.1,-14.6,93.9,-0.7C92.7,13.2,83.9,26.4,74.3,38.7C64.8,51.1,54.4,62.6,41.6,70.3C28.8,78.1,14.4,82.1,-0.8,83.5C-15.9,84.8,-31.9,83.5,-46.2,76.6C-60.5,69.7,-73.2,57.2,-81.8,42.6C-90.5,27.9,-95.2,14,-94.2,0.6C-93.3,-12.9,-86.8,-25.8,-78.1,-37.9C-69.4,-50,-58.5,-61.4,-45.7,-67.9C-33,-74.4,-16.5,-76.1,-0.3,-75.6C15.8,-75.1,32,-72.3,45.7,-76.3Z" transform="translate(100 100)" /></svg>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-10 md:mb-20 reveal">
                <h2 class="text-sm font-bold text-blue-600 uppercase tracking-wider mb-3">HOW IT WORKS</h2>
                <h3 class="text-3xl md:text-4xl font-extrabold text-slate-900">ขั้นตอนการให้บริการ</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-12 relative">
                <div class="hidden md:block absolute top-1/2 left-0 w-full h-1 bg-gray-100 -translate-y-1/2 z-0"></div>

                <div class="reveal reveal-delay-100 bg-white p-6 md:p-10 rounded-[2rem] md:rounded-[3rem] shadow-lg md:shadow-xl border border-gray-100 text-center hover:shadow-2xl transition-all duration-500 group transform hover:-translate-y-3 relative z-10">
                    <div class="w-16 h-16 md:w-24 md:h-24 mx-auto bg-blue-50 rounded-2xl md:rounded-3xl flex items-center justify-center mb-4 md:mb-8 group-hover:scale-110 group-hover:rotate-6 transition duration-500 relative">
                        <span class="text-2xl md:text-4xl font-black text-blue-600 relative z-10">1</span>
                         <div class="absolute inset-0 bg-blue-200 rounded-2xl md:rounded-3xl blur-xl opacity-0 group-hover:opacity-40 transition duration-500"></div>
                    </div>
                    <h4 class="text-xl md:text-2xl font-bold text-slate-800 mb-2 md:mb-4">ยืนยันตัวตน</h4>
                    <p class="text-sm md:text-base text-slate-500 leading-relaxed">ลงทะเบียนเพียงครั้งเดียว เพื่อความปลอดภัยและติดตามผลได้ตลอด 24 ชม.</p>
                </div>

                <div class="reveal reveal-delay-200 bg-white p-6 md:p-10 rounded-[2rem] md:rounded-[3rem] shadow-lg md:shadow-xl border border-gray-100 text-center hover:shadow-2xl transition-all duration-500 group transform hover:-translate-y-3 relative z-10">
                    <div class="w-16 h-16 md:w-24 md:h-24 mx-auto bg-yellow-50 rounded-2xl md:rounded-3xl flex items-center justify-center mb-4 md:mb-8 group-hover:scale-110 group-hover:-rotate-6 transition duration-500 relative">
                        <span class="text-2xl md:text-4xl font-black text-yellow-600 relative z-10">2</span>
                        <div class="absolute inset-0 bg-yellow-200 rounded-2xl md:rounded-3xl blur-xl opacity-0 group-hover:opacity-40 transition duration-500"></div>
                    </div>
                    <h4 class="text-xl md:text-2xl font-bold text-slate-800 mb-2 md:mb-4">แจ้งปัญหา</h4>
                    <p class="text-sm md:text-base text-slate-500 leading-relaxed">ระบุรายละเอียดและพิกัด GPS อัตโนมัติ เพื่อให้เจ้าหน้าที่เข้าถึงจุดเกิดเหตุได้รวดเร็ว</p>
                </div>

                <div class="reveal reveal-delay-300 bg-white p-6 md:p-10 rounded-[2rem] md:rounded-[3rem] shadow-lg md:shadow-xl border border-gray-100 text-center hover:shadow-2xl transition-all duration-500 group transform hover:-translate-y-3 relative z-10">
                    <div class="w-16 h-16 md:w-24 md:h-24 mx-auto bg-green-50 rounded-2xl md:rounded-3xl flex items-center justify-center mb-4 md:mb-8 group-hover:scale-110 group-hover:rotate-6 transition duration-500 relative">
                        <span class="text-2xl md:text-4xl font-black text-green-600 relative z-10">3</span>
                        <div class="absolute inset-0 bg-green-200 rounded-2xl md:rounded-3xl blur-xl opacity-0 group-hover:opacity-40 transition duration-500"></div>
                    </div>
                    <h4 class="text-xl md:text-2xl font-bold text-slate-800 mb-2 md:mb-4">รอรับการแก้ไข</h4>
                    <p class="text-sm md:text-base text-slate-500 leading-relaxed">ติดตามสถานะการดำเนินงานได้ทุกขั้นตอน พร้อมรับการแจ้งเตือนเมื่อเสร็จสิ้น</p>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-slate-900 text-slate-400 py-6 border-t border-slate-800 relative overflow-hidden">
        <div class="absolute -top-10 -left-10 w-64 h-64 bg-blue-800/10 rounded-full blur-3xl opacity-30 pointer-events-none"></div>
        <div class="absolute -bottom-10 -right-10 w-64 h-64 bg-yellow-600/5 rounded-full blur-3xl opacity-30 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center mb-6">
                <div class="flex items-center gap-3">
                    <div class="p-1.5 bg-white/5 rounded-xl backdrop-blur-sm">
                         <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 w-auto hover:scale-110 transition duration-300">
                    </div>
                    <div>
                        <p class="font-bold text-white text-base leading-tight">{{ $settings['footer_title_1'] ?? 'เทศบาลเมืองชัยภูมิ' }}</p>
                        <p class="text-blue-300 text-[10px] tracking-wide">{{ $settings['footer_title_2'] ?? 'Chaiyaphum Municipality' }}</p>
                    </div>
                </div>
                <div class="text-left md:text-right space-y-1">
                    <p class="text-xs text-slate-300 flex items-center md:justify-end gap-2">
                        <svg class="w-4 h-4 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        {{ $settings['footer_address'] ?? 'งานประชาสัมพันธ์ เทศบาลเมืองชัยภูมิ' }}
                    </p>
                    <p class="text-sm font-bold text-yellow-400 flex items-center md:justify-end gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        โทร: {{ $settings['footer_phone'] ?? '044-811-378' }}
                    </p>
                </div>
            </div>
            <div class="border-t border-slate-800/50 pt-3 text-center">
                <p class="text-[10px] text-slate-600">
                    {{ $settings['footer_copyright'] ?? '© 2025 Complaint Management System. All rights reserved.' }}
                </p>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const track = document.getElementById('slider-track');
            if (!track) return; // ถ้าไม่มีแบนเนอร์ให้ข้ามไป

            const dots = document.querySelectorAll('.dot-indicator');
            const totalSlides = dots.length;
            let currentSlide = 0;
            const intervalTime = 10000;
            let slideInterval;

            if (totalSlides <= 1) return;

            // 🌟 ฟังก์ชันเลื่อนแบนเนอร์ (Slide Effect)
            function updateSlider() {
                track.style.transform = `translateX(-${currentSlide * 100}%)`;
                
                // อัปเดตจุดไข่ปลา
                dots.forEach((dot, index) => {
                    if (index === currentSlide) {
                        dot.classList.add('bg-yellow-400', 'w-10');
                        dot.classList.remove('w-3');
                    } else {
                        dot.classList.remove('bg-yellow-400', 'w-10');
                        dot.classList.add('w-3');
                    }
                });
            }

            function goToSlide(index) {
                currentSlide = index;
                if (currentSlide >= totalSlides) currentSlide = 0;
                if (currentSlide < 0) currentSlide = totalSlides - 1;
                updateSlider();
            }

            function nextSlide() { goToSlide(currentSlide + 1); }
            function prevSlide() { goToSlide(currentSlide - 1); }

            function startAutoPlay() { slideInterval = setInterval(nextSlide, intervalTime); }
            function resetAutoPlay() { clearInterval(slideInterval); startAutoPlay(); }

            startAutoPlay();

            // ==========================================
            // ระบบ Swipe & Drag ติดนิ้ว/เมาส์
            // ==========================================
            let startPos = 0;
            let prevTranslate = 0;
            let isDragging = false;
            const sliderContainer = track.parentElement;

            sliderContainer.addEventListener('touchstart', touchStart, {passive: true});
            sliderContainer.addEventListener('touchend', touchEnd, {passive: true});
            sliderContainer.addEventListener('touchmove', touchMove, {passive: true});

            sliderContainer.addEventListener('mousedown', touchStart);
            sliderContainer.addEventListener('mouseup', touchEnd);
            sliderContainer.addEventListener('mouseleave', () => { if (isDragging) touchEnd(); });
            sliderContainer.addEventListener('mousemove', touchMove);

            function touchStart(event) {
                isDragging = true;
                startPos = getPositionX(event);
                track.style.transition = 'none'; // ปิดความหน่วงเพื่อให้ภาพติดนิ้ว
                prevTranslate = currentSlide * -100;
            }

            function touchMove(event) {
                if (!isDragging) return;
                const currentPosition = getPositionX(event);
                const movedBy = currentPosition - startPos;
                const movePercentage = (movedBy / sliderContainer.offsetWidth) * 100;
                track.style.transform = `translateX(${prevTranslate + movePercentage}%)`; // เลื่อนภาพตามนิ้ว
            }

            function touchEnd(event) {
                if (!isDragging) return;
                isDragging = false;
                track.style.transition = 'transform 0.5s ease-out'; // เปิดความสมูทกลับมาตอนปล่อยมือ
                
                const endPos = event.type.includes('mouse') ? event.clientX : event.changedTouches[0].clientX;
                const movedBy = startPos - endPos;

                if (movedBy > 50) {
                    nextSlide(); // ลากไปซ้าย -> รูปต่อไป
                    resetAutoPlay();
                } else if (movedBy < -50) {
                    prevSlide(); // ลากไปขวา -> รูปก่อนหน้า
                    resetAutoPlay();
                } else {
                    updateSlider(); // ลากไม่พอ ให้เด้งกลับที่เดิม
                }
            }

            function getPositionX(event) {
                return event.type.includes('mouse') ? event.clientX : event.touches[0].clientX;
            }

            // ==========================================
            // ระบบคลิกที่จุดไข่ปลา
            // ==========================================
            dots.forEach((dot, index) => {
                dot.addEventListener('click', () => {
                    goToSlide(index);
                    resetAutoPlay();
                });
            });
        });

        // แอนิเมชันตอนเลื่อนลงมาเจอ
        function reveal() {
            var reveals = document.querySelectorAll(".reveal");
            for (var i = 0; i < reveals.length; i++) {
                var windowHeight = window.innerHeight;
                var elementTop = reveals[i].getBoundingClientRect().top;
                if (elementTop < windowHeight - 150) reveals[i].classList.add("active");
            }
        }
        window.addEventListener("scroll", reveal);
        reveal();
    </script>

</body>
</html>
@endif