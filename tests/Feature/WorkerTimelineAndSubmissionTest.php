<?php

namespace Tests\Feature;

use App\Models\Jurusan;
use App\Models\Project;
use App\Models\ProjectLog;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class WorkerTimelineAndSubmissionTest extends TestCase
{
    protected User $worker;

    protected Jurusan $jurusan;

    protected Project $project;

    protected function setUp(): void
    {
        parent::setUp();

        $this->jurusan = Jurusan::firstOrCreate(
            ['nama_jurusan' => 'Software Engineering'],
            ['deskripsi_profil' => 'Focusing on web and app development.']
        );

        $this->worker = User::firstOrCreate(
            ['email' => 'worker_test@example.com'],
            [
                'name' => 'Worker Test',
                'password' => bcrypt('password'),
                'role' => 'worker',
                'jurusan_id' => $this->jurusan->id,
            ]
        );

        $this->project = Project::firstOrCreate(
            ['nama_projek' => 'Testing Project Worker'],
            [
                'jurusan_id' => $this->jurusan->id,
                'deskripsi' => 'Deskripsi testing penugasan worker.',
                'status' => 'in_progress',
                'progress' => 50,
                'worker_id' => $this->worker->id,
                'tenggat_waktu' => now()->addDays(4),
                'status_review' => 'draft',
            ]
        );
    }

    public function test_worker_can_view_dashboard_with_metrics_and_projects(): void
    {
        $response = $this->actingAs($this->worker)->get(route('worker.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('WORKER WORKSPACE');
        $response->assertSee('Tugas Berjalan');
        $response->assertSee('Testing Project Worker');
        $response->assertSee('Buka Lembar Kerja');
    }

    public function test_worker_can_view_project_detail_and_timeline(): void
    {
        ProjectLog::create([
            'project_id' => $this->project->id,
            'worker_id' => $this->worker->id,
            'catatan' => 'Catatan progres pertama oleh worker.',
            'link_eksternal' => 'https://figma.com/sample',
        ]);

        $response = $this->actingAs($this->worker)->get(route('worker.projects.show', $this->project->id));

        $response->assertStatus(200);
        $response->assertSee('Testing Project Worker');
        $response->assertSee('Catatan progres pertama oleh worker.');
        $response->assertSee('Tambah Catatan Lini Masa');
        $response->assertSee('Penyerahan Berkas Akhir');
    }

    public function test_worker_can_store_timeline_log_with_file_and_link(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('screenshot.png', 500);

        $response = $this->actingAs($this->worker)->post(route('worker.projects.log', $this->project->id), [
            'catatan' => 'Selesai integrasi modul timeline dan upload file.',
            'lampiran_file' => $file,
            'link_eksternal' => 'https://github.com/sample/repo',
            'progress' => 75,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('project_logs', [
            'project_id' => $this->project->id,
            'worker_id' => $this->worker->id,
            'catatan' => 'Selesai integrasi modul timeline dan upload file.',
            'link_eksternal' => 'https://github.com/sample/repo',
        ]);

        $log = ProjectLog::where('catatan', 'Selesai integrasi modul timeline dan upload file.')->latest('id')->first();
        $this->assertNotNull($log);
        $this->assertNotNull($log->lampiran_file);
        Storage::disk('public')->assertExists($log->lampiran_file);

        $this->assertEquals(75, $this->project->fresh()->progress);
    }

    public function test_worker_can_submit_final_file_for_admin_review(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('final_assets.zip', 2048);

        $response = $this->actingAs($this->worker)->post(route('worker.projects.submit', $this->project->id), [
            'file_hasil' => $file,
            'catatan_worker' => 'Semua modul dan asset selesai, siap diperiksa Admin Jurusan.',
        ]);

        $response->assertRedirect();

        $fresh = $this->project->fresh();
        $this->assertEquals('submitted', $fresh->status_review);
        $this->assertEquals('Semua modul dan asset selesai, siap diperiksa Admin Jurusan.', $fresh->catatan_worker);
        $this->assertNotNull($fresh->file_hasil);

        Storage::disk('public')->assertExists($fresh->file_hasil);
    }
}
