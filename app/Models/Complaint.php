<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    // ✅ เพิ่มฟิลด์ทั้งหมดที่อนุญาตให้บันทึกตรงนี้
    protected $fillable = [
        'user_id',
        'citizen_id',       // เลขบัตรประชาชน
        'title',            // คำนำหน้า
        'first_name',       // ชื่อ
        'last_name',        // นามสกุล
        'age',              // อายุ
        'phone_number',     // เบอร์โทร
        'house_no',         // บ้านเลขที่
        'moo',              // หมู่
        'road',             // ถนน
        'soi',              // ซอย
        'community',        // ชุมชน
        'sub_district',     // ตำบล
        'district',         // อำเภอ
        'province',         // จังหวัด
        'latitude',         // พิกัด
        'longitude',        // พิกัด
        'subject',          // หัวข้อเรื่อง (ตัวต้นเหตุ Error)
        'details',          // รายละเอียด
        'status',           // สถานะ
        'responsible_dept',
        'map_image_path',   // รูปแผนที่
        'photo_image_path', // รูปประกอบ (เก่า)
        'zone',             // เขต
        'admin_notes',      // โน้ตแอดมิน
        'processed_by_user_id', // คนจัดการ
        'user_edit_note',    // ประวัติแก้ไข
        'incident_community'
    ];

    // ความสัมพันธ์กับ User (ถ้ามี)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // ความสัมพันธ์กับรูปภาพ (ถ้ามี)
    public function images()
    {
        return $this->hasMany(ComplaintImage::class);
    }
}