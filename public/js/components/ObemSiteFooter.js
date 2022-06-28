'use strict';

//const e = React.createElement;

class ObemSiteFooter extends React.Component
{
    constructor(props)
    {
        super(props);
        this.state = {
            rerender: 0,
            powered_by_href: `mailto:${this.props.powered_by_email}`
        };
        this.setParentMaxWidth();
        this.set_powered_by_href();

    } // constructor

    render()
    {

        let div_style = {
            backgroundColor: 'black', 
            color: 'white', 
            fontWeight: 'bold',
            borderRadius: '5px'
        };

        let footer_actions = JSON.parse(this.props.footer_actions);

        return(
            e(
                'div', 
                {
                    id: "site-footer-component", 
                    className: "shadow-lg p-3 mb-5 bg-white rounded"
                },
                e(
                    'div', 
                    {
                        style: div_style
                    },
                    e(
                        'div', 
                        {
                            id: "site-footer-copy-right", 
                            className: "text-center", 
                            style: {padding: '10px'}
                        }, 
                        `\u00A9 ${this.props.copy_right_text}`
                    ),
                    e(
                        'div', 
                        {
                            id: "site-footer-div-links", 
                            className: "text-center"
                        },
                        e(
                            ObemSiteFooterLinks, 
                            {
                                footer_actions: footer_actions,
                                parent_selector: '#site-footer-div-links',
                                display_type: null,
                                parent_max_width: this.site_footer_links_parent_max_width
                            }
                        )
                    ),
                    e(
                        'div',
                        {
                            className: "text-center", 
                            style: {padding: '5px', margin: '5px'}
                        },
                        e(
                            'a',
                            {
                                href: this.state.powered_by_href,
                                style: {color: '#FFFF00', 'text-decoration': 'underline'}
                            },
                            this.props.powered_by_text
                        )
                    )
                )
            )
        );

    } // render

    componentDidMount()
    {
        this.hCenterComponents();
        window.addEventListener('resize', e => this.onResizeHandler(e));
        this.setParentMaxWidth();
        this.setState({
            rerender: 2
        });

    } // componentDidMount

    componentDidUpdate()
    {
        this.hCenterComponents();

    } // componentDidUpdate

    set_powered_by_href()
    {
        let laastras = 'https://laastras.bi';
        $.get(laastras)
            .done((data) => {
                this.setState({powered_by_href: laastras});
            })
            .fail((error) => {
                let href = 'https://laastras.herokuapp.com';
                $.get(href)
                    .done((data) =>{
                        //console.log('Found herokuapp: ' + href);
                        this.setState({powered_by_href: href});
                    });
            });

    } // set_powered_by_href

    hCenterComponents()
    {
        $('#site-footer-div-links').hcenter()
    } // hCenterComponents

    setParentMaxWidth()
    {
        let display = $('#site-footer-component').css('display');
        if(display === 'flex')
        {
            this.site_footer_links_parent_max_width = window.innerWidth 
                                                      - $('#site-footer-copy-right').outerWidth();
            this.site_footer_social_media_parent_max_width = window.innerWidth 
                                                             - $('#site-footer-copy-right').outerWidth()
                                                             - $('#site-footer-div-links').outerWidth();
        }
        else
        {
            this.site_footer_links_parent_max_width = window.innerWidth;
            this.site_footer_social_media_parent_max_width = window.innerWidth;
        }

    } // setParentMaxWidth

    onResizeHandler(e)
    {
        this.setParentMaxWidth();
        this.setState({
            rerender: this.state.rerender+1
        });

    } // onResizeHandler
}

ObemSiteFooter.propTypes = {
    footer_actions: PropTypes.string, // stringified array of {url:, inner_text:} hashes
    copy_right_text: PropTypes.string,
    powered_by_text: PropTypes.string,
    powered_by_email: PropTypes.string
};