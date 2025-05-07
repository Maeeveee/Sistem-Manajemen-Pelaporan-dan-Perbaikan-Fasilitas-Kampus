<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // USERS
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'mahasiswa', 'dosen', 'tendik', 'sarpras', 'teknisi']);
            $table->string('photo')->nullable();
            $table->timestamps();
        });

        // BUILDINGS
        Schema::create('buildings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('location')->nullable();
            $table->timestamps();
        });

        // FACILITIES
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('category', ['perangkat keras', 'jaringan', 'software', 'lainnya']);
            $table->string('location')->nullable();
            $table->foreignId('building_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['baik', 'rusak', 'diperbaiki'])->default('baik');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // REPORTS
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('facility_id')->constrained()->onDelete('cascade');
            $table->text('description');
            $table->string('photo')->nullable();
            $table->enum('urgency', ['rendah', 'sedang', 'tinggi'])->default('sedang');
            $table->enum('status', ['diajukan', 'diverifikasi', 'diproses', 'selesai'])->default('diajukan');
            $table->timestamps();
        });

        // REPAIRS
        Schema::create('repairs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained()->onDelete('cascade');
            $table->foreignId('technician_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('assigned_by')->constrained('users')->onDelete('cascade');
            $table->text('repair_notes')->nullable();
            $table->date('repair_date')->nullable();
            $table->enum('status', ['belum dikerjakan', 'dalam proses', 'selesai'])->default('belum dikerjakan');
            $table->text('feedback')->nullable();
            $table->tinyInteger('rating')->nullable();
            $table->timestamps();
        });

        // REPAIR PERIODS
        Schema::create('repair_periods', function (Blueprint $table) {
            $table->id();
            $table->year('year');
            $table->enum('semester', ['Ganjil', 'Genap']);
            $table->timestamps();
        });

        // PRIORITIES
        Schema::create('priorities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained()->onDelete('cascade');
            $table->enum('urgency_level', ['tinggi', 'sedang', 'rendah']);
            $table->text('reason');
            $table->foreignId('decided_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('priorities');
        Schema::dropIfExists('repair_periods');
        Schema::dropIfExists('repairs');
        Schema::dropIfExists('reports');
        Schema::dropIfExists('facilities');
        Schema::dropIfExists('buildings');
        Schema::dropIfExists('users');
    }
};
