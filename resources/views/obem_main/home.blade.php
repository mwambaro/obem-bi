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
        
        <div id="example" style="margin: 10px; padding: 10px">
        </div>
        @if(count($pages_analytics) > 0)
            <div id="pages_analytics" style="margin: 10px; padding: 10px">
            </div>
        @endif

        {{ view('reactjs') }}
        <script src="{{ asset('js/components/Example.js') }}"> </script>
        <script src="{{ asset('js/components/ObemSiteAnalytics.js') }}"> </script>
        <script type="text/javascript">
            ReactDOM.render(
                e(
                    Example, 
                    {
                        card_title: "<?php echo 'Example Component'; ?>", 
                        card_body: "<?php echo 'I am an example component!'; ?>",
                        card_image: "{{ asset('images/obem_banner_image.JPG') }}"
                    }
                ), 
                document.getElementById('example')
            );

            if(document.getElementById('pages_analytics'))
            {
                ReactDOM.render(
                    e(
                        ObemSiteAnalytics, 
                        {
                            page_analytics: '<?php echo json_encode($pages_analytics); ?>', 
                            number_of_visits_label: "{{ __('obem.number_of_visits') }}",
                            number_of_visitors_label: "{{ __('obem.number_of_visitors') }}",
                            page_visited_label: "{{ __('obem.page_visited') }}",
                            website_analytics_label: "{{ __('obem.website_analytics_label') }}"
                        }
                    ), 
                    document.getElementById('pages_analytics')
                );
            }
        </script>
    </body>
</html>