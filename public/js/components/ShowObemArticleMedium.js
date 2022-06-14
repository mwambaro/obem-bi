'use strict';

const e = React.createElement;

class ShowObemArticleMedium extends React.Component 
{
    constructor(props)
    {
        super(props);

    } // constructor

    render()
    {
        let medium_elt = '';

        if(this.props.mime_type.startsWith('image'))
        {
            medium_elt = e(
                'img',
                {
                    className: 'img-fluid',
                    src: this.props.medium_url
                }
            );
        }
        else if(this.props.mime_type.startsWith('video'))
        {
            medium_elt = e(
                'video',
                {
                    className: 'embed-responsive embed-responsive-23by12 text-center',
                    controls: true
                },
                [
                    e(
                        'source',
                        {
                            type: this.props.mime_type,
                            src: this.props.medium_url,
                            className: 'embed-responsive-item'
                        }
                    ),
                    'No video'
                ]
            );
        }
        else if(this.props.mime_type.startsWith('audio'))
        {
            medium_elt = e(
                'audio',
                {
                    className: 'embed-responsive text-center',
                    controls: true
                },
                [
                    e(
                        'source',
                        {
                            src: this.props.medium_url,
                            type: this.props.mime_type,
                            className: 'embed-responsive-item'
                        }
                    ),
                    'No audio'
                ]
            );
        }
        else 
        {
            medium_elt = e(
                'p',
                {
                    className: 'text-primary',
                    style: { padding: '10px'}
                },
                `${this.props.mime_type} unsupported by our component`
            );
        }

        let outer_div = e(
            'div',
            {
                className: 'row justify-content-start',
                style: { margin: '5px', padding: '5px'}
            },
            e(
                'div',
                {
                    className: 'shadow-sm p-1 mb-2 bg-white rounded col-md-6',
                },
                e(
                    'div',
                    {},
                    medium_elt
                )
            )
        );

        return outer_div;

    } // render
}

ShowObemArticleMedium.propTypes = {
    mime_type: PropTypes.string,
    medium_url: PropTypes.string
};