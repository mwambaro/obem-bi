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
            <div id="privacy-policy-section" class="modal fade" data-keyboard="false" tabIndex="-1" aria-hidden="true"
                 onBlur="onFocusOutHandler()">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header text-right">
                            <button type="button" class="close" aria-label="Close"
                                    onClick="leavePrivacyPolicy()">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="privacy-policy-body">
                                <?php echo $privacy_policy_data; ?>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="text-center">
                                <button type="button" 
                                        class="btn btn-primary" 
                                        onClick="leavePrivacyPolicy()">
                                    OK
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            let PrivacyPolicySectionModal = new bootstrap.Modal(
                document.getElementById('privacy-policy-section')
            );

            function privacy_policy_componentDidMount()
            {
                PrivacyPolicySectionModal.show();

                window.addEventListener('click', (event) => {
                    let object = event.target;
                    let id = 'privacy-policy-section';
                    if(event)
                    {
                        if(object.id != id)
                        {
                            let parent = object.parentElement;
                            let isChild = false;
                            while(parent)
                            {
                                if(parent.id === id)
                                {
                                    isChild = true;
                                    break;
                                }
                                parent = parent.parentElement;
                            }
                            if(isChild)
                            { 
                                onFocusOutHandler(event);
                            }
                        }
                    }
                });

            } // privacy_policy_componentDidMount

            function leavePrivacyPolicy(e)
            {
                PrivacyPolicySectionModal.hide();
                // go back
                window.location.assign('{{ action([ObemMainController::class, "home"]) }}#obem-site-footer');

            } // leavePrivacyPolicy

            function onFocusOutHandler(e)
            {
                window.location.assign('{{ action([ObemMainController::class, "home"]) }}#obem-site-footer');

            } // onFocusOutHandler

            $(document).ready(() => {
                privacy_policy_componentDidMount();
            });
        </script>
    </body>
</html>