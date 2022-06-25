<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    {{ 
        view('header')
            ->with('site_title', $site_title)
            ->with('obem_open_graph_proto_locale', $obem_open_graph_proto_locale);
    }}
    <body>
        {{
            view('banner_view');
        }}

        {{ view('reactjs') }}
        <script src="{{ asset('js/components/PagedViewControls.js') }}"> </script>

        @if($article_guid == article_containers_guids()['activities'])
            <div id="articles-index" style="margin:10px; padding: 10px">
            </div>
            <script src="{{ asset('js/components/ObemArticlesIndex.js') }}"> </script>
        @endif
        @if($article_guid == article_containers_guids()['events'])
            <div id="news-index" style="margin:10px; padding: 10px">
            </div>
            <script src="{{ asset('js/components/ObemNewsIndex.js') }}"> </script>
        @endif

        <div class="" style="zIndex: 99"> 
            @if($is_admin)
                <button id="new-article-button" 
                        title="New Article"
                        style="display: block; position: fixed; bottom: 20px; margin-right: 20px; margin-left: 20px; zIndex: 99; border: none; outline: none; background-color: red; color: white; cursor: pointer; padding: 15px; border-radius: 10px; font-size: 18px">
                    {{ __('obem.new_label') }}
                </button>
            @endif
        </div>

        <script type="text/javascript">
            if(document.getElementById('articles-index'))
            {
                ReactDOM.render(
                    e(
                        ObemArticlesIndex, 
                        {
                            articles_page: '<?php echo $articles_page; ?>',
                            total_number_of_pages: '<?php echo $total_number_of_pages; ?>',
                            obem_articles_page_endpoint: '<?php echo $obem_articles_page_endpoint; ?>',
                            next_label: "{{ __('obem.next_label') }}",
                            previous_label: "{{ __('obem.previous_label') }}",
                            csrf_token: "{{ csrf_token() }}"
                        }
                    ), 
                    document.getElementById('articles-index')
                );
            }
            if(document.getElementById('news-index'))
            {
                ReactDOM.render(
                    e(
                        ObemNewsIndex, 
                        {
                            articles_page: '<?php echo $articles_page; ?>',
                            total_number_of_pages: '<?php echo $total_number_of_pages; ?>',
                            obem_articles_page_endpoint: '<?php echo $obem_articles_page_endpoint; ?>',
                            next_label: "{{ __('obem.next_label') }}",
                            previous_label: "{{ __('obem.previous_label') }}",
                            csrf_token: "{{ csrf_token() }}"
                        }
                    ), 
                    document.getElementById('news-index')
                );
            }
            if(document.getElementById('new-article-button'))
            {
                manageFixedArticleButton("<?php echo $new_article_url; ?>", 'new-article-button');
                circle_shape_element('new-article-button');
            }
        </script>
    </body>
</html>