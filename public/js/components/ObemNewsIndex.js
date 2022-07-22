'use strict';

//const e = React.createElement;

class ObemNewsIndex extends React.Component 
{
    constructor(props)
    {
        super(props);

    } // constructor

    render()
    {
        let news_item_cards_factory = (news_page) =>
        {
            let cards = [];
            let img_width = ($(window).width()*1)/3;
            news_page.map((page_item, idx) => {
                let text_elt = e(
                    'div',
                    {
                        id: `div:${page_item.url}`,
                        className: 'd-flex flex-column justify-content-start align-items-start obem-index'
                    },
                    e(
                        'h4',
                        {
                            margin: '5px',
                            padding: '5px'
                        },
                        e(
                            'a',
                            {
                                href: page_item.url
                            },
                            page_item.capture
                        )
                    ),
                    e(
                        'p',
                        {
                            id: `p:${page_item.url}`,
                            className: 'obem-index'
                        },
                        page_item.description
                    )
                );
                let img_elt = e(
                    'div',
                    {
                        marginLeft: '20px'
                    },
                    (
                        page_item.first_image_url === '' ?
                        (
                            e('div')
                        ):
                        (
                            e(
                                'img',
                                {
                                    className: 'img-fluid obem-index',
                                    src: page_item.first_image_url,
                                    width: img_width,
                                    id: `img:${page_item.url}`
                                }
                            )
                        )
                    )
                );
                let outer_div = e(
                    'div',
                    {
                        className: 'shadow-sm p-1 mb-2 bg-white rounded',
                        key: `obem-news-index-key-${idx}`
                    },
                    e(
                        'div',
                        {
                            className: 'd-flex flex-row justify-content-center'
                        },
                        text_elt,
                        img_elt
                    )
                );
                cards.push(outer_div);
            });

            return cards;
        };

        let main_div = e(
            PagedViewControls,
            {
                page_elements_factory_callback: news_item_cards_factory,
                articles_page: JSON.parse(this.props.articles_page),
                total_number_of_pages: this.props.total_number_of_pages,
                next_label: this.props.next_label,
                previous_label: this.props.previous_label,
                obem_articles_page_endpoint: this.props.obem_articles_page_endpoint,
                page_url: this.props.page_url,
                current_page_number: this.props.current_page_number,
                csrf_token: this.props.csrf_token
            }
        );

        return main_div;

    } // render

    componentDidMount()
    {
        $('.obem-index').hover((e) => {
            e.target.style.cursor = 'pointer';
        });
        $('.obem-index').on('click', (e) => {
            let id = e.target.id;
            let regex = /:([^:].+)/;
            let match = regex.exec(id);
            if(match)
            {
                window.location = match[1];
            }
        });
    }
}

ObemNewsIndex.propTypes = {
    articles_page: PropTypes.string, // stringified array of {capture:, url:, first_image_url:}
    total_number_of_pages: PropTypes.string,
    next_label: PropTypes.string,
    previous_label: PropTypes.string,
    obem_articles_page_endpoint: PropTypes.string,
    page_url: PropTypes.string, // the url string with 'page_number' substring where to put the page number
    current_page_number: PropTypes.string,
    csrf_token: PropTypes.string
};