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

        <div id="upload-media" style="margin: 10px; padding: 10px">
        </div>

        {{ view('reactjs') }}
        <script src="{{ asset('js/components/ObemArticleMediaUpload.js') }}"></script>
        <script type="text/javascript">
            if(document.getElementById('upload-media'))
            {
                ReactDOM.render(
                    e(
                        ObemArticleMediaUpload, 
                        {
                            obem_site_media_upload_form_title: "{{ __('obem.obem_site_media_upload_form_title') }}", 
                            obem_media_upload_endpoint: '<?php echo $upload_endpoint; ?>',
                            submit_label: "{{ __('obem.submit_label') }}",
                            csrf_token: "{{ csrf_token() }}"
                        }
                    ), 
                    document.getElementById('upload-media')
                );
            }
        </script>
    </body>
</html>