'use strict';

const e = React.createElement;

class ObemUserShow extends React.Component
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
        let profile_photo_input_div = e(
            'div', 
            {
                className: "form-group", 
                style: {padding: '5px'}
            },
            e(
                'label',
                {
                    style: {weight: 'bold'}
                },
                `${this.props.profile_photo_label}:`
            ),
            e(
                'input', 
                {
                    type: "file", 
                    name: "profile_photo_uploaded_file",
                    className: 'form-control', 
                    id: "profile_photo_edit_file"
                }
            )
        );
        let employment_folder_div = this.props.has_employment_folder === 'true' ? 
            e(
                'div',
                {
                    className: 'shadow-sm p-1 mb-2 bg-white rounded',
                    style: {margin: '10px'}
                },
                e(
                    'div',
                    {
                        style: {padding: '5px'}
                    },
                    e(
                        'p',
                        {
                            style: {'word-wrap': 'break-word'}
                        },
                        e(
                            'strong',
                            {},
                            `${this.props.address_label}: `
                        ),
                        this.props.user_address
                    ),
                    e(
                        'p', 
                        {
                            style: {'word-wrap': 'break-word'}
                        },
                        e(
                            'strong',
                            {},
                            `${this.props.phone_number_label}: `
                        ),
                        this.props.user_phone_number
                    ),
                    e(
                        'p',
                        {
                            style: {'word-wrap': 'break-word'}
                        },
                        e(
                            'strong',
                            {},
                            `${this.props.highest_degree_label}: `
                        ),
                        this.props.user_highest_degree
                    ),
                    e(
                        'p',
                        {},
                        e(
                            'a',
                            {
                                href: this.props.cv_url
                            },
                            this.props.cv_label
                        )
                    ),
                    e(
                        'p',
                        {},
                        e(
                            'a',
                            {
                                href: this.props.cover_letter_url
                            },
                            this.props.cover_letter_label
                        )
                    ),
                    e(
                        'p',
                        {},
                        // Edit - Destroy
                        this.props.view_mode === 'false' ?
                        e(
                            'div',
                            {
                                className: 'd-flex flex-row justify-content-center'
                            },
                            e(
                                'a',
                                {
                                    href: this.props.edit_employment_folder_url,
                                    style: {margin: '5px', padding: '5px', 'text-decoration': 'underline'}
                                },
                                this.props.edit_label
                            ),
                            e(
                                'a',
                                {
                                    href: this.props.destroy_employment_folder_url,
                                    style: {margin: '5px', padding: '5px', 'text-decoration': 'underline'}
                                },
                                this.props.destroy_label
                            )
                        ):
                        e('div')
                    )
                )
            ) :
            e('div');
        let profile_div = e(
            'div',
            {
                className: 'shadow-sm p-1 mb-2 bg-white rounded',
                style: {margin: '10px'}
            },
            e(
                'div',
                {
                    className: 'd-flex flex-row justify-content-start'
                },
                e( // Image div
                    'div',
                    {
                        className: 'text-center'
                    },
                    e(
                        'img',
                        {
                            className: 'img-fluid',
                            src: this.props.profile_photo_url,
                            style: {
                                height: '155px',
                                width: '155px',
                                borderRadius: '50%',
                                display: 'inline-block',
                                margin: '10px'
                             }
                        }
                    )
                ),
                e( // Bio data div
                    'div',
                    {
                        className: 'd-flex flex-column justify-content-start align-items-start',
                        style: {margin: '5px', padding: '5px'}
                    },
                    e(
                        'h3',
                        {
                            style: {fontWeight: 'bold', 'word-wrap': 'break-word'}
                        },
                        this.props.user_full_name
                    ),
                    e(
                        'p',
                        {
                            style: {'word-wrap': 'break-word'}
                        },
                        e(
                            'strong',
                            {},
                            `${this.props.user_role_label}: `
                        ),
                        this.props.user_role
                    ),
                    e(
                        'p',
                        {
                            style: {'word-wrap': 'break-word'}
                        },
                        e(
                            'strong',
                            {},
                            `${this.props.email_label}: `
                        ),
                        this.props.user_email
                    ),
                    ( // Edit - Destroy
                        this.props.view_mode === 'false' ?
                        e(
                            'div',
                            {
                                style: {margin: '5px'}
                            },
                            e(
                                'div',
                                {
                                    className: 'd-flex flex-row justify-content-center'
                                },
                                e(
                                    'a',
                                    {
                                        href: this.props.edit_user_url,
                                        style: {margin: '5px', 'text-decoration': 'underline'}
                                    },
                                    this.props.edit_label
                                ),
                                e(
                                    'a',
                                    {
                                        href: this.props.destroy_user_url,
                                        style: {margin: '5px', 'text-decoration': 'underline'}
                                    },
                                    this.props.destroy_label
                                ),
                                e(
                                    'a',
                                    {
                                        href: this.props.view_mode_url,
                                        style: {margin: '5px', 'text-decoration': 'underline'}
                                    },
                                    this.props.view_mode_label
                                )
                            )
                        ) :
                        e('div') 
                    )
                )
            ),
            ( // Upload profile photo
                this.props.view_mode === 'false' ?
                e(
                    'div',
                    {
                        id: 'obem-user-profile-photo-div'
                    },
                    e(
                        'form',
                        {
                            role: 'form',
                            encType: 'multipart/form-data',
                            name: 'obem_profile_photo_edit_form',
                            id: 'obem-profile-photo-edit-form',
                            action: this.props.upload_profile_photo_action_url,
                            style: { backgroundColor: '#a68353' }
                        },
                        csrf_token,
                        profile_photo_input_div,
                        submit_div
                    )
                ):
                e('div')                   
            ),
            employment_folder_div
        );

        let main_div = e(
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
                        className: 'col-md-8'
                    },    
                    profile_div
                )
            )
        );

        return main_div;

    } // render

    componentDidMount()
    {
        this.hijackFormSubmitEvent();
    }

    hijackFormSubmitEvent()
    {
        try 
        {
            //console.log("processing sign up form.");
            var $form = $('#obem-profile-photo-edit-form');
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
                    //console.log(`E-mail:${document.obem_user_sign_up_form.email.value}`);
                    // See 
                    var form_data = new FormData();
                    form_data.append(
                        document.obem_profile_photo_edit_form._token.name, 
                        document.obem_profile_photo_edit_form._token.value
                    );
                    form_data.append(
                        document.getElementById('profile_photo_edit_file').name, 
                        document.getElementById('profile_photo_edit_file').files[0]
                    );

                    //console.log(`${document.obem_profile_photo_edit_form._token.name}: ${document.obem_profile_photo_edit_form._token.value}`);
                    
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
                        $('#obem-user-profile-photo-div').prepend(html);
                    };

                    //console.log(`URL: ${url}, Data to send: ${dataToSend}`);

                    var typeOfDataToReceive = 'json';
                    $.ajax
                    ({
                        url: url,
                        type: 'POST',
                        data: dataToSend,
                        async: true,
                        cache: false,
                        contentType: false,
                        enctype: 'multipart/form-data',
                        processData: false
                    })
                    .done(callback)
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
                        $('#obem-user-profile-photo-div').prepend(html);
                    });
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
                    $('#obem-user-profile-photo-div').prepend(html);
                }
            });
        }
        catch(error) 
        {
            console.log(`Exception: ${error.message}`);
        }

    } // hijackFormSubmitEvent
}

ObemUserShow.propTypes = {
    cover_letter_label: PropTypes.string,
    cover_letter_url: PropTypes.string,
    cv_label: PropTypes.string,
    cv_url: PropTypes.string,
    highest_degree_label: PropTypes.string,
    user_highest_degree: PropTypes.string,
    phone_number_label: PropTypes.string,
    user_phone_number: PropTypes.string,
    address_label: PropTypes.string,
    user_address: PropTypes.string,
    has_employment_folder: PropTypes.string,
    upload_profile_photo_action_url: PropTypes.string,
    view_mode: PropTypes.string,
    view_mode_label: PropTypes.string,
    view_mode_url: PropTypes.string,
    destroy_label: PropTypes.string,
    destroy_user_url: PropTypes.string,
    destroy_employment_folder_url: PropTypes.string,
    edit_label: PropTypes.string,
    edit_user_url: PropTypes.string,
    edit_employment_folder_url: PropTypes.string,
    email_label: PropTypes.string,
    user_email: PropTypes.string,
    user_role_label: PropTypes.string,
    user_role: PropTypes.string,
    user_full_name: PropTypes.string,
    profile_photo_url: PropTypes.string,
    profile_photo_label: PropTypes.string,
    submit_label: PropTypes.string,
    csrf_token: PropTypes.string
};