'use strict';

const e = React.createElement;

class PagedViewControls extends React.Component
{
    constructor(props)
    {
        super(props);
        try 
        {
            this.spinner_id = 'wait-spinner-component';
            this.button_initial_css = null;
            this.max_pages_segment_size = 2;
            this.total_number_of_pages = parseInt(this.props.total_number_of_pages);
            this.current_page_number = parseInt(this.props.current_page_number);
            
            let match = /#pages_segment=(\d+)-(\d+)/i.exec(window.location.href);
            if(match)
            {
                let seg = [];
                let start = parseInt(match[1]);
                let end = parseInt(match[2]);
                for(let i=start; i<=end; i++)
                {
                    seg.push(i);
                }
                this.current_pages_segment = seg;
            }
            else
            {
                this.current_pages_segment = this.calculate_pages_segment();
            }
            
            this.previous_pages_segment = null;
            this.state = {
                pages_segment: this.current_pages_segment,
                current_page_number: this.current_page_number,
                previous_page_number: 0,
                articles_page: this.props.articles_page
            };
        }
        catch(ex)
        {
            console.log(`constructor: ${ex.message}`);
        }

    } // constructor

    render()
    {
        let page_button_class = 'article-page-button';
        let container = $(document).isMobile() === true ? 
                        'container-fluid' : 
                        'container';
        
        let spinner = e(
            'div',
            {
                id: this.spinner_id,
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

        let page_elements = this.props.page_elements_factory_callback(this.state.articles_page);
        
        let pages_segment = [];
        // Prev
        if(this.state.pages_segment[0] > 1)
        {
            let prev = e(
                'button',
                {
                    id: 'previous',
                    key: 'article-page-key-prev',
                    className: `text-primary ${page_button_class}`,
                    style: {margin: '5px', padding: '5px'},
                    onClick: (se) => this.switch_to_page(se)
                },
                this.props.previous_label
            );
            pages_segment.push(prev);
        }

        // Page buttons
        this.state.pages_segment.map((page_number, idx) => {
            let page_button = e(
                'button',
                {
                    key: `article-page-key-${idx}`,
                    id: `article-page-number-${page_number}`,
                    className: `text-primary ${page_button_class}`,
                    style: this.state.current_page_number === page_number ? 
                        {
                            backgroundColor: 'black',
                            fontColor: 'white',
                            fontWeight: 'bold',
                            margin: '5px', 
                            padding: '5px'
                        } : 
                        {
                            margin: '5px', 
                            padding: '5px'
                        },
                    onClick: (se) => this.switch_to_page(se)
                },
                page_number
            );
            pages_segment.push(page_button);
        });
        
        // Next
        if(this.total_number_of_pages>this.state.pages_segment.length)
        {
            let next = e(
                'button',
                {
                    id: 'next',
                    key: 'article-page-key-next',
                    className: `text-primary ${page_button_class}`,
                    style: {margin: '5px', padding: '5px'},
                    onClick: (se) => this.switch_to_page(se)
                },
                this.props.next_label
            );
            pages_segment.push(next);
        }
        let pages_div = e(
            'div',
            {
                className: 'd-flex flex-row justify-content-center',
                style: {marginTop: '5px'}
            },
            pages_segment
        );

        let main_div = e(
            'div',
            {
                className: container,
                id: 'paged_views_controls_main_div'
            },
            page_elements,
            pages_div,
            spinner
        );

        return main_div;

    } // render

    componentDidMount()
    {
        scroll_element_into_view('paged_views_controls_main_div');

    } // componentDidMount

    componentDidUpdate()
    {
        console.log('Did update: current => ' + this.state.current_page_number + '; prev => ' + this.state.previous_page_number);
        scroll_element_into_view('paged_views_controls_main_div');

    } // componentDidUpdate

    calculate_pages_segment(page_number=1)
    {
        let segment = [];

        try 
        {
            let total_number_of_pages = parseInt(this.props.total_number_of_pages);
            //console.log(`Total Number of Pages: ${total_number_of_pages}`);
            let counter = 0;
            for(let j=page_number; j<=total_number_of_pages; j++)
            {
                if(counter === this.max_pages_segment_size)
                {
                    break;
                }
                segment.push(j);
                counter++;
            }
        }
        catch(ex)
        {
            console.log(`calculate_pages_segment: ${ex.message}`);
        }

        return segment;

    } // calculate_pages_segment

    calculate_previous_pages_segment(current)
    {
        let first_page = current[0] - this.max_pages_segment_size;
        let prev_segment = [];
        let page = first_page;
        for(let i=0; i<this.max_pages_segment_size; i++)
        {
            prev_segment.push(page);
            page += 1;
        }

        return prev_segment;

    } // calculate_previous_pages_segment

    switch_to_page(e)
    {
        try 
        {

            let button = e.target;
            let inner_html = button.innerHTML.trim();
            let id = button.id;
            
            if(/\d+/.test(inner_html))
            {
                this.current_page_number = parseInt(inner_html);
                this.pages_segment = this.state.pages_segment;
                this.fetch_articles_page();
            }
            else if(/^next$/i.test(id))
            {
                let length = this.state.pages_segment.length;
                this.current_page_number = this.state.pages_segment[length-1] + 1;
                //console.log(`Current Page Number: ${this.current_page_number}`);
                this.pages_segment = this.calculate_pages_segment(this.current_page_number);
                //console.log(`Pages Segment Length: ${this.pages_segment.length}`);
                if(this.pages_segment.length === 0) // restart
                {
                    this.current_page_number = 1;
                    this.pages_segment = this.calculate_pages_segment(this.current_page_number);
                }
                this.fetch_articles_page();
            }
            else if(/^previous$/i.test(id))
            {
                this.pages_segment = this.calculate_previous_pages_segment(this.pages_segment);
                let len = this.pages_segment.length;
                if(len>0)
                {
                    this.current_page_number = this.pages_segment[len-1];
                    this.fetch_articles_page();
                }
            }
        }
        catch(ex)
        {
            console.log(`switch_to_page: ${ex.message}`);
        }

    } // switch_to_page

    fetch_articles_page()
    {
        try 
        {
            this.show_wait_spinner();
            sleep(1000);
            let p_url = this.props.page_url.replace(/page_number/i, this.current_page_number);
            let len = this.pages_segment.length;
            let url = p_url + `#pages_segment=${this.pages_segment[0]}-${this.pages_segment[len-1]}`;
            window.location = url;
        }
        catch(ex)
        {
            console.log(`fetch_articles_page: ${ex.message}`);
        }

    } // fetch_articles_page

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

PagedViewControls.propTypes = {
    // Factory callback function that creates page elements 
    // with React.createElement; the elements making up a page. 
    // In this callback definition you can mind the 
    // insides of the 'articles_page' object.
    page_elements_factory_callback: PropTypes.object, 
    // we do not mind the insides of this object, you do, 
    // through the implementation of the callback. 
    // We just update it for you using the endpoint given.
    // The state in which you give it is maintained, so even the endpoint should.
    articles_page: PropTypes.object, 
    total_number_of_pages: PropTypes.string,
    next_label: PropTypes.string,
    previous_label: PropTypes.string,
    obem_articles_page_endpoint: PropTypes.string,
    page_url: PropTypes.string, // the url string with 'page_number' substring where to put the page number
    current_page_number: PropTypes.string,
    csrf_token: PropTypes.string
};

//export default PagedViewControls;