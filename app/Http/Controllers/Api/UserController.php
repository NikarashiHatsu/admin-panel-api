<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Mendaftarkan pengguna
     * 
     * @param Illuminate\Http\Request $request
     * 
     * @return Illuminate\Http\Response JSON
     */
    public function register(Request $request)
    {   
        // Custom message untuk validator
        $messages = [
            'required' => 'Kolom :attribute perlu diisi.',
            'string' => 'Kolom :attribute harus bertipe data string.',
            'min' => 'Kolom :attribute harus berisi minimal :min karakter.',
            'max' => 'Kolom :attribute tidak boleh melebihi :max karakter.',
            'email' => 'Kolom :attribute harus berisi email yang valid.',
            'confirmed' => 'Kolom konfirmasi password tidak sama.',
        ];
        
        // Validasi input yang masuk
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'min:6', 'max:255'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'max:255', 'confirmed'],
            'password_confirmation' => ['required', 'string', 'min:8', 'max:255'], 
        ], $messages);

        // Return error response jika input tidak memenuhi kriteria validasi
        if($validator->fails()) {
            return response()->json([
                'message' => 'Terjadi kesalahan pada saat mendaftarkan pengguna.',
                'error' => $validator->getMessageBag(),
            ], 500);
        }

        // Daftarkan pengguna jika validasi berhasil
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
        
        // Return sukses setelah berhasil mendaftarkan pengguna
        return response()->json([
            'message' => 'Berhasil mendaftarkan pengguna.',
            'login_url' => url('/api/login'),
        ], 200);
    }

    /**
     * Autentikasi pengguna
     * 
     * @param Illuminate\Http\Request $request
     * 
     * @return Illuminate\Http\Response JSON
     */
    public function login(Request $request)
    {
        // Custom message untuk validator
        $messages = [
            'required' => 'Kolom :attribute perlu diisi.',
            'string' => 'Kolom :attribute harus bertipe data string.',
            'email' => 'Kolom :attribute memerlukan email yang valid.',
            'min' => 'Kolom :attribute harus berisi :min karakter.',
            'max' => 'Kolom :attribute tidak boleh lebih dari :max karakter.',
        ];

        // Validator
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'max:255'],
        ], $messages);

        // Return error response jika input tidak memenuhi kriteria validasi
        if($validator->fails()) {
            return response()->json([
                'message' => 'Terjadi kesalahan pada saat mengautentikasi pengguna.',
                'error' => $validator->getMessageBag(),
            ], 500);
        }

        // Coba autentikasi pengguna
        $credentials = $request->only('email', 'password');

        if(!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Email atau password salah. Silahkan coba lagi.',
            ], 501);
        }

        $user = User::where('email', $request->email)->first();

        if(!Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Password salah.',
            ], 501);
        }

        $token = $user->createToken('authToken')->accessToken;

        return response()->json([
            'message' => 'Login berhasil.',
            'access_token' => $token,
            'token_type' => 'Bearer'
        ], 200);
    }

    /**
     * Logs out the user
     * 
     * @param Illuminate\Http\Request $request
     * 
     * @return Illuminate\Http\Response JSON
     */
    public function logout()
    {
        Auth::user()->token()->revoke();

        return response()->json([
            'message' => 'Logout berhasil',
        ], 200);
    }
}
