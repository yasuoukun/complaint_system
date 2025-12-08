<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        // $table->string('name');  <-- ลบบรรทัดนี้ทิ้ง (เราไม่ใช้ชื่อรวมแล้ว)
        
        // 🔥 เพิ่ม 3 บรรทัดนี้เข้าไปแทน
        $table->string('first_name'); // ชื่อจริง
        $table->string('last_name');  // นามสกุล
        $table->string('phone_number'); // เบอร์โทร
        
        $table->string('email')->unique();
        $table->timestamp('email_verified_at')->nullable();
        $table->string('password');
        $table->enum('role', ['complainant', 'admin'])->default('complainant'); // อย่าลืมบรรทัดนี้ที่เราเคยเพิ่มไว้
        $table->rememberToken();
        $table->timestamps();
    });
    
    // ...
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
