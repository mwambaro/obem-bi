<?php 

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use App\Models\PageView;
use App\Models\ObemSiteArticle;
use App\Models\ObemArticleMedium;
use App\Http\Controllers\ObemSiteMediaController;

/** 
 * Some of the functions below use database access and we need to make sure migrations were
 * upped into the database by running: php artisan migrate.
*/

function number_of_articles_per_page()
{
    return 1;

} // number_of_articles_per_page

function get_page_of_articles($page_number, $article_guid)
{
    $page_info = [];

    try 
    {
        $articles = DB::table('obem_site_articles')
                        ->where([
                            ['locale', '=', App::currentLocale()],
                            ['guid', '=', $article_guid]
                        ])
                        ->orderBy('date', 'asc')
                        ->get();
        $total_number_of_articles = $articles->count();
        $number_of_articles_per_page = number_of_articles_per_page();
        $total_number_of_pages = ceil($total_number_of_articles/$number_of_articles_per_page);
        $articles_page = array();
        $pages_count = 1;
        for($index=0; $index<$total_number_of_articles; $index++)
        {
            $pages_count = ceil(($index+1)/$number_of_articles_per_page);
            if($pages_count == $page_number)
            {
                $start_index = $index;
                $end_index = ($start_index + $number_of_articles_per_page) > 
                             $total_number_of_articles ?
                             ($total_number_of_articles - 1) :
                             ($start_index + $number_of_articles_per_page - 1);
                $iteration_length = $end_index + 1;
                // Get pages
                for($i=$start_index; $i<$iteration_length; $i++)
                {
                    $article = $articles[$i];
                    $show_article_url = action(
                        [ObemSiteMediaController::class, 'show_article'], 
                        ['id' => $article->id]
                    );
                    $short_description = get_partial_description($article->body) . ' ... ';
                    // Find first medium url
                    $first_medium_url = '';
                    $media = DB::table('obem_article_media')
                                ->where('article_id', '=', $article->id)
                                ->get();
                    if(count($media) > 0)
                    {
                        foreach($media as $idx => $medium)
                        {
                            if(preg_match('/\Aimage/i', $medium->mime_type))
                            {
                                $first_medium_url = action(
                                    [ObemSiteMediaController::class, 'serve_medium'], 
                                    ['id' => $medium->id]
                                );
                            }
                        }
                    }
                    // page 
                    array_push(
                        $articles_page, 
                        [
                            'capture' => $article->capture,
                            'url' => $show_article_url,
                            'description' => $short_description,
                            'first_image_url' => $first_medium_url
                        ]
                    );
                }
                // Break
                break;
            }
        }

        $page_info = [
            'articles_page' => $articles_page,
            'total_number_of_articles' => $total_number_of_articles,
            'total_number_of_pages' => $total_number_of_pages
        ];
    }
    catch(Exception $e)
    {
        $message = '(' . date("D M d, Y G:i") . ') ---> [' . __FUNCTION__ . '] ' . $e->getMessage();
        Log::error($message);
    }

    return $page_info;

} // get_page_of_articles

function get_partial_description($text)
{
    $partial = $text;

    try 
    {
        $paragraphs = get_paragraphs($text);
        $found = false;
        foreach($paragraphs as $idx => $paragraph)
        {
            if(!preg_match('/(\A#+)|(\Amedia_markup)/', $paragraph))
            {
                $found = true;
                $partial = get_partial_text($paragraph);
                break;
            }
        }
        if(!$found)
        {
            $partial = get_partial_text($text);
        }
    }
    catch(Exception $e)
    {
        $message = '(' . date("D M d, Y G:i") . ') ---> [' . __FUNCTION__ . '] ' . $e->getMessage();
        Log::error($message);
    }

    return $partial;

} // get_partial_description

function banner_image_asset_url()
{
    $url = asset('images/obem_banner_image.JPG');

    if(preg_match('/\Aen\Z/i', App::currentLocale()))
    {
        $url = asset('images/obem_banner_image_en_US.JPG');
    }

    return $url;

} // banner_image_asset_url

/** 
 * <summary>
 *   Get paragraphs out of the text. Paragraphs are delimited by a new line or '<##>'
 * </summary>
 * <param name="text"> The text to split into parapgraphs </param>
 * <returns> 
 *   An array of texts, each one being a paragraph, or a one-element array containing
 *   the given text, on failure.
 * </returns>
*/
function get_paragraphs($text)
{
    $paragraphs = [];

    try 
    {
        $segments = Str::of($text)->split('/\n|<##>|\\\n/');
        foreach($segments as $segment)
        {
            $par = preg_replace('/(\A\s+)|(\s+\Z)/', '', $segment);
            if(Str::of($par)->isNotEmpty())
            {
                array_push($paragraphs, $par);
            }
        }
    }
    catch(Exception $e)
    {
        $message = '(' . date("D M d, Y G:i") . ') ---> [' . __FUNCTION__ . '] ' . $e->getMessage();
        Log::error($message);
    }

    try 
    {
        if(count($paragraphs) == 0)
        {
            $paragraphs = [$text];
        }
    }
    catch(Exception $e)
    {
        $message = '(' . date("D M d, Y G:i") . ') ---> [' . __FUNCTION__ . '] ' . $e->getMessage();
        Log::error($message);
        $paragraphs = [$text];
    }


    return $paragraphs;

} // get_paragraphs

function get_partial_text($text, $max_words=20)
{
    $txt = $text;

    try 
    {
        $words = Str::of($text)->split('/\s+/');
        $len = count($words);
        for($i=0; $i<$len; $i++)
        {
            if($i == 0) // init main variable
            {
                $txt = "";
            }

            if($i == $max_words+1)
            {
                break;
            }
            $txt .= " " . $words[$i];
        }
    }
    catch(Exception $e)
    {
        $message = '(' . date("D M d, Y G:i") . ') ---> [' . __FUNCTION__ . '] ' . $e->getMessage();
        Log::error($message);
    }

    return $txt;

} // get_partial_text

/** 
 * <summary>
 *    Turns marked text into an HTML article. It supports '#' for headings, 'media_markup' for images, and 
 *     'media_markup_t', for image capture.
 * </summary>
 * * <param name="text">
 *     The marked-up text which we will turn into HTML.
 * </param>
 * <param name="article_capture">
 *     The article-specific title or capture. It helps us infer images from the database, as their capture are
 *     are gonna help us locate the article from DB and locate related media.
 * </param>
 * <param name="locale">
 *     The current locale. Default to the default locale, if null.
 * </param>
 * <details>
 *     For paragraph mark-up, please, refer to :get_paragraphs method. Example marked text:
 *       # Heading 1
 *       Paragraph 1
 *       media_markup 123erft.jpg
 *       media_markup_t This is image tells the story
 *       ## Heading 2
 * </details>
*/
function interpolate_article($text, $article_capture, $locale=null)
{
    $txt = $text;

    try 
    {
        if(!$locale)
        {
            $locale = App::currentLocale();
        }

        $paragraphs = get_paragraphs($text);
        $counter = 0;
        foreach($paragraphs as $paragraph)
        {
            // Init main variable
            if($counter == 0)
            {
                $txt = "";
            }
            // Heading
            if(preg_match('/\A\s*(#+)\s*(.+)\Z/', $paragraph, $matches))
            {
                $txt .= '<div><h' . Str::length($matches[1]) . '>' . $matches[2] . 
                         '</h' . Str::length($matches[1]) . '></div>';
            }
            // Media
            elseif(preg_match('/\A\s*media_markup\s+(.+)\Z/i', $paragraph, $matches))
            {
                $mime_type = "";
                $url = "";
                // base file name with or without extension OR unique file path
                $media_name = preg_replace('/(\A\s+)|(\s+\Z)/', '', $matches[1]);
                $uf = find_storage_unique_path($media_name); // maybe unique file path
                if($uf) // is unique path
                {
                    $uniq_path = $uf['unique_path'];
                    $medium = DB::table('obem_article_media')
                                ->where('media_file_path', $uniq_path)
                                ->first();
                    if($medium)
                    {
                        $mime_type = $medium->mime_type;
                        $url = action(
                            [ObemSiteMediaController::class, 'serve_medium'], 
                            ['id' => $medium->id]
                        );
                    }
                }
                else // Convention over configuration, so, hope we never get here
                {    // if you ran storage validation on article body, we won't get here
                    $article = DB::table('obem_site_articles')
                                ->where([
                                    ['capture', '=', $article_capture], 
                                    ['locale', '=', $locale]
                                ])
                                ->first();
                    if($article)
                    {
                        $article_id = $article->id;
                        $uf = find_storage_unique_path($media_name, $article_id);
                        if($uf)
                        {
                            $medium = $uf['medium'];
                            if($medium)
                            {
                                $mime_type = $medium->mime_type;
                                $url = action(
                                    [ObemSiteMediaController::class, 'serve_medium'], 
                                    ['id' => $medium->id]
                                );
                            }
                        }
                    }
                }

                if(Str::of($mime_type)->isNotEmpty() && Str::of($url)->isNotEmpty())
                {
                    if(Str::startsWith($mime_type, 'image'))
                    {
                        $txt .= '<div class="text-center" data-aos="fade-up">' . 
                                '<img class="img-fluid obem-article-media" src="' .
                                $url . 
                                '" /></div>';
                    }
                    elseif(Str::startsWith($mime_type, 'audio'))
                    {
                        $txt .= '<div class="text-center obem-article-media" data-aos="fade-up">' . 
                               '<audio class="embed-responsive" controls>' . 
                               '<source class="embed-responsive-item" src="' . $url .
                               '" type="' . $mime_type . 
                               '"/></audio></div>';
                    }
                    elseif(Str::startsWith($mime_type, 'video'))
                    {
                        $txt .= '<div class="text-center obem-article-media" data-aos="fade-up">' . 
                                '<video class="embed-responsive embed-responsive-21by10" controls>' . 
                                '<source class="embed-responsive-item" src="' . $url .
                                '" type="' . $mime_type .
                                '" /></video></div>';
                    }
                    else 
                    {
                        $txt .= '<div><p>' . $media_name . '[UNSUPPORTED]</p></div>';
                    }
                }
                else 
                {
                    $txt .= '<div><p>' . $media_name . ' [NOT FOUND]</p></div>';
                }
            }
            // Media capture
            elseif(preg_match('/\A\s*media_markup_t\s+(.+)\Z/i', $paragraph, $matches))
            {
                $capture = preg_replace('/(\A\s+)|(\s+\Z)/', '', $matches[1]);
                $txt .= '<div class="text-center" data-aos="fade-up">' . 
                        '<p> <strong>' . $capture . 
                        '</strong> </p> </div>';
            }
            // Paragraph
            else
            {
                $txt .= '<div data-aos="fade-up"> <p> ' . 
                        $paragraph . 
                        '</p></div>';
            }

            $counter += 1;
        }
    }
    catch(Exception $e)
    {
        $message = '(' . date("D M d, Y G:i") . ') ---> [' . __FUNCTION__ . '] ' . $e->getMessage();
        Log::error($message);
    }

    return $txt;

} // interpolate_article

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
            '/' . preg_quote($media_std_markup, '/') . '\s+([^\n\s]+)/', 
            $body, 
            $matches, 
            PREG_PATTERN_ORDER
        );
        if($i)
        {
            foreach($matches[1] as $key => $file)
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
                $uf = find_storage_unique_path($f, $article_id);
                if($uf)
                {
                    $found = true;
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
 * <returns> {medium:, unique_path: } hash. Null if not found. </returns>
 */
function find_storage_unique_path($file_name, $article_id=-1)
{
    $uf = null;

    try 
    {
        if(Storage::exists($file_name))
        {
            return ['unique_path' => $file_name, 'medium' => null];
        }

        $base_file_name = "";
        $i = preg_match('/^(.+)\.[^\.]+$/', $file_name, $matches);
        if($i)
        {
            $base_file_name = $matches[1];
        }
        else 
        {
            $base_file_name = $file_name;
        }
                
        $media = DB::table('obem_article_media')
                    ->where('article_id', $article_id)
                    ->get();
        foreach($media as $medium)
        {
            $i = preg_match('/^' . preg_quote($base_file_name, '/') . '/', $medium->media_file_name);
            if($i)
            {
                $uf = [
                    'unique_path' => $medium->media_file_path, 
                    'medium' => $medium
                ];
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

function article_containers_guids()
{
    $guids = null;

    try 
    {
        $guids = [
            'home' => 'c2581c15-8863-4c70-acda-2604e8ad5795',
            'events' => '52ff88ba-e7f7-4d7b-99a5-1bc018fef28e',
            'community' => '08558dca-a5d8-4b46-b2bd-cc96d1028f36',
            'activities' => 'fb6f2e53-b891-48e1-a96d-f6ed49627086'
        ];
    }
    catch(Exception $e)
    {
        $message = '(' . date("D M d, Y G:i") . ') ---> [' . __FUNCTION__ . '] ' . $e->getMessage();
        Log::error($message);
    }

    return $guids;

} // article_containers_guids

function create_article_according_to_capture_to_body_map($map, $guid, $locale)
{
    $val = false;

    try 
    {
        $activity_articles_capture_to_body_map = $map;
        foreach($activity_articles_capture_to_body_map as $capture => $activity)
        {
            $values = array_values($activity);
            $keys = array_keys($activity);
            $article = ObemSiteArticle::create([
                'guid' => $guid,
                'capture' => $capture,
                'locale' => $locale,
                'body' => preg_replace('/\r/', "", preg_replace('/\n/', "<##>", $values[0])),
                'date' => $keys[0]
            ]);
            if(!$article->save())
            {
                Log::error('Failed to create activity article with capture: ' . $capture);
            }

            // associated media
            foreach($values[1] as $medium_file_name => $mime_type)
            {
                $path = public_path('images/' . $medium_file_name);
                $unique_file_name = $medium_file_name;
                $unique_file_path = 'public/' . $unique_file_name;

                //Log::info('Seed medium file path: ' . $path);
                        
                $medium = ObemArticleMedium::create([
                    'mime_type' => $mime_type,
                    'media_file_name' => $medium_file_name,
                    'media_file_path' => $unique_file_path,
                    'article_id' => $article->id
                ]);
                if(!$medium->save())
                {
                    Log::error('Failed to create medium whose path is: ' . $path);
                }

                $contents = file_get_contents($path);
                Storage::put($unique_file_path, $contents);
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

} // create_article_according_to_capture_to_body_map

function seed_articles()
{
    $val = false;

    try 
    {
        $locales = get_supported_languages();
        $app_locale = App::currentLocale();
        
        foreach($locales as $index => $lc)
        {
            $locale = $lc['locale'];
            App::setLocale($locale);

            // activities
            $guid = article_containers_guids()['activities'];
            $any = DB::table('obem_site_articles')
                        ->where('guid', '=', $guid)
                        ->count();
            if($any == 0)
            {
                $activity_articles_capture_to_body_map = [
                    // GIHANGA
                    __('obem.gihanga_porte_ouverte_report') => [
                        date('2017-11-30 08:30:00') => __('obem.report_journee_porte_ouverte_gihanga'),
                        'media' => [
                            'report_gihanga_0.jpg' => 'image/jpeg',
                            'report_gihanga_1.jpg' => 'image/jpeg',
                            'report_gihanga_2.jpg' => 'image/jpeg',
                            'report_gihanga_3.jpg' => 'image/jpeg',
                            'report_gihanga_4.jpg' => 'image/jpeg',
                            'report_gihanga_5.jpg' => 'image/jpeg',
                            'report_gihanga_6.jpg' => 'image/jpeg',
                            'report_gihanga_7.jpg' => 'image/jpeg',
                            'report_gihanga_8.jpg' => 'image/jpeg'
                        ]
                    ],
                    // KIGANDA
                    __('obem.kiganda_porte_ouverte_report') => [
                        date('2019-06-11 08:30:00') => __('obem.report_journee_porte_ouverte_kiganda'),
                        'media' => [
                            'report_kiganda_0.jpg' => 'image/jpeg',
                            'report_kiganda_1.jpg' => 'image/jpeg',
                            'report_kiganda_2.jpg' => 'image/jpeg'
                        ]
                    ],
                    // MURAMVYA
                    __('obem.muramvya_porte_ouverte_report') => [
                        date('2019-06-13 08:30:00') => __('obem.report_journee_porte_ouverte_muramvya'),
                        'media' => [
                            'report_muramvya_0.jpg' => 'image/jpeg',
                            'report_muramvya_1.jpg' => 'image/jpeg',
                            'report_muramvya_2.jpg' => 'image/jpeg',
                        ]
                    ]
                ];

                create_article_according_to_capture_to_body_map(
                    $activity_articles_capture_to_body_map, $guid, $locale
                );  
            }

            // Events
            $guid = article_containers_guids()['events'];
            $any = DB::table('obem_site_articles')
                        ->where('guid', '=', $guid)
                        ->count();
            if($any == 0)
            {
                $activity_articles_capture_to_body_map = [
                    // MPANDA and MUSIGATI
                    __('obem.mpanda_and_musigati_events_01_capture') => [
                        date('2017-06-01 08:30:00') => __('obem.mpanda_and_musigati_events_01'),
                        'media' => [
                            'events_01.JPG' => 'image/jpeg'
                        ]
                    ],
                    __('obem.mpanda_and_musigati_events_02_capture') => [
                        date('2017-06-02 08:30:00') => __('obem.mpanda_and_musigati_events_02'),
                        'media' => [
                            'events_02.JPG' => 'image/jpeg'
                        ]
                    ],
                    // RUHORORO
                    __('obem.ruhororo_commune_events_03_capture') => [
                        date('2017-06-25 08:30:00') => __('obem.ruhororo_commune_events_03'),
                        'media' => [
                            'events_03.JPG' => 'image/jpeg'
                        ]
                    ],
                    __('obem.ruhororo_commune_events_04_capture') => [
                        date('2017-06-26 08:30:00') => __('obem.ruhororo_commune_events_04'),
                        'media' => [
                            'events_04.JPG' => 'image/jpeg'
                        ]
                    ]
                ];

                create_article_according_to_capture_to_body_map(
                    $activity_articles_capture_to_body_map, $guid, $locale
                );
            }

            // Community
            $guid = article_containers_guids()['community'];
            $any = DB::table('obem_site_articles')
                        ->where('guid', '=', $guid)
                        ->count();
            if($any == 0)
            {
                $activity_articles_capture_to_body_map = [
                    // COMMUNITY
                    __('obem.community_media_capture') => [
                        date('2022-06-19 08:30:00') => '',
                        'media' => [
                            'institution_objective_image.JPG' => 'image/jpeg',
                            'report_gihanga_3.jpg' => 'image/jpeg',
                            'report_kiganda_0.jpg' => 'image/jpeg',
                            'report_muramvya_0.jpg' => 'image/jpeg'
                        ]
                    ]
                ];

            }            
        }

        App::setLocale($app_locale);
    }
    catch(Exception $e)
    {
        $message = '(' . date("D M d, Y G:i") . ') ---> [' . __FUNCTION__ . '] ' . $e->getMessage();
        Log::error($message);
    }

    return $val;

} // seed_articles