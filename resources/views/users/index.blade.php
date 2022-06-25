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
        <script src="{{ asset('js/components/ObemUserShow.js') }}"> </script>
        <div>
            <?php echo $view_data; ?>
        </div>
    </body>
</html>