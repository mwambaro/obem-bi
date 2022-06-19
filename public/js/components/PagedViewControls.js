'use strict';

const e = React.createElement;

class PagedViewControls extends React.Component
{
    constructor(props)
    {
        super(props);
        try 
        {
            this.max_pages_segment_size = 2;
            this.total_number_of_pages = parseInt(this.props.total_number_of_pages);
            this.current_page_number = 1;
            this.state = {
                pages_segment: this.calculate_pages_segment(),
                current_page_number: 1,
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
        
        let page_elements = this.props.page_elements_factory_callback(this.state.articles_page);
        
        let pages_segment = [];
        this.state.pages_segment.map((page_number, idx) => {
            let page_button = e(
                'button',
                {
                    key: `article-page-key-${idx}`,
                    className: `text-primary ${page_button_class}`,
                    style: {marginLeft: '5px'}
                },
                page_number
            );
            pages_segment.push(page_button);
        });
        
        if(this.total_number_of_pages>this.state.pages_segment.length)
        {
            let next = e(
                'button',
                {
                    key: 'article-page-key-next',
                    className: `text-primary ${page_button_class}`,
                    style: {marginLeft: '5px'}
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
                className: container
            },
            page_elements,
            pages_div
        );

        return main_div;

    } // render

    componentDidMount()
    {
        $('.article-page-button').on('click', (e) => {
            this.switch_to_page(e);
        });

    } // componentDidMount

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

    switch_to_page(e)
    {
        try 
        {

            let button = e.target;
            let inner_html = button.innerHTML.trim();
            let next_regex = new RegExp(this.props.next_label);
            if(/\d+/.test(inner_html))
            {
                this.current_page_number = parseInt(inner_html);
                this.pages_segment = this.state.pages_segment;
                this.fetch_articles_page();
            }
            else if(next_regex.test(inner_html))
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
            else 
            {
                this.wait_spinner.hide_wait_spinner();
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
            let url = this.props.obem_articles_page_endpoint;
            let data_to_send = {
                page_number: this.current_page_number,
                _token: this.props.csrf_token
            };
            let callback = (received_data) => {
                let code = parseInt(received_data.code);
                let data = JSON.parse(received_data.data);
                if(code === 1)
                {
                    this.setState({
                        current_page_number: this.current_page_number,
                        articles_page: data,
                        pages_segment: this.pages_segment
                    });
                }
                else 
                {
                    console.log('Error: ' + data);
                }
            };
            let response_type = 'json';
            $.post(url, data_to_send, callback, response_type)
            .fail((error) =>
            {
                console.log('Post failed: ' + error.status + '; ' + error.statusText);
            });
        }
        catch(ex)
        {
            console.log(`fetch_articles_page: ${ex.message}`);
        }

    } // fetch_articles_page
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
    obem_articles_page_endpoint: PropTypes.string,
    csrf_token: PropTypes.string
};

//export default PagedViewControls;