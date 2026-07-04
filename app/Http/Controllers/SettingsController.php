<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'dorm_name'    => config('app.dorm_name', 'YurtYönet'),
            'dorm_address' => config('app.dorm_address', ''),
            'dorm_phone'   => config('app.dorm_phone', ''),
            'exit_time'    => config('app.exit_time', '06:30'),
            'entry_time'   => config('app.entry_time', '23:00'),
        ];
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        // .env dosyasına yazmak yerine session'da tutalım (basit yaklaşım)
        session([
            'dorm_name'    => $request->dorm_name,
            'dorm_address' => $request->dorm_address,
            'dorm_phone'   => $request->dorm_phone,
            'exit_time'    => $request->exit_time,
            'entry_time'   => $request->entry_time,
        ]);

        return redirect('/admin/settings')->with('success', 'Ayarlar kaydedildi.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mevcut şifre yanlış.']);
        }

        $user->update(['password' => Hash::make($request->new_password)]);

        return redirect('/admin/settings')->with('success', 'Şifre güncellendi.');
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:11',
        ]);

        Auth::user()->update([
            'name'  => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return redirect('/admin/settings')->with('success', 'Profil güncellendi.');
    }
}
