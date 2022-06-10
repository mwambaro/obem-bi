<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    {{ 
        view('header')
            ->with('site_title', 'OBEM')
            ->with('obem_open_graph_proto_locale', 'fr_FR');
    }}
    <body>
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="shadow-sm p-1 mb-2 bg-white rounded col-md-8">
                    <p>
                        {{ $fail_safe_message }}
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>