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
            <div id="terms-of-use-section" class="modal fade" data-keyboard="false" tabIndex="-1" aria-hidden="true"
                 onBlur="onFocusOutHandler()">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header text-right">
                            <button type="button" class="close" aria-label="Close"
                                    onClick="leaveTermsOfUse()">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="terms-of-use-body">
                                <?php echo $terms_of_use_data; ?>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="text-center">
                                <button type="button" 
                                        class="btn btn-primary" 
                                        onClick="leaveTermsOfUse()">
                                    OK
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            let termsOfUseSectionModal = new bootstrap.Modal(
                document.getElementById('terms-of-use-section')
            );

            function terms_of_use_componentDidMount()
            {
                termsOfUseSectionModal.show();

            } // terms_of_use_componentDidMount

            function leaveTermsOfUse(e)
            {
                termsOfUseSectionModal.hide();
                // go back
                window.location.assign('{{ action([ObemMainController::class, "home"]) }}#obem-site-footer');

            } // leaveTermsOfUse

            function onFocusOutHandler(e)
            {
                window.location.assign('{{ action([ObemMainController::class, "home"]) }}#obem-site-footer');

            } // onFocusOutHandler

            $(document).ready(() => {
                terms_of_use_componentDidMount();
            });
        </script>
    </body>
</html>