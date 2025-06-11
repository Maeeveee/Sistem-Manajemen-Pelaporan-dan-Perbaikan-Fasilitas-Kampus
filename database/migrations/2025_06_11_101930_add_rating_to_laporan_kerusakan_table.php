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
        Schema::table('laporan_kerusakan', function ($table) {
            $table->unsignedTinyInteger('rating')->nullable()->after('status_admin');
        });
    }

    public function down()
    {
        Schema::table('laporan_kerusakan', function ($table) {
            $table->dropColumn('rating');
        });
    }
};