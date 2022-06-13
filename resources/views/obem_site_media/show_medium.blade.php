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

        <div id="show-medium" class="container-fluid" style="margin: 5px; padding: 5px">
        </div>

        {{ view('reactjs') }}
        <script src="{{ asset('js/components/ShowObemArticleMedium.js') }}"> </script>
        <script type="text/javascript">
            if(document.getElementById('show-medium'))
            {
                ReactDOM.render(
                    e(
                        ShowObemArticleMedium, 
                        {
                            mime_type: '<?php echo $mime_type; ?>',
                            medium_url: '<?php echo $medium_url; ?>'
                        }
                    ), 
                    document.getElementById('show-medium')
                );
            }
        </script>
    </body>
</html>