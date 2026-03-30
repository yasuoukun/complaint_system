<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplaintImage extends Model
{
    use HasFactory;
    
    // ✅ ต้องมีบรรทัดนี้ ไม่งั้นอัปโหลดรูปไปก็ไม่เข้าฐานข้อมูล
    protected $fillable = ['complaint_id', 'image_path'];

    public function complaint()
    {
        return $this->belongsTo(Complaint::class);
    }
}