<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        
            // Data
            $pages_analytics = get_pages_analytics();
        
            return view('obem_main.home')
                    ->with('site_title', obem_site_title(__FUNCTION__))
                    ->with('obem_open_graph_proto_locale', $obem_open_graph_proto_locale)
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
}
