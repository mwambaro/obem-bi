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

        <div id="new-article" class="container-fluid" style="margin:10px; padding: 10px">
        </div>

        {{ view('reactjs') }}
        <script src="{{ asset('js/components/ObemNewArticle.js') }}"> </script>
        <script type="text/javascript">
            if(document.getElementById('new-article'))
            {
                ReactDOM.render(
                    e(
                        ObemNewArticle, 
                        {
                            capture_label: "{{ __('obem.capture_label') }}",
                            date_label: "{{ __('obem.date_label') }}",
                            body_placeholder_text: "{{ __('obem.description_placeholder_text') }}",
                            supported_languages: '<?php echo $stringified_supported_languages; ?>',
                            should_update: '<?php echo $should_update; ?>',
                            obem_site_article_new_form_title: "{{ __('obem.obem_site_article_new_form_title') }}",
                            obem_article_create_endpoint: '<?php echo $obem_article_create_endpoint; ?>',
                            update_article_note: '<?php echo $update_article_note; ?>',
                            article_guid: '<?php echo $article_guid; ?>',
                            article: '<?php echo $stringified_article; ?>',
                            submit_label: "{{ __('obem.submit_label') }}",
                            csrf_token: "{{ csrf_token() }}"
                        }
                    ), 
                    document.getElementById('new-article')
                );
            }
        </script>
    </body>
</html>