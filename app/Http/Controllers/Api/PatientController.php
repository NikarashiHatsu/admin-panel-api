<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Patient;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Ambil semua data pasien dari database
        $patients = Patient::all();

        // Tampilkan data pasien
        return response()->json([
            'message' => 'Berhasil mengambil data semua pasien.',
            'data' => $patients,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Custom message untuk validator
        $messages = [
            'required' => 'Kolom :attribute perlu diisi.',
            'min' => 'Kolom :attribute membutuhkan setidaknya :min karakter.',
            'max' => 'Kolom :attribute tidak boleh melebihi :max karakter.',
            'integer' => 'Kolom :attribute harus berisi angka.',
            'in' => 'Kolom ini hanya bisa diisi dengan "ayah", "ibu", atau "anak".',
        ];

        // Validator
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => ['required', 'min:1', 'max:255'],
            'nik' => ['required', 'min:16', 'max:16'],
            'alamat' => ['required'],
            'no_rekam_medis' => ['required', 'min:1', 'max:64'],
            'tinggi_badan' => ['required', 'integer', 'min:1', 'max:999'],
            'berat_badan' => ['required', 'integer', 'min:1', 'max:999'],
            'peranan_keluarga' => ['required', 'in:ayah,ibu,anak'],
        ]);

        // Cek jika validator gagal
        if($validator->fails()) {
            return response()->json([
                'message' => 'Terjadi kesalahan pada saat menambahkan data pasien.',
                'error' => $validator->getMessageBag(),
            ], 500);
        }

        // Daftarkan pasien
        $patient = new Patient;
        $patient->nama_lengkap = $request->nama_lengkap;
        $patient->nik = $request->nik;
        $patient->alamat = $request->alamat;
        $patient->no_rekam_medis = $request->no_rekam_medis;
        $patient->tinggi_badan = $request->tinggi_badan;
        $patient->peranan_keluarga = $request->peranan_keluarga;
        $patient->riwayat_penyakit = ($request->riwayat_penyakit ? $request->riwayat_penyakit : null);
        $patient->save();

        // Tampilkan data pasien yang baru didaftarkan
        return response()->json([
            'message' => 'Berhasil menambahkan data pasien.',
            'data' => $patient
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function show(Patient $patient)
    {
        // Tampilkan detail pasien
        return response()->json([
            'message' => 'Detail pasien berhasil diambil.',
            'data' => $patient,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function edit(Patient $patient)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Patient $patient)
    {
        // Custom message untuk validator
        $messages = [
            'required' => 'Kolom :attribute perlu diisi.',
            'min' => 'Kolom :attribute membutuhkan setidaknya :min karakter.',
            'max' => 'Kolom :attribute tidak boleh melebihi :max karakter.',
            'integer' => 'Kolom :attribute harus berisi angka.',
            'in' => 'Kolom ini hanya bisa diisi dengan "ayah", "ibu", atau "anak".',
        ];

        // Validator
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => ['min:1', 'max:255'],
            'nik' => ['min:16', 'max:16'],
            'no_rekam_medis' => ['min:1', 'max:64'],
            'tinggi_badan' => ['integer', 'min:1', 'max:999'],
            'berat_badan' => ['integer', 'min:1', 'max:999'],
            'peranan_keluarga' => ['in:ayah,ibu,anak'],
        ]);

        // Cek jika validator gagal
        if($validator->fails()) {
            return response()->json([
                'message' => 'Terjadi kesalahan pada saat menambahkan data pasien.',
                'error' => $validator->getMessageBag(),
            ], 500);
        }

        // Update data pasien
        if($request->nama_lengkap != '') {
            $patient->nama_lengkap = $request->nama_lengkap;
        }

        if($request->nik != '') {
            $patient->nik = $request->nik;
        }

        if($request->alamat != '') {
            $patient->alamat = $request->alamat;
        }

        if($request->no_rekam_medis != '') {
            $patient->no_rekam_medis = $request->no_rekam_medis;
        }

        if($request->tinggi_badan != '') {
            $patient->tinggi_badan = $request->tinggi_badan;
        }

        if($request->berat_badan != '') {
            $patient->berat_badan = $request->berat_badan;
        }

        if($request->peranan_keluarga != '') {
            $patient->peranan_keluarga = $request->peranan_keluarga;
        }

        if($request->riwayat_penyakit != '') {
            $patient->riwayat_penyakit = ($request->riwayat_penyakit ? $request->riwayat_penyakit : null);
        }

        $patient->save();

        // Tampilkan response
        return response()->json([
            'message' => 'Berhasil mengubah data pasien.',
            'data' => $patient,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function destroy(Patient $patient)
    {
        // Hapus data pasien
        $patient->delete();

        /// Tampilkan reponse
        return response()->json([
            'message' => 'Berhasil menghapus data pasien.',
        ], 200);
    }
}
