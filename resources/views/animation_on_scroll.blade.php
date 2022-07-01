        
        <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
        <script>
            try 
            {
                if(AOS && typeof(AOS) != 'undefined')
                {
                    AOS.init();
                    console.log('AOS inited.');
                    jQuery(window).scrollTop(20);
                }
            }
            catch(e)
            {
                console.log("AOS: " + e);
            }
        </script>