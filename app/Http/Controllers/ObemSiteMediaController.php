<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\ObemSiteArticle;
use App\Models\ObemArticleMedium;

class ObemSiteMediaController extends Controller
{
    function new_article(Request $request, $id=-1)
    {
        $fail_safe = __('obem.fail_safe_message');

        try 
        {
            // Prolog
            load_locale($request);
            $obem_open_graph_proto_locale = 'fr_FR';
            $obem_site_title = obem_site_title(__FUNCTION__);

            // Data
            $stringified_supported_languages = json_encode(
                get_supported_languages(), 0, 30
            );
            $should_update = 'false';
            $update_article_note = "";
            $stringified_article = "";
            $obem_article_create_endpoint = action(
                [ObemSiteMediaController::class, 'create_article']
            );

            if($id != -1)
            {
                $article = ObemSiteArticle::find($id);
                if($article)
                {
                    $should_update = 'true';
                    $update_article_note = __('obem.update_article_note');
                    $stringified_article = json_encode($article, 0, 30);
                    $obem_article_create_endpoint = action(
                        [
                            ObemSiteMediaController::class, 
                            'update_article'
                        ],
                        ['id' => $article->id]
                    );
                }
            }

            return view('obem_site_media.new_article')
                    ->with('site_title', $obem_site_title)
                    ->with(
                        'obem_open_graph_proto_locale', 
                        $obem_open_graph_proto_locale
                    )
                    ->with('should_update', $should_update)
                    ->with('update_article_note', $update_article_note)
                    ->with(
                        'stringified_supported_languages', 
                        $stringified_supported_languages
                    )
                    ->with('stringified_article', $stringified_article)
                    ->with(
                        'obem_article_create_endpoint', 
                        $obem_article_create_endpoint
                    );
        }
        catch(Exception $e)
        {
            $message = '(' . date("D M d, Y G:i") . ') ---> [' . __FUNCTION__ . '] ' . $e->getMessage();
            Log::error($message);
        }

        return view('fail_safe')
                ->with('fail_safe_message', $fail_safe);

    } // new_article

    function new_media(Request $request, $id=-1)
    {
        $fail_safe = __('obem.fail_safe_message');

        try 
        {
            // Prolog
            load_locale($request);
            $obem_open_graph_proto_locale = 'fr_FR';
            $obem_site_title = obem_site_title(__FUNCTION__);

            // Data
            $upload_endpoint = action(
                [ObemSiteMediaController::class, 'upload_media']
            );

            if($id != -1)
            {
                $upload_endpoint = action(
                    [ObemSiteMediaController::class, 'update_media'], ['id' => $id]
                );
            }

            return view('obem_site_media.new_media')
                    ->with('site_title', $obem_site_title)
                    ->with(
                        'obem_open_graph_proto_locale', 
                        $obem_open_graph_proto_locale
                    )
                    ->with('upload_endpoint', $upload_endpoint);
        }
        catch(Exception $e)
        {
            $message = '(' . date("D M d, Y G:i") . ') ---> [' . __FUNCTION__ . '] ' . $e->getMessage();
            Log::error($message);
        }

        return view('fail_safe')
                ->with('fail_safe_message', $fail_safe);

    } // new_media

    function serve_medium(Request $request, $id)
    {
        try 
        {
            $medium = ObemArticleMedium::find($id);
            if(!$medium)
            {
                throw new Exception('Medium with id ' . $id . ' could not be located');
            }
            $path_to_file = storage_path('app/' . $medium->media_file_path);
            //Log::info('---> Path to file: ' . $path_to_file);

            return response()->file($path_to_file);

        }
        catch(Exception $e)
        {
            $message = '(' . date("D M d, Y G:i") . ') ---> [' . __FUNCTION__ . '] ' . $e->getMessage();
            Log::error($message);
        }

    } // serve_medium

    function show_medium(Request $request, $id)
    {
        $fail_safe = __('obem.fail_safe_message');

        try 
        {
            // Prolog
            $obem_open_graph_proto_locale = 'fr_FR';
            $site_title = obem_site_title(__FUNCTION__);

            // Data

            $medium = ObemArticleMedium::find($id);
            if(!$medium)
            {
                throw new Exception('Medium with id ' . $id . ' could not be located');
            }
            $medium_type = $medium->mime_type;
            $medium_url = action(
                [
                    ObemSiteMediaController::class, 
                    'serve_medium'
                ],
                ['id' => $medium->id]
            );

            return view('obem_site_media.show_medium')
                    ->with('site_title', $site_title)
                    ->with(
                        'obem_open_graph_proto_locale', 
                        $obem_open_graph_proto_locale
                    )
                    ->with('medium_url', $medium_url)
                    ->with('mime_type', $medium_type);
        }
        catch(Exception $e)
        {
            $message = '(' . date("D M d, Y G:i") . ') ---> [' . __FUNCTION__ . '] ' . $e->getMessage();
            Log::error($message);
        }

        return view('fail_safe')
                ->with('fail_safe_message', $fail_safe);

    } // show_medium

    function update_article(Request $request, $id)
    {
        $fail_safe = __('obem.fail_safe_message');

        try 
        {
            $article = ObemSiteArticle::find($id);
            if($article)
            {
                $data_to_send = $this->store_article($request, true, $article);
            }
            else 
            {
                $message = 'No valid article to update was found';
                $data_to_send = [
                    'message' => $message,
                    'code' => 0
                ];
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
        }

        return view('fail_safe')
                ->with('fail_safe_message', $fail_safe);

    } // update_article

    function create_article(Request $request)
    {
        $fail_safe = __('obem.fail_safe_message');

        try 
        {
            $data_to_send = $this->store_article($request, false, null);

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
        }

        return view('fail_safe')
                ->with('fail_safe_message', $fail_safe);

    } // create_article

    // The id here is not medium id but is the id of the article to which the media
    // whose article_id = id belongs
    function update_media(Request $request, $id)
    {
        $fail_safe = __('obem.fail_safe_message');

        try 
        {
            if($request->has('uploads_completed'))
            {
                $data_to_send = $this->finishing_up_media_uploads(true);
            }
            else 
            {
                $data_to_send = $this->store_media($request, true); // same as upload
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
        }

        return view('fail_safe')
                ->with('fail_safe_message', $fail_safe);

    } // update_media

    function upload_media(Request $request)
    {
        $fail_safe = __('obem.fail_safe_message');

        try 
        {
            $data_to_send = null;
            if($request->has('uploads_completed'))
            {
                $data_to_send = $this->finishing_up_media_uploads();
            }
            else 
            {
                $data_to_send = $this->store_media($request, false, null);
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
        }

        return view('fail_safe')
                ->with('fail_safe_message', $fail_safe);

    } // upload_media

    function store_article(Request $request, $should_update=false, $article=null)
    {
        $data = null;

        try 
        {
            $data_to_send = null;

            if(
                !$request->has(['capture', 'locale', 'body', 'date'])
            ){
                $message = 'One of the following keys are missing in the request: capture, locale, body, and date';
                $data_to_send = [
                    'message' => $message,
                    'code' => 0
                ];
            }
            else // success
            {
                $capture = $request->input('capture');
                $locale = $request->input('locale');
                $body = $request->input('body');
                $date = $request->input('date');
                $date_time = date($date);
                $obem_site_article = $article;

                $stored = false;
                $action_url = "";
                if($should_update)
                {
                    if(!$obem_site_article)
                    {
                        throw new Exception('You must have given an invalid article object');
                    }

                    $stored = $obem_site_article->update([
                        'capture' => $capture,
                        'locale' => $locale,
                        'body' => $body,
                        'date' => $date_time
                    ]);
                    $action_url = action(
                        [
                            ObemSiteMediaController::class, 
                            'new_media'
                        ],
                        ['id' => $obem_site_article->id]
                    );
                }
                else 
                {
                    $obem_site_article = ObemSiteArticle::create([
                        'capture' => $capture,
                        'locale' => $locale,
                        'body' => $body,
                        'date' => $date_time
                    ]);
                    $stored = $obem_site_article->save();
                    $action_url = action(
                        [ObemSiteMediaController::class, 'new_media']
                    );
                }

                if($stored)
                {
                    session(['new_article_id' => $obem_site_article->id]);
                    $message = 'OBEM article created successfully. You can' . 
                               ' proceed and create contained media at: <a href="' . 
                               $action_url . 
                               '" style="decoration: underline">' . 
                               __('obem.create_media') . '</a>';
                    $data_to_send = [
                        'message' => $message,
                        'code' => 1
                    ];
                }
                else 
                {
                    $message = 'Sorry, we failed to create the article, certainly due to input data or some othe errors';
                    $data_to_send = [
                        'message' => $message,
                        'code' => 0
                    ];
                }
            }

            $data = $data_to_send;
        }
        catch(Exception $e)
        {
            $message = '(' . date("D M d, Y G:i") . ') ---> [' . __FUNCTION__ . '] ' . $e->getMessage();
            Log::error($message);
            $data = [
                'message' => $message,
                'code' => 0
            ];
        }

        return $data;

    } // store_article

    function store_media(Request $request, $should_update=false)
    {
        $data = null;
        
        try 
        {
            $data_to_send = null;
            $file_key = 'uploaded_site_media_file';
            if(!$request->hasFile($file_key))
            {
                //$dump = '===> Upload request dump: ' . json_encode($request, 0, 30);
                //Log::info($dump);

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
                //Log::info('Processing uploaded file ...');
                $article_id = session('new_article_id', -1);
                $do_not_ignore_check = true;

                if(!ObemSiteArticle::find($article_id) && $do_not_ignore_check)
                {
                    Log::info('---> ARTICLE ID: ' . $article_id);
                    $file = $request->file($file_key);
                    $file_name = $file->getClientOriginalName();
                    $message = 'Media [' . $file_name . '] does not seem to belong'.
                               ' to any article, so we could not create it.';
                    $data_to_send = [
                        'message' => $message,
                        'code' => 0
                    ];
                }
                else 
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
                        'article_id' => $article_id
                    ]);
                    $stored = $medium->save();
                    $medium_id = $medium->id;

                    if($stored)
                    {
                        // medium-validate article
                        medium_validate_article_body($medium);
                        // General feedback
                        $action_url = action(
                            [
                                ObemSiteMediaController::class, 
                                'show_medium'
                            ],
                            ['id' => $medium_id]
                        );
                        $message = 'Media successfully uploaded. You can visualize'. 
                                   ' it at: <a href="' . $action_url . 
                                   '" style="decoration: underline"> See Medium </a>';
                        session(['last_uploaded_medium_id' => $medium_id]);

                        // Give feedback
                        $data_to_send = [
                            'message' => $message,
                            'code' => 1
                        ];
                    }
                    else 
                    {
                        $message = 'We failed to store media to our database';
                        $data_to_send = [
                            'message' => $message,
                            'code' => 0
                        ];
                    }
                }
            }

            $data = $data_to_send;
        }
        catch(Exception $e)
        {
            $message = '(' . date("D M d, Y G:i") . ') ---> [' . __FUNCTION__ . '] ' . $e->getMessage();
            Log::error($message);
            $data = [
                'message' => $message,
                'code' => 0
            ];
        }

        return $data;

    } // store_media

    function finishing_up_media_uploads($should_update=false)
    {
        try 
        {
            $data_to_send = null;
            if(true)
            {
                $article_id = session('new_article_id');
                $medium_id = session('last_uploaded_medium_id');
                session(['new_article_id' => -1]);
                session(['last_uploaded_medium_id' => -1]);

                // Clean ?
                if($should_update)
                {
                    clean_media_storage($article_id, $medium_id);
                }

                $message = '';
                $action_url = action(
                    [
                        ObemSiteMediaController::class, 
                        'show_medium'
                    ],
                    ['id' => $medium_id]
                );
                $ary = storage_validate_article_body($article_id);
                if(count($ary) > 0)
                {
                    $update_url = action(
                        [ObemSiteMediaController::class, 'new_article'], 
                        ['id' => $article_id]
                    );
                    $message = 'These files: ' . join(', ', $ary) . 
                               ' must not have been uploaded. So your' . 
                               ' article is invalid, unless you' . 
                               ' update it here: <a href="' . $update_url .
                               '" style="decoration: underline"> '. 
                               'Validate Article </a> OR <a href="' . 
                               $action_url .
                               '" style="decoration: underline">'. 
                               ' View Medium </a>';
                }
                else 
                {
                    $message = 'Success. The upload process is over. ' .
                               'View last upload media at: <a href="' . 
                               $action_url . 
                               '" style="decoration: underline"> See Medium </a>';
                }
                $data_to_send = [
                    'message' => $message,
                    'code' => 1
                ];
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

    } // finishing_up_media_uploads
}
