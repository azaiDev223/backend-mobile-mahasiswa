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
        Schema::create('khs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->onDelete('cascade');
            $table->integer('semester');
            $table->string('tahun_akademik', 9);
            $table->decimal('ip', 5, 2)->nullable();   // Total nilai mentah
            $table->decimal('ips', 5, 2)->nullable();  // IP semester
            $table->decimal('ipk', 5, 2)->nullable();  // IPK kumulatif
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('khs');
    }
};
