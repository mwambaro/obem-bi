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
        
        <div id="new-sign-in">
        </div>

        {{ view('reactjs') }}
        <script src="{{ asset('js/components/ObemUserSignIn.js') }}"> </script>
        <script type="text/javascript">
            if(document.getElementById('new-sign-in'))
            {
                ReactDOM.render(
                    e(
                        ObemUserSignIn, 
                        {
                            user_sign_in_endpoint: '<?php echo $user_sign_in_endpoint; ?>',
                            new_user_url: '<?php echo $new_user_url; ?>',
                            csrf_token: "{{ csrf_token() }}",
                            obem_sign_in_form_title: "{{ __('obem.obem_user_sign_in_form_label') }}",
                            email_label: "{{ __('obem.email_label') }}",
                            password_label: "{{ __('obem.password_label') }}",
                            sign_up_label: "{{ __('obem.sign_up_label') }}",
                            submit_label: "{{ __('obem.submit_label') }}"
                        }
                    ), 
                    document.getElementById('new-sign-in')
                );
            }
        </script>

    </body>
</html>