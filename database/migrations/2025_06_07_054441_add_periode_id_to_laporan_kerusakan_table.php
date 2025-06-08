<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPeriodeIdToLaporanKerusakanTable extends Migration
{
    public function up()
    {
        Schema::table('laporan_kerusakan', function (Blueprint $table) {
            $table->unsignedBigInteger('periode_id')->nullable()->after('sub_kriteria_id');
            $table->foreign('periode_id')->references('id')->on('periodes')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('laporan_kerusakan', function (Blueprint $table) {
            $table->dropForeign(['periode_id']);
            $table->dropColumn('periode_id');
        });
    }
}
