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

        <div class="mapouter">
            <div class="gmap_canvas embed-responsive embed-responsive-21by9">
                <iframe id="gmap_canvas" 
                        class="embed-responsive-item"
                        src="https://maps.google.com/maps?q=obem,%20bujumbura-mairie,%20burundi&t=&z=13&ie=UTF8&iwloc=&output=embed" 
                        frameborder="0" 
                        scrolling="no" 
                        marginheight="0" 
                        marginwidth="0">
                </iframe>
                <a href="https://123movies-a.com"></a><br>
                <style>
                    .mapouter{position:relative;text-align:center;}
                </style>
                <style>
                    .gmap_canvas {overflow:hidden;background:none!important;}
                </style>
            </div>
        </div>

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