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
    Schema::table('complaints', function (Blueprint $table) {
        // เพิ่มช่องเก็บเลขบัตรประชาชน
        $table->string('citizen_id')->nullable()->after('user_id');
        
        // แก้ให้ user_id เป็นค่าว่างได้ (สำหรับคนไม่ได้ล็อกอิน)
        $table->unsignedBigInteger('user_id')->nullable()->change();
    });
}

public function down(): void
{
    Schema::table('complaints', function (Blueprint $table) {
        $table->dropColumn('citizen_id');
        $table->unsignedBigInteger('user_id')->nullable(false)->change();
    });
}
};
