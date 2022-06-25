'use strict';

const e = React.createElement;

class ObemUserSignIn extends React.Component 
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
        let email_input_div = e(
            'div', 
            {
                className: "form-group", 
                style: {padding: '5px'}
            },
            e(
                'input', 
                {
                    type: "text", 
                    name: "email",
                    className: 'form-control', 
                    id: "obem-user-sign-in-email",
                    placeholder: this.props.email_label
                }
            )
        );
        let password_input_div = e(
            'div', 
            {
                className: "form-group", 
                style: {padding: '5px'}
            },
            e(
                'input', 
                {
                    type: "password", 
                    name: "password",
                    className: 'form-control', 
                    id: "obem-user-sign-in-password",
                    placeholder: this.props.password_label
                }
            )
        );
        let csrf_token = e(
            'input',
            {
                type: 'hidden',
                name: '_token',
                value: this.props.csrf_token
            }
        );
        let submit_div = e(
            'div',
            {
                className: 'text-center',
                style: { padding: '5px' }
            },
            e(
                'button',
                {
                    type: 'submit',
                    className: 'btn btn-default',
                    style: { backgroundColor: 'white' }
                },
                this.props.submit_label
            )
        );
        let form_div = e(
            'div',
            {
                className: 'row justify-content-center'
            },
            e(
                'div',
                {
                    id: 'obem-user-sign-in-form-div',
                    className: `col-md-8 shadow p-3 mb-5 bg-body rounded`,
                    style: {padding: '10px'}
                },
                e(
                    'h3',
                    { className: 'text-center' },
                    this.props.obem_sign_in_form_title
                ),
                e(
                    'form',
                    {
                        role: 'form',
                        encType: 'multipart/form-data',
                        name: 'obem_user_sign_in',
                        id: 'obem-user-sign-in-form',
                        action: this.props.user_sign_in_endpoint,
                        style: { backgroundColor: '#944653' }
                    },
                    csrf_token,
                    email_input_div,
                    password_input_div,
                    submit_div
                ),
                e(
                    'div',
                    {
                        className: 'd-flex flex-row justify-content-end',
                        style: {margin: '10px', padding: '5px'}
                    },
                    e(
                        'a',
                        {
                            href: this.props.new_user_url
                        },
                        this.props.sign_up_label
                    )
                )
            )
        );

        let outer_div = e(
            'div',
            {
                className: container
            },
            form_div
        );

        return outer_div;

    } // render

    componentDidMount()
    {
        this.hijackFormSubmitEvent();

    } // componentDidMount

    hijackFormSubmitEvent()
    {
        try 
        {
            //console.log("processing sign up form.");
            var $form = $('#obem-user-sign-in-form');
            $form.submit((event) => {
                try 
                {
                    event.preventDefault();
                    var $this = $form;
                    // Validation code
                    //...
                    // this tells the server-side process that Ajax was used
                    $('input[name="usingAJAX"]',$this).val('true');
                    var url = $this.attr('action');
                    //console.log(`E-mail:${document.obem_user_sign_in.email.value}`);
                    // See 
                    var form_data = {
                        email: document.obem_user_sign_in.email.value,
                        password: document.obem_user_sign_in.password.value,
                        _token: document.obem_user_sign_in._token.value
                    };

                    //console.log('Data to send formatted');
                    
                    var dataToSend = form_data;
                    var callback = (dataReceived) => {
                        // use the data received
                        //console.log(`RECEIVED: ${JSON.stringify(dataReceived)}`);
                        let data = dataReceived;
                        let code = data.code;
                        let message = data.message;
                        let html = '';
                        if(code === 1) // success
                        {
                            $this.hide();
                            html = `
                                <div class="row" style="background-color: white; padding: 10px" id="verbose-message-div">
                                    <div class="col-sm-1 text-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="green" class="bi bi-check-circle" viewBox="0 0 16 16">
                                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                            <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
                                        </svg>
                                    </div>
                                    <div class="col-sm-11"> <p> ${message} </p> </div>
                                </div>`;
                        }
                        else // failure
                        {
                            html = `
                                <div class="row" id="verbose-message-div">
                                    <div class="col-sm-1 text-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="red" class="bi bi-x-circle" viewBox="0 0 16 16">
                                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                        </svg>
                                    </div>
                                    <div class="col-sm-11"> <p> ${message} </p> </div>
                                </div>`;
                        }

                        $('#verbose-message-div').remove();
                        //console.log('Feedback message removed');
                        $('#obem-user-sign-in-form-div').prepend(html);
                    };

                    //console.log(`URL: ${url}, Data to send: ${dataToSend}`);

                    var typeOfDataToReceive = 'json';
                    $.post(url, dataToSend, callback, typeOfDataToReceive)
                    .fail((error) => {
                        let message = `Failed to post sign up form: ${error.status}; ${error.statusText}`;
                        let html = `
                            <div class="row" id="verbose-message-div">
                                <div class="col-sm-1 text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="red" class="bi bi-x-circle" viewBox="0 0 16 16">
                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                    </svg>
                                </div>
                                <div class="col-sm-11"> <p> ${message} </p> </div>
                            </div>`;
                        $('#verbose-message-div').remove();
                        $('#obem-user-sign-in-form-div').prepend(html);
                    });

                    //console.log('Form submitted');
                }
                catch(error)
                {
                    let message = `Exception submit form function: ${error.message}`;
                    let html = `
                        <div class="row" id="verbose-message-div">
                            <div class="col-sm-1 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="red" class="bi bi-x-circle" viewBox="0 0 16 16">
                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                </svg>
                            </div>
                            <div class="col-sm-11"> <p> ${message} </p> </div>
                        </div>`;
                    $('#verbose-message-div').remove();
                    $('#obem-user-sign-in-form-div').prepend(html);
                }
            });
        }
        catch(error) 
        {
            console.log(`Exception: ${error.message}`);
        }

    } // hijackFormSubmitEvent
}

ObemUserSignIn.propTypes = {
    user_sign_in_endpoint: PropTypes.string,
    new_user_url: PropTypes.string,
    csrf_token: PropTypes.string,
    obem_sign_in_form_title: PropTypes.string,
    email_label: PropTypes.string,
    password_label: PropTypes.string,
    sign_up_label: PropTypes.string,
    submit_label: PropTypes.string
};