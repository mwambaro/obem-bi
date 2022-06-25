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
        return(
            e(
                'div',
                {},
                e(
                    'div', 
                    {
                        style: {margin: '10px'},
                        className: container,
                        id: "obem-orientation-service-body"
                    }
                )
            )
        );

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