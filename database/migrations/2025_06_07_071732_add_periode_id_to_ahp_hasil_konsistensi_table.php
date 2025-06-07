<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPeriodeIdToAhpHasilKonsistensiTable extends Migration
{
    public function up()
    {
        Schema::table('ahp_hasil_konsistensi', function (Blueprint $table) {
            $table->bigInteger('periode_id')->unsigned()->nullable()->after('is_consistent');
            $table->foreign('periode_id')->references('id')->on('periodes')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('ahp_hasil_konsistensi', function (Blueprint $table) {
            $table->dropForeign(['periode_id']);
            $table->dropColumn('periode_id');
        });
    }
}