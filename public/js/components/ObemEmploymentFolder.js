'use strict';

const e = React.createElement;

class ObemEmploymentFolder extends React.Component 
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

        let address_input_div = e(
            'div', 
            {
                className: "form-group", 
                style: {padding: '5px'}
            },
            e(
                'input', 
                {
                    type: "text", 
                    name: "address",
                    className: 'form-control', 
                    id: "obem-employment-folder-address",
                    placeholder: this.props.address_label
                }
            )
        );
        let phone_number_input_div = e(
            'div', 
            {
                className: "form-group", 
                style: {padding: '5px'}
            },
            e(
                'input', 
                {
                    type: "text", 
                    name: "phone_number",
                    className: 'form-control', 
                    id: "obem-employment-folder-phone-number",
                    placeholder: this.props.phone_number_label
                }
            )
        );
        let highest_degree_input_div = e(
            'div', 
            {
                className: "form-group", 
                style: {padding: '5px'}
            },
            e(
                'input', 
                {
                    type: "text", 
                    name: "highest_degree",
                    className: 'form-control', 
                    id: "obem-employment-folder-highest-degree",
                    placeholder: this.props.highest_degree_label
                }
            )
        );
        let cv_input_div = e(
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
                `${this.props.cv_label}:`
            ),
            e(
                'input', 
                {
                    type: "file", 
                    name: "cv_uploaded_file",
                    className: 'form-control', 
                    id: "obem-employment-folder-cv"
                }
            )
        );
        let cover_letter_input_div = e(
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
                `${this.props.cover_letter_label}:`
            ),
            e(
                'input', 
                {
                    type: "file", 
                    name: "cover_letter_uploaded_file",
                    className: 'form-control', 
                    id: "obem-employment-folder-cover-letter"
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
                    id: 'obem-employment-folder-form-div',
                    className: `col-md-8 shadow p-3 mb-5 bg-body rounded`,
                    style: {padding: '10px'}
                },
                e(
                    'h3',
                    { className: 'text-center' },
                    this.props.obem_employment_folder_form_title
                ),
                e(
                    'form',
                    {
                        role: 'form',
                        encType: 'multipart/form-data',
                        name: 'obem_employment_folder_form',
                        id: 'obem-employment-folder-form',
                        action: this.props.employment_folder_create_endpoint,
                        style: { backgroundColor: '#a68353' }
                    },
                    csrf_token,
                    address_input_div,
                    phone_number_input_div,
                    highest_degree_input_div,
                    cv_input_div,
                    cover_letter_input_div,
                    submit_div
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
        this.manageUpdateMode();
        this.hijackFormSubmitEvent();

    } // componentDidMount

    manageUpdateMode()
    {
        try 
        {
            if(this.props.should_update === 'true')
            {
                //console.log("JSON: " + this.props.stringified_employment_folder);
                let folder = JSON.parse(this.props.stringified_employment_folder);
                if(folder)
                {
                    document.obem_employment_folder_form.address.value = folder.address;
                    document.obem_employment_folder_form.phone_number.value = folder.phone_number;
                    document.obem_employment_folder_form.highest_degree.value = folder.highest_degree;
                }
            }
        }
        catch(error)
        {
            console.log('manageUpdateMode: ' + error.message);
        }

    } // manageUpdateMode

    hijackFormSubmitEvent()
    {
        try 
        {
            //console.log("processing sign up form.");
            var $form = $('#obem-employment-folder-form');
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
                        document.obem_employment_folder_form.address.name, 
                        document.obem_employment_folder_form.address.value
                    );
                    form_data.append(
                        document.obem_employment_folder_form.phone_number.name, 
                        document.obem_employment_folder_form.phone_number.value
                    );
                    form_data.append(
                        document.obem_employment_folder_form.highest_degree.name, 
                        document.obem_employment_folder_form.highest_degree.value
                    );
                    form_data.append(
                        document.obem_employment_folder_form._token.name, 
                        document.obem_employment_folder_form._token.value
                    );
                    form_data.append(
                        document.getElementById('obem-employment-folder-cv').name, 
                        document.getElementById('obem-employment-folder-cv').files[0]
                    );
                    form_data.append(
                        document.getElementById('obem-employment-folder-cover-letter').name, 
                        document.getElementById('obem-employment-folder-cover-letter').files[0]
                    );
                    
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
                        $('#obem-employment-folder-form-div').prepend(html);
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
                        $('#obem-employment-folder-form-div').prepend(html);
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
                    $('#obem-employment-folder-form-div').prepend(html);
                }
            });
        }
        catch(error) 
        {
            console.log(`Exception: ${error.message}`);
        }

    } // hijackFormSubmitEvent
}

ObemEmploymentFolder.propTypes = {
    employment_folder_create_endpoint: PropTypes.string,
    stringified_employment_folder: PropTypes.string,
    should_update: PropTypes.string,
    update_employment_folder_note: PropTypes.string,
    csrf_token: PropTypes.string,
    obem_employment_folder_form_title: PropTypes.string,
    address_label: PropTypes.string,
    phone_number_label: PropTypes.string,
    highest_degree_label: PropTypes.string,
    submit_label: PropTypes.string,
    cv_label: PropTypes.string,
    cover_letter_label: PropTypes.string
};