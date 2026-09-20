<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    /**
     * Display a listing of projects with status filter.
     */
    public function index(Request $request): View
    {
        $jurusanId = auth()->user()->jurusan_id;
        $statusFilter = $request->query('status');

        $projectsQuery = Project::with('worker');
        if ($jurusanId) {
            $projectsQuery->where('jurusan_id', $jurusanId);
        }

        if ($statusFilter && in_array($statusFilter, ['pending', 'in_progress', 'completed', 'cancelled'])) {
            $projectsQuery->where('status', $statusFilter);
        }

        $projects = $projectsQuery->orderBy('created_at', 'desc')->get();

        $workersQuery = User::where('role', 'worker');
        if ($jurusanId) {
            $workersQuery->where('jurusan_id', $jurusanId);
        }
        $workers = $workersQuery->get();

        return view('admin.projects.index', compact('projects', 'workers', 'statusFilter'));
    }

    /**
     * Store a newly created project in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama_projek' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'progress' => 'required|integer|min:0|max:100',
            'worker_id' => 'nullable|exists:users,id',
            'tenggat_waktu' => 'nullable|date',
        ]);

        Project::create([
            'jurusan_id' => auth()->user()->jurusan_id,
            'nama_projek' => $request->nama_projek,
            'deskripsi' => $request->deskripsi,
            'status' => $request->status,
            'progress' => $request->progress,
            'worker_id' => $request->worker_id,
            'tenggat_waktu' => $request->tenggat_waktu,
        ]);

        return redirect()->route('admin.projects.index')->with('success', 'Projek baru berhasil ditambahkan.');
    }

    /**
     * Update project progress, worker assignment, and status.
     */
    public function update(Request $request, Project $project): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'progress' => 'required|integer|min:0|max:100',
            'worker_id' => 'nullable|exists:users,id',
            'tenggat_waktu' => 'nullable|date',
        ]);

        $project->update([
            'status' => $request->status,
            'progress' => $request->progress,
            'worker_id' => $request->worker_id,
            'tenggat_waktu' => $request->tenggat_waktu,
        ]);

        return redirect()->route('admin.projects.index')->with('success', 'Progress dan data projek berhasil diperbarui.');
    }

    /**
     * Remove the specified project from storage.
     */
    public function destroy(Project $project): RedirectResponse
    {
        $project->delete();

        return redirect()->route('admin.projects.index')->with('success', 'Projek berhasil dihapus.');
    }
}
