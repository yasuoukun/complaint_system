<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('complaint_images', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('complaint_id'); // เชื่อมกับตารางหลัก
        $table->string('image_path'); // เก็บ path รูป
        $table->timestamps();

        // เชื่อม FK (ถ้าลบคำร้อง รูปจะหายด้วย)
        $table->foreign('complaint_id')->references('id')->on('complaints')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaint_images');
    }
};
