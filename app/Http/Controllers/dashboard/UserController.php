<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UserController extends Controller
{
    public function index(Request $req): Response
    {
        $req->validate([
            'search' => ['nullable'],
        ]);

        $users = User::query()
            ->where('name', 'LIKE', '%'.$req->string('search').'%')

            ->orderBy('created_at', 'DESC')
            ->paginate(10);

        return Inertia::render('dashboard/users/index', [
            'page_title' => 'Users',
            'navigation' => 'sidebar',
            'users' => $users,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('dashboard/users/create', [
            'page_title' => 'Create User',
            'navigation' => 'sidebar',
        ]);
    }

    public function store(Request $req): RedirectResponse
    {
        $req->validate([
            'name' => ['required', 'min:4'],
            'email' => ['required', 'email', 'min:4'],
            'password' => ['required', 'min:4', 'confirmed'],
        ]);

        User::create([
            'name' => $req->string('name'),
            'email' => $req->string('email'),
            'password' => $req->string('password'),
        ]);

        return back()
            ->with('success', [
                'title' => 'User created',
                'content' => 'The user was created successfully.',
            ]);
    }

    public function destroy(Request $req, User $user): RedirectResponse
    {
        if ((string) $req->user()->getKey() === (string) $user->getKey()) {
            return back()->with('error', [
                'title' => 'Cannot delete user',
                'content' => 'You cannot delete your own account.',
            ]);
        }

        $user->delete();

        return back()
            ->with('success', [
                'title' => 'User deleted',
                'content' => 'The user was removed successfully.',
            ]);
    }

    public function edit(User $user): Response
    {
        return Inertia::render('dashboard/users/edit', [
            'page_title' => 'Edit User',
            'navigation' => 'sidebar',
            'user' => $user,
        ]);
    }

    public function update(Request $req, User $user): RedirectResponse
    {
        $req->validate([
            'name' => ['required', 'min:4'],
            'email' => ['required', 'email', 'min:4',  Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'min:6', 'confirmed'],
        ]);

        $user->name = $req->string('name');
        $user->email = $req->string('email');

        if ($req->filled('password')) {
            $user->password = $req->string('password');
        }

        $user->save();

        return back()
            ->with('success', [
                'title' => 'User updated',
                'content' => 'The user was updated successfully.',
            ]);
    }

    public function print(Request $req): StreamedResponse
    {
        $req->validate([
            'search' => ['nullable'],
        ]);

        $users = User::query()
            ->where('name', 'LIKE', '%'.$req->string('search').'%')
            ->orderBy('created_at', 'DESC')
            ->get(['id', 'name', 'email', 'created_at']);

        $filename = 'users-'.now()->format('Ymd_His').'.csv';

        return response()->streamDownload(function () use ($users): void {
            $stream = fopen('php://output', 'w');

            if ($stream === false) {
                return;
            }

            fputcsv($stream, ['ID', 'Name', 'Email', 'Created At']);

            foreach ($users as $user) {
                fputcsv($stream, [
                    $user->id,
                    $user->name,
                    $user->email,
                    optional($user->created_at)?->toDateTimeString(),
                ]);
            }

            fclose($stream);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
