<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap', 64);
            $table->char('nik', 16);
            $table->text('alamat');
            $table->string('no_rekam_medis', 64);
            $table->char('tinggi_badan', 3);
            $table->char('berat_badan', 3);
            $table->enum('peranan_keluarga', ['ayah', 'ibu', 'anak']);
            $table->text('riwayat_penyakit')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patients');
    }
}
