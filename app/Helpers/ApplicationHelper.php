<?php 

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PageView;

if(!function_exists('user_has_admin_role'))
{
    function user_has_admin_role()
    {
        $has = false;

        return $has;

    } // user_has_admin_role
}

function who_is_logged_in()
{
    $user = null;

    return $user;

} // who_is_logged_in

function harvest_analytics($request)
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
    $string = preg_replace('/obem_main\/home/i', '', $string);
    $request_url = trim($string);

    $page_view = PageView::create([
        'user_id' => $user_id,
        'session' => $session_string,
        'ip_address' => $ip_address,
        'request_url' => $request_url,
        'user_agent' => $user_agent,
        'referer' => $referer
    ]);

    return $page_view->save();

} // harvest_analytics

function get_pages_analytics()
{
    // Page visits
    $sql = "SELECT request_url, COUNT(*) AS number_of_visits FROM page_views GROUP BY request_url HAVING COUNT(*) > 0 ORDER BY number_of_visits DESC";
    $page_views = DB::select($sql);
    $sql = "SELECT request_url, COUNT(DISTINCT ip_address) AS number_of_visitors FROM page_views GROUP BY request_url";
    $page_visitors = DB::select($sql);
    $page_analytics = [];
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

    return $page_analytics;

} // get_pages_analytics