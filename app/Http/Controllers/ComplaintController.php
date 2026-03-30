<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\User;
use App\Mail\NewComplaintNotification;
use App\Mail\ComplaintStatusUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

// Word Processing
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;

// QR Code (Endroid)
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Writer\PngWriter;

class ComplaintController extends Controller
{
    // --- Helper: แปลงชื่อชุมชนเป็นเขต ---
    private function getZoneFromCommunity($community)
    {
        $manualAssign = ['หนองสังข์', 'โนนตาปาน'];
        if (in_array($community, $manualAssign)) return null;

        $zones = [
            '1' => ['ขี้เหล็กใหญ่', 'ขี้เหล็กน้อยมิตรภาพ', 'หนองปลาเฒ่า', 'หนองหลอด', 'เมืองพญาแล', 'ทานตะวัน', 'ตลาดสดฝั่งทิศตะวันตก', 'ตลาด'],
            '2' => ['เมืองเก่า', 'โนนไฮ', 'หนองบัว', 'คลองเรียง', 'หินตั้งโพนงาม', 'หินตั้ง-โพนงาม', 'ราษฎรเจริญสุข', 'ราษฎร์เจริญสุข', 'ใหม่พัฒนา', 'ขี้เหล็กน้อย ปรางกู่', 'ขี้เหล็กน้อย-ปรางค์กู่'],
            '3' => ['โนนสาทร', 'โนนสมอ', 'กุดแคน', 'กุดแคน-ฝั่งถนน', 'หนองบ่อ', 'เอื้ออาทร', 'อาทร ทวีสุข', 'เมืองน้อยใต้', 'เมืองน้อยเหนือ', 'โคกน้อย', 'คลองลี่', 'นามบิน', 'สนามบิน']
        ];

        foreach ($zones as $zone => $communities) {
            if (in_array($community, $communities)) return $zone;
        }
        return null;
    }

    // 1. หน้า Dashboard รวม
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $query = Complaint::query();

            // ค้นหา
            if ($request->filled('search')) {
                // ✅ ลบเครื่องหมาย # ออกเผื่อแอดมินพิมพ์ติดมา (เช่น พิมพ์ #16 ให้เหลือแค่ 16)
                $search = str_replace('#', '', $request->search); 
                
                $query->where(function($q) use ($search) {
                    $q->where('id', 'like', "%{$search}%") // ✅ เพิ่มการค้นหาจากรหัสร้องเรียน (ID) ตรงนี้
                      ->orWhere('subject', 'like', "%{$search}%")
                      ->orWhere('details', 'like', "%{$search}%")
                      ->orWhere('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('citizen_id', 'like', "%{$search}%")
                      ->orWhere('phone_number', 'like', "%{$search}%");
                });
            }

            // กรองเขต
            if ($request->filled('filter_zone')) {
                $query->where('zone', $request->filter_zone);
            }

            // กรองวันที่
            if ($request->filled('filter_date')) {
                $query->whereDate('created_at', $request->filter_date);
            }

            $pendingComplaints = (clone $query)->where('status', 'pending')->latest()->get(); 
            $waitingComplaints = (clone $query)->where('status', 'waiting')->latest()->get(); 
            $inProgressComplaints = (clone $query)->where('status', 'in_progress')->latest()->get(); 
            $historyComplaints = (clone $query)->whereIn('status', ['completed', 'rejected', 'unsuccessful'])->latest()->get();

            return view('admin.complaints.index', compact('pendingComplaints', 'waitingComplaints', 'inProgressComplaints', 'historyComplaints'));
        
        } elseif ($user->role === 'council_member') {
            return redirect()->route('complaints.history');
        } else {
            return redirect()->route('complaints.history');
        }
    }

    // 2. หน้าฟอร์มเขียนคำร้อง
    public function create()
    {
        return view('complaint.create');
    }

    // 3. บันทึกข้อมูล
    public function store(Request $request)
    {
        // 1. ตรวจสอบข้อมูล (Validation)
        $request->validate([
            'subject' => 'required',
            'title' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'age' => 'required',
            'phone_number' => 'required',
            'details' => 'required',
            'house_no' => 'nullable',
            'moo' => 'nullable',
            'road' => 'nullable',
            'community' => 'required',           // ชุมชนผู้แจ้ง
            'incident_community' => 'required',  // ชุมชนที่เกิดเหตุ
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
            'images' => 'max:4',
        ], [
            'images.max' => 'อัปโหลดรูปภาพได้สูงสุด 4 รูปเท่านั้น',
            'incident_community.required' => 'กรุณาระบุชุมชน/พื้นที่ ที่เกิดเหตุ',
        ]);

        DB::beginTransaction();

        try {
            // ---------------------------------------------------------
            // 🤖 ส่วนระบบแยกเขตอัตโนมัติ (Auto Zone Mapping)
            // ---------------------------------------------------------
            $zoneMapping = [
                // === เขต 1 ===
                'ขี้เหล็กใหญ่' => '1', 'ขี้เหล็กน้อย-มิตรภาพ' => '1', 'หนองปลาเฒ่า' => '1',
                'หนองหลอด' => '1', 'เมืองพญาแล' => '1', 'ทานตะวัน' => '1', 'ตลาด' => '1',

                // === เขต 2 ===
                'เมืองเก่า' => '2', 'โนนไฮ' => '2', 'หนองบัว' => '2', 'คลองเรียง' => '2',
                'หินตั้ง-โพนงาม' => '2', 'ราษฎร์เจริญสุข' => '2', 'ใหม่พัฒนา' => '2', 'ขี้เหล็กน้อย-ปรางค์กู่' => '2',

                // === เขต 3 ===
                'โนนสาทร' => '3', 'โนนสมอ' => '3', 'กุดแคน-ฝั่งถนน' => '3', 'หนองบ่อ' => '3',
                'อาทร ทวีสุข' => '3', 'เมืองน้อยใต้' => '3', 'เมืองน้อยเหนือ' => '3', 'โคกน้อย' => '3',
                'คลองลี่' => '3', 'สนามบิน' => '3',
                
                // === ชุมชนที่ให้แอดมินเลือกเอง ===
                'หนองสังข์' => null, 'โนนตาปาน' => null,
            ];

            $autoZone = $zoneMapping[$request->incident_community] ?? null; 
            // ---------------------------------------------------------

            // 2. จัดการรูปภาพแผนที่
            $mapPath = null;
            if ($request->map_capture) {
                $img = $request->map_capture;
                if (str_contains($img, 'base64,')) {
                    $img = explode('base64,', $img)[1];
                }
                $data = base64_decode(str_replace(' ', '+', $img));
                if ($data) {
                    $fileName = 'map_' . time() . '_' . uniqid() . '.png';
                    $mapPath = 'complaints/' . $fileName;
                    Storage::disk('public')->put($mapPath, $data);
                }
            }

            // 3. บันทึกข้อมูลลงฐานข้อมูล
            $complaintId = DB::table('complaints')->insertGetId([
                'user_id' => auth()->id(),
                'title' => $request->title,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'age' => $request->age,
                'phone_number' => $request->phone_number,
                
                'house_no' => $request->house_no,
                'moo' => $request->moo,
                'road' => $request->road,
                'soi' => $request->soi,
                
                'community' => $request->community,                   
                'incident_community' => $request->incident_community, 
                
                'zone' => $autoZone, 
                
                'sub_district' => 'ในเมือง',
                'district' => 'เมืองชัยภูมิ',
                'province' => 'ชัยภูมิ',
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'subject' => $request->subject,
                'details' => $request->details,
                'status' => 'pending',
                'map_image_path' => $mapPath,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            // 4. จัดการรูปภาพประกอบ
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $imageFile) {
                    $path = $imageFile->store('complaints', 'public');
                    DB::table('complaint_images')->insert([
                        'complaint_id' => $complaintId,
                        'image_path' => $path,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('complaints.history')->with('success', 'บันทึกข้อมูลเรียบร้อยแล้ว');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage())->withInput();
        }
    }

    // 4. หน้าประวัติ
    public function history()
    {
        $user = Auth::user();

        if ($user->role === 'council_member') {
            $zoneId = preg_replace('/[^0-9]/', '', $user->zone);
            $mySubmissions = Complaint::where('user_id', $user->id)->latest()->get();
            $zoneComplaints = Complaint::where('zone', $zoneId)
                                     ->where(function($query) use ($user) {
                                         $query->where('user_id', '!=', $user->id)
                                               ->orWhereNull('user_id');
                                     })
                                     ->latest()->get();

            return view('complaint.council_dashboard', compact('mySubmissions', 'zoneComplaints'));

        } else {
            $complaints = Complaint::where('user_id', $user->id)->latest()->get();
            return view('complaint.history', compact('complaints'));
        }
    }

    // 5. ดาวน์โหลด Word
    public function downloadDocx($id)
    {
        $complaint = DB::table('complaints')->where('id', $id)->first();
        if (!$complaint) return back()->with('error', 'ไม่พบข้อมูลคำร้องนี้');

        $templatePath = storage_path('app/templates/คำร้องทั่วไป.docx');
        if (!file_exists($templatePath)) {
            $templatePath = public_path('storage/templates/คำร้องทั่วไป.docx');
            if (!file_exists($templatePath)) {
                return back()->with('error', "ไม่พบไฟล์ Template");
            }
        }

        $templateProcessor = new TemplateProcessor($templatePath);

        $createdAt = Carbon::parse($complaint->created_at);
        $thaiMonths = [1=>'มกราคม', 2=>'กุมภาพันธ์', 3=>'มีนาคม', 4=>'เมษายน', 5=>'พฤษภาคม', 6=>'มิถุนายน', 7=>'กรกฎาคม', 8=>'สิงหาคม', 9=>'กันยายน', 10=>'ตุลาคม', 11=>'พฤศจิกายน', 12=>'ธันวาคม'];
        
        $templateProcessor->setValue('วัน', $createdAt->day);
        $templateProcessor->setValue('เดือน', $thaiMonths[$createdAt->month]);
        $templateProcessor->setValue('พ.ศ.', $createdAt->year + 543);
        
        $fields = ['subject', 'title', 'first_name', 'last_name', 'age', 'house_no', 'moo', 'road', 'community', 'phone_number', 'details'];
        $labels = ['subject'=>'หัวเรื่อง', 'title'=>'คำนำหน้า', 'first_name'=>'ชื่อ', 'last_name'=>'นามสกุล', 'age'=>'อายุ', 'house_no'=>'บ้านเลขที่', 'moo'=>'หมู่', 'road'=>'ถนน', 'community'=>'ชุมชน', 'phone_number'=>'เบอร์', 'details'=>'เนื้อหาคำร้อง'];
        
        foreach ($labels as $db => $var) {
            $templateProcessor->setValue($var, $complaint->$db ?? '-');
        }

        $getImagePath = function($path) {
            if (!$path) return null;
            $p1 = storage_path('app/public/' . $path);
            if (file_exists($p1)) return $p1;
            $p2 = public_path('storage/' . $path);
            if (file_exists($p2)) return $p2;
            if (file_exists($path)) return $path;
            return null;
        };

        $validImages = [];
        $dbImages = DB::table('complaint_images')->where('complaint_id', $id)->get();
        foreach ($dbImages as $img) {
            $realPath = $getImagePath($img->image_path);
            if ($realPath) $validImages[] = $realPath;
        }
        if (!empty($complaint->photo_image_path)) {
            $realPath = $getImagePath($complaint->photo_image_path);
            if ($realPath) $validImages[] = $realPath;
        }

        $validImages = array_unique($validImages);
        $validImages = array_values($validImages);

        for ($i = 1; $i <= 4; $i++) {
            $varName = "รูปภาพ{$i}";
            $idx = $i - 1;

            if (isset($validImages[$idx])) {
                try {
                    $templateProcessor->setImageValue($varName, [
                        'path' => $validImages[$idx], 
                        'width' => 280, 
                        'height' => 210, 
                        'ratio' => true
                    ]);
                } catch (\Exception $e) {
                    $templateProcessor->setValue($varName, '');
                }
            } else {
                $templateProcessor->setValue($varName, '');
            }
        }

        $mapTags = ['แผนที่', ' แผนที่'];
        $mapPath = $getImagePath($complaint->map_image_path);
        $mapInserted = false;

        if ($mapPath) {
            foreach ($mapTags as $tag) {
                try {
                    $templateProcessor->setImageValue($tag, ['path' => $mapPath, 'width' => 300, 'height' => 200, 'ratio' => false]);
                    $mapInserted = true;
                } catch (\Exception $e) {}
            }
        }
        if (!$mapInserted) foreach ($mapTags as $t) $templateProcessor->setValue($t, '');

        $qrTags = ['คิวอาร์โค้ด', ' คิวอาร์โค้ด'];
        $qrFile = storage_path('app/public/qr_temp_' . $id . '.png');
        try {
            if ($complaint->latitude && $complaint->longitude) {
                $url = "https://www.google.com/maps/search/?api=1&query={$complaint->latitude},{$complaint->longitude}";
                $result = Builder::create()->writer(new PngWriter())->data($url)->encoding(new Encoding('UTF-8'))->size(150)->margin(0)->build();
                $result->saveToFile($qrFile);
                if (file_exists($qrFile)) {
                    foreach ($qrTags as $t) $templateProcessor->setImageValue($t, ['path' => $qrFile, 'width' => 100, 'height' => 100]);
                } else foreach ($qrTags as $t) $templateProcessor->setValue($t, '');
            } else foreach ($qrTags as $t) $templateProcessor->setValue($t, '');
        } catch (\Exception $e) { foreach ($qrTags as $t) $templateProcessor->setValue($t, ''); }

        $outputName = 'complaint_' . $id . '.docx';
        $outputPath = storage_path('app/public/' . $outputName);
        $templateProcessor->saveAs($outputPath);

        if (file_exists($qrFile)) @unlink($qrFile);

        return response()->download($outputPath)->deleteFileAfterSend(true);
    }

   
    // 6. อัปเดตสถานะ
    public function process(Request $request, $id)
    {
        $complaint = Complaint::findOrFail($id);
        if (auth()->user()->role !== 'admin') abort(403);

        $request->validate([
            'status' => 'required|in:waiting,in_progress,completed,rejected,unsuccessful,pending',
            'admin_notes' => 'nullable|string',
            'zone' => 'nullable|string',
            'responsible_dept' => 'nullable|string',
        ]);

        if ($request->status !== 'pending') {
            $complaint->status = $request->status;
        }

        // 🟢 บันทึกข้อมูลลงฐานข้อมูลโดยตรง (ไม่ใส่ if ดักแล้ว)
        // ทำให้เวลาแอดมินลบข้อความออกจนเป็นค่าว่าง ระบบก็จะบันทึกค่าว่างให้ทันที
        $complaint->admin_notes = $request->admin_notes;
        $complaint->zone = $request->zone;
        $complaint->responsible_dept = $request->responsible_dept; 
        
        $complaint->processed_by_user_id = auth()->id();
        $complaint->save();

        if ($complaint->user && $complaint->user->email && $request->status !== 'pending') {
            Mail::to($complaint->user->email)->send(new ComplaintStatusUpdate($complaint));
        }

        return back()->with('success', 'บันทึกข้อมูลเรียบร้อยแล้ว');
    }

    // 7. ดูรายละเอียด
    public function show($id)
    {
        $complaint = Complaint::findOrFail($id);

        if (auth()->check()) {
            if (auth()->user()->role !== 'admin' && auth()->user()->id !== $complaint->user_id) {
                 if (auth()->user()->role === 'council_member') {
                     $myZone = preg_replace('/[^0-9]/', '', auth()->user()->zone);
                     if ($complaint->zone != $myZone) abort(403);
                 } else {
                     abort(403);
                 }
            }

            if (auth()->user()->role === 'admin') {
                return view('admin.complaints.show', compact('complaint'));
            }
        }

        return view('complaint.show', compact('complaint'));
    }

    // 8. ลบรายการเดียว (✅ อัปเดตให้ลบรูปภาพด้วย)
    public function destroy($id)
    {
        $complaint = Complaint::findOrFail($id);
        if (auth()->user()->role !== 'admin') abort(403);
        
        // 1. ลบรูปภาพประกอบ (จากตาราง complaint_images)
        if ($complaint->images) {
            foreach ($complaint->images as $img) {
                if (Storage::disk('public')->exists($img->image_path)) {
                    Storage::disk('public')->delete($img->image_path);
                }
            }
        }

        // 2. ลบรูปแผนที่และรูปเก่า (ถ้ามี)
        if ($complaint->map_image_path && Storage::disk('public')->exists($complaint->map_image_path)) {
            Storage::disk('public')->delete($complaint->map_image_path);
        }
        if ($complaint->photo_image_path && Storage::disk('public')->exists($complaint->photo_image_path)) {
            Storage::disk('public')->delete($complaint->photo_image_path);
        }

        // 3. ลบข้อมูลจากฐานข้อมูล
        $complaint->delete();
        
        return back()->with('success', 'ลบข้อมูลและรูปภาพเรียบร้อยแล้ว');
    }

    // 9. ลบแบบกลุ่ม (✅ อัปเดตให้ลบรูปภาพด้วย)
    public function bulkDestroy(Request $request)
    {
        if (auth()->user()->role !== 'admin') abort(403);

        $query = Complaint::whereIn('status', ['completed', 'rejected', 'unsuccessful']);

        if ($request->type == 'week') {
            $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            $msg = 'ลบประวัติงานที่จบแล้วของ "สัปดาห์นี้" เรียบร้อย';
        } elseif ($request->type == 'month') {
            $query->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year);
            $msg = 'ลบประวัติงานที่จบแล้วของ "เดือนนี้" เรียบร้อย';
        } else {
            return back()->with('error', 'คำสั่งไม่ถูกต้อง');
        }

        $items = $query->with('images')->get(); // ดึงความสัมพันธ์ images มาด้วย
        
        foreach ($items as $item) {
            // ลบรูปภาพประกอบ
            if ($item->images) {
                foreach ($item->images as $img) {
                    if (Storage::disk('public')->exists($img->image_path)) {
                        Storage::disk('public')->delete($img->image_path);
                    }
                }
            }
            // ลบแผนที่/รูปเก่า
            if ($item->map_image_path && Storage::disk('public')->exists($item->map_image_path)) {
                Storage::disk('public')->delete($item->map_image_path);
            }
            if ($item->photo_image_path && Storage::disk('public')->exists($item->photo_image_path)) {
                Storage::disk('public')->delete($item->photo_image_path);
            }
        }

        $query->delete();
        return back()->with('success', $msg);
    }

    // 10. หน้าฟอร์ม Guest
    public function guestCreate() { return view('complaint.guest_create'); }

    // 11. บันทึก Guest
    public function guestStore(Request $request)
{
    // 1. ตรวจสอบข้อมูล (Validation)
    $request->validate([
        'citizen_id' => 'required|numeric|digits:13', 
        'subject' => 'required|string',
        'title' => 'required|string',
        'first_name' => 'required|string',
        'last_name' => 'required|string',
        'age' => 'required|integer',
        'phone_number' => 'required|string',
        'details' => 'required',
        
        // ที่อยู่ผู้แจ้ง (เปลี่ยนเป็น nullable ตามฟอร์มใหม่)
        'house_no' => 'nullable|string',
        'moo' => 'nullable|string',
        'road' => 'nullable|string',
        
        // ชุมชน (บังคับทั้งคู่)
        'community' => 'required|string',           // ชุมชนผู้แจ้ง
        'incident_community' => 'required|string',  // ✅ เพิ่ม: ชุมชนที่เกิดเหตุ
        
        'images' => 'max:4',
        'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
    ], [
        'incident_community.required' => 'กรุณาระบุชุมชน/พื้นที่ ที่เกิดเหตุ',
    ]);

    // เช็คโควต้า 3 ครั้ง/วัน
    $countToday = Complaint::where('citizen_id', $request->citizen_id)
                            ->whereDate('created_at', Carbon::today())
                            ->count();
                            
    if ($countToday >= 3) {
        return back()->withInput()->withErrors(['citizen_id' => 'คุณส่งเรื่องครบโควต้า 3 ครั้ง/วัน แล้วครับ']);
    }

    DB::beginTransaction();

    try {
        // ---------------------------------------------------------
        // 🤖 ระบบแยกเขตอัตโนมัติ (Auto Zone Mapping)
        // ---------------------------------------------------------
        $zoneMapping = [
            // === เขต 1 ===
            'ขี้เหล็กใหญ่' => '1', 'ขี้เหล็กน้อย-มิตรภาพ' => '1', 'หนองปลาเฒ่า' => '1',
            'หนองหลอด' => '1', 'เมืองพญาแล' => '1', 'ทานตะวัน' => '1', 'ตลาด' => '1',
            // === เขต 2 ===
            'เมืองเก่า' => '2', 'โนนไฮ' => '2', 'หนองบัว' => '2', 'คลองเรียง' => '2',
            'หินตั้ง-โพนงาม' => '2', 'ราษฎร์เจริญสุข' => '2', 'ใหม่พัฒนา' => '2', 'ขี้เหล็กน้อย-ปรางค์กู่' => '2',
            // === เขต 3 ===
            'โนนสาทร' => '3', 'โนนสมอ' => '3', 'กุดแคน-ฝั่งถนน' => '3', 'หนองบ่อ' => '3',
            'อาทร ทวีสุข' => '3', 'เมืองน้อยใต้' => '3', 'เมืองน้อยเหนือ' => '3', 'โคกน้อย' => '3',
            'คลองลี่' => '3', 'สนามบิน' => '3',
            // === อื่นๆ ===
            'หนองสังข์' => null, 'โนนตาปาน' => null,
        ];
        
        // คำนวณเขตจากชุมชนที่เกิดเหตุ
        $autoZone = $zoneMapping[$request->incident_community] ?? null; 
        // ---------------------------------------------------------

        // จัดการรูปแผนที่
        $mapPath = null;
        if ($request->filled('map_capture')) {
            $imageName = 'guest_map_' . time() . '_' . uniqid() . '.png';
            $image = str_replace(' ', '+', preg_replace('#^data:image/\w+;base64,#i', '', $request->map_capture));
            Storage::disk('public')->put('complaints/' . $imageName, base64_decode($image));
            $mapPath = 'complaints/' . $imageName;
        }

        // บันทึกลงฐานข้อมูล
        $complaint = Complaint::create([
            'user_id' => null,
            'citizen_id' => $request->citizen_id,
            'subject' => $request->subject,
            'title' => $request->title,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'age' => $request->age,
            'phone_number' => $request->phone_number,
            
            'house_no' => $request->house_no,
            'moo' => $request->moo,
            'road' => $request->road,
            
            'community' => $request->community,                   // ชุมชนผู้แจ้ง
            'incident_community' => $request->incident_community, // ✅ ชุมชนที่เกิดเหตุ
            'zone' => $autoZone,                                  // ✅ เขตอัตโนมัติ
            
            'sub_district' => 'ในเขตเทศบาล',
            'district' => 'เมืองชัยภูมิ',
            'province' => 'ชัยภูมิ',
            'details' => $request->details,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'map_image_path' => $mapPath,
            'status' => 'pending',
        ]);

        // จัดการรูปภาพประกอบ
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imageFile) {
                $path = $imageFile->store('complaints', 'public');
                DB::table('complaint_images')->insert([
                    'complaint_id' => $complaint->id,
                    'image_path' => $path,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        // ส่งเมลแจ้งเตือนแอดมิน (ถ้ามีระบบเมล)
        $adminEmails = User::where('role', 'admin')->pluck('email');
        if ($adminEmails->count() > 0) { 
            try {
                Mail::to($adminEmails)->send(new NewComplaintNotification($complaint)); 
            } catch (\Exception $e) {}
        }

        DB::commit();
        return redirect('/')->with('success', 'ส่งเรื่องร้องเรียนเรียบร้อยแล้ว! เจ้าหน้าที่จะเร่งตรวจสอบครับ');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage())->withInput();
    }
}

    // 12. หน้าแก้ไขคำร้อง
    public function edit($id)
    {
        $complaint = Complaint::findOrFail($id);

        if (auth()->user()->role === 'admin') {
            return view('complaint.edit', compact('complaint'));
        }

        if (auth()->id() !== $complaint->user_id) abort(403);
        if ($complaint->created_at->addMinutes(10)->isPast()) {
            return back()->with('error', 'หมดเวลาแก้ไขข้อมูลแล้ว');
        }

        return view('complaint.edit', compact('complaint'));
    }

    // 13. บันทึกการแก้ไข
    public function update(Request $request, $id)
    {
        $complaint = Complaint::findOrFail($id);
        $user = Auth::user();

        if ($user->role !== 'admin') {
            if (auth()->id() !== $complaint->user_id) abort(403);
            if ($complaint->created_at->addMinutes(10)->isPast()) {
                return redirect()->route('complaints.history')->with('error', 'หมดเวลาแก้ไขข้อมูลแล้ว');
            }
        }

        $request->validate([
            'subject' => 'required|string',
            'community' => 'required|string',
        ]);

        if ($user->role !== 'admin') {
            $zone = $this->getZoneFromCommunity($request->community);
            $complaint->zone = $zone;
        }

        $complaint->fill($request->except(['map_capture', 'photo_image']));

        if ($request->filled('map_capture')) {
            if ($complaint->map_image_path) Storage::disk('public')->delete($complaint->map_image_path);
            $imageName = 'map_' . time() . '.png';
            $image = str_replace(' ', '+', preg_replace('#^data:image/\w+;base64,#i', '', $request->map_capture));
            Storage::disk('public')->put('complaints/' . $imageName, base64_decode($image));
            $complaint->map_image_path = 'complaints/' . $imageName;
        }
        if ($request->hasFile('photo_image')) {
            if ($complaint->photo_image_path) Storage::disk('public')->delete($complaint->photo_image_path);
            $complaint->photo_image_path = $request->file('photo_image')->store('complaints', 'public');
        }

        $timestamp = date('d/m/Y H:i');
        $actor = $user->role === 'admin' ? 'แอดมิน' : 'ผู้แจ้ง';
        $history = "🔴 แก้ไขโดย {$actor} เมื่อ {$timestamp}";
        $complaint->user_edit_note = $history . "\n" . $complaint->user_edit_note;
        
        $complaint->save();

        if ($user->role === 'admin') {
            return redirect()->route('admin.complaints.index')->with('success', 'แอดมินแก้ไขข้อมูลเรียบร้อยแล้ว');
        }
        
        return redirect()->route('complaints.history')->with('success', 'แก้ไขข้อมูลเรียบร้อยแล้ว');
    }

    // ฟังก์ชันสำหรับแอดมินกดดูรายละเอียด (เพิ่มใน ComplaintController)
    public function showAdmin($id)
    {
        // ✅ ใช้ Model (Complaint::...) เพื่อให้ created_at เป็นวันที่ (Carbon)
        $complaint = Complaint::findOrFail($id);
        
        // ดึงรูปภาพแยก (กรณีใน Model ยังไม่ได้ทำ Relation ไว้)
        // เพื่อความชัวร์ว่ารูปจะขึ้นแน่นอน
        $complaint->images = DB::table('complaint_images')->where('complaint_id', $id)->get();

        return view('admin.complaints.show', compact('complaint'));
    }

}