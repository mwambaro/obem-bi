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
        
        <div id="new-employment-folder">
        </div>

        {{ view('reactjs') }}
        <script src="{{ asset('js/components/ObemEmploymentFolder.js') }}"> </script>
        <script type="text/javascript">
            if(document.getElementById('new-employment-folder'))
            {
                ReactDOM.render(
                    e(
                        ObemEmploymentFolder, 
                        {
                            employment_folder_create_endpoint: '<?php echo $employment_folder_create_endpoint; ?>',
                            stringified_employment_folder: '<?php echo $stringified_employment_folder; ?>',
                            should_update: '<?php echo $should_update; ?>',
                            update_employment_folder_note: '<?php echo $update_employment_folder_note; ?>',
                            csrf_token: "{{ csrf_token() }}",
                            obem_employment_folder_form_title: "{{ __('obem.obem_employment_folder_form_title') }}",
                            address_label: "{{ __('obem.address_label') }}",
                            phone_number_label: "{{ __('obem.phone_number_label') }}",
                            highest_degree_label: "{{ __('obem.highest_degree_label') }}",
                            submit_label: "{{ __('obem.submit_label') }}",
                            cv_label: "{{ __('obem.cv_label') }}",
                            cover_letter_label: "{{ __('obem.cover_letter_label') }}"
                        }
                    ), 
                    document.getElementById('new-employment-folder')
                );
            }
        </script>

    </body>
</html>