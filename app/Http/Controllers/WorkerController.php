<?php

namespace App\Http\Controllers;

use App\Models\Penugasan;
use App\Models\Progres;
use App\Models\Project;
use App\Models\ProjectLog;
use App\Traits\HandlesUploads;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WorkerController extends Controller
{
    use HandlesUploads;

    /**
     * Display worker dashboard overview with assigned projects and stats.
     */
    public function index(): View
    {
        $user = auth()->user();

        // 1. Projects assigned to this worker
        $projects = Project::where('worker_id', $user->id)
            ->with(['jurusan', 'logs'])
            ->latest()
            ->get();

        // 2. Metrics
        $tugasBerjalan = $projects->where('status', 'in_progress')->count();
        $menungguReview = $projects->where('status_review', 'submitted')->count();
        $tugasSelesai = $projects->filter(function ($p) {
            return $p->status === 'completed' || $p->status_review === 'approved';
        })->count();
        $totalTugas = $projects->count();

        // 3. Legacy penugasans (if any)
        $penugasans = Penugasan::with(['pesanan.detailPesanans.produk', 'progres'])
            ->where('worker_id', $user->id)
            ->get();

        return view('worker.dashboard', compact(
            'projects',
            'tugasBerjalan',
            'menungguReview',
            'tugasSelesai',
            'totalTugas',
            'penugasans',
            'user'
        ));
    }

    /**
     * Display details and progress tracking for a specific project.
     */
    public function showProject(int|string $id): View
    {
        $project = Project::with(['jurusan', 'worker', 'logs.worker'])
            ->where('worker_id', auth()->id())
            ->findOrFail($id);

        $logs = $project->logs;

        return view('worker.projects.show', compact('project', 'logs'));
    }

    /**
     * Store a new progress timeline log for the project.
     */
    public function storeLog(Request $request, int|string $id): RedirectResponse
    {
        $project = Project::where('worker_id', auth()->id())->findOrFail($id);

        $request->validate([
            'catatan' => 'required|string',
            'lampiran_file' => 'nullable|file|max:5120',
            'link_eksternal' => 'nullable|url|max:500',
            'progress' => 'nullable|integer|min:0|max:100',
        ]);

        $lampiranPath = null;
        if ($request->hasFile('lampiran_file')) {
            $lampiranPath = $this->storeUploadedFile($request->file('lampiran_file'), 'worker_logs');
        }

        ProjectLog::create([
            'project_id' => $project->id,
            'worker_id' => auth()->id(),
            'catatan' => $request->catatan,
            'lampiran_file' => $lampiranPath,
            'link_eksternal' => $request->link_eksternal,
        ]);

        if ($request->filled('progress')) {
            $project->update(['progress' => (int) $request->progress]);
        }

        return redirect()->back()->with('success', 'Catatan progres timeline berhasil ditambahkan.');
    }

    /**
     * Submit final project output for review by Admin Jurusan.
     */
    public function submitProject(Request $request, int|string $id): RedirectResponse
    {
        $project = Project::where('worker_id', auth()->id())->findOrFail($id);

        $request->validate([
            'file_hasil' => 'required|file|max:10240',
            'catatan_worker' => 'nullable|string|max:1000',
        ]);

        $filePath = $this->storeUploadedFile($request->file('file_hasil'), 'submissions');

        $project->update([
            'file_hasil' => $filePath,
            'catatan_worker' => $request->catatan_worker,
            'status_review' => 'submitted',
        ]);

        // Add an entry in the timeline feed for traceability
        ProjectLog::create([
            'project_id' => $project->id,
            'worker_id' => auth()->id(),
            'catatan' => 'Menyerahkan berkas akhir untuk direview Admin: '.($request->catatan_worker ?: 'Berkas pengerjaan diunggah.'),
            'lampiran_file' => $filePath,
            'link_eksternal' => null,
        ]);

        return redirect()->back()->with('success', 'Berkas akhir berhasil diserahkan! Status kini Menunggu Review Admin.');
    }

    /**
     * Legacy penugasan progress updater.
     */
    public function updateProgress(Request $request, Penugasan $penugasan): RedirectResponse
    {
        $request->validate(['keterangan_progres' => 'required']);

        Progres::create([
            'penugasan_id' => $penugasan->id,
            'keterangan_progres' => $request->keterangan_progres,
            'tanggal_update' => now(),
        ]);

        if ($request->status == 'Selesai') {
            $penugasan->update(['status_tugas' => 'Selesai']);
            $penugasan->pesanan->update(['status_pesanan' => 'Completed']);
        } else {
            $penugasan->update(['status_tugas' => 'Diproses']);
        }

        return redirect()->back()->with('success', 'Progress updated.');
    }
}
