<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function index(Request $request): View
    {
        return view('profile.index', [
            'user' => $request->user(),
            'genders' => ['Laki-laki', 'Perempuan']
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $rules = [
            'username' => [
                'required',
                'string',
                'min:3',
                'max:30',
                'regex:/^[a-z0-9]+(_[a-z0-9]+)*$/',
            ],
            'name' => 'required|min:3|max:50',
            'email' => 'required|email',
            'phone_number' => [
                'nullable',
                'string',
                'min:10',
                'max:16',
                'regex:/^(\+62|0)[0-9]{9,13}$/'
            ],
            'gender' => 'nullable|in:Laki-laki,Perempuan',
            'date_of_birth' => 'nullable|date|before_or_equal:today',
        ];

        if ($request->hasFile('image')) {
            $rules['image'] = "required|image|mimes:jpeg,png,jpg|max:2096";
        }

        if ($request->input('username') != $user->username) {
            $rules['username'] = [
                'required',
                'string',
                'min:3',
                'max:30',
                'regex:/^[a-z0-9]+(_[a-z0-9]+)*$/',
                'unique:users,username'
            ];
        }
        if ($request->input('email') != $user->email) {
            $rules['email'] = 'required|email|unique:users,email';
        }

        $validated = $request->validate($rules, [
            'image.required' => 'Gambar produk wajib diunggah',
            'image.image' => 'File harus berupa gambar',
            'image.mimes' => 'Format gambar harus jpeg, png, atau jpg',
            'image.max' => 'Ukuran gambar maksimal 2MB',

            'username.regex' => 'Username hanya boleh mengandung huruf kecil, angka, dan underscore',
        ]);


        DB::transaction(function () use ($request, $user) {
            // Update data user
            $user->fill($request->only(['name', 'email', 'username']));

            // Handle profile data
            $profileData = $request->only(['phone_number', 'date_of_birth', 'gender']);

            // Handle image upload
            $profileData['image'] = $user->profile?->image;

            // Periksa apakah gambar berasal dari Google Avatar
            $isGoogleAvatar = Str::startsWith($user->profile?->image, 'https://lh3.googleusercontent.com/');

            if ($request->hasFile('image')) {
                // Hanya hapus gambar lama jika BUKAN dari Google Avatar
                if ($user->profile && $user->profile->image && !$isGoogleAvatar) {
                    Storage::delete($user->profile->image);
                }

                // Simpan gambar yang diunggah
                $path = $request->file('image')->store('gambar/gambar-profile');
                $profileData['image'] = $path;
            }

            // Update or create profile
            if ($user->profile) {
                $user->profile->update($profileData);
            } else {
                $user->profile()->create($profileData);
            }

            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
                $user->sendEmailVerificationNotification();
            }

            $user->save();
        });

        return back()->with(['success' => 'Profil berhasil diperbarui']);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $user;

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}