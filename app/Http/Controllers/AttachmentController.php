<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Check;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AttachmentController extends Controller
{
    public function destroy(Request $request, string $attachment_id): JsonResponse|RedirectResponse
    {
        $attachment = Attachment::find($attachment_id);

        if (! $attachment) {
            return response()->json(['message' => 'Attachment not found'], 404);
        }

        $attachable = $attachment->attachable;

        if (! $attachable) {
            return response()->json(['message' => 'Related attachment owner not found'], 404);
        }

        if (! $attachable instanceof Check) {
            return response()->json(['message' => 'Unauthorized to delete this attachment'], 403);
        }

        if (! $attachable->exists) {
            return response()->json(['message' => 'Related check not found'], 404);
        }

        $rawClientUuid = $request->cookie('client_uuid');

        if (! $rawClientUuid) {
            return response()->json(['message' => 'Missing client identifier'], 403);
        }

        // Try to decrypt the cookie value if it was encrypted by Laravel, otherwise use raw value.
        try {
            $clientUuid = decrypt($rawClientUuid);
        } catch (\Throwable $e) {
            $clientUuid = $rawClientUuid;
        }

        // Validate the browser_id (uuid) from cookie matches the check.browser_id
        if ($attachable->browser_id !== $clientUuid) {
            return response()->json(['message' => 'Unauthorized to delete this attachment'], 403);
        }

        // NOTE: The attachment is no longer hard delete
        // Remove file from public storage if present
        // $filePath = public_path($attachment->file_location);
        // if (file_exists($filePath)) {
        //     @unlink($filePath);
        // }

        $attachment->delete();

        return back()
            ->with('success', [

                'content' => 'You successfuly removed the image.',
            ]);
    }
}
