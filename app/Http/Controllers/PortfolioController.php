<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PortfolioController extends Controller
{
    /**
     * Tab filter: nilai pada query `jurusan` => label tampilan.
     *
     * @var array<string, string>
     */
    private const FILTERS = [
        'RPL' => 'RPL',
        'DKV' => 'DKV',
        'TKJ' => 'TKJ',
        'GIM' => 'GIM',
        'PSPT' => 'PSPT',
        'ANI' => 'ANIMASI',
    ];

    /**
     * Halaman publik daftar portofolio (pencarian + filter jurusan via query string).
     */
    public function index(Request $request): View
    {
        $aktifJurusan = strtoupper($request->string('jurusan')->toString());

        if (! array_key_exists($aktifJurusan, self::FILTERS)) {
            $aktifJurusan = 'all';
        }

        $portfolios = Portfolio::with(['jurusan', 'images'])
            ->when(
                $aktifJurusan !== 'all',
                fn ($query) => $query->whereHas('jurusan', fn ($jurusan) => $jurusan->where('kode', $aktifJurusan))
            )
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%'.addcslashes($request->string('q')->toString(), '\\%_').'%';

                $query->where(fn ($q) => $q
                    ->where('title', 'like', $term)
                    ->orWhere('description', 'like', $term));
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('public.portofolio', [
            'portfolios' => $portfolios,
            'filters' => self::FILTERS,
            'aktifJurusan' => $aktifJurusan,
        ]);
    }

    /**
     * Halaman publik detail portofolio (slideshow gambar).
     */
    public function show(Portfolio $portfolio): View
    {
        $portfolio->load(['jurusan', 'images']);

        return view('public.portofolio.show', compact('portfolio'));
    }
}
