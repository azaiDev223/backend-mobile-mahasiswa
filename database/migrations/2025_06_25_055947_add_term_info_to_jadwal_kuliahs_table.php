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
        Schema::table('jadwal_kuliahs', function (Blueprint $table) {
            //
            // Tambahkan kolom-kolom ini setelah kolom 'ruangan'
            $table->string('tahun_akademik', 20)->after('ruangan');
            $table->integer('kuota')->unsigned()->after('tahun_akademik');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_kuliahs', function (Blueprint $table) {
            //
            // Untuk bisa rollback jika terjadi kesalahan
            $table->dropColumn(['tahun_akademik', 'kuota']);
        });
    }
};
