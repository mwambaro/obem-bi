'use strict';

const e = React.createElement;

class ObemCommunity extends React.Component 
{
    constructor(props)
    {
        super(props);
        try 
        {
            this.obem_community_media = JSON.parse(this.props.obem_community_media);
        }
        catch(ex)
        {
            console.log('ObemCommunity constructor: ' + ex.message);
        }

    } // constructor

    render()
    {
        let main_div = e('div');
        
        let carousel_indicator_buttons = [];
        this.obem_community_media.map((data, idx) =>
        {
            let btn = e(
                'button',
                { 
                    key: `carousel-indicator-${idx}`,
                    type: "button", 
                    'data-bs-target': "#carouselExampleIndicators",
                    'data-bs-slide-to': `${idx}`,
                    className: idx === 0 ? 'active' : '',
                    'aria-current': idx === 0 ? 'true' : 'false',
                    'aria-label': `Slide ${idx+1}`
                }
            );
            carousel_indicator_buttons.push(btn);
        });
        let carousel_indicators_div = e(
            'div',
            {
                className: 'carousel-indicators'
            },
            carousel_indicator_buttons
        );
        
        //console.log('Carousel indicator buttons done.');

        let carousel_inner_data = [];
        this.obem_community_media.map((image_data, idx) =>
        {
            let data = e(
                'div', 
                {
                    key: `carousel-image-data-div-${idx}`,
                    className: idx === 0 ? 'carousel-item active' : 'carousel-item'
                },
                e(
                    'img',
                    {
                        src: image_data.url, 
                        className: "d-block w-100 img-fluid",
                        id: `obem-community-image-${idx}`,
                        alt: `obem community image ${idx+1}`
                    }
                ),
                e(
                    'div', 
                    {
                        className: "carousel-caption d-none d-md-block", 
                        style: {backgroundColor: 'black'}
                    },
                    e(
                        'h5', 
                        {},
                        image_data.capture
                    ),
                    e(
                        'p',
                        {},
                        image_data.description
                    )
                )
            );
            carousel_inner_data.push(data);                            
        });
        let carousel_inner_div = e(
            'div',
            {
                className: 'carousel-inner'
            },
            carousel_inner_data
        );
        
        //console.log('Carousel inner data div done.');

        let btn_prev = e(
            'button', 
            {
                style: {backgroundColor: 'black'},
                className: "carousel-control-prev",
                type: "button",
                'data-bs-target': "#carouselExampleIndicators", 
                'data-bs-slide': "prev"
            },
            e(
                'span',
                {
                    className: "carousel-control-prev-icon", 
                    'aria-hidden': "true"
                }
            ),
            e(
                'span', 
                {
                    className: "visually-hidden"
                },
                'Previous'
            )
        );
        let btn_next = e(
            'button', 
            {
                style: {backgroundColor: 'black'},
                className: "carousel-control-next",
                type: "button",
                'data-bs-target': "#carouselExampleIndicators", 
                'data-bs-slide': "next"
            },
            e(
                'span',
                {
                    className: "carousel-control-next-icon", 
                    'aria-hidden': "true"
                }
            ),
            e(
                'span', 
                {
                    className: "visually-hidden"
                },
                'Next'
            )
        );

        //console.log('Carousel next and prev buttons done.');

        let carousel_div = e(
            'div', 
            {
                id: "carouselExampleIndicators",
                className: "carousel slide",
                'data-bs-ride': "carousel"
            },
            carousel_indicators_div,
            carousel_inner_div,
            btn_prev,
            btn_next
        );

        let community_text = e(
            'div',
            {},
            e(
                'div',
                {
                    style: {margin: '10px'},
                    id: "obem-community-description"
                }
            ),
            e(
                'div', 
                {
                    className: "text-center",
                    style: {margin: '10px'}
                },
                e(
                    'div', 
                    {
                        className: "text-center"
                    },
                    e(
                        'img', 
                        {
                            src: this.props.community_diagram_url,
                            className: "img-fluid"
                        }
                    )
                )
            )
        );

        //console.log('Text next to carousel done.');
        
        let container = $(document).isMobile() === true ? 
                        'container-fluid' : 
                        'container';
        main_div = e(
            'div',
            {
                style: {marginTop: '40px'},
                className: container
            },
            e(
                'div',
                {
                    id: 'community-view-div'
                },
                carousel_div
            ),
            community_text
        );
        //console.log('Main div element done.');

        return main_div;

    } // render

    componentDidMount()
    {
        try 
        {
            this.cycleThroughElements();
            $('#obem-community-description').append(this.props.community_description);
            this.resizeCaroussel();
            window.addEventListener('resize', (e) => {
                this.resizeCaroussel();
            });
        }
        catch(ex)
        {
            console.log('componentDidMount: ' + ex.message);
        }

    } // componentDidMount

    resizeCaroussel()
    {
        try 
        {
            let height = ($(window).height()*2)/3;
            let width = $(window).isMobile() === true ? 
                    ($(window).width()-50) :
                    (($(window).width()*1)/2);
            $('#carouselExampleIndicators').width(width);
            $('#community-view-div').hcenter();
        }
        catch(ex)
        {
            console.log('resizeCaroussel: ' + ex.message);
        }

    } // resizeCaroussel

    cycleThroughElements()
    {
        try 
        {
            const myCarouselElement = document.querySelector('#carouselExampleIndicators');
            if(myCarouselElement)
            {
                const carousel = new bootstrap.Carousel(myCarouselElement, {
                    interval: 4000,
                    wrap: true
                });
                carousel.cycle();
            }
        }
        catch(ex)
        {
            console.log('cycleThroughElements: ' + ex.message);
        }

    } // cycleThroughElements
}

ObemCommunity.propTypes = {
    obem_community_media: PropTypes.string, // stringified array of {url:, capture:, description}
    community_description: PropTypes.string,
    community_diagram_url: PropTypes.string
}