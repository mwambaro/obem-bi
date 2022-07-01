'use strict';

const e = React.createElement;

class ObemArticleMediaUpload extends React.Component 
{
    constructor(props)
    {
        super(props);

        this.all_html = "";
        this.number_of_files = 0;
        this.number_of_responses = 0;
        this.file_names = new Array();
        this.uploads_response_event = 'all-uploads-responded-to';

    } // constructor

    render()
    {
        let container = $(document).isMobile() === true ? 
                        'container-fluid' : 
                        'container';
        let spinner = e(
            'div',
            {
                id: 'obem-wait-uploads-spinner',
                style: {
                    display: 'none',
                    zIndex: '99',
                    border: 'none',
                    outline: 'none',
                    backgroundColor: 'transparent',
                    position: 'fixed'
                }
            },
            e(
                'div',
                {
                    role: 'status',
                    className: 'spinner-border text-success',
                    style: { width: '100px', height: '100px'}
                },
                e(
                    'span',
                    {
                        className: 'sr-only'
                    },
                    'Wait...'
                )
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
        let input_div = e(
            'div', 
            {
                className: 'form-group',
                style: {padding: '5px'}
            },
            e(
                'input', 
                {
                    type: 'file', 
                    name: 'uploaded_site_media_file', 
                    className: 'form-control', 
                    multiple: true,
                    id: 'capture_site_media_upload_file',
                    accept: 'audio/*, video/*, image/*'
                }
            )
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
                    className: 'col-md-8 shadow-sm p-1 mb-2 bg-white rounded',
                    id: 'obem_site_media_upload_main_div',
                    style: { padding: '10px' }
                },
                [
                    e(
                        'h3',
                        { className: 'text-center' },
                        this.props.obem_site_media_upload_form_title
                    ),
                    e(
                        'form',
                        {
                            role: 'form',
                            encType: 'multipart/form-data',
                            name: 'obem_site_media_upload_form',
                            id: 'obem-site-media-upload-form',
                            action: this.props.obem_media_upload_endpoint,
                            style: { backgroundColor: '#a68353' }
                        },
                        [csrf_token, input_div, submit_div]
                    ),
                    spinner
                ]
            )
        );

        let outer_div = e(
            'div',
            {
                className: container,
                id: 'outermost-div-container'
            },
            form_div
        );

        return outer_div;

    } // render

    componentDidMount()
    {
        this.hide_wait_spinner();
        this.hijackFormSubmitEvent();
        window.addEventListener(this.uploads_response_event, (e) => {
            this.hide_wait_spinner();
            this.send_uploads_completed_message(e.data);
        });

    } // componentDidMount

    reset_variables()
    {
        this.all_html = "";
        this.number_of_files = 0;
        this.number_of_responses = 0;
        this.file_names = new Array();
    }

    fire_uploads_responded_to_event(data)
    {
        // Fire event
        const event_name = this.uploads_response_event;
        const event = new Event(
            event_name, 
            {
                bubbles: true,
                cancelable: false,
                composed: true
            }
        );
        event.data = data;

        let form = document.getElementById('obem-site-media-upload-form');
        form.dispatchEvent(event);

    } // fire_uploads_responded_to_event

    give_feedback_to_user(html)
    {
        let all_html = `
            <div id="all-verbose-message" class="shadow-sm p-1 mb-2 bg-white rounded verbose-message-div" style="margin: 10px">
                <div style="padding: 10px">
                    ${html}
                </div>
            </div>
        `
        $('.verbose-message-div').remove();
        $('#obem_site_media_upload_main_div').prepend(all_html);
        scroll_element_into_view('outermost-div-container');

    } // give_feedback_to_user

    show_wait_spinner()
    {
        let spinner = document.getElementById('obem-wait-uploads-spinner');
        if(spinner)
        {
            spinner.style.display = "block";
            $('#obem-wait-uploads-spinner').css({  
                position: 'absolute',
                top: '50%',
                left: '50%',
                transform: 'translate(-50%, -50%)'
            });
            $('body').css('opacity', '0.5');
            this.center_spinner_in_the_viewport();
        }

    } // show_wait_spinner

    hide_wait_spinner()
    {
        let spinner = document.getElementById('obem-wait-uploads-spinner');
        if(spinner)
        {
            spinner.style.position = 'fixed';
            spinner.style.display = "none";
            $('body').css('opacity', '1.0');
        }

    } // hide_wait_spinner

    center_spinner_in_the_viewport()
    {
        var viewportWidth = jQuery(window).width(),
        viewportHeight = jQuery(window).height(),
        $foo = jQuery('#obem-wait-uploads-spinner'),
        elWidth = $foo.width(),
        elHeight = $foo.height(),
        elOffset = $foo.offset();
        jQuery(window)
            .scrollTop(elOffset.top + (elHeight/2) - (viewportHeight/2))
            .scrollLeft(elOffset.left + (elWidth/2) - (viewportWidth/2));

    } // center_spinner_in_the_viewport

    send_uploads_completed_message(data)
    {
        var typeOfDataToReceive = 'json';
        var dataToSend = {
            uploads_completed: 'true',
            _token: this.props.csrf_token
        };
        var callback = (data_received) => 
        {
            let message = data_received.message;
            let html = `
            <div class="row verbose-message-div" style="background-color: white; padding: 10px">
                <div class="col-sm-1 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="green" class="bi bi-check-circle" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                        <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
                    </svg>
                </div>
                <div class="col-sm-11"> <p> All Files Uploaded </p> <p> ${message} </p> </div>
            </div>`;
            this.give_feedback_to_user(data+html);
            this.reset_variables();
        };

        var url = this.props.obem_media_upload_endpoint;
        $.post(url, dataToSend, callback, typeOfDataToReceive)
        .fail((error) =>
        {
            let message = "Failed to post form: " + error.status + "; " + error.statusText;
            let html = `
                <div class="row verbose-message-div">
                    <div class="col-sm-1 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="red" class="bi bi-x-circle" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                        </svg>
                    </div>
                    <div class="col-sm-11"><p>All Files Uploaded</p> <p> ${message} </p> </div>
                </div>`;
            this.give_feedback_to_user(data+html);
            this.reset_variables();
        });

    } // send_uploads_completed_message

    hijackFormSubmitEvent()
    {
        try 
        {
            //console.log("processing sign up form.");
            var $form = $('#obem-site-media-upload-form');
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
                    var files = document.getElementById('capture_site_media_upload_file').files;
                    this.number_of_files = files.length;
                    for(let i=0; i<this.number_of_files; i++) 
                    {
                        var file = files[i];
                        this.file_names.push(file.name);
                        var form_data = new FormData();
                        form_data.append(
                            document.getElementById('capture_site_media_upload_file').name, file
                        );
                        form_data.append(
                            document.obem_site_media_upload_form._token.name, 
                            document.obem_site_media_upload_form._token.value
                        );
                    
                        var dataToSend = form_data;
                        var callback = (dataReceived) => {
                            try 
                            {
                                // use the data received
                                //console.log(`RECEIVED: ${JSON.stringify(dataReceived)}`);
                                let data = dataReceived;
                                let code = data.code;
                                let message = data.message;
                                let html = '';
                                if(code === 1) // success
                                {
                                    let index = this.number_of_responses;
                                    let filename = this.file_names[index];
                                    html = `
                                        <div class="row verbose-message-div" style="background-color: white; padding: 10px">
                                            <div class="col-sm-1 text-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="green" class="bi bi-check-circle" viewBox="0 0 16 16">
                                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                                    <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
                                                </svg>
                                            </div>
                                            <div class="col-sm-11"> <p> ${index+1}. ${filename}</p> <p> ${message} </p> </div>
                                        </div>`;
                                    this.all_html += html;
                                }
                                else // failure
                                {
                                    let index = this.number_of_responses;
                                    let filename = this.file_names[index];
                                    html = `
                                        <div class="row verbose-message-div">
                                            <div class="col-sm-1 text-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="red" class="bi bi-x-circle" viewBox="0 0 16 16">
                                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                                </svg>
                                            </div>
                                            <div class="col-sm-11"> <p> ${index+1}. ${filename}</p> <p> ${message} </p> </div>
                                        </div>`;
                                    this.all_html += html;
                                }
                            }
                            catch(e)
                            {
                                this.hide_wait_spinner();
                                let message = `${e.name}: ${e.message}`;
                                let index = this.number_of_responses;
                                let filename = this.file_names[index];
                                let html = `
                                    <div class="row verbose-message-div">
                                        <div class="col-sm-1 text-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="red" class="bi bi-x-circle" viewBox="0 0 16 16">
                                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                            </svg>
                                        </div>
                                        <div class="col-sm-11"> <p> ${index+1}. ${filename}</p> <p> ${message} </p> </div>
                                    </div>`;
                                this.all_html += html;
                            }

                            this.number_of_responses += 1;
                            if(this.number_of_files === this.number_of_responses)
                            {
                                this.fire_uploads_responded_to_event(this.all_html);
                            }
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
                            try 
                            {
                                let index = this.number_of_responses;
                                let filename = this.file_names[index];
                                let message = "Failed to post sign up form: " + error.status + "; " + error.statusText;
                                let html = `
                                    <div class="row verbose-message-div">
                                        <div class="col-sm-1 text-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="red" class="bi bi-x-circle" viewBox="0 0 16 16">
                                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                            </svg>
                                        </div>
                                        <div class="col-sm-11"><p>${index+1}. ${filename}</p> <p> ${message} </p> </div>
                                    </div>`;
                                this.all_html += html;
                            }
                            catch(e)
                            {
                                let message = `Failed to post sign up form clause: ${e.message}`;
                                let index = this.number_of_responses;
                                let filename = this.file_names[index];
                                let html = `
                                    <div class="row verbose-message-div">
                                        <div class="col-sm-1 text-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="red" class="bi bi-x-circle" viewBox="0 0 16 16">
                                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                            </svg>
                                        </div>
                                        <div class="col-sm-11"><p>${index+1}. ${filename}</p> <p> ${message} </p> </div>
                                    </div>`;
                                this.all_html += html;
                            }

                            this.number_of_responses += 1;
                            if(this.number_of_files === this.number_of_responses)
                            {
                                this.fire_uploads_responded_to_event(this.all_html);
                            }
                        });
                    };

                    this.show_wait_spinner();
                }
                catch(error)
                {
                    this.hide_wait_spinner();
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
                    $('#obem_site_media_upload_main_div').prepend(html);
                }
            });
        }
        catch(error) 
        {
            this.hide_wait_spinner();
            console.log(`Exception: ${error.message}`);
        }

    } // hijackFormSubmitEvent
}

ObemArticleMediaUpload.propTypes = {
    obem_site_media_upload_form_title: PropTypes.string,
    obem_media_upload_endpoint: PropTypes.string,
    submit_label: PropTypes.string,
    csrf_token: PropTypes.string
};