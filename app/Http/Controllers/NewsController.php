<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::latest()->paginate(10);
        return view('admin.news.index', compact('news'));
    }

    // ฟังก์ชัน Create จะรับค่า type มาเพื่อเลือกว่าจะเปิดหน้าไหน
    public function create(Request $request)
    {
        $type = $request->query('type');

        if ($type == 'link') {
            return view('admin.news.create_link'); // หน้าแบบเก่า (แนบลิงก์)
        } else {
            return view('admin.news.create_content'); // หน้าแบบใหม่ (เขียนเอง)
        }
    }

    public function store(Request $request)
    {
        // เช็คประเภทข่าว
        $type = $request->input('news_type');

        if ($type == 'link') {
            // --- 🟢 แบบที่ 1: ข่าวแนบลิงก์ ---
            $request->validate([
                'title' => 'required|string|max:255',
                'image_path' => 'required|image|max:10240',
                'link_url' => 'required|url',
                'content' => 'nullable', // ✅ เพิ่มบรรทัดนี้: อนุญาตให้ใส่เนื้อหาได้
            ]);
        } else {
            // --- 🔵 แบบที่ 2: เขียนข่าวเอง ---
            $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required',
                'image_path' => 'nullable|image|max:10240',
            ]);
        }

        // ส่วนบันทึกรูปภาพ (เหมือนเดิม)
        $imagePath = null;
        if ($request->hasFile('image_path')) {
            $imagePath = $request->file('image_path')->store('news', 'public');
        }

        // บันทึกข้อมูล
        News::create([
            'title' => $request->input('title'),
            'content' => $request->input('content'),     // บันทึกเนื้อหา (ถ้ามี)
            'link_url' => $request->input('link_url'),   // บันทึกลิงก์ (ถ้ามี)
            'image_path' => $imagePath,
            'status' => 'published',
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('admin.news.index')->with('success', 'ลงประกาศข่าวเรียบร้อยแล้ว');
    }

    // ส่วน Edit/Update/Destroy คงไว้เหมือนเดิม (ใช้ร่วมกันได้)
    public function edit($id)
    {
        $news = News::findOrFail($id);
        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'image_path' => 'nullable|image|max:10240',
        ]);

        $news->title = $request->input('title');
        
        // อัปเดตทั้งคู่ (ค่าไหนไม่ได้แก้จะเป็นค่าเดิมหรือว่าง)
        if ($request->has('content')) $news->content = $request->input('content');
        if ($request->has('link_url')) $news->link_url = $request->input('link_url');

        if ($request->hasFile('image_path')) {
            if ($news->image_path && Storage::disk('public')->exists($news->image_path)) {
                Storage::disk('public')->delete($news->image_path);
            }
            $news->image_path = $request->file('image_path')->store('news', 'public');
        }

        $news->save();
        return redirect()->route('admin.news.index')->with('success', 'แก้ไขข้อมูลเรียบร้อยแล้ว');
    }

    public function destroy($id)
    {
        $news = News::findOrFail($id);
        if ($news->image_path && Storage::disk('public')->exists($news->image_path)) {
            Storage::disk('public')->delete($news->image_path);
        }
        $news->delete();
        return back()->with('success', 'ลบข้อมูลเรียบร้อยแล้ว');
    }
    public function show($id)
    {
        $news = News::findOrFail($id);
        
        // ถ้าเป็นข่าวแบบแนบลิงก์ ให้เด้งไปลิงก์นั้นเลย (กันเหนียว)
        if ($news->link_url) {
            return redirect($news->link_url);
        }

        return view('news.show', compact('news'));
    }
}