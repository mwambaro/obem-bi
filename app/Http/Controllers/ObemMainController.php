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
                    ->with('news_url', $news_url);
        }
        catch(Exception $e)
        {
            $message = '(' . date("D M d, Y G:i") . ') ---> [' . __FUNCTION__ . '] ' . $e->getMessage();
            Log::error($message);
        }

        return view('fail_safe')
                ->with('fail_safe_message', $fail_safe);

    } // home
}
