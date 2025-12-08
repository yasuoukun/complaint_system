<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    // กำหนดความสัมพันธ์กับตาราง User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // *** สำคัญ: ต้องเพิ่ม 'user_id' เข้าไปในนี้ด้วย ***
    protected $fillable = [
        'user_id',      // <--- เพิ่มบรรทัดนี้ครับ
        'citizen_id',
        'subject', 
        'title', 
        'first_name', 
        'last_name', 
        'age',
        'house_no', 
        'moo', 
        'road', 
        'community', 
        'sub_district',
        'district', 
        'province', 
        'phone_number', 
        'details',
        'map_image_path', 
        'photo_image_path',
        'user_edit_note',
        'zone',
        // 'status', 'admin_notes', 'processed_by_user_id' ไม่ต้องใส่ เพราะเราไม่ได้บันทึกผ่านฟอร์มสร้างคำร้อง
    ];
}
