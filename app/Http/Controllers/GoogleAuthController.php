<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
{
    try {
        $googleUser = Socialite::driver('google')->user();

        // Cari user berdasarkan google_id
        $user = User::where('google_id', $googleUser->getId())->first();

        if (!$user) {
            // Kalau belum ada google_id, cek apakah email sudah ada
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // Update akun lama agar terhubung dengan Google
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                ]);
            } else {
                // Kalau email juga belum ada, buat akun baru
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'password' => bcrypt(str()->random(16)),
                ]);
            }
        }

        // Login user
        Auth::login($user);

        return redirect()->route('notes.index');

    } catch (\Exception $e) {
        return redirect()->route('login')->with('error', 'Gagal login dengan Google!');
    }
}
}
