<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class FilePondUploadController extends Controller
{
    /**
     * Upload image to tmp dir and return encrypted path.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request)
    {
        $request->validate([
            'attachments' => 'sometimes|image|mimes:jpeg,png',
        ]);

        $file = $request->file('attachments');

        $filePath = tempnam(realpath(sys_get_temp_dir()), 'attachment-');
        $filePath .= ".{$file->getClientOriginalExtension()}";
        $fileInfo = pathinfo($filePath);

        if (! $file->move($fileInfo['dirname'], $fileInfo['basename'])) {
            return response('File could not be saved.', Response::HTTP_BAD_REQUEST);
        }

        return response(Crypt::encryptString($filePath), Response::HTTP_OK, ['Content-Type' => 'text/plain']);
    }

    /**
     * Delete image from tmp dir.
     *
     * @param Request $request
     * @return void
     */
    public function delete(Request $request)
    {
        $uniqueFileId = $request->getContent();

        if (empty($uniqueFileId)) {
            return response()->json([
                'error' => [
                    'message' => 'Missing file!',
                ],
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $filePath = Crypt::decryptString($uniqueFileId);

            if (unlink($filePath)) {
                return response()->json(['success' => true], Response::HTTP_OK);
            }

            return response()->json([
                'error' => [
                    'message' => 'Could not remove file.',
                ],
            ], Response::HTTP_NOT_ACCEPTABLE);
        } catch (DecryptException $e) {
            return response()->json([
                'error' => [
                    'message' => $e->getMessage(),
                ],
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
