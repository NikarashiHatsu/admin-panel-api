<?php

namespace App\Http\Controllers\Api;

use App\AppManagement;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class AppManagementController extends Controller
{
    /**
     * Get base url
     * 
     * @return Illuminate\Http\Response
     */
    public function get_base_url()
    {
        $base = AppManagement::first()->base_url;

        // Ambil data base_url
        return response()->json([
            'message' => 'Berhasil mengambil data base url.',
            'data' => $base,
        ], 200);
    }

    /**
     * Update base url
     * 
     * @param Illuminate\Http\Request $request
     * 
     * @return Illuminate\Http\Response
     */
    public function update_base_url(Request $request)
    {
        // Custom message untuk validator
        $message = [
            'required' => 'Kolom :attribute perlu diisi.',
            'string' => 'Kolom :attribute hanya boleh diisi dengan huruf.',
        ];

        // Validator
        $validator = Validator::make($request->all(), [
            'base_url' => ['required', 'string'],
        ], $message);

        // Tampilkan response jika validator gagal
        if($validator->fails()) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat mengubah base_url.',
                'error' => $validator->getMessageBag(),
            ], 500);
        }

        // Update base url
        $appManagement = AppManagement::first();

        if($request->base_url != '') {
            $appManagement->base_url = $request->base_url;
        }

        $appManagement->save();

        // Tampilkan response
        return response()->json([
            'message' => 'Berhasil mengubah base url.',
            'data' => $appManagement->base_url,
        ], 200);
    }

    /**
     * Get the logo
     *  
     * @return Illuminate\Http\Response
     */
    public function get_logo()
    {
        // Ambil url logo
        $logo = AppManagement::first();

        // Tampilkan response
        return response()->json([
            'message' => 'Berhasil mengambil logo.',
            'data' => $logo->logo,
        ], 200);
    }
    
    /**
     * Update the logo
     * 
     * @param Illuminate\Http\Request $request
     * 
     * @return Illuminate\Http\Response;
     */
    public function update_logo(Request $request)
    {
        // Custom message
        $messages = [
            'required' => 'Kolom :attribute perlu diisi.',
            'file' => 'Kolom :attribute harus diisi dengan file.',
            'mimetype' => 'Kolom :attribute harus diisi dengan foto yang berekstensi jpg, jpeg, atau png.',
            'size' => 'File foto tidak boleh melebihi 3MB.',
        ];

        // Validator
        $validator = Validator::make($request->all(), [
            // 'logo' => ['file', 'mimetype:image/jpg,image/jpeg,image/png', 'size:3072'],
        ], $messages);

        // Tampilkan response jika validator gagal memvalidasi
        if($validator->fails()) {
            return response()->json([
                'message' => 'Ada kesalahan pada saat mengupload logo.',
                'error' => $validator->getMessageBag(),
            ], 500);
        }

        // Upload file
        $path = '';

        if($request->hasFile('logo')) {
            $extension = $request->logo->extension();
            $path = $request->logo->storeAs('logo', 'logo-' . Carbon::now() . '.' . $extension);

            if(!$request->file('logo')->isValid()) {
                return response()->json([
                    'message' => 'Ada kesalahan pada saat mengunggah logo.',
                ], 500);
            }
        }

        // Update logo
        $logo = AppManagement::first();

        if($path != '') {
            $logo->logo = $path;
        }

        $logo->save();

        // Tampilkan response
        return response()->json([
            'message' => 'Berhasil menyimpan logo.',
            'data' => $logo->logo,
        ], 200);
    }
}
