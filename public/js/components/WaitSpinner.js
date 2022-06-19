'use strict';

const e = React.createElement;

class WaitSpinner extends React.Component 
{
    constructor(props)
    {
        super(props);
        this.spinner_id = 'wait-spinner-component';
    }

    render()
    {
        let spinner = e(
            'div',
            {
                id: 'obem-wait-spinner',
                style: {
                    display: 'none',
                    zIndex: '99',
                    border: 'none',
                    outline: 'none',
                    backgroundColor: 'transparent',
                    position: 'fixed'
                }
            },
            e(
                'div',
                {
                    role: 'status',
                    className: 'spinner-border text-success',
                    style: { width: '100px', height: '100px'}
                },
                e(
                    'span',
                    {
                        className: 'sr-only'
                    },
                    'Wait...'
                )
            )
        );

        return spinner;
    }

    show_wait_spinner()
    {
        let spinner = document.getElementById(this.spinner_id);
        if(spinner)
        {
            spinner.style.display = "block";
            $(`#${this.spinner_id}`).css({  
                position: 'absolute',
                top: '50%',
                left: '50%',
                transform: 'translate(-50%, -50%)'
            });
            $('body').css('opacity', '0.5');
            this.center_spinner_in_the_viewport();
        }

    } // show_wait_spinner

    hide_wait_spinner()
    {
        let spinner = document.getElementById(this.spinner_id);
        if(spinner)
        {
            spinner.style.position = 'fixed';
            spinner.style.display = "none";
            $('body').css('opacity', '1.0');
        }

    } // hide_wait_spinner

    center_spinner_in_the_viewport()
    {
        var viewportWidth = jQuery(window).width(),
        viewportHeight = jQuery(window).height(),
        $foo = jQuery(`#${this.spinner_id}`),
        elWidth = $foo.width(),
        elHeight = $foo.height(),
        elOffset = $foo.offset();
        jQuery(window)
            .scrollTop(elOffset.top + (elHeight/2) - (viewportHeight/2))
            .scrollLeft(elOffset.left + (elWidth/2) - (viewportWidth/2));

    } // center_spinner_in_the_viewport
}