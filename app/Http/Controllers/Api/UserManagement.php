<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class UserManagement extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Ambil semua user pada database
        $users = User::all();

        // Tampilkan semua data user
        return response()->json([
            'message' => 'Berhasil mengambil semua data pengguna.',
            'data' => $users,
        ], 200);
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
            'unique' => 'Email sudah terpakai.',
            'email' => 'Kolom :attribute perlu diisi oleh email yang valid.',
            'min' => 'Kolom :attribute perlu diisi setidaknya :min karakter.',
            'max' => 'Kolom :attribute tidak boleh melebihi :max karakter.',
            'confirmed' => 'Password konfirmasi tidak sama.',
        ];

        // Validator
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'min:8', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:8', 'max:255'],
            'password_confirmation' => ['required', 'min:8', 'max:255'],
            'role' => ['required', 'in:user,admin'],
        ], $messages);

        // Jika validator gagal
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
        $user->role = $request->role;
        $user->save(); 

        // Tampilkan pesan sukses mendaftar
        return response()->json([
            'message' => 'Pengguna baru berhasil didaftarkan.',
            'data' => $user,
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        // Tampilkan user secara spesifik
        return response()->json([
            'message' => 'Detail pengguna berhasil diambil.',
            'data' => $user,
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        // Custom message untuk validator
        $messages = [
            'required' => 'Kolom :attribute perlu diisi.',
            'unique' => 'Email sudah terpakai.',
            'email' => 'Kolom :attribute perlu diisi oleh email yang valid.',
            'min' => 'Kolom :attribute perlu diisi setidaknya :min karakter.',
            'max' => 'Kolom :attribute tidak boleh melebihi :max karakter.',
            'confirmed' => 'Password konfirmasi tidak sama.',
        ];

        // Validator
        $validator = Validator::make($request->all(), [
            'name' => ['min:8', 'max:255'],
            'email' => ['email', 'unique:users'],
            'old_password' => ['min:8', 'max:255'],
            'password' => ['confirmed', 'min:8', 'max:255'],
            'password_confirmation' => ['min:8', 'max:255'],
            'role' => ['in:user,admin'],
        ], $messages);

        // Cek jika validator gagal
        if($validator->fails()) {
            return response()->json([
                'message' => 'Terjadi kesalahan pada saat mengubah pengguna.',
                'error' => $validator->getMessageBag(),
            ], 500);
        }

        // Cek password
        if($request->old_password != '') {
            if(!password_verify($request->old_password, $user->password)) {
                return response()->json([
                    'message' => 'Password tidak sama dengan password sebelumnya.',
                    'error' => $validator->getMessageBag(),
                ], 500);
            }

            $user->password = Hash::make($request->password);
        }

        // Update pengguna
        if($request->name != '') {
            $user->name = $request->name;
        }

        if($request->email != '') {
            $user->email = $request->email;
        }

        if($request->role != '') {
            $user->role = $request->role;
        }

        $user->save();

        // Tampilkan response
        return response()->json([
            'message' => 'Berhasil mengubah data pengguna.',
            'data' => $user,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        // Hapus pengguna
        $user->delete();

        // Tampilkan response
        return response()->json([
            'message' => 'Pengguna berhasil dihapus.',
        ], 200);
    }
}
