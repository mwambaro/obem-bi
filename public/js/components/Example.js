'use strict';

const e = React.createElement;

class Example extends React.Component
{
    constructor(props)
    {
        super(props);

    } // constructor

    render()
    {
        let elt = e(
            'div', {className: 'container'},
            e('div', {className: 'row justify-content-center'},
            e('div', {className: 'col-md-8'},
            e('div', {className: 'card'}, 
            [   
                e('div', {className: 'card-header'}, this.props.card_title), 
                e('div', {className: 'card-body'}, 
                    [
                        e('div', {className: 'text-center'}, this.props.card_body),
                        e('div', {className: 'text-center'}, e('img', {src: this.props.card_image, className: 'img-fluid'}))
                    ]
                )
            ]
        ))));
    
        return (elt);

    } // render
}

Example.propTypes = {
    card_title: PropTypes.string,
    card_body: PropTypes.string,
    card_image: PropTypes.string
}

//export default Example;
/** 
if (document.getElementById('example')) {
    ReactDOM.render(e(Example), document.getElementById('example'));
}
**/