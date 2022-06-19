<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ObemSiteMediaController;

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
            $pages_analytics = get_pages_analytics();
            $show_article_url = '#';
            $news_url = '#';
            $community_url = action([ObemMainController::class, 'community']);
            $guids = article_containers_guids();

            if(DB::table('obem_site_articles')->where('guid', '=', $guids['activities'])->count() > 0)
            {
                $show_article_url = action(
                    [ObemSiteMediaController::class, 'articles_index'], 
                    ['page_number' => 1, 'article_guid' => $guids['activities']]
                );
            }
            if(DB::table('obem_site_articles')->where('guid', '=', $guids['events'])->count() > 0)
            {
                $news_url = action(
                    [ObemSiteMediaController::class, 'articles_index'], 
                    ['page_number' => 1, 'article_guid' => $guids['events']]
                );
            }
        
            return view('obem_main.home')
                    ->with('site_title', $site_title)
                    ->with('obem_open_graph_proto_locale', $obem_open_graph_proto_locale)
                    ->with('pages_analytics', $pages_analytics)
                    ->with('show_article_url', $show_article_url)
                    ->with('news_url', $news_url)
                    ->with('community_url', $community_url);
        }
        catch(Exception $e)
        {
            $message = '(' . date("D M d, Y G:i") . ') ---> [' . __FUNCTION__ . '] ' . $e->getMessage();
            Log::error($message);
        }

        return view('fail_safe')
                ->with('fail_safe_message', $fail_safe);

    } // home

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
