<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Validation\Rule;
use Inertia\{Inertia, Response};

use App\Models\College;

class CollegeController extends Controller
{
    public function index(Request $req) : Response {
        $req->validate([
            'search' => ['nullable']
        ]);

        $colleges = College::query()
            ->where('name', 'LIKE', '%' . $req->string('search') . '%')
            ->with(['employees' => fn($q) => $q->limit(4)->orderBy('created_at', 'DESC')])
            ->withCount('employees')
            ->orderBy('created_at', 'DESC')
            ->paginate(42);

        return Inertia::render('dashboard/colleges/index', [
            'page_title' => 'Colleges',
            'navigation' => 'sidebar',
            'colleges' => $colleges
        ]);
    }

    public function create() : Response {
        return Inertia::render('dashboard/colleges/create', [
            'page_title' => 'Create College or Department',
            'navigation' => 'sidebar',
        ]);
    }

    public function store(Request $req) : RedirectResponse {
        $req->validate([
            'name' => ['required', 'min:4', 'unique:colleges,name'],
        ]);

        College::create([
            'name' => $req->string('name'),
        ]);

        return to_route('dashboard.colleges.index')
            ->with('success', [
                'title' => 'College or Department created',
                'content' => 'The college or department was created successfully.',
            ]);
    }

    public function edit(College $college) : Response{
        return Inertia::render('dashboard/colleges/edit', [
            'page_title' => 'Edit College or Department',
            'navigation' => 'sidebar',
            'college' => $college
        ]);
    }

    // DEBUG: Add auth user restriction only admin can delete
    public function destroy(College $college) : RedirectResponse {
        $college->delete();

        return to_route('dashboard.colleges.index')
            ->with('success', [
                'title' => 'College or Department deleted',
                'content' => 'The college or department was removed successfully.',
            ]);
    }

    public function update(Request $req, College $college) : RedirectResponse {
        $req->validate([
            'name' => ['required', 'min:4', Rule::unique('colleges', 'name')->ignore($college->id)],
        ]);

        $college->name = $req->string('name');

        $college->save();

        return to_route('dashboard.colleges.index')
            ->with('success', [
                'title' => 'College or Department updated',
                'content' => 'The college or department was updated successfully.',
            ]);
    }
}
