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
    Schema::table('news', function (Blueprint $table) {
        // เพิ่มคอลัมน์เนื้อหาข่าว (LongText รองรับเนื้อหายาวๆ และรูปภาพ Base64)
        $table->longText('content')->nullable()->after('title');

        // เพิ่มสถานะ (เผื่ออยากร่างข่าวไว้ก่อน)
        $table->enum('status', ['published', 'draft'])->default('published')->after('content');

        // ปรับ link_url ให้เป็นว่างได้ (เพราะเราจะเขียนข่าวเองแล้ว)
        $table->string('link_url')->nullable()->change();
    });
}

public function down(): void
{
    Schema::table('news', function (Blueprint $table) {
        $table->dropColumn(['content', 'status']);
        // $table->string('link_url')->nullable(false)->change(); // ถ้าต้องการย้อนกลับ
    });
}
};
