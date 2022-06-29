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

        {{
            view('obem_main._home_main')
                ->with('home_main_data', $home_main_data);
        }}

        <div id="obem-site-footer" data-aos="fade-up">
        </div>

        {{ view('reactjs') }}
        <script src="{{ asset('js/components/ObemLocaleSettings.js') }}"> </script>
        <script src="{{ asset('js/components/ObemNavigationBar.js') }}"> </script>
        <script src="{{ asset('js/components/ObemSiteFooterLinks.js') }}"> </script>
        <script src="{{ asset('js/components/ObemSiteFooter.js') }}"> </script>
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

            if(document.getElementById('obem-site-footer'))
            {
                ReactDOM.render(
                    e(
                        ObemSiteFooter, 
                        {
                            footer_actions: '<?php echo $footer_actions; ?>',
                            copy_right_text: '<?php echo $copy_right_text; ?>',
                            powered_by_text: '<?php echo $powered_by_text; ?>',
                            powered_by_email: '<?php echo $powered_by_email; ?>'
                        }
                    ), 
                    document.getElementById('obem-site-footer')
                );
            }

            function arrangeContentAccordingToDevice()
            {
                if(jQuery(window).width() < 500 || jQuery(window).isMobile())
                {
                    $('#obem_home_page_main_intro_section').removeClass('flex-row');
                    $('#obem_home_page_main_intro_section').addClass('flex-column');
                    $('.secondary-content').removeClass('flex-row');
                    $('.secondary-content').addClass('flex-column');
                }
                else 
                {
                    $('#obem_home_page_main_intro_section').removeClass('flex-column');
                    $('#obem_home_page_main_intro_section').removeClass('flex-row');
                    $('#obem_home_page_main_intro_section').addClass('flex-row');
                    $('.secondary-content').removeClass('flex-column');
                    $('.secondary-content').removeClass('flex-row');
                    $('.secondary-content').addClass('flex-row');
                }
            }
            arrangeContentAccordingToDevice();
            window.addEventListener('resize', (e) => {
                arrangeContentAccordingToDevice();
            });
        </script>
        {{ view('animation_on_scroll') }}
    </body>
</html>