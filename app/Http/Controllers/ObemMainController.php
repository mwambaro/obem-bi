<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ObemSiteMediaController;
use App\Http\Controllers\EmploymentFoldersController;

class ObemMainController extends Controller
{
    function home(Request $request)
    {
        $fail_safe = __('obem.fail_safe_message');

        try 
        {
            // Prolog
            load_locale($request);
            seed_articles();
            harvest_analytics($request);
            $obem_open_graph_proto_locale = 'fr_FR';
            $site_title = obem_site_title(__FUNCTION__);
        
            // Data
            $obem_navigation_bar_actions = json_encode(get_obem_navigation_bar_actions());
            $supported_languages = json_encode(get_supported_languages());
            $locale_end_point = action([ObemMainController::class, 'locale']);
            $active_language_locale = App::currentLocale();
            $sign_up_url = action([UsersController::class, 'new_user']);
            $sign_in_url = action([UsersController::class, 'new_sign_in']);
            $sign_out_url = action([UsersController::class, 'sign_out']);
            $obem_user_is_logged_in = 'false';
            $profile_photo_url = null;
            $show_profile_url = null;
            $home_url = action([ObemMainController::class, 'home']);
            $pages_analytics = get_pages_analytics();

            $user = who_is_logged_in();
            if($user)
            {
                $obem_user_is_logged_in = 'true';
                $profile_photo_url = $user->profile_photo_id != null ?
                                     action(
                                         [ObemSiteMediaController::class, 'serve_medium'],
                                         ['id' => $user->profile_photo_id]
                                     ) :
                                     asset('images/profile_photo.JPG');
                $show_profile_url = action(
                    [UsersController::class, 'show_user'],
                    ['id' => $user->id]
                );
            }
        
            return view('obem_main.home')
                    ->with('site_title', $site_title)
                    ->with('obem_open_graph_proto_locale', $obem_open_graph_proto_locale)
                    ->with('obem_navigation_bar_actions', $obem_navigation_bar_actions)
                    ->with('supported_languages', $supported_languages)
                    ->with('locale_end_point', $locale_end_point)
                    ->with('active_language_locale', $active_language_locale)
                    ->with('sign_up_url', $sign_up_url)
                    ->with('sign_in_url', $sign_in_url)
                    ->with('sign_out_url', $sign_out_url)
                    ->with('obem_user_is_logged_in', $obem_user_is_logged_in)
                    ->with('profile_photo_url', $profile_photo_url)
                    ->with('show_profile_url', $show_profile_url)
                    ->with('home_url', $home_url)
                    ->with('pages_analytics', $pages_analytics);
        }
        catch(Exception $e)
        {
            $message = '(' . date("D M d, Y G:i") . ') ---> [' . __FUNCTION__ . '] ' . $e->getMessage();
            Log::error($message);
        }

        return view('fail_safe')
                ->with('fail_safe_message', $fail_safe);

    } // home

    function orientation(Request $request)
    {
        $fail_safe = __('obem.fail_safe_message');

        try 
        {
            // Prolog
            load_locale($request);
            seed_articles();
            harvest_analytics($request);
            $obem_open_graph_proto_locale = 'fr_FR';
            $site_title = obem_site_title(__FUNCTION__);
        
            // Data
            $orientation_articles = null;

            $orientation_addresses_html_body = interpolate_article(
                __('obem.orientation_addresses')
            );
            $orientation_service_html_body = interpolate_article(
                __('obem.orientation_text')
            );
            $orientation = [
                $orientation_service_html_body,
                $orientation_addresses_html_body
            ];
            $orientation_articles = $orientation;

            return view('obem_main.orientation')
                    ->with('site_title', $site_title)
                    ->with('obem_open_graph_proto_locale', $obem_open_graph_proto_locale)
                    ->with('orientation_articles', $orientation_articles);
        }
        catch(Exception $e)
        {
            $message = '(' . date("D M d, Y G:i") . ') ---> [' . __FUNCTION__ . '] ' . $e->getMessage();
            Log::error($message);
        }

        return view('fail_safe')
                ->with('fail_safe_message', $fail_safe);

    } // orientation

    function locale(Request $request)
    {
        try 
        {
            $data_to_send = set_locale($request);
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

    } // locale

    function community(Request $request)
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

            // Data
            $obem_community_media = [];
            $community_description = interpolate_article(__('obem.organigramme_text'));

            $media = [
                'institution_objective_image.JPG',
                'report_gihanga_3.jpg',
                'report_kiganda_0.jpg',
                'report_muramvya_0.jpg'
            ];
            foreach($media as $idx => $image)
            {
                if($idx == 0)
                {
                    array_push(
                        $obem_community_media,
                        [
                            'url' => asset("images/" . $image),
                            'capture' => __('obem.institution_objective'),
                            'description' => __('obem.institution_objective_quote')
                        ]
                    );
                }
                elseif($idx == 1)
                {
                    array_push(
                        $obem_community_media,
                        [
                            'url' => asset("images/" . $image),
                            'capture' => __('obem.gihanga_porte_ouverte_report'),
                            'description' => ''
                        ]
                    );
                }
                elseif($idx == 2)
                {
                    array_push(
                        $obem_community_media,
                        [
                            'url' => asset("images/" . $image),
                            'capture' => __('obem.kiganda_porte_ouverte_report'),
                            'description' => ''
                        ]
                    );
                }
                elseif($idx == 3)
                {
                    array_push(
                        $obem_community_media,
                        [
                            'url' => asset("images/" . $image),
                            'capture' => __('obem.muramvya_porte_ouverte_report'),
                            'description' => ''
                        ]
                    );
                }
            }
            if(count($obem_community_media) > 0)
            {
                $obem_community_media = json_encode($obem_community_media);
            }

            return view('obem_main.community')
                    ->with('site_title', $obem_site_title)
                    ->with(
                        'obem_open_graph_proto_locale', 
                        $obem_open_graph_proto_locale
                    )
                    ->with('obem_community_media', $obem_community_media)
                    ->with('community_description', $community_description);
        }
        catch(Exception $e)
        {
            $message = '(' . date("D M d, Y G:i") . ') ---> [' . __FUNCTION__ . '] ' . $e->getMessage();
            Log::error($message);
        }

        return view('fail_safe')
                ->with('fail_safe_message', $fail_safe);

    } // community
}
