<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\EmploymentFolder;

class EmploymentFoldersController extends Controller
{
    function serve_cv(Request $request, $id)
    {
        try 
        {
            $folder = EmploymentFolder::find($id);
            if(!$folder)
            {
                throw new Exception('Could not locate folder with id ' . $id);
            }
            $path_to_file = storage_path('app/' . $folder->cv_unique_file_path);
            //Log::info('---> Path to file: ' . $path_to_file);

            return response()->file($path_to_file);
        }
        catch(Exception $e)
        {
            $message = '(' . date("D M d, Y G:i") . ') ---> [' . __FUNCTION__ . '] ' . $e->getMessage();
            Log::error($message);
        }

    } // serve_cv

    function serve_cover_letter(Request $request, $id)
    {
        try 
        {
            $folder = EmploymentFolder::find($id);
            if(!$folder)
            {
                throw new Exception('Could not locate folder with id ' . $id);
            }
            $path_to_file = storage_path('app/' . $folder->cover_letter_unique_file_path);
            //Log::info('---> Path to file: ' . $path_to_file);

            return response()->file($path_to_file);
        }
        catch(Exception $e)
        {
            $message = '(' . date("D M d, Y G:i") . ') ---> [' . __FUNCTION__ . '] ' . $e->getMessage();
            Log::error($message);
        }

    } // serve_cover_letter

    function new_employment_folder(Request $request, $id=-1, $new_user_id=-1)
    {
        $fail_safe = __('obem.fail_safe_message');

        try 
        {
            // Prolog
            load_locale($request);
            seed_articles();
            $obem_open_graph_proto_locale = 'fr_FR';
            $obem_site_title = obem_site_title(__FUNCTION__);

            // Data
            $should_update = 'false';
            $update_employment_folder_note = __('obem.update_employment_folder_note');
            $stringified_employment_folder = '';
            $employment_folder_create_endpoint = action(
                [EmploymentFoldersController::class, 'create_employment_folder']
            );

            // Body
            $user = who_is_logged_in();
            $new_user = $new_user_id == -1 ? null : User::find($new_user_id);
            if($user || $new_user) // can access dossier creation
            {
                if($new_user)
                {
                    session(['new_user_id' => $new_user_id]);
                }
            }

            if($id != -1)
            {
                $employment_folder = EmploymentFolder::find($id);
                if($employment_folder)
                {
                    $stringified_employment_folder = json_encode($employment_folder);
                    $employment_folder_create_endpoint = action(
                        [EmploymentFoldersController::class, 'update_employment_folder'],
                        ['id' => $employment_folder->id]
                    );
                    $should_update = 'true';
                }
            }

            return view('employment_folders.new_employment_folder')
                    ->with('site_title', $obem_site_title)
                    ->with(
                        'obem_open_graph_proto_locale', 
                        $obem_open_graph_proto_locale
                    )
                    ->with('should_update', $should_update)
                    ->with(
                        'stringified_employment_folder', 
                        $stringified_employment_folder
                    )
                    ->with(
                        'employment_folder_create_endpoint', 
                        $employment_folder_create_endpoint
                    )
                    ->with(
                        'update_employment_folder_note',
                        $update_employment_folder_note
                    );
        }
        catch(Exception $e)
        {
            $message = '(' . date("D M d, Y G:i") . ') ---> [' . __FUNCTION__ . '] ' . $e->getMessage();
            Log::error($message);
        }

        return view('fail_safe')
                ->with('fail_safe_message', $fail_safe);

    } // new_employment_folder

    function update_employment_folder(Request $request, $id)
    {
        try 
        {
            // Prolog
            load_locale($request);

            $employment_folder = EmploymentFolder::find($id);
            $data_to_send = $this->store_employment_folder($request, true, $employment_folder);

            // return data to client
            if(!$data_to_send)
            {
                $data_to_send = [
                    'message' => __('obem.something_went_wrong'),
                    'code' => 0
                ];
            }

            return response()->json($data_to_send);
        }
        catch(Exception $e)
        {
            $message = '(' . date("D M d, Y G:i") . ') ---> [' . __FUNCTION__ . '] ' . $e->getMessage();
            Log::error($message);
            $data_to_send = [
                'message' => $message,
                'code' => 0
            ];
            return response()->json($data_to_send);
        }

    } // update_employment_folder

    function create_employment_folder(Request $request)
    {
        try 
        {
            // Prolog
            load_locale($request);

            $data_to_send = $this->store_employment_folder($request);

            // return data to client
            if(!$data_to_send)
            {
                $data_to_send = [
                    'message' => __('obem.something_went_wrong'),
                    'code' => 0
                ];
            }

            return response()->json($data_to_send);
        }
        catch(Exception $e)
        {
            $message = '(' . date("D M d, Y G:i") . ') ---> [' . __FUNCTION__ . '] ' . $e->getMessage();
            Log::error($message);
            $data_to_send = [
                'message' => $message,
                'code' => 0
            ];
            return response()->json($data_to_send);
        }

    } // create_employment_folder

    function delete_employment_folder(Request $request, $id)
    {
        try 
        {
            // Prolog
            load_locale($request);
            
            $val = DB::table('employment_folders')->where('id', $id)->delete();
            if($val)
            {
                // Update user
                $user = who_is_logged_in();
                if($user)
                {
                    $actual_user = DB::table('users')
                                    ->where('employment_folder_id', $id)
                                    ->get()->first();
                    $actual_user->update([
                        'employment_folder_id' => 0
                    ]);
                }

                // Clean employment folder
            }
        }
        catch(Exception $e)
        {
            $message = '(' . date("D M d, Y G:i") . ') ---> [' . __FUNCTION__ . '] ' . $e->getMessage();
            Log::error($message);
        }

    } // delete_employment_folder

    function store_employment_folder(Request $request, $should_update=false, $employment_folder=null)
    {
        $data_to_send = null;

        try 
        {
            if(
                !$request->has(
                    ['address', 'phone_number', 'highest_degree']
                ) ||
                !$request->hasFile('cv_uploaded_file') ||
                !$request->hasFile('cover_letter_uploaded_file')
            ){
                $message = 'The obem employment folder form must ' .
                           'contain all of these input fields: ' . 
                           'address, phone_number, highest_degree, ' .
                           'cv_uploaded_file, cover_letter_uploaded_file';
                $data_to_send = [
                    'message' => $message,
                    'code' => 0
                ];
            }
            elseif(!$request->file('cv_uploaded_file')->isValid())
            {
                $err_msg = $request->file('cv_uploaded_file')->getErrorMessage();
                $message = 'ERROR: ' . $err_msg;
                $data_to_send = [
                    'message' => $message,
                    'code' => 0
                ];
            }
            elseif(!$request->file('cover_letter_uploaded_file')->isValid())
            {
                $err_msg = $request->file('cover_letter_uploaded_file')->getErrorMessage();
                $message = 'ERROR: ' . $err_msg;
                $data_to_send = [
                    'message' => $message,
                    'code' => 0
                ];
            }
            else 
            {
                $address = preg_replace('/\'/', '’', $request->input('address'));
                $phone_number = $request->input('phone_number');
                $highest_degree = preg_replace('/\'/', '’', $request->input('highest_degree'));
                $file = $request->file('cv_uploaded_file');
                $cv_mime_type = $file->getMimeType();
                $cv_unique_file_path = $file->store('public');
                $file = $request->file('cover_letter_uploaded_file');
                $cover_letter_mime_type = $file->getMimeType();
                $cover_letter_unique_file_path = $file->store('public');

                $stored = false;
                if($should_update)
                {
                    if(!$employment_folder)
                    {
                        throw new Exception('You must provide valid employment folder object for update');
                    }
                    $cv = $employment_folder->cv_unique_file_path;
                    $cover = $employment_folder->cover_letter_unique_file_path;
                    $stored = $employment_folder->update([
                        'address' => $address,
                        'phone_number' => $phone_number,
                        'highest_degree' => $highest_degree,
                        'cv_mime_type' => $cv_mime_type,
                        'cv_unique_file_path' => $cv_unique_file_path,
                        'cover_letter_mime_type' => $cover_letter_mime_type,
                        'cover_letter_unique_file_path' => $cover_letter_unique_file_path
                    ]);
                    // Delete previous files
                    if($stored)
                    {
                        Storage::delete($cv);
                        Storage::delete($cover);
                    }
                }
                else 
                {
                    $folder = EmploymentFolder::create([
                        'address' => $address,
                        'phone_number' => $phone_number,
                        'highest_degree' => $highest_degree,
                        'cv_mime_type' => $cv_mime_type,
                        'cv_unique_file_path' => $cv_unique_file_path,
                        'cover_letter_mime_type' => $cover_letter_mime_type,
                        'cover_letter_unique_file_path' => $cover_letter_unique_file_path
                    ]);
                    $stored = $folder->save();

                    // Update user
                    $user = who_is_logged_in();
                    if(!$user)
                    {
                        if(session('new_user_id'))
                        {
                            $user = User::find(session('new_user_id'));
                        }
                    }

                    if($user)
                    {
                        $updated = DB::table('users')
                                    ->where('id', $user->id)
                                    ->update([
                                        'employment_folder_id' => $folder->id
                                    ]);
                        if(!$updated)
                        {
                            Log::error(
                                'Failed to update user with id ' . $user->id . 
                                ' with employment folder id of ' . $folder->id
                            );
                        }
                        else 
                        {
                            Log::info(
                                'Successfully updated user with employment folder id ' . 
                                $folder->id
                            );
                        }
                    }
                    else 
                    {
                        Log::warning(
                            'Employment folder with id ' . $folder->id . 
                            ' should belong to a logged in user'
                        );
                    }
                }

                if($stored)
                {
                    $data_to_send = [
                        'message' => 'Employment folder successfully created',
                        'code' => 1
                    ];
                }
                else 
                {
                    $data_to_send = [
                        'message' => 'Could not create employment folder due to errors',
                        'code' => 1
                    ];
                }
            }
        }
        catch(Exception $e)
        {
            $message = '(' . date("D M d, Y G:i") . ') ---> [' . __FUNCTION__ . '] ' . $e->getMessage();
            Log::error($message);
            $data_to_send = [
                'message' => $message,
                'code' => 0
            ];
        }

        return $data_to_send;

    } // store_employment_folder
}
