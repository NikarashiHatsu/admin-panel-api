<?php

namespace App\Http\Controllers\Api;

use App\Article;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Ambil semua artikel dari database
        $articles = Article::with('writer')->get();

        // Tampilkan data artikel
        return response()->json([
            'message' => 'Berhasil mengambil semua artikel.',
            'data' => $articles,
        ], 200);
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
            'integer' => 'Kolom :attribute harus berbentuk angka.',
        ];

        // Validator
        $validator = Validator::make($request->all(), [
            'id_penulis' => ['required', 'integer'],
            // 'foto_depan' => File, Gambar, Tipe: jpg, jpeg, png
            'redaksi' => ['required'],
            'published' => ['required'],
        ]);

        // Jika validator gagal
        if($validator->fails()) {
            return response()->json([
                'message' => 'Ada kesalahan saat menambahkan artikel.',
                'error' => $validator->getMessageBag(),
            ], 500);
        }

        // Jika validator berhasil
        $article = new Article;
        $article->id_penulis = $request->id_penulis;
        $article->foto_depan = $request->foto_depan;
        $article->redaksi = $request->redaksi;
        $article->published = $request->published;
        $article->save();

        $writer = $article->writer;

        // Jika status published adalah 0 (draft)
        if($request->published == 0) {
            return response()->json([
                'message' => 'Artikel berhasil disimpan sebagai draft.',
                'data' => $article,
            ], 200);
        }

        // Tampilkan response
        return response()->json([
            'message' => 'Artikel berhasil dipublikasikan.',
            'data' => $article,
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        // Tampilkan detail artikel.
        return response()->json([
            'message' => 'Berhasil mengambil detail artikel.',
            'data' => $article,
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function edit(Article $article)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Article $article)
    {
        // Custom message untuk validator
        $messages = [
            'required' => 'Kolom :attribute perlu diisi.',
            'integer' => 'Kolom :attribute harus berbentuk angka.',
        ];

        // Validator
        $validator = Validator::make($request->all(), [
            'id_penulis' => ['required', 'integer'],
            // 'foto_depan' => File, Gambar, Tipe: jpg, jpeg, png
            'redaksi' => ['required'],
            'published' => ['required'],
        ]);

        // Jika validator gagal
        if($validator->fails()) {
            return response()->json([
                'message' => 'Ada kesalahan saat mengubah artikel.',
                'error' => $validator->getMessageBag(),
            ], 500);
        }

        // Update artikel
        if($request->id_penulis != '') {
            $article->id_penulis = $request->id_penulis;
        }

        // TODO: DO IMAGE EDIT
        
        if($request->redaksi != '') {
            $article->redaksi = $request->redaksi;
        }

        if($request->published != '') {
            $article->published = $request->published;
        }

        $article->save();

        $writer = $article->writer;

        // Jika status published adalah 0 (draft)
        if($request->published == 0) {
            return response()->json([
                'message' => 'Berhasil mengubah draft.',
                'data' => $article,
            ], 200);
        }

        // Tampilkan response
        return response()->json([
            'message' => 'Berhasil mengubah artikel.',
            'data' => $article,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        // Hapus artikel
        $article->delete();

        // Tampilkan response
        return response()->json([
            'message' => 'Berhasil menghapus artikel.',
        ], 200);
    }
}
