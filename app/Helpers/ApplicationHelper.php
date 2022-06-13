<?php 

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\App;
use App\Models\PageView;

/** 
 * Some of the functions below use database access and we need to make sure migrations were
 * upped into the database by running: php artisan migrate.
*/

function get_supported_languages()
{
    $ary = null;

    try 
    {
        $ary = [
            ['locale' => 'en', 'language' => __('obem.english'), 'country' => __('obem.usa')],
            ['locale' => 'fr', 'language' => __('obem.french'), 'country' => __('obem.france')]
        ];
    }
    catch(Exception $e)
    {
        $message = '(' . date("D M d, Y G:i") . ') ---> [' . __FUNCTION__ . '] ' . $e->getMessage();
        Log::error($message);
    }

    return $ary;

} // get_supported_languages

function obem_site_title($action)
{
    $title = 'OBEM';
    
    try 
    {
        $t_string = $title . ' | ' . $action;
        $title = $t_string;
    }
    catch(Exception $e)
    {
        $message = '(' . date("D M d, Y G:i") . ') ---> [' . __FUNCTION__ . '] ' . $e->getMessage();
        Log::error($message);
    }

    return $title;

} // obem_site_title

function load_locale($request)
{
    $loaded = false;

    try 
    {
        if($request->session()->has('active_language'))
        {
            App::setLocale(session('active_language'));
            $loaded = true;
        }
    }
    catch(Exception $e)
    {
        $message = '(' . date("D M d, Y G:i") . ') ---> [' . __FUNCTION__ . '] ' . $e->getMessage();
        Log::error($message);
    }

    return $loaded;

} // load_locale

function set_locale($request)
{
    $did = false;

    try 
    {
        // get locale from request
        if(!$request->has('locale'))
        {
            return $did;
        }
        $locale = $request->query('locale');
        // set locale
        session(['active_language' => $locale]);
        App::setLocale($locale);

        $did = true;
    }
    catch(Exception $e)
    {
        $message = '(' . date("D M d, Y G:i") . ') ---> [' . __FUNCTION__ . '] ' . $e->getMessage();
        Log::error($message);
    }

    return $did;

} // set_locale

function user_has_admin_role()
{
    $has = false;

    try 
    {}
    catch(Exception $e)
    {
        $message = '(' . date("D M d, Y G:i") . ') ---> [' . __FUNCTION__ . '] ' . $e->getMessage();
        Log::error($message);
    }

    return $has;

} // user_has_admin_role

function who_is_logged_in()
{
    $user = null;

    try 
    {}
    catch(Exception $e)
    {
        $message = '(' . date("D M d, Y G:i") . ') ---> [' . __FUNCTION__ . '] ' . $e->getMessage();
        Log::error($message);
    }

    return $user;

} // who_is_logged_in

function harvest_analytics($request)
{
    $saved = false;

    try 
    {
        $user = who_is_logged_in();
        $user_id = -1;
        if($user != null)
        {
            $user_id = $user->id;
        }
        $session_string = session()->getId();
        $ip_address = $request->ip();
        $user_agent = $request->header('User-Agent');
        $referer = $request->header('referer') ?? ""; // $request->headers->get('referer');
        $request_url = $request->url();
        $string = preg_replace('/http(s)*:\/\//i', '', $request_url);
        $string = preg_replace('/(\/)*obem_main\/home/i', '', $string);
        $request_url = trim($string);

        $page_view = PageView::create([
            'user_id' => $user_id,
            'session' => $session_string,
            'ip_address' => $ip_address,
            'request_url' => $request_url,
            'user_agent' => $user_agent,
            'referer' => $referer
        ]);
        $saved = $page_view->save();
    }
    catch(Exception $e)
    {
        $message = '(' . date("D M d, Y G:i") . ') ---> [' . __FUNCTION__ . '] ' . $e->getMessage();
        Log::error($message);
    }

    return $saved;

} // harvest_analytics

function get_pages_analytics()
{
    $page_analytics = [];
    try 
    {
        // Page visits
        $sql = "SELECT request_url, COUNT(*) AS number_of_visits FROM page_views GROUP BY request_url HAVING COUNT(*) > 0 ORDER BY number_of_visits DESC";
        $page_views = DB::select($sql);
        $sql = "SELECT request_url, COUNT(DISTINCT ip_address) AS number_of_visitors FROM page_views GROUP BY request_url";
        $page_visitors = DB::select($sql);
        foreach($page_views as $key => $page)
        {
            $request_url = $page->request_url;
            $n_visits = $page->number_of_visits;
            $n_visitors = 1;
            foreach($page_visitors as $kkey => $vpage)
            {
                $url = $vpage->request_url;
                if($url == $request_url)
                {
                    $n_visitors = $vpage->number_of_visitors;
                    break;
                }
            }
            array_push($page_analytics, [
                'page' => $request_url,
                'number_of_visits' => $n_visits,
                'number_of_visitors' => $n_visitors
            ]);
        }
    }
    catch(Exception $e)
    {
        $message = '(' . date("D M d, Y G:i") . ') ---> [' . __FUNCTION__ . '] ' . $e->getMessage();
        Log::error($message);
    }

    return $page_analytics;

} // get_pages_analytics