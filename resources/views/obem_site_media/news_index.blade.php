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

        <div id="news-index" style="margin:10px; padding: 10px">
        </div>

        <div class="" style="zIndex: 99"> 
            @if($is_admin)
                <button id="new-news-item-button" 
                        title="New News Item"
                        style="display: block; position: fixed; bottom: 20px; margin-right: 20px; zIndex: 99; border: none; outline: none; background-color: red; color: white; cursor: pointer; padding: 15px; border-radius: 10px; font-size: 18px">
                    {{ __('obem.new_label') }}
                </button>
            @endif
        </div>

        {{ view('reactjs') }}
        <script src="{{ asset('js/components/WaitSpinner.js') }}"> </script>
        <script src="{{ asset('js/components/PagedViewControls.js') }}"> </script>
        <script src="{{ asset('js/components/ObemNewsIndex.js') }}"> </script>
        <script type="text/javascript">
            if(document.getElementById('news-index'))
            {
                ReactDOM.render(
                    e(
                        ObemNewsIndex, 
                        {
                            articles_page: '<?php echo $news_page; ?>',
                            total_number_of_pages: '<?php echo $total_number_of_pages; ?>',
                            obem_articles_page_endpoint: '<?php echo $obem_articles_page_endpoint; ?>',
                            next_label: "{{ __('obem.next_label') }}",
                            csrf_token: "{{ csrf_token() }}"
                        }
                    ), 
                    document.getElementById('news-index')
                );
            }
            if(document.getElementById('new-news-item-button'))
            {
                manageFixedArticleButton("<?php echo $new_news_item_url; ?>", 'new-news-item-button');
                circle_shape_element('new-news-item-button');
            }
        </script>
    </body>
</html>