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
        Schema::table('users', function (Blueprint $table) {
            // إضافة عمود role بعد عمود password مباشرة
            // وضعنا القيمة الافتراضية 'customer' لكي يكون أي مسجل جديد عميلاً بشكل آلي
            $table->string('role')->default('customer')->after('password');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // لحذف العمود في حال أردنا التراجع
            $table->dropColumn('role');
        });
    }
};
