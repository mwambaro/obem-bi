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

        <div id="obem-navigation-bar">
        </div>

        {{ view('reactjs') }}
        <script src="{{ asset('js/components/ObemLocaleSettings.js') }}"> </script>
        <script src="{{ asset('js/components/ObemNavigationBar.js') }}"> </script>
        <script type="text/javascript">

            if(document.getElementById('obem-navigation-bar'))
            {
                ReactDOM.render(
                    e(
                        ObemNavigationBar, 
                        {
                            obem_navigation_bar_actions: '<?php echo $obem_navigation_bar_actions; ?>', // stringified array of {url: '', inner_text: '', dropdown_boolean: '', data: ''} hashes
                            supported_languages: '<?php echo $supported_languages; ?>', // stringified array of {locale: '',  language: '', country: ''} hashes 
                            locale_end_point: '<?php echo $locale_end_point; ?>',
                            active_language_locale: '<?php echo $active_language_locale; ?>',
                            sign_in_label: "{{ __('obem.sign_in_label') }}",
                            sign_in_url: '<?php echo $sign_in_url; ?>',
                            sign_up_label: "{{ __('obem.sign_up_label') }}",
                            sign_up_url: '<?php echo $sign_up_url; ?>',
                            sign_out_label: "{{ __('obem.sign_out_label') }}",
                            sign_out_url: '<?php echo $sign_out_url; ?>',
                            obem_user_is_logged_in: '<?php echo $obem_user_is_logged_in; ?>',
                            profile_photo_url: '<?php echo $profile_photo_url; ?>',
                            show_profile_url: '<?php echo $show_profile_url; ?>',
                            home_url: '<?php echo $home_url; ?>',
                            csrf_token: "{{ csrf_token() }}"
                        }
                    ), 
                    document.getElementById('obem-navigation-bar')
                );
            }
        </script>
    </body>
</html>