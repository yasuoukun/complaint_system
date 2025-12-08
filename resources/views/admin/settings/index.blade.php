<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-blue-900">แก้ไขข้อมูลติดต่อ (Footer)</h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 rounded-xl shadow-md border border-gray-200">
                <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label class="block font-bold text-gray-700 mb-2">ชื่อหน่วยงาน (บรรทัด 1)</label>
                        <input type="text" name="footer_title_1" value="{{ $settings['footer_title_1'] ?? 'เทศบาลเมืองชัยภูมิ' }}" class="w-full border-gray-300 rounded-lg shadow-sm">
                    </div>

                    <div>
                        <label class="block font-bold text-gray-700 mb-2">ชื่อหน่วยงาน (บรรทัด 2)</label>
                        <input type="text" name="footer_title_2" value="{{ $settings['footer_title_2'] ?? 'Chaiyaphum Municipality' }}" class="w-full border-gray-300 rounded-lg shadow-sm">
                    </div>

                    <div>
                        <label class="block font-bold text-gray-700 mb-2">ที่อยู่</label>
                        <textarea name="footer_address" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm">{{ $settings['footer_address'] ?? 'เลขที่ 1 ถนน... ต.ในเมือง อ.เมือง จ.ชัยภูมิ 36000' }}</textarea>
                    </div>

                    <div>
                        <label class="block font-bold text-gray-700 mb-2">เบอร์โทรศัพท์</label>
                        <input type="text" name="footer_phone" value="{{ $settings['footer_phone'] ?? '044-811-378' }}" class="w-full border-gray-300 rounded-lg shadow-sm">
                    </div>

                    <div>
                        <label class="block font-bold text-gray-700 mb-2">ข้อความลิขสิทธิ์ (Copyright)</label>
                        <input type="text" name="footer_copyright" value="{{ $settings['footer_copyright'] ?? '© 2025 ระบบรับเรื่องร้องทุกข์ออนไลน์' }}" class="w-full border-gray-300 rounded-lg shadow-sm">
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="bg-green-600 text-white px-8 py-3 rounded-lg font-bold shadow hover:bg-green-700 transition">
                            บันทึกข้อมูล
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>