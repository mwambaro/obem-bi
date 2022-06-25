<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ObemArticleMedium;
use App\Models\EmploymentFolder;
use App\Http\Controllers\ObemSiteMediaController;
use App\Http\Controllers\EmploymentFoldersController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{
    function index(Request $request)
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
            $view_data = '';

            $users = DB::table('users')->get();
            foreach($users as $idx => $user)
            {
                $id = $user->id;
                $show_user_id = 'show-user-' . $idx;
                $user_data = $this->show_user_data($id, 'true');
                $view_data .= "\n\n" . view('users._show_user')
                                        ->with('show_user_id', $show_user_id)
                                        ->with('user_data', $user_data);
            }

            return view('users.index')
                    ->with('site_title', $obem_site_title)
                    ->with(
                        'obem_open_graph_proto_locale', 
                        $obem_open_graph_proto_locale
                    )
                    ->with('view_data', $view_data);
        }
        catch(Exception $e)
        {
            $message = '(' . date("D M d, Y G:i") . ') ---> [' . __FUNCTION__ . '] ' . $e->getMessage();
            Log::error($message);
        }

        return view('fail_safe')
                ->with('fail_safe_message', $fail_safe);

    } // index

    function show_user(Request $request, $id, $view_mode_str='false')
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
            $show_user_id = 'show-user';
            $user_data = $this->show_user_data($id, $view_mode_str);

            return view('users.show_user')
                    ->with('site_title', $obem_site_title)
                    ->with(
                        'obem_open_graph_proto_locale', 
                        $obem_open_graph_proto_locale
                    )
                    ->with('show_user_id', $show_user_id)
                    ->with('user_data', $user_data);
        }
        catch(Exception $e)
        {
            $message = '(' . date("D M d, Y G:i") . ') ---> [' . __FUNCTION__ . '] ' . $e->getMessage();
            Log::error($message);
        }

        return view('fail_safe')
                ->with('fail_safe_message', $fail_safe);

    } // show_user

    function show_user_data($id, $view_mode_str)
    {
        $data = null;

        try 
        {
            // Data
            $cover_letter_url = null;
            $cv_url = null;
            $user_highest_degree = null;
            $user_phone_number = null;
            $user_address = null;
            $has_employment_folder = 'false';
            $upload_profile_photo_action_url = null;
            $view_mode = $view_mode_str;
            $view_mode_url = null;
            $destroy_user_url = null;
            $edit_user_url = null;
            $user_email = null;
            $user_role = null;
            $user_full_name = null;
            $profile_photo_url = null;

            // Process
            $user = User::find($id);
            if($user)
            {
                $upload_profile_photo_action_url = action(
                    [UsersController::class, 'create_profile_photo'],
                    ['id' => $user->id]
                );
                $user_email = $user->email;
                $user_role = $user->role;
                $user_full_name = $user->first_name . ' ' . $user->last_name;
                $profile_photo_url = $user->profile_photo_id != null ?
                                     action(
                                        [ObemSiteMediaController::class, 'serve_medium'],
                                        ['id' => $user->profile_photo_id]
                                     ) :
                                     asset('images/profile_photo.JPG');
                $edit_user_url = action(
                    [UsersController::class, 'new_user'],
                    ['id' => $user->id]
                );
                $destroy_user_url = action(
                    [UsersController::class, 'delete_user'],
                    ['id' => $user->id]
                );
                $view_mode_url = action(
                    [UsersController::class, 'show_user'],
                    ['id' => $user->id, 'view_mode_str' => 'true']
                );
                $has_employment_folder = $user->employment_folder_id != null ? 'true' : 'false';
                if($has_employment_folder)
                {
                    $folder = EmploymentFolder::find($user->employment_folder_id);
                    if($folder)
                    {
                        $user_address = $folder->address;
                        $user_phone_number = $folder->phone_number;
                        $user_highest_degree = $folder->highest_degree;
                        $cv_url = action(
                            [
                                EmploymentFoldersController::class, 
                                'serve_cv'
                            ],
                            ['id' => $folder->id]
                        );
                        $cover_letter_url = action(
                            [
                                EmploymentFoldersController::class, 
                                'serve_cover_letter'
                            ],
                            ['id' => $folder->id]
                        );
                    }
                }
            }

            $data = [
                'cover_letter_url' => $cover_letter_url,
                'cv_url' => $cv_url,
                'user_highest_degree' => $user_highest_degree,
                'user_phone_number' => $user_phone_number,
                'user_address' => $user_address,
                'has_employment_folder' => $has_employment_folder,
                'upload_profile_photo_action_url' => $upload_profile_photo_action_url,
                'view_mode' => $view_mode,
                'view_mode_url' => $view_mode_url,
                'destroy_user_url' => $destroy_user_url,
                'edit_user_url' => $edit_user_url,
                'user_email' => $user_email,
                'user_role' => $user_role,
                'user_full_name' => $user_full_name,
                'profile_photo_url' => $profile_photo_url
            ];
        }
        catch(Exception $e)
        {
            $message = '(' . date("D M d, Y G:i") . ') ---> [' . __FUNCTION__ . '] ' . $e->getMessage();
            Log::error($message);
        }

        return $data;

    } // show_user_data

    function new_user(Request $request, $id=-1)
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
            $stringified_user = '';
            $user_create_endpoint = action(
                [UsersController::class, 'create_user']
            );

            // Body
            if($id != -1)
            {
                $user = User::find($id);
                if($user)
                {
                    $stringified_user = json_encode($user);
                    $should_update = 'true';
                    $user_create_endpoint = action(
                        [UsersController::class, 'update_user'],
                        ['id' => $user->id]
                    );
                }
            }

            return view('users.new_user')
                    ->with('site_title', $obem_site_title)
                    ->with(
                        'obem_open_graph_proto_locale', 
                        $obem_open_graph_proto_locale
                    )
                    ->with('should_update', $should_update)
                    ->with('stringified_user', $stringified_user)
                    ->with('user_create_endpoint', $user_create_endpoint);
        }
        catch(Exception $e)
        {
            $message = '(' . date("D M d, Y G:i") . ') ---> [' . __FUNCTION__ . '] ' . $e->getMessage();
            Log::error($message);
        }

        return view('fail_safe')
                ->with('fail_safe_message', $fail_safe);

    } // new_user

    function sign_out(Request $request)
    {
        $fail_safe = __('obem.fail_safe_message');

        try 
        {
            // Prolog
            load_locale($request);

            log_out();

            return redirect(action(
                [ObemMainController::class, 'home']
            ));
        }
        catch(Exception $e)
        {
            $message = '(' . date("D M d, Y G:i") . ') ---> [' . __FUNCTION__ . '] ' . $e->getMessage();
            Log::error($message);
        }

        return view('fail_safe')
                ->with('fail_safe_message', $fail_safe);

    } // sign_out

    function new_sign_in(Request $request)
    {
        $fail_safe = __('obem.fail_safe_message');

        try 
        {
            // Prolog
            load_locale($request);
            seed_articles();
            harvest_analytics($request);
            $obem_open_graph_proto_locale = 'fr_FR';
            $obem_site_title = obem_site_title(__FUNCTION__);

            // Logged in?
            $user = who_is_logged_in();
            if($user)
            {
                return back();
            }

            // Data
            $user_sign_in_endpoint = action(
                [UsersController::class, 'sign_in']
            );
            $new_user_url = action(
                [UsersController::class, 'new_user']
            );

            return view('users.new_sign_in')
                    ->with('site_title', $obem_site_title)
                    ->with(
                        'obem_open_graph_proto_locale', 
                        $obem_open_graph_proto_locale
                    )
                    ->with('user_sign_in_endpoint', $user_sign_in_endpoint)
                    ->with('new_user_url', $new_user_url);
        }
        catch(Exception $e)
        {
            $message = '(' . date("D M d, Y G:i") . ') ---> [' . __FUNCTION__ . '] ' . $e->getMessage();
            Log::error($message);
        }

        return view('fail_safe')
                ->with('fail_safe_message', $fail_safe);

    } // new_sign_in

    function sign_in(Request $request)
    {
        try 
        {
            // Prolog
            load_locale($request);

            $data_to_send = null;

            if(!$request->has(['password', 'email']))
            {
                $message = 'The obem sign in form must ' .
                           'contain all of these input fields: ' . 
                           'password, and email.';
                $data_to_send = [
                    'message' => $message,
                    'code' => 0
                ];
            }
            else 
            {
                $password = $request->input('password');
                $email = $request->input('email');
                $user = DB::table('users')
                            ->where('email', $email)
                            ->first();
                if($user)
                {
                    $digest = password_digest($password);
                    if($user->password == $digest)
                    {
                        session(['user_id' => $user->id]);
                        DB::table('users')
                            ->where('id', $user->id)
                            ->update(['last_login' => date('Y-m-d H:i:s')]);
                        $data_to_send = [
                            'message' => __('obem.logged_in_true'),
                            'code' => 1
                        ];
                    }
                    else 
                    {
                        $data_to_send = [
                            'message' => __('obem.logged_in_false'),
                            'code' => 0
                        ];
                    }
                }
                else 
                {
                    $data_to_send = [
                        'message' => __('obem.logged_in_false'),
                        'code' => 0
                    ];
                }
            }

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

    } // sign_in

    function update_user(Request $request, $id)
    {
        try 
        {
            // Prolog
            load_locale($request);

            $user = User::find($id);
            $data_to_send = $this->store_user($request, true, $user);

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

    } // update_user

    function create_user(Request $request)
    {
        try 
        {
            // Prolog
            load_locale($request);

            $data_to_send = $this->store_user($request);

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

    } // create_user

    function delete_user(Request $request, $id)
    {
        try 
        {
            // Prolog
            load_locale($request);
            
            $user = User::find($id);
            if($user)
            {
                $profile_photo_id = $user->profile_photo_id;
                $folder_id = $user->employment_folder_id;

                $val = DB::table('users')->where('id', $id)->delete();
                if($val)
                {}

                if($profile_photo_id)
                {
                    $this->clean_profile_photo($profile_photo_id);
                }
                if($folder_id)
                {
                    clean_employment_folder($folder_id);
                }
            }
        }
        catch(Exception $e)
        {
            $message = '(' . date("D M d, Y G:i") . ') ---> [' . __FUNCTION__ . '] ' . $e->getMessage();
            Log::error($message);
        }

    } // delete_user

    function clean_profile_photo($id)
    {
        try 
        {  
            $old_medium = ObemArticleMedium::find($id);
            if($old_medium)
            {
                $path = $old_medium->media_file_path;
                Storage::delete($path);
                DB::table('obem_article_media')
                    ->where('id', $old_medium->id)
                    ->delete();
            }
        }
        catch(Exception $e)
        {
            $message = '(' . date("D M d, Y G:i") . ') ---> [' . __FUNCTION__ . '] ' . $e->getMessage();
            Log::error($message);
        }

    } // clean_profile_photo

    function create_profile_photo(Request $request, $id)
    {
        $data_to_send = null;

        try 
        {
            $file_key = 'profile_photo_uploaded_file';
            if(!$request->hasFile($file_key))
            {
                $message = 'Your form data must contain this file key: ' . $file_key;
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
            else 
            {
                $user = User::find($id);
                if($user)
                {
                    $file = $request->file($file_key);
                    $mime_type = $file->getMimeType();
                    $file_name = $file->getClientOriginalName();
                    $media_file_path = $file->store('public');

                    Log::info('Upload: MimeType => "' . $mime_type . '", FileName => "' . $file_name . '"');

                    $medium = ObemArticleMedium::create([
                        'mime_type' => $mime_type,
                        'media_file_name' => $file_name,
                        'media_file_path' => $media_file_path,
                        'article_id' => 0
                    ]);
                    $stored = $medium->save();
                    if($stored)
                    {
                        $old_profile_photo_id = $user->profile_photo_id;
                        // Update user
                        DB::table('users')
                            ->where('id', $user->id)
                            ->update(['profile_photo_id' => $medium->id]);

                        // Clean storage
                        $this->clean_profile_photo($old_profile_photo_id);

                        $message = 'Profile photo successfully saved to database';
                        $data_to_send = [
                            'message' => $message,
                            'code' => 1
                        ];
                    }
                    else 
                    {
                        $message = 'We failed to store profile photo to our database';
                        $data_to_send = [
                            'message' => $message,
                            'code' => 0
                        ];
                    }
                }
                else 
                {
                    $message = 'We could not locate the user ' . 
                               'for whom the photo is. ID: ' . $id;
                    $data_to_send = [
                        'message' => $message,
                        'code' => 0
                    ];
                }
            }

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

    } // create_profile_photo

    function store_user(Request $request, $should_update=false, $user=null)
    {
        $data_to_send = null;

        try 
        {
            if(
                !$request->has(
                    [
                        'first_name', 
                        'last_name', 
                        'user_name',
                        'email', 
                        'password',
                        'password_verification', 
                        'obem_employee'
                    ]
                )
            ){
                $message = 'The obem sign up form must ' .
                           'contain all of these input fields: ' . 
                           'first_name, last_name, email, user_name, ' .
                           'password, password_verification, obem_employee';
                $data_to_send = [
                    'message' => $message,
                    'code' => 0
                ];
            }
            else 
            {
                $first_name = $request->input('first_name');
                $last_name = $request->input('last_name');
                $email = $request->input('email');
                $user_name = $request->input('user_name');
                $password = $request->input('password');
                $verif_password = $request->input('password_verification');
                $admin_email = env('ADMIN_EMAIL', 'onkezabahizi@gmail.com');
                $email_regex = '/\A' . preg_quote($admin_email) . '\Z/i';
                $obem_employee = $request->input('obem_employee');
                $role = 'Client';

                if($password != $verif_password)
                {
                    $message = __('obem.model_create_mismatch');
                    return $data_to_send = [
                        'message' => $message,
                        'code' => 0
                    ];
                }
                if(preg_match($email_regex, $email))
                {
                    $role = 'Admin';
                }
                elseif(preg_match('/\Ayes\Z/i', $obem_employee))
                {
                    $role = 'Employee';
                }

                $stored = false;
                if($should_update)
                {
                    if(!$user)
                    {
                        throw new Exception('You should have given a valid user object to update');
                    }

                    $stored = $user->update([
                        'first_name' => $first_name,
                        'last_name' => $last_name,
                        'user_name' => $user_name,
                        'role' => $role,
                        'email' => $email,
                        'password' => password_digest($password)
                    ]);
                }
                else 
                {
                    $user = User::create([
                        'first_name' => $first_name,
                        'last_name' => $last_name,
                        'user_name' => $user_name,
                        'role' => $role,
                        'email' => $email,
                        'password' => password_digest($password)
                    ]);
                    $stored = $user->save();
                }

                if($stored)
                {
                    $message = 'User successfully created.';
                    if(preg_match('/\Aclient\Z/i', $role) && !$user->employment_folder_id)
                    {
                        $url = action(
                            [EmploymentFoldersController::class, 'new_employment_folder'],
                            ['id' => -1, 'new_user_id' => $user->id]
                        );
                        $message = $message . 
                                   ' You may want to create your employment dossier at: ' . 
                                   '<a href="' . $url .
                                   '" style="text-decoration: underline"> Dossier </a>';
                    }

                    $data_to_send = [
                        'message' => $message,
                        'code' => 1
                    ];
                }
                else 
                {
                    $message = 'Could not create user due to errors';
                    $data_to_send = [
                        'message' => $message,
                        'code' => 1
                    ];
                }
            }
        }
        catch(Exception $e)
        {
            $message = '(' . date("D M d, Y G:i") . ') ---> [' . __FUNCTION__ . '] ' . $e->getMessage();
            Log::error($message);
        }

        return $data_to_send;

    } // store_user
}
