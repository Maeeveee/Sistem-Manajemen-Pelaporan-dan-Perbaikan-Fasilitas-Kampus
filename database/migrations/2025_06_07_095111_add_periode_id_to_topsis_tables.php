<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPeriodeIdToTopsisTables extends Migration
{
    public function up()
    {
        // Tabel hasil_topsis
        Schema::table('hasil_topsis', function (Blueprint $table) {
            $table->unsignedBigInteger('periode_id')->nullable()->after('alternatif_id');
            $table->foreign('periode_id')->references('id')->on('periodes')->onDelete('set null');
        });

        // Tabel matriks_keputusan
        Schema::table('matriks_keputusan', function (Blueprint $table) {
            $table->unsignedBigInteger('periode_id')->nullable()->after('kriteria_id');
            $table->foreign('periode_id')->references('id')->on('periodes')->onDelete('set null');
        });

        // Tabel matriks_normalisasi_keputusan
        Schema::table('matriks_normalisasi_keputusan', function (Blueprint $table) {
            $table->unsignedBigInteger('periode_id')->nullable()->after('kriteria_id');
            $table->foreign('periode_id')->references('id')->on('periodes')->onDelete('set null');
        });

        // Tabel matriks_normalisasi_bobot_keputusan
        Schema::table('matriks_normalisasi_bobot_keputusan', function (Blueprint $table) {
            $table->unsignedBigInteger('periode_id')->nullable()->after('kriteria_id');
            $table->foreign('periode_id')->references('id')->on('periodes')->onDelete('set null');
        });

        // Tabel solusi_ideal_positif
        Schema::table('solusi_ideal_positif', function (Blueprint $table) {
            $table->unsignedBigInteger('periode_id')->nullable()->after('alternatif_id');
            $table->foreign('periode_id')->references('id')->on('periodes')->onDelete('set null');
        });

        // Tabel solusi_ideal_negatif
        Schema::table('solusi_ideal_negatif', function (Blueprint $table) {
            $table->unsignedBigInteger('periode_id')->nullable()->after('alternatif_id');
            $table->foreign('periode_id')->references('id')->on('periodes')->onDelete('set null');
        });

        // Tabel penilaian
        Schema::table('penilaian', function (Blueprint $table) {
            $table->unsignedBigInteger('periode_id')->nullable()->after('sub_kriteria_id');
            $table->foreign('periode_id')->references('id')->on('periodes')->onDelete('set null');
        });
    }

    public function down()
    {
        // Tabel hasil_topsis
        Schema::table('hasil_topsis', function (Blueprint $table) {
            $table->dropForeign(['periode_id']);
            $table->dropColumn('periode_id');
        });

        // Tabel matriks_keputusan
        Schema::table('matriks_keputusan', function (Blueprint $table) {
            $table->dropForeign(['periode_id']);
            $table->dropColumn('periode_id');
        });

        // Tabel matriks_normalisasi_keputusan
        Schema::table('matriks_normalisasi_keputusan', function (Blueprint $table) {
            $table->dropForeign(['periode_id']);
            $table->dropColumn('periode_id');
        });

        // Tabel matriks_normalisasi_bobot_keputusan
        Schema::table('matriks_normalisasi_bobot_keputusan', function (Blueprint $table) {
            $table->dropForeign(['periode_id']);
            $table->dropColumn('periode_id');
        });

        // Tabel solusi_ideal_positif
        Schema::table('solusi_ideal_positif', function (Blueprint $table) {
            $table->dropForeign(['periode_id']);
            $table->dropColumn('periode_id');
        });

        // Tabel solusi_ideal_negatif
        Schema::table('solusi_ideal_negatif', function (Blueprint $table) {
            $table->dropForeign(['periode_id']);
            $table->dropColumn('periode_id');
        });

        // Tabel penilaian
        Schema::table('penilaian', function (Blueprint $table) {
            $table->dropForeign(['periode_id']);
            $table->dropColumn('periode_id');
        });
    }
}