<!DOCTYPE html>
<html lang="th" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $news->title }}</title>
    <link rel="icon" type="image/png" href="{{ asset('storage/system/logo.png') }}">
    
    <link rel="preconnect" href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Sarabun', sans-serif; background-color: #f8fafc; }
        
        /* Progress Bar */
        #progress-bar { width: 0%; height: 4px; background: linear-gradient(90deg, #EAB308, #CA8A04); position: fixed; top: 0; left: 0; z-index: 9999; transition: width 0.1s; }

        /* --- จัดรูปแบบเนื้อหาภาษาไทย (Thai Typography) --- */
        .content-body {
            font-size: 1.125rem; /* 18px */
            color: #334155;      /* Slate 700 */
        }
        
        .content-body p {
            line-height: 1.8;
            margin-bottom: 1.5rem;
            text-align: justify;
            text-indent: 2.5rem;
        }

        .content-body img {
            display: block;
            max-width: 100%;
            height: auto;
            margin: 2rem auto;
            border-radius: 1rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .content-body h1, .content-body h2, .content-body h3 {
            color: #1e3a8a;
            font-weight: 700;
            margin-top: 2.5rem;
            margin-bottom: 1rem;
            line-height: 1.4;
            text-align: left;
            text-indent: 0;
        }
        
        .content-body h2 { font-size: 1.5rem; border-left: 4px solid #F59E0B; padding-left: 1rem; }

        .content-body a { color: #2563eb; text-decoration: underline; transition: all 0.2s; }
        .content-body a:hover { color: #1e40af; background-color: #dbeafe; }
        .content-body ul, .content-body ol { margin-bottom: 1.5rem; padding-left: 1.5rem; line-height: 1.8; }
        .content-body ul { list-style-type: disc; }
        .content-body ol { list-style-type: decimal; }

        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="antialiased" x-data="{ scroll: 0 }" @scroll.window="scroll = (window.pageYOffset / (document.documentElement.scrollHeight - window.innerHeight)) * 100">

    <div id="progress-bar" :style="'width: ' + scroll + '%'"></div>

    <nav class="fixed top-0 w-full z-50 transition-all duration-300 bg-white/90 backdrop-blur-md border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="/" class="flex items-center gap-2 text-slate-700 hover:text-blue-900 transition font-bold group">
                    <div class="p-1.5 bg-slate-100 rounded-full group-hover:bg-blue-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </div>
                    <span class="hidden sm:inline">หน้าหลัก</span>
                </a>
                <div class="flex items-center gap-2">
                    <span class="font-bold text-blue-900 text-lg">เทศบาลเมืองชัยภูมิ</span>
                    <span class="px-2 py-0.5 bg-yellow-100 text-yellow-800 text-xs font-bold rounded uppercase">News</span>
                </div>
                <div class="w-10"></div> </div>
        </div>
    </nav>

    <header class="relative w-full h-[55vh] min-h-[400px] overflow-hidden group">
        @if($news->image_path)
            <img src="{{ asset('storage/' . $news->image_path) }}" class="absolute inset-0 w-full h-full object-cover transition duration-[2s] group-hover:scale-105">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/60 to-transparent"></div>
        @else
            <div class="absolute inset-0 bg-gradient-to-br from-blue-900 to-slate-800"></div>
        @endif

        <div class="absolute bottom-0 left-0 w-full p-4 sm:p-8 pb-12 sm:pb-16 z-10">
            <div class="max-w-5xl mx-auto">
                <div class="flex flex-wrap gap-3 mb-6 animate-fade-in-up">
                    <span class="px-3 py-1 bg-yellow-500 text-blue-900 text-xs font-bold uppercase tracking-wider rounded shadow-lg">ข่าวประชาสัมพันธ์</span>
                    <span class="px-3 py-1 bg-white/20 backdrop-blur-md text-white text-xs font-medium rounded border border-white/30 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        {{ $news->created_at->format('d/m/Y') }}
                    </span>
                </div>
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white leading-tight drop-shadow-lg max-w-4xl">
                    {{ $news->title }}
                </h1>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative z-20 pb-20">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">

            <aside class="hidden lg:block lg:col-span-3 relative">
                <div class="sticky top-24 space-y-6">
                    
                    {{-- [แก้ไข] ลบวงกลมตัว A ออก แต่เก็บชื่อหน่วยงานไว้ --}}
                    <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 text-center">
                        <p class="text-[10px] text-slate-400 uppercase tracking-widest font-bold mb-1">POSTED BY</p>
                        <h3 class="font-bold text-slate-800 text-lg">{{ $news->user->name ?? 'กองประชาสัมพันธ์' }}</h3>
                        <p class="text-xs text-slate-500">เทศบาลเมืองชัยภูมิ</p>
                    </div>

                    <div class="bg-white p-4 rounded-2xl shadow-md border border-gray-100">
                        <p class="text-xs text-slate-400 font-bold mb-3 uppercase text-center">Share</p>
                        <div class="flex justify-center gap-2">
                            <button class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center hover:bg-blue-700 transition" title="Facebook">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.791-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </button>
                            <button class="w-10 h-10 rounded-full bg-green-500 text-white flex items-center justify-center hover:bg-green-600 transition" title="Line">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.373 0 0 4.296 0 9.596c0 4.296 3.553 7.925 8.444 9.172-.255.945-.886 2.535-.916 2.695-.03.16 0 .32.18.32.12 0 .27-.06.42-.18 1.77-1.35 4.05-3.03 5.43-4.14.78.18 1.62.27 2.442.27 6.627 0 12-4.296 12-9.596S17.373 0 12 0z"/></svg>
                            </button>
                            <button class="w-10 h-10 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center hover:bg-gray-200 transition" title="Copy Link" onclick="navigator.clipboard.writeText(window.location.href); alert('คัดลอกลิงก์เรียบร้อย')">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </aside>

            <article class="col-span-1 lg:col-span-9 bg-white rounded-3xl shadow-xl border border-gray-100 min-h-[500px] overflow-hidden">
                
                {{-- [แก้ไข] ส่วนของ Mobile ก็ลบวงกลมออกเช่นกัน เหลือแค่ชื่อ --}}
                <div class="lg:hidden p-6 border-b border-gray-100 bg-gray-50 flex items-center gap-4">
                    <div>
                        <p class="font-bold text-sm text-slate-800">{{ $news->user->name ?? 'กองประชาสัมพันธ์' }}</p>
                        <p class="text-xs text-slate-500">{{ $news->created_at->format('d M Y เวลา H:i น.') }}</p>
                    </div>
                </div>

                <div class="p-8 md:p-12 lg:p-16">
                    <div class="content-body">
                        {!! $news->content !!}
                    </div>
                </div>

                <div class="bg-slate-50 p-8 md:p-12 border-t border-gray-100">
                    <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        สอบถามข้อมูลเพิ่มเติม
                    </h3>
                    <p class="text-slate-600 text-sm mb-6">หากท่านมีข้อสงสัยหรือต้องการสอบถามรายละเอียดเพิ่มเติมเกี่ยวกับข่าวประชาสัมพันธ์นี้ สามารถติดต่อได้ที่สำนักงานเทศบาลเมืองชัยภูมิ ในวันและเวลาราชการ</p>
                    
                    <a href="/" class="inline-flex items-center gap-2 text-blue-600 font-bold hover:underline transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        กลับสู่หน้าหลัก
                    </a>
                </div>

            </article>

        </div>
    </main>

    <footer class="bg-slate-900 text-slate-400 py-10 border-t border-slate-800 text-center text-sm">
        <div class="max-w-7xl mx-auto px-4">
            <img src="{{ asset('storage/system/logo.png') }}" class="h-10 w-auto mx-auto mb-4 opacity-50 grayscale hover:grayscale-0 transition duration-500">
            <p>&copy; {{ date('Y') }} เทศบาลเมืองชัยภูมิ. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>