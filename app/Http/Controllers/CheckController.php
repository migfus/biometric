<?php

namespace App\Http\Controllers;

use Illuminate\Http\{Request, JsonResponse, RedirectResponse};

use App\Models\Check;
class CheckController extends Controller
{
    public function updateIpLocation(Request $req, string $check_id) : JsonResponse {
        $check = Check::find($check_id);

        if (! $check) {
            return response()->json(['message' => 'Related check not found'], 404);
        }

        // Allow admins, otherwise require matching browser uuid from cookie.
        if (! $req->user() && $check->browser_id !== $this->getClientUUID($req)) {
            return response()->json(['message' => 'Unauthorized to update this check'], 403);
        }

        if (! empty($check->ip_location)) {
            return response()->json([
                'message' => 'IP location is already set',
                'ip_location' => $check->ip_location,
            ]);
        }

        $val = $req->validate([
            'ip_location' => ['required', 'string', 'max:255'],
        ]);

        $check->ip_location = $val['ip_location'];
        $check->save();

        return response()->json([
            'message' => 'IP location updated',
            'ip_location' => $check->ip_location,
        ]);
    }

    public function destroy(Request $req, string $check_id) : JsonResponse | RedirectResponse {
        $check = Check::find($check_id);

        if (! $check) {
            return response()->json(['message' => 'Related check not found'], 404);
        }

        // Validate the browser_id (uuid) from cookie matches the check.browser_id
        if ($check->browser_id !== $this->getClientUUID($req)) {
            return response()->json(['message' => 'Unauthorized to delete this attachment'], 403);
        }

        $check->delete();

        return to_route('index')->with('success', [
            'title' => 'Removed',
            'content' => 'Your check has been removed.',
        ]);
    }
}
