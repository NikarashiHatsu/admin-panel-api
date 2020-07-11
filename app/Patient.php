<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama_lengkap', 'nik', 'alamat', 'no_rekam_medis', 'tinggi_badan', 'berat_badan', 'peranan_keluarga', 'riwayat_penyakit'
    ];
}
