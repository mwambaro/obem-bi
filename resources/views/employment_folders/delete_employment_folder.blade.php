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
        <?php use App\Http\Controllers\UsersController; ?>
        <?php use App\Http\Controllers\EmploymentFoldersController; ?>
        
        <div style="margin: 10px">
            @if($require_confirmation)
                <div class="d-flex flex-row justify-content-center">
                    <div>
                        <p style="color: red"> <?php echo $message; ?> </p>
                        <div style="margin: 10px" class="d-flex flex-row justify-content-center">
                            <button id="yes" class="btn btn-primary" type="button" style="background-color: black; color: white; font-weight: bold; margin: 10px">
                                {{ __('obem.yes_label') }}
                            </button>
                            <button id="no" class="btn btn-primary" type="button" style="background-color: black; color: white; font-weight: bold; margin: 10px">
                                {{ __('obem.no_label') }}
                            </button>
                        </div>
                    </div>
                </div>
            @else
                <div style="margin: 10px" class="d-flex flex-row justify-content-center">
                    <div>
                        <p style="color: green; margin: 10px"> <?php echo $message; ?> </p>
                        <p> 
                            <a href="{{ action([UsersController::class, 'show_user'], ['id' => $obem_user->id]) }}"> {{ __('obem.user_label') }} </a>
                        </p>
                    </div>
                </div>
            @endif
        </div>
        <script>
            var yesBtn = document.getElementById('yes');
            var noBtn = document.getElementById('no');
            if(yesBtn)
            {
                yesBtn.onclick = function(e){
                    window.location = "{{ action([EmploymentFoldersController::class, 'delete_employment_folder'], ['id' => $obem_user->employment_folder_id]) }}?confirm=yes"
                }
                noBtn.onclick = function(e){
                    window.location = "{{ action([UsersController::class, 'show_user'], ['id' => $obem_user->id]) }}"
                }
            }
        </script>
    </body>
</html>