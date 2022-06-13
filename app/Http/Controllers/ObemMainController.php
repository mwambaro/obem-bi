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
            harvest_analytics($request);
            $obem_open_graph_proto_locale = 'fr_FR';
            $site_title = obem_site_title(__FUNCTION__);
        
            // Data
            $pages_analytics = get_pages_analytics();
            $create_article_url = action([ObemSiteMediaController::class, 'new_article']);
            $update_article_url = '#';

            if(DB::table('obem_site_articles')->count() > 0)
            {
                $article = DB::table('obem_site_articles')->get()->first();
                $update_article_url = action([ObemSiteMediaController::class, 'new_article'], ['id' => $article->id]);
            }
        
            return view('obem_main.home')
                    ->with('site_title', $site_title)
                    ->with('obem_open_graph_proto_locale', $obem_open_graph_proto_locale)
                    ->with('pages_analytics', $pages_analytics)
                    ->with('create_article_url', $create_article_url)
                    ->with('update_article_url', $update_article_url);
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
