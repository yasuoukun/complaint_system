<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>เข้าสู่ระบบ - เทศบาลเมืองชัยภูมิ</title>

       <link rel="icon" type="image/png" href="{{ asset('storage/system/logo.png') }}">
        <link rel="preconnect" href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style> body { font-family: 'Sarabun', sans-serif; } </style>
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-100">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative overflow-hidden">
            
            <div class="absolute top-0 w-full h-64 bg-gradient-to-b from-blue-900 to-blue-800 z-0 rounded-b-[50px] shadow-md"></div>

            <div class="w-full sm:max-w-md mt-10 px-6 py-8 bg-white shadow-2xl overflow-hidden sm:rounded-2xl relative z-10 border-t-4 border-yellow-500">
                
                <div class="flex flex-col items-center mb-8">
                    <img src="{{ asset('images/logo.png') }}" class="h-24 w-auto drop-shadow-md mb-4" alt="Logo">
                    
                    <h2 class="text-2xl font-bold text-blue-900 text-center">ระบบร้องทุกข์ออนไลน์</h2>
                    <p class="text-gray-500 text-sm font-medium">เทศบาลเมืองชัยภูมิ (นายก บรรยงค์)</p>
                </div>

                {{ $slot }}
            </div>
            
            <div class="z-10 mt-8 text-gray-500 text-sm flex flex-col items-center gap-1">
                <p>&copy; 2025 เทศบาลเมืองชัยภูมิ</p>
            </div>
        </div>
    </body>
</html>