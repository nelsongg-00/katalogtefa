<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Traits\HandlesUploads;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    use HandlesUploads;

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $view = match ($request->user()->role ?? 'pelanggan') {
            'super_admin' => 'profile.superadmin',
            'admin_jurusan' => 'profile.admin',
            'worker' => 'profile.worker',
            default => 'profile.edit',
        };

        return view($view, [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        if (array_key_exists('phone', $data)) {
            $data['phone'] = ($data['phone'] === null || $data['phone'] === '') ? null : trim($data['phone']);
        }

        // Simpan foto profil baru (jika ada) — file lama dihapus setelah tersimpan.
        $oldPhoto = $user->foto_profil;

        if ($request->hasFile('foto_profil')) {
            $data['foto_profil'] = $this->storeUploadedFile($request->file('foto_profil'), 'avatars');
        } else {
            unset($data['foto_profil']);
        }

        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        if (isset($data['foto_profil']) && $oldPhoto && $oldPhoto !== $data['foto_profil']
            && Storage::disk('public')->exists($oldPhoto)) {
            Storage::disk('public')->delete($oldPhoto);
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
