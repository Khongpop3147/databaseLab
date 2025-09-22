<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     */
    public function index(Request $request)
    {
        $user = $request->user(); // authenticated user

        // prepare photo URL (prefer controller route 'user.photo' if you implemented it)
        if ($user && $user->profile_photo) {
            // Try using named route, fallback to storage asset
            try {
                $photoUrl = route('user.photo', ['filename' => $user->profile_photo]);
            } catch (\Exception $e) {
                $photoUrl = asset('storage/' . $user->profile_photo);
            }
        } else {
            $photoUrl = asset('images/default-photo.png');
        }

        return view('dashboard', [
            'user' => $user,
            'photoUrl' => $photoUrl,
        ]);
    }
}
