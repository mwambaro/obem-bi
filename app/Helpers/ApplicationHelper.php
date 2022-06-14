<?php 

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use App\Models\PageView;
use App\Models\ObemSiteArticle;

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

/**
 * <summary>
 *   Replaces the medium reference, if any, with a unique relative path name, which contains the path to
 *   the stored medium relative to root drive path. Check Laravel documentation for the default root path.
 *   The unicity is needed so the user does not need to worry about duplicate file names on upload.
 * </summary>
 * <param name="medium"> The medium model object </param>
 * <returns> True on success and False on failure </returns>
 */
function medium_validate_article_body($medium)
{
    $val = false;

    try 
    {
        $unique_file_path = $medium->media_file_path;
        $media_file_name = $medium->media_file_name;
        $article = ObemSiteArticle::find($medium->article_id);
        $body = $article->body;
        // base file name
        $i = preg_match('/^(.+)\.[^\.]+$/', $media_file_name, $matches);
        if(!$i)
        {
            throw new Exception('Something is up. File name: ' . $media_file_name);
        }
        $base_file_name = $matches[1];
        //Log::info('---> Base file name: ' . $base_file_name . '; Unique: ' . $unique_file_path);
        // Validate body
        $validated_body = preg_replace(
            '/' . preg_quote($base_file_name, '/') . '\.*[^\.\s\n]*/', 
            $unique_file_path, 
            $body
        );
        //Log::info('---> Validated body: ' . $validated_body);
        $article->update(['body' => $validated_body]);

        $val = true;
    }
    catch(Exception $e)
    {
        $message = '(' . date("D M d, Y G:i") . ') ---> [' . __FUNCTION__ . '] ' . $e->getMessage();
        Log::error($message);
    }

    return $val;

} // medium_validate_article_body

/**
 * <summary>
 *   Check whether we have in storage all media referenced in the article body, if any media are referenced at all.
 * </summary
 * <param name="article_id"> The id of the article whose body we need to storage-validate. </param>
 * <param name="media_std_markup"> 
 *   The standard symbol used in articles to introduce reference to media. Generally followed by media 
 *   base file name with or without extension. E.g. media_markup 23erdt6n0p.jpg
 * </param>
 * <returns> Empty array on success and, on failure, array of files in the article that are not stored. </returns>
 */
function storage_validate_article_body($article_id, $media_std_markup='media_markup')
{
    $ary = [];

    try 
    {   
        $body = ObemSiteArticle::find($article_id)->body;
        $i = preg_match_all(
            '/' . preg_quote($media_std_markup, '/') . '\s+([^\n]+)/', 
            $body, 
            $matches, 
            PREG_PATTERN_ORDER
        );
        if($i)
        {
            foreach($matches[1] as $file)
            {
                $f = trim($file);
                // if unique path
                if(Storage::exists($f))
                {
                    continue;
                }
                // if local file name
                // Find its unique name
                $found = false;
                $uf = find_storage_unique_path($article_id, $f);
                if($uf)
                {
                    if(Storage::exists($uf))
                    {
                        $found = true;
                    }
                }

                if(!$found)
                {
                    array_push($ary, $f);
                }
            }
        }
    }
    catch(Exception $e)
    {
        $message = '(' . date("D M d, Y G:i") . ') ---> [' . __FUNCTION__ . '] ' . $e->getMessage();
        Log::error($message);
    }

    return $ary;

} // storage_validate_article_body

/**
 * <summary>
 *   Find the unique path to the stored version of a file, if it has been stored at all
 * </summary>
 * <param name="article_id"> The id of the article to which the given file belongs </param>
 * <param name="file_name"> The file name with or without extension </param>
 * <returns> Unique file path to stored media. Null if not found. </returns>
 */
function find_storage_unique_path($article_id, $file_name)
{
    $uf = null;

    try 
    {
        $base_file_name = "";
        $i = preg_match('/^(.+)\.[^\.]+$/', $file_name, $matches);
        if($i)
        {
            $base_file_name = $matches[1];
        }
        else 
        {
            $base_file_name = $f;
        }
                
        $found = false;
        $media = DB::table('obem_article_media')
                    ->where('article_id', $article_id)
                    ->get();
        foreach($media as $medium)
        {
            $i = preg_match('/^' . preg_quote($base_file_name, '/') . '/', $medium->media_file_name);
            if($i)
            {
                $uf = $medium->media_file_path;
                break;        
            }
        }
    }
    catch(Exception $e)
    {
        $message = '(' . date("D M d, Y G:i") . ') ---> [' . __FUNCTION__ . '] ' . $e->getMessage();
        Log::error($message);
    }

    return $uf;

} // find_storage_unique_path

/**
 * <summary>
 *   Deletes all media from storage that are no longer referenced by any article, especially
 *   after updating the article, since all media have to be re-uploaded 
 * </summary>
 * <param name="article_id"> The id of the article whose media might be storage-deleted </param>
 * <param name="medium_id"> The id of the medium to exclude from deletion from storage. E.g. Current Medium </param>
 * <returns> True on success and False on failure </returns>
 */
function clean_media_storage($article_id, $medium_id)
{
    $val = false;

    try 
    {
        $media = DB::table('obem_article_media')
            ->where('article_id', $article_id)
            ->get();
        foreach($media as $medium)
        {
            if($medium_id == $medium->id) // exclude from deletion
            {
                continue;
            }

            $file_path = $medium->media_file_path;
            $file_name = $medium->media_file_name;
            $body = ObemSiteArticle::find($article_id)->body;
            // base file name
            $i = preg_match('/^(.+)\.[^\.]+$/', $file_name, $matches);
            if(!$i)
            {
                throw new Exception('Something is up. File name: ' . $file_name);
            }
            $base_file_name = $matches[1];
            // base file path
            $i = preg_match('/^(.+)\.[^\.]+$/', $file_path, $matches);
            if(!$i)
            {
                throw new Exception('Something is up. File path: ' . $file_path);
            }
            $base_file_path = $matches[1];

            $unique_file_name = Str::of($base_file_path)->basename();
            // Does body contain base file name ?
            $contains = Str::contains($body, [$base_file_path, $base_file_name, $unique_file_name]);

            if(!$contains)
            {
                Storage::delete($file_path);
                DB::table('obem_article_media')->where('id', $medium->id)->delete();
            }
        }

        $val = true;
    }
    catch(Exception $e)
    {
        $message = '(' . date("D M d, Y G:i") . ') ---> [' . __FUNCTION__ . '] ' . $e->getMessage();
        Log::error($message);
    }

    return $val;

} // clean_media_storage