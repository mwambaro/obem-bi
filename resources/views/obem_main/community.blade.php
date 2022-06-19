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
        
        <div id="obem-community">
        </div>

        {{ view('reactjs') }}
        <script src="{{ asset('js/components/ObemCommunity.js') }}"> </script>
        <script type="text/javascript">
            if(document.getElementById('obem-community'))
            {
                ReactDOM.render(
                    e(
                        ObemCommunity, 
                        {
                            obem_community_media: '<?php echo $obem_community_media; ?>', // array of {url:, capture:, description}
                            community_description: '<?php echo $community_description; ?>',
                            community_diagram_url: "{{ asset('images/organigramme_obem.jpg') }}"
                        }
                    ), 
                    document.getElementById('obem-community')
                );
            }
        </script>

    </body>
</html>