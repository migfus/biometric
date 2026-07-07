<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index(Request $request): Response
    {
        return Inertia::render('dashboard/profile/index', [
            'page_title' => 'Profile',
            'sidebar' => true
        ]);
    }

    public function store(Request $req) : RedirectResponse {
        $req->validate([
            'type' => ['required', 'in:profile,password,avatar']
        ]);

        $type = (string) $req->input('type');

        return match ($type) {
            'avatar' => $this->storeUpdateAvatar($req),
            'profile' => $this->storeUpdateProfile($req),
            'password' => $this->storeUpdatePassword($req),
            default => to_route('dashboard.profile.index')
                        ->with('error', ['title' => 'Type', 'content' => 'Type error.'])
        };
    }

    private function storeUpdateAvatar(Request $req) : RedirectResponse {
        $req->validate([
            'avatar' => ['required', 'mimes:jpg,png', 'max:300'],
        ]);

        $avatarFile = $req->file('avatar');

        if ($avatarFile) {
            $avatarPath = $this->uploadAvatarImage($avatarFile);

            $user = Auth::user();
            $user->forceFill([
                'avatar' => $avatarPath,
            ])->save();
        }

        return to_route('dashboard.profile.index')
            ->with('success', ['title' => 'Avatar', 'content' => 'Avatar has been changed.']);
    }

    private function storeUpdateProfile(Request $req) : RedirectResponse {
        $req->validate([
            'name' => ['required', 'min:4'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore(Auth::user()->id)],
        ]);

        $user = Auth::user();

        $user->forceFill([
            'name' => $req->string('name'),
            'email' => $req->string('email'),
        ])->save();

        return to_route('dashboard.profile.index')
            ->with('success', ['title' => 'Profile Information', 'content' => 'Profile Information has been changed.']);
    }

    private function storeUpdatePassword(Request $req): RedirectResponse {
        $val = $req->validate([
            'old_password' => ['required'],
            'password' => ['min:6', 'required', 'confirmed'],
        ]);

        $user = Auth::user();

        if (! Hash::check($req->string('old_password'), $user->password)) {
            return to_route('dashboard.profile.index')
                ->withErrors(['old_password' => 'The current password is incorrect.']);
        }

        $user->forceFill([
            'password' => Hash::make($req->string('password')),
        ])->save();

        return to_route('dashboard.profile.index')
            ->with('success', ['title' => 'Password', 'content' => 'Password has been changed.']);
    }
}
