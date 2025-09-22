<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\UserBio; // เพิ่ม import นี้

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        // ส่ง bio ไปยัง view ด้วย (ถ้ามี)
        $user = $request->user();
        $bio = $user->bio ?? null;

        return view('profile.edit', [
            'user' => $user,
            'bio' => $bio,
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

        // --- ทางเลือก: ถ้าคุณเพิ่ม 'bio' ใน ProfileUpdateRequest และต้องการอัพเดต bio พร้อมกัน ---
        // if ($request->filled('bio')) {
        //     $user = $request->user();
        //     $bioData = ['bio' => $request->input('bio')];
        //     if ($user->bio) {
        //         $user->bio->update($bioData);
        //     } else {
        //         $user->bio()->create($bioData);
        //     }
        // }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Show the user's bio edit page.
     */
    public function showBio(): View
    {
        $user = Auth::user();
        $bio = $user->bio ?? null;

        return view('profile.show-bio', compact('user', 'bio'));
    }

    /**
     * Update (or create) the user's bio.
     */
    public function updateBio(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $request->validate([
            'bio' => 'required|string',
        ]);

        $bioData = ['bio' => $request->input('bio')];

        if ($user->bio) {
            $user->bio->update($bioData);
        } else {
            $user->bio()->create($bioData);
        }

        return Redirect::route('profile.show-bio')->with('status', 'Bio updated successfully!');
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
