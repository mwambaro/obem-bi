'use strict';

//const e = React.createElement;

class ObemNavigationBar extends React.Component 
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
        let div_flex_style = {
            backgroundColor: 'white',
            margin: '5px'
        };
        let btn_flex_style = {
            backgroundColor: 'black',
            margin: '5px',
            fontColor: 'white',
            fontWeight: 'bold'
        };

        let navbar_brand = e(
            'a', 
            {
                className: "navbar-brand", 
                href: this.props.home_url
            },
            'OBEM'
        );

        let navbar_toggler = e(
            'button', 
            {
                className: "navbar-toggler", 
                type: "button",
                'data-bs-toggle': "collapse", 
                'data-bs-target': "#navbarNavDropdown", 
                'aria-controls': "navbarNavDropdown", 
                'aria-expanded': "false",
                'aria-label': "Toggle navigation"
            },
            e(
                'span', 
                {
                    className: "navbar-toggler-icon"
                }
            )
        );

        let navbar_items = [];
        JSON.parse(this.props.obem_navigation_bar_actions).map((action, idx) =>
        {
            let inner_dropdown_items = [];
            if(action.dropdown_boolean === 'true')
            {
                action.data.map((a, i) =>
                {
                    let inner_item = e(
                        'li', 
                        {
                            key: `dropdown-item-${idx}${i}`
                        },
                        e(
                            'a', 
                            {
                                className: "dropdown-item",
                                href: a.url
                            },
                            a.inner_text
                        )
                    );
                    inner_dropdown_items.push(inner_item);
                });
            }
            let main_item = e(
                'li', 
                {
                    key: `nav-item-${idx}`,
                    className: "nav-item"
                },
                action.dropdown_boolean === 'true' ?
                e(
                    'li', 
                    {
                        className: "nav-item dropdown"
                    },
                    e(
                        'a', 
                        {
                            className: "nav-link dropdown-toggle", 
                            href: "#",
                            id: `navbarDropdownMenuLink${idx}`, 
                            role: "button",
                            'data-bs-toggle': "dropdown", 
                            'aria-expanded': "false"
                        },
                        action.inner_text
                    ),
                    e(
                        'ul', 
                        {
                            className: "dropdown-menu", 
                            'aria-labelledby': `navbarDropdownMenuLink${idx}`
                        },
                        inner_dropdown_items
                    )
                ) :
                e(
                    'a', 
                    {
                        className: "nav-link", 
                        href: action.url
                    },
                    action.inner_text
                )
            );
            
            navbar_items.push(main_item);
        });
        let navbar_div = e(
            'div',
            {
                className: "collapse navbar-collapse", 
                id: "navbarNavDropdown"
            },
            e(
                'ul',
                {
                    className: "navbar-nav me-auto mb-2 mb-lg-0"
                },
                navbar_items
            ),
            e(
                'div', 
                {
                    className: "d-flex justify-content-end"
                },
                (
                    this.props.obem_user_is_logged_in === 'true' ?
                    e(
                        'button', 
                        {
                            className: "btn btn-primary",
                            type: "button",
                            style: btn_flex_style,
                            onClick: (se) => this.onClickSignOutButton(se)
                        },
                        this.props.sign_out_label
                    ):
                    e(
                        'div',
                        {},
                        e(
                            'button', 
                            {
                                className: "btn btn-primary", 
                                type: "button",
                                style: btn_flex_style,
                                onClick: (se) => this.onClickSignInButton(se)
                            },
                            this.props.sign_in_label
                        ),
                        e(
                            'button', 
                            {
                                className: "btn btn-primary",
                                type: "button",
                                style: btn_flex_style,
                                onClick: (se) => this.onClickSignUpButton(se)
                            },
                            this.props.sign_up_label
                        )
                    )
                ),
                e(
                    'div', 
                    {
                        style: {
                            backgroundColor: 'white',
                            margin: '5px'
                        }
                    },
                    e(
                        ObemLocaleSettings, 
                        {
                            locale_end_point: this.props.locale_end_point,
                            supported_languages: this.props.supported_languages,
                            active_language_locale: this.props.active_language_locale,
                            csrf_token: this.props.csrf_token
                        }
                    )
                ),
                this.props.obem_user_is_logged_in === 'true' ?
                e(
                    'div',
                    {},
                    e( 
                        'img', 
                        {
                            src: this.props.profile_photo_url,
                            id: "obem-user-profile-photo",
                            className: "img-fluid",
                            style: {
                                width: '50px',
                                height: '50px',
                                borderRadius: '50%',
                                display: 'inline-block'
                            }
                        }
                    )
                ):
                e(
                    'div'
                )
            )
        );

        let navbar_main = e(
            'nav', 
            {
                className: "navbar navbar-expand-lg navbar-dark bg-primary"
            },
            e(
                'div', 
                {
                    className: "container-fluid"
                },
                navbar_brand,
                navbar_toggler,
                navbar_div
            )
        );

        let main_div = e(
            'div',
            {
                className: 'container-fluid'
            },
            navbar_main
        );

        return main_div;

    } // render

    componentDidMount()
    {
        this.manageProfilePhoto();
        
    } // componentDidMount

    onClickSignInButton(e)
    {
        window.location = this.props.sign_in_url;

    } // onClickSignInButton

    onClickSignUpButton(e)
    {
        window.location = this.props.sign_up_url;

    } // onClickSignUpButton

    onClickSignOutButton(e)
    {
        window.location = this.props.sign_out_url;

    } // onClickSignUpButton

    manageProfilePhoto()
    {
        $('#obem-user-profile-photo').hover((e) => {
            e.target.style.cursor = 'pointer';
        });
        $('#obem-user-profile-photo').on('click', (e) => {
            window.location = this.props.show_profile_url;
        });

    } // manageProfilePhoto

}

ObemNavigationBar.propTypes = {
    obem_navigation_bar_actions: PropTypes.string, // stringified array of {url: '', inner_text: '', dropdown_boolean: '', data: ''} hashes
    supported_languages: PropTypes.string, // stringified array of {locale: '',  language: '', country: ''} hashes 
    locale_end_point: PropTypes.string,
    active_language_locale: PropTypes.string,
    sign_in_label: PropTypes.string,
    sign_in_url: PropTypes.string,
    sign_up_label: PropTypes.string,
    sign_up_url: PropTypes.string,
    sign_out_label: PropTypes.string,
    sign_out_url: PropTypes.string,
    obem_user_is_logged_in: PropTypes.string,
    profile_photo_url: PropTypes.string,
    show_profile_url: PropTypes.string,
    home_url: PropTypes.string,
    csrf_token: PropTypes.string
};