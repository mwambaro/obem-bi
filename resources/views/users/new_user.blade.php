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
        
        <div id="new-user">
        </div>

        {{ view('reactjs') }}
        <script src="{{ asset('js/components/ObemUserSignUp.js') }}"> </script>
        <script type="text/javascript">
            if(document.getElementById('new-user'))
            {
                ReactDOM.render(
                    e(
                        ObemUserSignUp, 
                        {
                            user_create_endpoint: '<?php echo $user_create_endpoint; ?>',
                            stringified_user: '<?php echo $stringified_user; ?>',
                            should_update: '<?php echo $should_update; ?>',
                            csrf_token: "{{ csrf_token() }}",
                            obem_sign_up_form_title: "{{ __('obem.obem_user_sign_up_form_label') }}",
                            first_name_label: "{{ __('obem.first_name_label') }}",
                            last_name_label: "{{ __('obem.last_name_label') }}",
                            user_name_label: "{{ __('obem.username_label') }}",
                            email_label: "{{ __('obem.email_label') }}",
                            password_label: "{{ __('obem.password_label') }}",
                            password_verification_label: "{{ __('obem.password_confirmation_label') }}",
                            are_you_obem_employee_label: "{{ __('obem.are_you_obem_employee_label') }}",
                            no_label: "{{ __('obem.no_label') }}",
                            yes_label: "{{ __('obem.yes_label') }}",
                            submit_label: "{{ __('obem.submit_label') }}"
                        }
                    ), 
                    document.getElementById('new-user')
                );
            }
        </script>

    </body>
</html>