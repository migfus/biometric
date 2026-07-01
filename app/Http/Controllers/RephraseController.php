<?php

namespace App\Http\Controllers;

use App\Ai\Agents\WorkDescriptionRephraser;
use Illuminate\Http\Request;

class RephraseController extends Controller
{
    public function rephrase(Request $req)
    {
        $req->validate([
            'work_description' => ['required'],
        ]);

        $work_description = $req->input('work_description');

        $response = WorkDescriptionRephraser::make()->prompt($work_description);

        // dd($response['work_description']);

        return response()->json([
            'rephrased_work_description' => $response['work_description'],
        ]);
    }
}
