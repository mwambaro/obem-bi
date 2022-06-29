        
        <div id="<?php echo $show_user_id; ?>" data-aos="fade-up">
        </div>

        <script type="text/javascript">
            if(document.getElementById('<?php echo $show_user_id; ?>'))
            {
                ReactDOM.render(
                    e(
                        ObemUserShow, 
                        {
                            cover_letter_label: "{{ __('obem.cover_letter_short_label') }}",
                            cover_letter_url: '<?php echo $user_data["cover_letter_url"]; ?>',
                            cv_label: "{{ __('obem.cv_short_label') }}",
                            cv_url: '<?php echo $user_data["cv_url"]; ?>',
                            highest_degree_label: "{{ __('obem.highest_degree_short_label') }}",
                            user_highest_degree: '<?php echo $user_data["user_highest_degree"]; ?>',
                            phone_number_label: "{{ __('obem.phone_number_label') }}",
                            user_phone_number: '<?php echo $user_data["user_phone_number"]; ?>',
                            address_label: "{{ __('obem.address_label') }}",
                            user_address: '<?php echo $user_data["user_address"]; ?>',
                            has_employment_folder: '<?php echo $user_data["has_employment_folder"]; ?>',
                            upload_profile_photo_action_url: '<?php echo $user_data["upload_profile_photo_action_url"]; ?>',
                            view_mode: '<?php echo $user_data["view_mode"]; ?>',
                            view_mode_label: "{{ __('obem.view_mode_label') }}",
                            view_mode_url: '<?php echo $user_data["view_mode_url"]; ?>',
                            destroy_label: "{{ __('obem.destroy_label') }}",
                            destroy_user_url: '<?php echo $user_data["destroy_user_url"]; ?>',
                            destroy_employment_folder_url: '<?php echo $user_data["destroy_employment_folder_url"]; ?>',
                            edit_label: "{{ __('obem.edit_label') }}",
                            edit_user_url: '<?php echo $user_data["edit_user_url"]; ?>',
                            edit_employment_folder_url: '<?php echo $user_data["edit_employment_folder_url"]; ?>',
                            email_label: "{{ __('obem.email_label') }}",
                            user_email: '<?php echo $user_data["user_email"]; ?>',
                            user_role_label: "{{ __('obem.role_label') }}",
                            user_role: '<?php echo $user_data["user_role"]; ?>',
                            user_full_name: '<?php echo $user_data["user_full_name"]; ?>',
                            profile_photo_url: '<?php echo $user_data["profile_photo_url"]; ?>',
                            profile_photo_label: "{{ __('obem.profile_photo_label') }}",
                            submit_label: "{{ __('obem.submit_label') }}",
                            csrf_token: "{{ csrf_token() }}"
                        }
                    ), 
                    document.getElementById('<?php echo $show_user_id; ?>')
                );
            }
        </script>