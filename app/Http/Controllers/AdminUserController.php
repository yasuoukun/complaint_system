<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Complaint;

class AdminUserController extends Controller
{
    // 1. แสดงรายชื่อสมาชิก (พร้อมระบบค้นหา)
    public function index(Request $request)
    {
        // เริ่มต้น Query: ดึงทุกคน ยกเว้นตัวเอง
        $query = User::where('id', '!=', Auth::id());

        // 🔍 ค้นหาจากคำค้น (ชื่อ, นามสกุล, อีเมล, เบอร์)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        // 🔍 กรองตามตำแหน่ง
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // จัดเรียงล่าสุดขึ้นก่อน
        $users = $query->orderBy('created_at', 'desc')->get();
                     
        return view('admin.users.index', compact('users'));
    }

    // 2. อัปเดตตำแหน่ง
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'role' => 'required|in:complainant,council_member,admin',
            'zone' => 'nullable|string',
        ]);

        $user->role = $request->role;
        
        if ($request->role == 'council_member') {
            $user->zone = $request->zone;
        } else {
            $user->zone = null;
        }

        $user->save();

        return back()->with('success', 'อัปเดตข้อมูลสมาชิกเรียบร้อยแล้ว');
    }

    // 3. ลบสมาชิก
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'ไม่สามารถลบบัญชีตัวเองได้');
        }

        $user->delete();

        return back()->with('success', 'ลบบัญชีผู้ใช้งานเรียบร้อยแล้ว');
    }

    public function show($id)
    {
        // 1. ดึงข้อมูลสมาชิก
        $user = User::findOrFail($id);

        // 2. ดึงประวัติการร้องเรียนทั้งหมดของคนนี้
        $history = Complaint::where('user_id', $id)
                        ->orderBy('created_at', 'desc')
                        ->get();

        // 3. ส่งไปหน้า View
        return view('admin.users.show', compact('user', 'history'));
    }

}