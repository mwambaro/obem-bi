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

        <?php use App\Http\Controllers\ObemMainController; ?>

        <div class="container-fluid">
            <div id="cookies-policy-section" class="modal fade" data-keyboard="false" tabIndex="-1" aria-hidden="true"
                 onBlur="onFocusOutHandler()">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header text-right">
                            <button type="button" class="close" aria-label="Close"
                                    onClick="leaveCookiesPolicy()">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="cookies-policy-body">
                                <?php echo $cookies_policy_data; ?>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="text-center">
                                <button type="button" 
                                        class="btn btn-primary" 
                                        onClick="leaveCookiesPolicy()">
                                    OK
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            let CookiesPolicySectionModal = new bootstrap.Modal(
                document.getElementById('cookies-policy-section')
            );

            function cookies_policy_componentDidMount()
            {
                CookiesPolicySectionModal.show();

            } // cookies_policy_componentDidMount

            function leaveCookiesPolicy(e)
            {
                CookiesPolicySectionModal.hide();
                // go back
                window.location.assign('{{ action([ObemMainController::class, "home"]) }}#obem-site-footer');

            } // leaveCookiesPolicy

            function onFocusOutHandler(e)
            {
                window.location.assign('{{ action([ObemMainController::class, "home"]) }}#obem-site-footer');

            } // onFocusOutHandler

            $(document).ready(() => {
                cookies_policy_componentDidMount();
            });
        </script>
    </body>
</html>