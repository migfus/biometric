<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function index(Request $req) : Response {
        $req->validate([
            'search' => ['nullable']
        ]);

        $users = User::query()
            ->where('name', 'LIKE', '%' . $req->string('search') . '%')

            ->orderBy('created_at', 'DESC')
            ->paginate(10);

        return Inertia::render('dashboard/users/index', [
            'page_title' => 'Users',
            'sidebar' => true,
            'users' => $users
        ]);
    }

    public function create() : Response {
        return Inertia::render('dashboard/users/create', [
            'page_title' => 'Create User',
            'sidebar' => true,
        ]);
    }

    public function store(Request $req) : RedirectResponse {
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

        return to_route('dashboard.users.index')
            ->with('success', [
                'title' => 'User created',
                'content' => 'The user was created successfully.',
            ]);
    }

    public function destroy(Request $req, User $user) : RedirectResponse {
        if ((string) $req->user()->getKey() === (string) $user->getKey()) {
            return back()->with('error', [
                'title' => 'Cannot delete user',
                'content' => 'You cannot delete your own account.',
            ]);
        }

        $user->delete();

        return to_route('dashboard.users.index')
            ->with('success', [
                'title' => 'User deleted',
                'content' => 'The user was removed successfully.',
            ]);
    }

    public function edit(User $user) : Response {
        return Inertia::render('dashboard/users/edit', [
            'page_title' => 'Edit User',
            'sidebar' => true,
            'user' => $user
        ]);
    }

    public function update(Request $req, User $user) : RedirectResponse {
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

        return to_route('dashboard.users.index')
            ->with('success', [
                'title' => 'User updated',
                'content' => 'The user was updated successfully.',
            ]);
    }
}
