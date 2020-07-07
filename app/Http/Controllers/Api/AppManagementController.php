<?php

namespace App\Http\Controllers\Api;

use App\AppManagement;
use App\Http\Controllers\Controller;
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
        // Ambil data base_url
        $base = AppManagement::all();

        return response()->json([
            'message' => 'Berhasil mengambil data base url.',
            'data' => $base->base_url,
        ], 200);
    }

    /**
     * Store base url
     * 
     * @param Illuminate\Http\Request $request
     * 
     * @return Illuminate\Http\Response;
     */
    public function store_base_url(Request $request)
    {
        // Custom message untuk validator
        $messages = [
            'required' => 'Kolom :attribute perlu diisi.',
            'string' => 'Kolom :attribute harus berisi huruf.',
        ];

        // Validator
        $validator = Validator::make($request->all(), [
            'base_url' => ['required', 'string'],
        ], $messages);

        // Jika validator gagal, tampilkan response
        if($validator->fails()) {
            return response()->json([
                'message' => 'Ada kesalahan pada saat membuat base url.',
                'error' => $validator->getMessageBag(),
            ], 500);
        }

        // Buat base url
        $app = new AppManagement;
        $app->base_url = $request->base_url;
        $app->save();

        // Tampilkan response
        return response()->json([
            'message' => 'Berhasil membuat base url.',
            'data' => $app->base_url,
        ], 200);
    }

    /**
     * Update base url
     * 
     * @param Illuminate\Http\Request $request
     * 
     * @return Illuminate\Http\Response
     */
    public function update_base_url(Request $request, AppManagement $appManagement)
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
}
