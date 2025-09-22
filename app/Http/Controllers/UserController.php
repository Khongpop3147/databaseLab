<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function updateProfilePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        // Delete old photo from public disk if exists
        if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        // safe filename
        $originalName = $request->file('profile_photo')->getClientOriginalName();
        $fileName = time() . '_' . $user->id . '_' . preg_replace('/\s+/', '_', $originalName);

        // store on the public disk: storage/app/public/profile_photos
        $path = $request->file('profile_photo')->storeAs('profile_photos', $fileName, 'public');

        // Save path to DB (e.g. "profile_photos/xxxxx.jpg")
        $user->profile_photo = $path;
        $user->save();

        return redirect()->route('profile.edit')->with('status', 'profile-photo-updated');
    }

    /**
     * Optional: keep this if you still want a controller route to serve the photo.
     * This version supports when DB stores "profile_photos/xxx.jpg" and route provides only the filename.
     */
    public function showProfilePhoto($filename)
    {
        $user = auth()->user();

        // Compare basenames (handle DB storing "profile_photos/xxx.jpg" or just "xxx.jpg")
        $stored = $user->profile_photo ?? '';

        if (basename($stored) !== $filename) {
            abort(403);
        }

        // path on public disk
        $storagePath = $stored;
        if (!Storage::disk('public')->exists($storagePath)) {
            abort(404);
        }

        $fullPath = Storage::disk('public')->path($storagePath);

        return response()->file($fullPath);
    }
}
