        <?php use App\Http\Controllers\ObemMainController; ?>
        
        <div class="container-fluid">
            <div class="text-center">
                <img src="{{ banner_image_asset_url() }}" class="img-fluid banner-image" />
            </div>
            <hr style="color: #E10600; margin: 3px; height: 3px"> 
            <hr style="color: #45E00B; margin: 3px; height: 3px"> 
        </div>

        <script type="text/javascript">
            let banners = document.getElementsByClassName('banner-image');
            let blength = banners.length;
            if(blength > 0)
            {
                for(let i=0; i<blength; i++)
                {
                    banners[i].onclick = function(){
                        window.location = "{{ action([ObemMainController::class, 'home']) }}";
                    }
                    banners[i].onmouseover = function(e){
                        e.target.style.cursor = 'pointer';
                    }
                }
            }  
        </script>