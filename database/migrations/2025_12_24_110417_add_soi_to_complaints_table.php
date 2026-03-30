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
    Schema::table('complaints', function (Blueprint $table) {
        $table->string('soi')->nullable()->after('road'); // เพิ่มต่อจาก road
    });
}

public function down()
{
    Schema::table('complaints', function (Blueprint $table) {
        $table->dropColumn('soi');
    });
}
};
