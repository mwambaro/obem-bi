'use strict';

const e = React.createElement;

class ObemOrientationService extends React.Component
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
        let html = e(
            'div',
            {
                className: container
            },
            e(
                'div',
                {
                    className: 'row justify-content-center'
                },
                e(
                    'div', 
                    {
                        style: {margin: '10px'},
                        className: 'col-md-8',
                        id: "obem-orientation-service-body"
                    }
                )
            )
        );

        return html;

    } // render

    componentDidMount()
    {
        let html = this.props.orientation_service_html;
        html += this.props.orientation_addresses_html;
        $('#obem-orientation-service-body').append(html);

    } // componentDidMount
}

ObemOrientationService.propTypes = {
    orientation_service_html: PropTypes.string,
    orientation_addresses_html: PropTypes.string
};