'use strict';

const e = React.createElement;

class ObemSiteAnalytics extends React.Component 
{
    constructor(props)
    {
        super(props);

    } // constructor

    render()
    {
        let container = $(document).isMobile() === true ? 
                        'container-fluid' : 
                        'container';

        let inner_array_html = [];

        //console.log(this.props.page_analytics);

        JSON.parse(this.props.page_analytics).map((analytics, idx) =>
        {
            let elt = [
                e('p', {}, [e('strong', {}, `${this.props.number_of_visits_label}: `), analytics.number_of_visits]),
                e('p', {}, [e('strong', {}, `${this.props.number_of_visitors_label}: `), analytics.number_of_visitors]),
                e('p', {style: {'word-wrap': 'break-word'}}, [e('strong', {}, `${this.props.page_visited_label}: `), analytics.page])
            ];
            inner_array_html.push(
                e(
                    'div', 
                    {
                        className: 'shadow-sm p-1 mb-2 bg-white rounded', 
                        style: {margin: '5px'}, 
                        key: `obem-site-analytics-${idx}`,
                        'data-aos': 'fade-up'
                    }, 
                    elt
                )
            );
        });

        //console.log('Inner elt created ...');


        let title_html = e(
            'div', {className: 'text-center'}, e('h1', {}, this.props.website_analytics_label)
        );

        let html = e(
            'div', 
            {
                className: container, 
                style: {margin: '5px'}
            }, 
            e(
                'div',
                {
                    className: 'row justify-content-center'
                },
                e(
                    'div',
                    {
                        className: 'col-md-8'
                    },
                    title_html, 
                    inner_array_html
                )
            )
        )

        //console.log('All elts created.');

        return html;

    } // render
}

ObemSiteAnalytics.propTypes = {
    page_analytics: PropTypes.string, // stringified array of {page:, number_of_visits:, number_of_visitors:}
    number_of_visits_label: PropTypes.string,
    number_of_visitors_label: PropTypes.string,
    page_visited_label: PropTypes.string,
    website_analytics_label: PropTypes.string
};