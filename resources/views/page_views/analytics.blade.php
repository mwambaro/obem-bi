<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    {{ 
        view('header')
            ->with('site_title', $site_title)
            ->with('obem_open_graph_proto_locale', $obem_open_graph_proto_locale);
    }}
    <body>

        {{ view('reactjs') }}
        <script src="{{ asset('js/components/ObemSiteAnalytics.js') }}"> </script>
        <script type="text/javascript">
        </script>
    </body>
</html>