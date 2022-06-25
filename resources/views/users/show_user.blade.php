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
        {{
            view('users._show_user')
                ->with('show_user_id', $show_user_id)
                ->with('user_data', $user_data);
        }}

    </body>
</html>