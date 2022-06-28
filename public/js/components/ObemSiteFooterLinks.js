'use strict';

//const e = React.createElement;

class ObemSiteFooterLinks extends React.Component
{
    constructor(props)
    {
        super(props);
        this.state = {
            rerender: false
        };
        this.hspace = null;
    }

    render()
    {
        let render_data = null;
        let figure_it_out = true;

        if(this.props.display_type)
        {
            figure_it_out = false;
            switch(this.props.display_type)
            {
                case 'flex':
                    render_data = this.renderFooterLinksAsFlexList();
                    break;
                case 'block-list':
                    render_data = this.renderFooterLinksAsTable();
                    break;
                case 'flex-block-list':
                    render_data = this.renderFooterLinksAsTable();
                    break;
                default:
                    figure_it_out = true;
                    break;
            }
        }

        if(figure_it_out)
        {
            if(this.state.rerender)
            {
                if(!this.hspace)
                {
                    this.hspace = new HorizontalSpace('#footer-links-ul-list', this.props.parent_selector);
                }
                this.hspace.assessViewPortSize(this.props.parent_max_width);
            }
        
            // Make sure 'this.state.rerender' state does not change between component
            // construction and first render
            if(!this.state.rerender) // just for precise size assessment
            {
                render_data = this.renderFooterLinksAsFlexList();
            }
            else if (this.hspace.horizontal_space_state.display_type === 'flex')
            {
                render_data = this.renderFooterLinksAsFlexList();
            }
            else if (this.hspace.horizontal_space_state.display_type === 'block-list')
            {
                render_data = this.renderFooterLinksAsTable();
            }
            else
            {
                render_data = this.renderFooterLinksAsTable();
            }
        }

        return render_data;
    }

    renderFooterLinksAsTable()
    {
        let text_style = {
            whiteSpace: 'nowrap'
        };

        let actions_elt = [];
        this.props.footer_actions.map((action, idx) => 
        {
            let elt = e(
                'tr', 
                {
                    key: `footer-table-tr-${idx}`
                },
                e(
                    'td', 
                    {},
                    e(
                        'a', 
                        {
                            className: "nav-link",
                            href: action.url
                        },
                        e(
                            'span', 
                            {
                                style: text_style
                            }, 
                            action.inner_text
                        )
                    )
                )
            );
            actions_elt.push(elt);
        });
        
        return(
            e(
                'div', 
                {
                    id: "footer-links-component"
                }, 
                e(
                    'div', 
                    {},
                    e(
                        'table', 
                        {
                            className: "table"
                        },
                        e(
                            'tbody', 
                            {
                                id: "footer-links-table-body"
                            },
                            actions_elt
                        )
                    )
                )
            )
        );
    }

    renderFooterLinksAsBlockList()
    {
        let footer_ul_style = {
            display: 'block'
        };

        return(this.renderFooterLinksAsList(footer_ul_style));
    }

    renderFooterLinksAsFlexList()
    {
        let footer_ul_style = {
            display: 'flex'
        };

        return(this.renderFooterLinksAsList(footer_ul_style));
    }

    renderFooterLinksAsList(ul_style)
    {
        let footer_ul_style = ul_style;
        let footer_li_style = {
            listStyleType: 'none'
        };
        let text_style = {
            whiteSpace: 'nowrap'
        }

        let actions_elt = [];
        this.props.footer_actions.map((action, idx) => 
        {
            let action_elt = e(
                'li', 
                {
                    key: `footer-ul-li-${idx}`, 
                    className: "nav-item", 
                    style: footer_li_style
                },
                e(
                    'a', 
                    {
                        className: "nav-link",
                        href: action.url
                    },
                    e(
                        'span', 
                        {
                            style: text_style
                        },
                        action.inner_text
                    )
                )
            );
            actions_elt.push(action_elt);
        });

        return(
            e(
                'div', 
                {
                    id: "footer-links-component"
                },
                e(
                    'div', 
                    {},
                    e(
                        'ul', 
                        {
                            id: "footer-links-ul-list", 
                            style: footer_ul_style
                        },
                        actions_elt
                    )
                )
            )
        );
    }

    componentDidMount()
    {
        this.setState({
            rerender: true
        });
    }
}

ObemSiteFooterLinks.propTypes = {
    footer_actions: PropTypes.array, // an array of {url:, inner_text:} hashes
    parent_selector: PropTypes.string,
    display_type: PropTypes.string, //one of these {null, 'flex', 'flex-block-list', 'block-list'}. Set to null to let the component decide
    parent_max_width: PropTypes.number
};