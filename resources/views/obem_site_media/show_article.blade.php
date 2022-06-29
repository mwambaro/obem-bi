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
        {{
            view('obem_site_media._show_article')
                ->with('article_html_body', $article_html_body)
                ->with('is_admin', $is_admin);
        }}
        <script type="text/javascript">
            if(document.getElementById('edit-article-button'))
            {
                manageFixedArticleButton("<?php echo $edit_article_url; ?>", 'edit-article-button');
                circle_shape_element('edit-article-button');
            }
            if(document.getElementById('myBackToTopBtn'))
            {
                circle_shape_element('myBackToTopBtn');
            }
            scale_obem_site_media();
            window.onresize = (e) => {
                scale_obem_site_media();
            }
        </script>
        {{ view('animation_on_scroll') }}
    </body>
</html>