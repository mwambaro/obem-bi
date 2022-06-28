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

        <div class="container-fluid">
            <div class="row justify-content-center">
                <div style="margin: 10px"
                     class="col-md-8"
                     id="obem-contact-us-show-body">
                     <?php echo $contact_us_data; ?>
                </div>
            </div>
        </div>
    </body>
</html>