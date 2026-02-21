<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;


class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.show')->with('status', 'profile-updated');
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

    public function show()
    {
        return view('profile.show');
    }

    public function updateAvatar(Request $request): RedirectResponse
    {
        $request->validate([
            'avatar_cropped' => ['required', 'string'],
        ]);

        $user = $request->user();

        // Supprimer l'ancien avatar
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Décoder le base64
        $imageData = $request->input('avatar_cropped');
        $imageData = str_replace('data:image/jpeg;base64,', '', $imageData);
        $imageData = base64_decode($imageData);

        // Sauvegarder
        $filename = 'avatars/' . uniqid() . '.jpg';
        Storage::disk('public')->put($filename, $imageData);

        $user->update(['avatar' => $filename]);

        return Redirect::route('profile.show')->with('status', 'avatar-updated');
    }
    public function settings()
    {
        return view('settings');
    }
    public function updatePreferences(Request $request): RedirectResponse
    {
        $request->validate([
            'view_mode'      => ['required', 'in:grid,list'],
            'notif_share'    => ['nullable', 'boolean'],
            'notif_activate' => ['nullable', 'boolean'],
            'notif_comment'  => ['nullable', 'boolean'],
        ]);

        $request->user()->update([
            'preferences' => [
                'view_mode'      => $request->view_mode,
                'notif_share'    => $request->boolean('notif_share'),
                'notif_activate' => $request->boolean('notif_activate'),
                'notif_comment'  => $request->boolean('notif_comment'),
            ]
        ]);

        return Redirect::route('settings')->with('status', 'preferences-updated');
    }
}