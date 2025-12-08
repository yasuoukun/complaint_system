<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\User;
use App\Mail\NewComplaintNotification;
use App\Mail\ComplaintStatusUpdate;
use App\Mail\ComplaintEditedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;
use Carbon\Carbon;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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
    // ในไฟล์ app/Http/Controllers/ComplaintController.php

public function index(Request $request) // 👈 อย่าลืมเพิ่ม Request $request ตรงนี้
{
    $user = Auth::user();

    if ($user->role === 'admin') {
        // --- เริ่มต้น Query ---
        $query = Complaint::query();

        // 🔍 1. ค้นหาจากคำ (หัวข้อ, รายละเอียด, ชื่อ, เบอร์, เลขบัตร)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhere('details', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('citizen_id', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        // 📍 2. กรองตามเขต
        if ($request->filled('filter_zone')) {
            $query->where('zone', $request->filter_zone);
        }

        // 📅 3. กรองตามวันที่
        if ($request->filled('filter_date')) {
            $query->whereDate('created_at', $request->filter_date);
        }

        // --- แยกตามสถานะ (ใช้ clone $query เพื่อให้ตัวกรองส่งผลกับทุกตาราง) ---
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

    // 3. บันทึกข้อมูล (สมาชิก)
    public function store(Request $request)
    {
        $user = Auth::user(); 

        if ($user->role === 'complainant') {
            $countToday = Complaint::where('user_id', $user->id)->whereDate('created_at', Carbon::today())->count();
            if ($countToday >= 3) return back()->with('error', 'วันนี้ท่านส่งเรื่องร้องเรียนครบ 3 ครั้งแล้ว');
        }

        $validated = $request->validate([
            'subject' => 'required|string|max:255', 'title' => 'required|string', 'first_name' => 'required|string', 'last_name' => 'required|string', 'age' => 'required|integer', 'house_no' => 'required|string', 'moo' => 'required|string', 'road' => 'nullable|string', 'community' => 'required|string', 'phone_number' => 'required|string', 'details' => 'required|string', 'latitude' => 'nullable|numeric', 'longitude' => 'nullable|numeric', 'map_capture' => 'nullable|string', 'photo_image' => 'nullable|image|max:2048',
        ]);

        $zone = $this->getZoneFromCommunity($request->community);

        $mapPath = null;
        if ($request->filled('map_capture')) {
            $imageName = 'map_' . time() . '.png';
            $image = str_replace(' ', '+', preg_replace('#^data:image/\w+;base64,#i', '', $request->map_capture));
            Storage::disk('public')->put('complaints/' . $imageName, base64_decode($image));
            $mapPath = 'complaints/' . $imageName;
        }

        $photoPath = null;
        if ($request->hasFile('photo_image')) {
            $photoPath = $request->file('photo_image')->store('complaints', 'public');
        }

        $complaint = Complaint::create([
            'user_id' => Auth::id(),
            'subject' => $validated['subject'],
            'title' => $validated['title'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'age' => $validated['age'],
            'house_no' => $validated['house_no'],
            'moo' => $validated['moo'],
            'road' => $request->road,
            'community' => $request->community,
            'zone' => $zone,
            'sub_district' => 'ในเขตเทศบาล',
            'district' => 'เมืองชัยภูมิ',
            'province' => 'ชัยภูมิ',
            'phone_number' => $validated['phone_number'],
            'details' => $validated['details'],
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'map_image_path' => $mapPath,
            'photo_image_path' => $photoPath,
            'status' => 'pending',
        ]);

        $adminEmails = User::where('role', 'admin')->pluck('email');
        if ($adminEmails->count() > 0) { Mail::to($adminEmails)->send(new NewComplaintNotification($complaint)); }

        return redirect()->route('complaints.history')->with('success', 'บันทึกคำร้องเรียบร้อยแล้ว');
    }

    // 4. หน้าประวัติ (User & สท.)
    public function history()
    {
        $user = Auth::user();

        if ($user->role === 'council_member') {
            // --- สท. (Council Member) ---
            $zoneId = preg_replace('/[^0-9]/', '', $user->zone);

            // 1. เรื่องที่ สท. ยื่นเอง
            $mySubmissions = Complaint::where('user_id', $user->id)->latest()->get();
            
            // 2. เรื่องจากชาวบ้านในเขต (รวมถึง Guest)
            // 🔥 แก้ไขตรงนี้: เพิ่ม orWhereNull('user_id') เพื่อให้เห็น Guest ด้วย 🔥
            $zoneComplaints = Complaint::where('zone', $zoneId)
                                       ->where(function($query) use ($user) {
                                           $query->where('user_id', '!=', $user->id)
                                                 ->orWhereNull('user_id'); // รวม Guest ที่ไม่มี user_id
                                       })
                                       ->latest()
                                       ->get();

            return view('complaint.council_dashboard', compact('mySubmissions', 'zoneComplaints'));

        } else {
            // --- User ---
            $complaints = Complaint::where('user_id', $user->id)->latest()->get();
            return view('complaint.history', compact('complaints'));
        }
    }

    // 5. ดาวน์โหลด Word
    public function downloadDocx($id)
    {
        $complaint = Complaint::findOrFail($id);
        if (auth()->user()->role !== 'admin') abort(403, 'Unauthorized action.');

        $templatePath = storage_path('app/templates/คำร้องทั่วไป.docx'); 
        $fileName = 'คำร้อง_' . $complaint->id . '.docx';
        $tempPath = storage_path('app/public/temp/' . $fileName);

        if (!file_exists(dirname($tempPath))) mkdir(dirname($tempPath), 0755, true);

        try {
            $templateProcessor = new TemplateProcessor($templatePath);
            $date = Carbon::parse($complaint->created_at);
            $thaiMonths = [1 => 'มกราคม', 2 => 'กุมภาพันธ์', 3 => 'มีนาคม', 4 => 'เมษายน', 5 => 'พฤษภาคม', 6 => 'มิถุนายน', 7 => 'กรกฎาคม', 8 => 'สิงหาคม', 9 => 'กันยายน', 10 => 'ตุลาคม', 11 => 'พฤศจิกายน', 12 => 'ธันวาคม'];

            $templateProcessor->setValue('วัน', $date->day);
            $templateProcessor->setValue('เดือน', $thaiMonths[$date->month]);
            $templateProcessor->setValue('พ.ศ.', $date->year + 543);
            $templateProcessor->setValue('หัวเรื่อง', $complaint->subject);
            $templateProcessor->setValue('คำนำหน้า', $complaint->title);
            $templateProcessor->setValue('ชื่อ', $complaint->first_name);
            $templateProcessor->setValue('นามสกุล', $complaint->last_name);
            $templateProcessor->setValue('อายุ', $complaint->age);
            $templateProcessor->setValue('บ้านเลขที่', $complaint->house_no);
            $templateProcessor->setValue('หมู่', $complaint->moo);
            $templateProcessor->setValue('ถนน', $complaint->road ?? '-');
            $templateProcessor->setValue('ชุมชน', $complaint->community ?? '-');
            $templateProcessor->setValue('ตำบล', $complaint->sub_district);
            $templateProcessor->setValue('อำเภอ', $complaint->district);
            $templateProcessor->setValue('จังหวัด', $complaint->province);
            $templateProcessor->setValue('เบอร์', $complaint->phone_number);
            $templateProcessor->setValue('เนื้อหาคำร้อง', $complaint->details);

            if ($complaint->map_image_path && Storage::disk('public')->exists($complaint->map_image_path)) {
                $templateProcessor->setImageValue('แผนที่', ['path' => Storage::disk('public')->path($complaint->map_image_path), 'width' => 400, 'height' => 300, 'ratio' => true]);
            } else { $templateProcessor->setValue('แผนที่', ''); }

            if ($complaint->photo_image_path && Storage::disk('public')->exists($complaint->photo_image_path)) {
                $templateProcessor->setImageValue('รูปภาพ', ['path' => Storage::disk('public')->path($complaint->photo_image_path), 'width' => 400, 'height' => 300, 'ratio' => true]);
            } else { $templateProcessor->setValue('รูปภาพ', ''); }

            $templateProcessor->saveAs($tempPath);
            return response()->download($tempPath)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            return back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }

    // 6. อัปเดตสถานะ
    // 6. อัปเดตสถานะ (Admin) - แก้ไขให้กดบันทึกค่าเฉยๆ ได้
    public function process(Request $request, $id)
    {
        $complaint = Complaint::findOrFail($id);
        if (auth()->user()->role !== 'admin') abort(403);

        // 🔥 เพิ่ม 'pending' เข้าไปในรายการที่ยอมรับ 🔥
        $request->validate([
            'status' => 'required|in:waiting,in_progress,completed,rejected,unsuccessful,pending',
            'admin_notes' => 'nullable|string',
            'zone' => 'nullable|string',
        ]);

        // ถ้าไม่ใช่สถานะ pending ให้เปลี่ยนสถานะได้ (ถ้าเป็น pending คือแค่บันทึกข้อมูลอื่น)
        if ($request->status !== 'pending') {
            $complaint->status = $request->status;
        }

        // บันทึกข้อความตอบกลับ
        if ($request->filled('admin_notes')) {
            $complaint->admin_notes = $request->admin_notes;
        }
        
        // บันทึกเขต
        if ($request->filled('zone')) {
            $complaint->zone = $request->zone;
        }
        
        $complaint->processed_by_user_id = auth()->id();
        $complaint->save();

        // ส่งเมลเฉพาะตอนเปลี่ยนสถานะจริง (ไม่ใช่แค่กดบันทึก)
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
            // เช็คสิทธิ์การเข้าดู
            if (auth()->user()->role !== 'admin' && auth()->user()->id !== $complaint->user_id) {
                 // สท. ดูในเขตตัวเองได้
                 if (auth()->user()->role === 'council_member') {
                     $myZone = preg_replace('/[^0-9]/', '', auth()->user()->zone);
                     if ($complaint->zone != $myZone) abort(403);
                 } else {
                     abort(403);
                 }
            }

            // 🔥 เพิ่มส่วนนี้ครับ: ถ้าเป็น Admin ให้ไปที่หน้า Admin Show (ที่มีปุ่มบันทึก) 🔥
            if (auth()->user()->role === 'admin') {
                return view('admin.complaints.show', compact('complaint'));
            }
        }

        // ถ้าเป็นคนทั่วไป หรือ สท. ให้ไปหน้าเดิมปกติ
        return view('complaint.show', compact('complaint'));
    }

    // 8. ลบรายการเดียว
    public function destroy($id)
    {
        $complaint = Complaint::findOrFail($id);
        if (auth()->user()->role !== 'admin') abort(403);
        
        if ($complaint->map_image_path) Storage::disk('public')->delete($complaint->map_image_path);
        if ($complaint->photo_image_path) Storage::disk('public')->delete($complaint->photo_image_path);

        $complaint->delete();
        return back()->with('success', 'ลบข้อมูลรายการนี้เรียบร้อยแล้ว');
    }

    // 9. ลบแบบกลุ่ม
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

        $items = $query->get();
        foreach ($items as $item) {
            if ($item->map_image_path) Storage::disk('public')->delete($item->map_image_path);
            if ($item->photo_image_path) Storage::disk('public')->delete($item->photo_image_path);
        }

        $query->delete();
        return back()->with('success', $msg);
    }

    // 10. หน้าฟอร์ม Guest
    public function guestCreate() { return view('complaint.guest_create'); }

    // 11. บันทึก Guest
    public function guestStore(Request $request)
    {
        $request->validate([
            'citizen_id' => 'required|numeric|digits:13', 
            'subject' => 'required|string',
            'title' => 'required|string',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'age' => 'required|integer',
            'house_no' => 'required|string',
            'moo' => 'required|string',
            'community' => 'required|string',
            'phone_number' => 'required|string',
            'details' => 'required',
        ]);

        $countToday = Complaint::where('citizen_id', $request->citizen_id)->whereDate('created_at', Carbon::today())->count();
        if ($countToday >= 3) return back()->withInput()->withErrors(['citizen_id' => 'ครบโควต้า 3 ครั้ง/วัน แล้ว']);

        $zone = $this->getZoneFromCommunity($request->community);

        $mapPath = null;
        if ($request->filled('map_capture')) {
            $imageName = 'guest_map_' . time() . '.png';
            $image = str_replace(' ', '+', preg_replace('#^data:image/\w+;base64,#i', '', $request->map_capture));
            Storage::disk('public')->put('complaints/' . $imageName, base64_decode($image));
            $mapPath = 'complaints/' . $imageName;
        }

        $photoPath = null;
        if ($request->hasFile('photo_image')) {
            $photoPath = $request->file('photo_image')->store('complaints', 'public');
        }

        $complaint = Complaint::create([
            'user_id' => null,
            'citizen_id' => $request->citizen_id,
            'subject' => $request->subject,
            'title' => $request->title ?? 'คุณ',
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'age' => $request->age ?? 0,
            'house_no' => $request->house_no,
            'moo' => $request->moo,
            'road' => $request->road ?? null,
            'community' => $request->community,
            'zone' => $zone, // บันทึกเขต (ถ้าชุมชนอยู่ในรายการ)
            'sub_district' => 'ในเขตเทศบาล',
            'district' => 'เมืองชัยภูมิ',
            'province' => 'ชัยภูมิ',
            'phone_number' => $request->phone_number,
            'details' => $request->details,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'map_image_path' => $mapPath,
            'photo_image_path' => $photoPath,
            'status' => 'pending',
        ]);

        $adminEmails = User::where('role', 'admin')->pluck('email');
        if ($adminEmails->count() > 0) { Mail::to($adminEmails)->send(new NewComplaintNotification($complaint)); }

        return redirect('/')->with('success', 'ส่งเรื่องร้องเรียนเรียบร้อยแล้ว! เจ้าหน้าที่จะเร่งตรวจสอบครับ');
    }

    // 12. หน้าแก้ไขคำร้อง
    // 12. หน้าแก้ไขคำร้อง (แก้ไข: ให้ Admin เข้าได้ตลอดเวลา)
    public function edit($id)
    {
        $complaint = Complaint::findOrFail($id);

        // ถ้าเป็น Admin ให้เข้าได้เลย
        if (auth()->user()->role === 'admin') {
            return view('complaint.edit', compact('complaint'));
        }

        // ถ้าเป็น User ต้องเป็นเจ้าของและไม่เกิน 10 นาที
        if (auth()->id() !== $complaint->user_id) abort(403);
        if ($complaint->created_at->addMinutes(10)->isPast()) {
            return back()->with('error', 'หมดเวลาแก้ไขข้อมูลแล้ว');
        }

        return view('complaint.edit', compact('complaint'));
    }

    // 13. บันทึกการแก้ไข (แก้ไข: ให้ Admin บันทึกได้ / เปลี่ยนเขตได้)
    public function update(Request $request, $id)
    {
        $complaint = Complaint::findOrFail($id);
        $user = Auth::user();

        // ตรวจสอบสิทธิ์
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

        // ถ้ามีการเปลี่ยนชุมชน ให้คำนวณเขตใหม่ (เฉพาะ User แก้, Admin แก้เขตเองได้)
        if ($user->role !== 'admin') {
            $zone = $this->getZoneFromCommunity($request->community);
            $complaint->zone = $zone;
        }

        $complaint->fill($request->except(['map_capture', 'photo_image']));

        // จัดการรูปภาพ (Map & Photo)
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

        // บันทึกประวัติการแก้ไข
        $timestamp = date('d/m/Y H:i');
        $actor = $user->role === 'admin' ? 'แอดมิน' : 'ผู้แจ้ง';
        $history = "🔴 แก้ไขโดย {$actor} เมื่อ {$timestamp}";
        $complaint->user_edit_note = $history . "\n" . $complaint->user_edit_note;
        
        $complaint->save();

        // Redirect กลับตามบทบาท
        if ($user->role === 'admin') {
            return redirect()->route('admin.complaints.index')->with('success', 'แอดมินแก้ไขข้อมูลเรียบร้อยแล้ว');
        }
        
        return redirect()->route('complaints.history')->with('success', 'แก้ไขข้อมูลเรียบร้อยแล้ว');
    }
}