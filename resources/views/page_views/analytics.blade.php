<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    {{ 
        view('header')
            ->with('site_title', $site_title);
    }}
    <body>

        {{ view('reactjs') }}
        <script src="{{ asset('js/components/ObemSiteAnalytics.js') }}"> </script>
        <script type="text/javascript">
        </script>
    </body>
</html>