<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class JurusanController extends Controller
{
    /**
     * Halaman publik profil program keahlian (/jurusan/{slug}).
     *
     * Konten statis dari config/jurusan.php — kolom jurusans.deskripsi di DB
     * masih kosong dan kode jurusan tidak unik, jadi lookup sengaja tidak
     * menyentuh database. Slug tak dikenal → 404.
     */
    public function show(string $slug): View
    {
        $jurusan = config('jurusan.'.$slug);

        abort_if(! is_array($jurusan), 404);

        return view('public.jurusan.show', [
            'slug' => $slug,
            'jurusan' => $jurusan,
        ]);
    }
}
