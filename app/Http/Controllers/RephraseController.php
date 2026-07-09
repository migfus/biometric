<?php

namespace App\Http\Controllers;

use App\Ai\Agents\WorkDescriptionRephraser;
use Illuminate\Http\{Request, JsonResponse};

class RephraseController extends Controller
{
    public function rephrase(Request $req) : JsonResponse {
        $req->validate([
            'work_description' => ['required'],
        ]);

        $work_description = $req->input('work_description');

        $response = WorkDescriptionRephraser::make()->prompt($work_description);

        // ensure we return a plain array/string instead of an AgentResponse object
        $responseArr = method_exists($response, 'toArray') ? $response->toArray() : (is_array($response) ? $response : []);

        return response()->json([
            'rephrased_work_description' => $responseArr['work_description'] ?? null,
        ]);
    }
}
