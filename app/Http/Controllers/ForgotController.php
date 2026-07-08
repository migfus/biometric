<?php

namespace App\Http\Controllers;

use App\Mail\ForgotPasswordLink;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Inertia\Inertia;

class ForgotController extends Controller
{
    public function index() {
        return Inertia::render('forgot/index', [
            'page_title' => 'Forgot Password',
        ]);
    }

    // NOTE: SEND LINK
    public function store(Request $req) {
        $req->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $email = $req->string('email')->lower();
        $throttleKey = 'forgot-password:' . sha1($email);

        if (RateLimiter::tooManyAttempts($throttleKey, 1)) {
            return back()
                ->withInput()
                ->withErrors([
                    'email' => 'A password reset link was recently sent. Please try again in ' . RateLimiter::availableIn($throttleKey) . ' seconds.',
                ]);
        }

        $user = User::where('email', $email)->firstOrFail();
        $token = Password::createToken($user);

        Mail::to($user->email)->send(new ForgotPasswordLink($user, $token));
        RateLimiter::hit($throttleKey, 180);

        return to_route('forgot.index')
            ->with('success', [
                'title' => 'Link sent!',
                'content' => 'You can open the link and begin to update the password.',
            ]
        );
    }

    public function show(Request $req, string $token) {
        $email = $req->query('email');

        if (! $email) {
            return to_route('forgot.index')
                ->with('error', [
                    'title' => 'Invalid reset link',
                    'content' => 'The password reset link is missing required information.',
                ]);
        }

        $user = User::where('email', $email)->first();

        if (! $user || ! Password::tokenExists($user, $token)) {
            return to_route('forgot.index')
                ->with('error', [
                    'title' => 'Invalid reset link',
                    'content' => 'The password reset token is invalid or expired.',
                ]);
        }

        return Inertia::render('forgot/show', [
            'page_title' => 'Reset Link Sent',
            'id' => $token,
            'email' => $email,
        ]);
    }

    public function update(Request $req, string $token) {
        $req->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        $status = Password::reset(
            [
                'email' => $req->string('email'),
                'password' => $req->string('password'),
                'password_confirmation' => $req->string('password_confirmation'),
                'token' => $token,
            ],
            function (User $user, string $password) {
                $user->password = $password;
                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return to_route('login.index', ['email' => $req->string('email')])
                ->with('success', [
                    'title' => 'Password updated',
                    'content' => 'Your password has been reset. You may now log in.',
                ]);
        }

        return back()->withErrors(['email' => trans($status)]);
    }
}
