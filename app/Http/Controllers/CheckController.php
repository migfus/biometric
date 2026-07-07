<?php

namespace App\Http\Controllers;

use App\Models\Check;
use Illuminate\Http\Request;

class CheckController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Check $check)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Check $check)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Check $check)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $req, string $check_id)
    {
        $check = Check::find($check_id);

        if (! $check) {
            return response()->json(['message' => 'Related check not found'], 404);
        }

        // Validate the browser_id (uuid) from cookie matches the check.browser_id
        if ($check->browser_id !== $this->getClientUUID($req)) {
            return response()->json(['message' => 'Unauthorized to delete this attachment'], 403);
        }

        $check->delete();

        return redirect('/');
    }
}
