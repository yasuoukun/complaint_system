<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $table = 'news'; // ชื่อตาราง

    protected $fillable = [
        'title',
        'content',      // <--- ⚠️ ต้องมีบรรทัดนี้ครับ!
        'image_path',
        'link_url',
        'status',
        'user_id',
    ];
}