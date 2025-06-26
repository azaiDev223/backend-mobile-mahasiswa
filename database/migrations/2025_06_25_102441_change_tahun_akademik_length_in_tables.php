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
        

        // Mengubah panjang kolom di tabel 'krs'
        Schema::table('krs', function (Blueprint $table) {
            // Mengubah tipe kolom menjadi varchar dengan panjang 20
            $table->string('tahun_akademik', 20)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tables', function (Blueprint $table) {
            //
        });
    }
};
