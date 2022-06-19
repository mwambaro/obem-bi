
    <div class="container-fluid" style="margin-top: 20px">
        <div>
            <?php echo $article_html_body; ?>  
        </div>
        <div class="" style="zIndex: 99">  
                <button id="myBackToTopBtn" 
                        title="Go to top"
                        style="display: none; position: fixed; bottom: 20px; right: 20px; zIndex: 99; border: none; outline: none; background-color: red; color: white; cursor: pointer; padding: 15px; border-radius: 10px; font-size: 18px">
                    {{ __('obem.back_to_top_label') }}
                </button>
            @if($is_admin)
                <button id="edit-article-button" 
                        title="Edit Article"
                        style="display: block; position: fixed; bottom: 20px; margin-right: 20px; zIndex: 99; border: none; outline: none; background-color: red; color: white; cursor: pointer; padding: 15px; border-radius: 10px; font-size: 18px">
                    {{ __('obem.edit_label') }}
                </button>
            @endif
        </div>
    </div>