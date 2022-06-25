'use strict';

//const e = React.createElement;

class ObemArticlesIndex extends React.Component 
{
    constructor(props)
    {
        super(props);

    } // constructor

    render()
    {
        let cards_factory_callback = (articles_page) =>
        {
            let cards = [];
            articles_page.map((article, idx) =>
            {
                let card = e(
                    'div', 
                    {
                        className: '',
                        key: `obem-article-key-${idx}`,
                        style: {marginTop: '5px'}
                    },
                    e(
                        'div', 
                        {className: 'row justify-content-start'},
                        e(
                            'div', 
                            {className: 'col-md-10'},
                            e(
                                'div', 
                                {className: 'card'}, 
                                [   
                                    e(
                                        'div', 
                                        {className: 'card-header'}, 
                                        e(
                                            'a',
                                            {
                                                id: 'obem-article-capture',
                                                href: article.url
                                            },
                                            article.capture
                                        )
                                    )
                                ]
                            )
                        )
                    )
                );
                cards.push(card);
            });

            return cards;
        }

        let main_div = e(
            PagedViewControls,
            {
                page_elements_factory_callback: cards_factory_callback,
                articles_page: JSON.parse(this.props.articles_page),
                total_number_of_pages: this.props.total_number_of_pages,
                next_label: this.props.next_label,
                previous_label: this.props.previous_label,
                obem_articles_page_endpoint: this.props.obem_articles_page_endpoint,
                csrf_token: this.props.csrf_token
            }
        );

        return main_div;

    } // render
}

ObemArticlesIndex.propTypes = {
    articles_page: PropTypes.string, // stringified array of {capture:, url:}
    total_number_of_pages: PropTypes.string,
    next_label: PropTypes.string,
    previous_label: PropTypes.string,
    obem_articles_page_endpoint: PropTypes.string,
    csrf_token: PropTypes.string
};