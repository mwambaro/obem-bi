<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    {{ 
        view('header')
            ->with('site_title', 'OBEM')
            ->with('obem_open_graph_proto_locale', 'fr_FR');
    }}
    <body class="container-fluid">
        
        <div id="example" style="margin: 10px; padding: 10px">
        </div>
        
        {{ view('reactjs') }}
        <script src="{{ asset('js/components/Example.js') }}"> </script>
        <script type="text/javascript">
            ReactDOM.render(
                e(
                    Example, 
                    {
                        card_title: "{{ __('obem.fail_safe_title') }}", 
                        card_body: "<?php echo $fail_safe_message; ?>",
                        card_image: "{{ asset('images/obem_banner_image.JPG') }}"
                    }
                ), 
                document.getElementById('example')
            );
            $('#example').css({  
                position: 'absolute',
                top: '50%',
                left: '50%',
                transform: 'translate(-50%, -50%)'
            });
        </script>
    </body>
</html>