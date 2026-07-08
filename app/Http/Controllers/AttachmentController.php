<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Check;
use Illuminate\Http\Request;

class AttachmentController extends Controller
{
    public function destroy(Request $request, string $attachment_id)  {
        $attachment = Attachment::find($attachment_id);

        if (! $attachment) {
            return response()->json(['message' => 'Attachment not found'], 404);
        }

        $check = Check::find($attachment->check_id);

        if (! $check) {
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
        if ($check->browser_id !== $clientUuid) {
            return response()->json(['message' => 'Unauthorized to delete this attachment'], 403);
        }

        // NOTE: The attachment is no longer hard delete
        // Remove file from public storage if present
        // $filePath = public_path($attachment->file_location);
        // if (file_exists($filePath)) {
        //     @unlink($filePath);
        // }

        $attachment->delete();

        return to_route('index')
            ->with('success', [
                'title' => 'Image Removed',
                'content' => 'You successfuly removed the image.'
            ]);
    }
}
