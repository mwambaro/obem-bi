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

        <div id="obem-orientation-service">
        </div>

        {{ view('reactjs') }}
        <script src="{{ asset('js/components/ObemOrientationService.js') }}"> </script>
        <script type="text/javascript">
            if(document.getElementById('obem-orientation-service'))
            {
                ReactDOM.render(
                    e(
                        ObemOrientationService, 
                        {
                            orientation_service_html: '<?php echo $orientation_articles[0]; ?>',
                            orientation_addresses_html: '<?php echo $orientation_articles[1]; ?>'
                        }
                    ), 
                    document.getElementById('obem-orientation-service')
                );
            }
        </script>
    </body>
</html>