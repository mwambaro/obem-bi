'use strict';

const e = React.createElement;

class ObemNewArticle extends React.Component 
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
        
        let update_article_note = e(
            'div'
        );
        if(this.props.should_update === 'true')
        {
            update_article_note = e(
                'div',
                {
                    className: 'text-primary',
                    style: {margin: '5px'}
                },
                e(
                    'p',
                    {
                        className: 'text-primary',
                        style: {margin: '5px', padding: '5px'}
                    },
                    this.props.update_article_note
                )
            )
        }

        let capture_input_div = e(
            'div',
            {
                className: 'form-group',
                style: { padding: '5px' }
            },
            e(
                'input',
                {
                    className: 'form-control',
                    id: 'site_article_new_capture',
                    placeholder: this.props.capture_label,
                    type: 'text',
                    name: 'capture'
                }
            )
        );
        let date_input_div = e(
            'div',
            {
                className: 'form-group',
                style: { padding: '5px' }
            },
            e(
                'input',
                {
                    className: 'form-control',
                    id: 'site_article_new_date',
                    placeholder: this.props.date_label,
                    type: 'text',
                    name: 'date'
                }
            )
        );
        let body_textarea_div = e(
            'div',
            {
                className: 'form-group',
                style: { padding: '5px' }
            },
            e(
                'textarea',
                {
                    className: 'form-control',
                    id: 'site_article_new_body',
                    placeholder: this.props.body_placeholder_text,
                    rows: '10',
                    name: 'body'
                }
            )
        );
        let select_options = [];
        JSON.parse(this.props.supported_languages).map((lang, idx) =>
        {
            let option = e(
                'option',
                {
                    key: `obem-site-article-new-lang-${idx}`,
                    value: lang.locale
                },
                `${lang.language} (${lang.country})`
            );
            select_options.push(option);
        });
        let supported_languages_select_div = e(
            'div',
            {
                className: 'form-group',
                style: { padding: '5px' }
            },
            e(
                'select',
                {
                    className: 'form-select',
                    'aria-label': 'Languages select',
                    id: 'obem-site-article-new-select-lang'
                },
                select_options
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
        let csrf_token = e(
            'input',
            {
                type: 'hidden',
                name: '_token',
                value: this.props.csrf_token
            }
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
                    id: 'obem_site_article_new_main_div',
                    style: { padding: '10px' }
                },
                [
                    e(
                        'h3',
                        { className: 'text-center' },
                        this.props.obem_site_article_new_form_title
                    ),
                    e(
                        'form',
                        {
                            role: 'form',
                            encType: 'multipart/form-data',
                            name: 'obem_site_article_new_form',
                            id: 'obem-site-article-new-form',
                            action: this.props.obem_article_create_endpoint,
                            style: { backgroundColor: '#a68353' }
                        },
                        [
                            csrf_token, capture_input_div, date_input_div, 
                            supported_languages_select_div, body_textarea_div,  
                            submit_div
                        ]
                    ),
                    update_article_note
                ]
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
    }

    manageUpdateMode()
    {
        try 
        {
            if(this.props.should_update === 'true')
            {
                //console.log("JSON: " + this.props.article);
                let article = JSON.parse(this.props.article);
                if(article)
                {
                    document.obem_site_article_new_form.capture.value = article.capture;
                    document.obem_site_article_new_form.body.value = article.body.replaceAll('<##>', "\n");
                    document.obem_site_article_new_form.date.value = article.date;
                    document.getElementById('obem-site-article-new-select-lang').value = article.locale;
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
            var $form = $('#obem-site-article-new-form');
            $form.submit((event) => {
                try 
                {
                    event.preventDefault();
                    var $this = $form;
                    // Validation code
                    //...
                    // this tells the server-side process that Ajax was used
                    $('input[name="usingAJAX"]',$this).val('true');
                    let url = $this.attr('action');
                    //console.log(`E-mail:${document.laastras_user_sign_up_form.email.value}`);
                    var form_data = {
                        capture: document.obem_site_article_new_form.capture.value,
                        locale: document.getElementById('obem-site-article-new-select-lang').value,
                        date: document.obem_site_article_new_form.date.value,
                        body: document.obem_site_article_new_form.body.value,
                        _token: document.obem_site_article_new_form._token.value
                    };
                    if(this.props.article_guid != '')
                    {
                        form_data['article_guid'] = this.props.article_guid;
                    }

                    var dataToSend = form_data;
                    var callback = (dataReceived, status, xq) => {
                        // use the data received
                        let code = parseInt(dataReceived.code);
                        let message = dataReceived.message;
                        let html = '';
                        if(code === 1) // success
                        {
                            $this.hide();
                            html = `
                                <div class="row" style="background-color: white; padding: 10px">
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
                                <div class="row">
                                    <div class="col-sm-1 text-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="red" class="bi bi-x-circle" viewBox="0 0 16 16">
                                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                        </svg>
                                    </div>
                                    <div class="col-sm-11"> <p> ${message} </p> </div>
                                </div>`;
                        }
                        //console.log('Feedback message removed');
                        let all_html = `
                            <div class="shadow-sm p-1 mb-2 bg-white rounded" id="verbose-message-div" style="margin: 10px">
                                <div style="padding: 10px">
                                    ${html}
                                </div>
                            </div>
                        `;
                        $('#verbose-message-div').remove();
                        $('#obem_site_article_new_main_div').prepend(all_html);
                    };

                    //console.log(`URL: ${url}, Data to send: ${dataToSend}`);

                    var typeOfDataToReceive = 'json';
                    $.post(url, dataToSend, callback, typeOfDataToReceive)
                    .fail((error) => {
                        let message = "Failed to post sign up form: " + error.status + "; " + error.statusText;
                        let html = `
                            <div class="row">
                                <div class="col-sm-1 text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="red" class="bi bi-x-circle" viewBox="0 0 16 16">
                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                    </svg>
                                </div>
                                <div class="col-sm-11"> <p> ${message} </p> </div>
                            </div>`;
                        let all_html = `
                            <div class="shadow-sm p-1 mb-2 bg-white rounded" id="verbose-message-div" style="margin: 10px">
                                <div style="padding: 10px">
                                    ${html}
                                </div>
                            </div>
                        `;
                        $('#verbose-message-div').remove();
                        $('#obem_site_article_new_main_div').prepend(all_html);
                    });
                }
                catch(error)
                {
                    let message = `Exception submit form function: ${error.message}`;
                    let html = `
                        <div class="row">
                            <div class="col-sm-1 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="red" class="bi bi-x-circle" viewBox="0 0 16 16">
                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                </svg>
                            </div>
                            <div class="col-sm-11"> <p> ${message} </p> </div>
                        </div>`;
                    let all_html = `
                        <div class="shadow-sm p-1 mb-2 bg-white rounded" id="verbose-message-div" style="margin: 10px">
                            <div style="padding: 10px">
                                ${html}
                            </div>
                        </div>
                    `;
                    $('#verbose-message-div').remove();
                    $('#obem_site_article_new_main_div').prepend(all_html);
                }
            });
        }
        catch(error) 
        {
            console.log(`Exception: ${error.message}`);
        }

    } // hijackFormSubmitEvent
}

ObemNewArticle.propTypes = {
    capture_label: PropTypes.string,
    date_label: PropTypes.string,
    body_placeholder_text: PropTypes.string,
    supported_languages: PropTypes.string, // stringified array of {locale:, language:, country:}
    should_update: PropTypes.string, // 'true' or 'false'
    submit_label: PropTypes.string,
    obem_site_article_new_form_title: PropTypes.string,
    obem_article_create_endpoint: PropTypes.string,
    update_article_note: PropTypes.string,
    article_guid: PropTypes.string,
    article: PropTypes.string, // stringified article model {capture:, locale:, body:, date}
    csrf_token: PropTypes.string
};