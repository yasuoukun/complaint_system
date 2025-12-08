<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    // แสดงหน้าจัดการข่าว
    public function index()
    {
        $news = News::latest()->get();
        return view('admin.news.index', compact('news'));
    }

    // บันทึกข่าวใหม่
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:5120', // ไม่เกิน 5MB
            'title' => 'nullable|string|max:255',
            'link_url' => 'nullable|url',
        ]);

        $path = $request->file('image')->store('news', 'public');

        News::create([
            'title' => $request->title,
            'image_path' => $path,
            'link_url' => $request->link_url,
        ]);

        return back()->with('success', 'ลงประกาศข่าวสารเรียบร้อยแล้ว');
    }

    // แสดงหน้าฟอร์มแก้ไข (เพิ่มใหม่)
    public function edit($id)
    {
        $news = News::findOrFail($id);
        return view('admin.news.edit', compact('news'));
    }

    // อัปเดตข้อมูล (เพิ่มใหม่)
    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);

        $request->validate([
            'image' => 'nullable|image|max:5120', // ไม่บังคับรูป (ถ้าไม่เปลี่ยน)
            'title' => 'nullable|string|max:255',
            'link_url' => 'nullable|url',
        ]);

        // อัปเดตข้อความ
        $news->title = $request->title;
        $news->link_url = $request->link_url;

        // ถ้ามีการอัปโหลดรูปใหม่
        if ($request->hasFile('image')) {
            // ลบรูปเก่าทิ้ง
            if (Storage::disk('public')->exists($news->image_path)) {
                Storage::disk('public')->delete($news->image_path);
            }
            // บันทึกรูปใหม่
            $path = $request->file('image')->store('news', 'public');
            $news->image_path = $path;
        }

        $news->save();

        return redirect()->route('admin.news.index')->with('success', 'แก้ไขข่าวสารเรียบร้อยแล้ว');
    }

    // ลบข่าว
    public function destroy($id)
    {
        $news = News::findOrFail($id);
        
        if (Storage::disk('public')->exists($news->image_path)) {
            Storage::disk('public')->delete($news->image_path);
        }
        
        $news->delete();

        return back()->with('success', 'ลบข่าวสารเรียบร้อยแล้ว');
    }
}