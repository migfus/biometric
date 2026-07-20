<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Response;

use App\Models\Check;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;

class RecordController extends Controller
{
    public function index(Request $req): Response {
        $uuid = $this->getClientUUID($req);

        $checks = Check::query()
            ->with(['attachments:id,check_id,file_location,preview_location,created_at', 'verified_user:id,avatar',  'employee.office', 'employee.college'])

            ->where('browser_id', $uuid)
            ->orderBy('created_at', 'DESC')

            ->paginate(20)
            ->through(function (Check $check): array {
                return [
                    'id' => $check->id,
                    'check_in' => $check->check_in,
                    'created_at' => $check->created_at,
                    'work_description' => $check->work_description,
                    'ip_address' => $check->ip_address,
                    'ip_location' => $check->ip_location,

                    'attachments' => $check->attachments,
                    'verified_user' => $check->verified_user?->only(['avatar']),
                    'employee' => [
                        'office' => $check->employee?->office?->only(['name']),
                        'college' => $check->employee?->college?->only(['name']),
                        'full_name' => $check->employee?->full_name
                    ],
                ];
            });

        return Inertia::render('records/index', [
            'page_title' => 'Records',
            'checks' => $checks,
        ]);
    }

    public function destroy(Request $req, string $check_id) : JsonResponse | RedirectResponse {
        $check = Check::find($check_id);

        if (! $check) {
            return response()->json(['message' => 'Related check not found'], 404);
        }

        // Validate the browser_id (uuid) from cookie matches the check.browser_id
        if ($check->browser_id !== $this->getClientUUID($req)) {
            return response()->json(['message' => 'Unauthorized to delete this check'], 403);
        }

        $check->delete();

        return back()->with('success', [

            'content' => 'Your check has been removed.',
        ]);
    }

    public function update(Request $req, string $check_id) : JsonResponse {
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
}
