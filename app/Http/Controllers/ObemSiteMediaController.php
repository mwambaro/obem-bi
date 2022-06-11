<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ObemSiteMediaController extends Controller
{
    function upload_media(Request $request)
    {
        $fail_safe = __('obem.fail_safe_message');

        try 
        {
            $data_to_send = null;
            $file_key = 'uploaded_site_media_file';
            if(!$request->hasFile($file_key))
            {
                $dump = '===> Upload request dump: ' . json_encode($request, 0, 30);
                Log::info($dump);

                $message = 'File with key [' . $file_key . '] was not found in the request';
                $data_to_send = [
                    'message' => $message,
                    'code' => 0
                ];
            }
            elseif(!$request->file($file_key)->isValid())
            {
                $err_msg = $request->file($file_key)->getErrorMessage();
                $message = 'ERROR: ' . $err_msg;
                $data_to_send = [
                    'message' => $message,
                    'code' => 0
                ];
            }
            else // success
            {
                Log::info('Processing uploaded file ...');
                $file = $request->file($file_key);
                $mime_type = $file->getMimeType();
                $file_name = $file->getClientOriginalName();
                $data = file_get_contents($file->getRealPath());

                Log::info('Upload: MimeType => "' . $mime_type . '", FileName => "' . $file_name . '"');

                $message = 'Media successfully uploaded';
                $data_to_send = [
                    'message' => $message,
                    'code' => 1
                ];
            }

            // return data to client
            if($data_to_send)
            {
                return response()->json($data_to_send);
            }
            else 
            {
                throw 'Yikes something must have gone wrong on our end';
            }
        }
        catch(Exception $e)
        {
            $message = '(' . date("D M d, Y G:i") . ') ---> [' . __FUNCTION__ . '] ' . $e->getMessage();
            Log::error($message);
        }

        return view('fail_safe')
                ->with('fail_safe_message', $fail_safe);

    } // upload_media
}
