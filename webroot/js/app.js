/**
 * reCAPTCHA Stuff
 */
var captchaShort;
var captchaContact;
var captchaSignin;
var captchaSignup;
var captchaForgotpassword;
var invisibleCaptchaShort;
var invisibleCaptchaContact;
var invisibleCaptchaSignin;
var invisibleCaptchaSignup;
var invisibleCaptchaForgotpassword;

window.onload = function () {

    if (app_vars['enable_captcha'] !== 'yes') {
        return true;
    }

    if (app_vars['captcha_type'] === 'solvemedia') {
        if (app_vars['user_id'] === null &&
            app_vars['captcha_short_anonymous'] === '1' &&
            $('#captchaShort').length) {
            captchaShort = ACPuzzle.create(
                app_vars['solvemedia_challenge_key'],
                'captchaShort',
                {multi: true, id: 'captchaShort'}
            );
        }

        if (app_vars['captcha_contact'] === 'yes' && $('#captchaContact').length) {
            captchaContact = ACPuzzle.create(
                app_vars['solvemedia_challenge_key'],
                'captchaContact',
                {multi: true, id: 'captchaContact'}
            );
        }

        if (app_vars['captcha_signin'] === 'yes' && $('#captchaSignin').length) {
            captchaSignin = ACPuzzle.create(
                app_vars['solvemedia_challenge_key'],
                'captchaSignin',
                {multi: true, id: 'captchaSignin'}
            );
        }

        if (app_vars['captcha_signup'] === 'yes' && $('#captchaSignup').length) {
            captchaSignup = ACPuzzle.create(
                app_vars['solvemedia_challenge_key'],
                'captchaSignup',
                {multi: true, id: 'captchaSignup'}
            );
        }

        if (app_vars['captcha_forgot_password'] === 'yes' &&
            $('#captchaForgotpassword').length) {
            captchaForgotpassword = ACPuzzle.create(
                app_vars['solvemedia_challenge_key'],
                'captchaForgotpassword',
                {multi: true, id: 'captchaForgotpassword'}
            );
        }
    }

};

var onloadRecaptchaCallback = function () {

    if (app_vars['enable_captcha'] !== 'yes') {
        return true;
    }

    if (app_vars['captcha_type'] === 'recaptcha') {
        if (app_vars['user_id'] === null &&
            app_vars['captcha_short_anonymous'] === '1' &&
            $('#captchaShort').length) {
            $('#shorten .btn-captcha').attr('disabled', 'disabled');
            captchaShort = grecaptcha.render('captchaShort', {
                'sitekey': app_vars['reCAPTCHA_site_key'],
                'callback': function (response) {
                    $('#shorten .btn-captcha').removeAttr('disabled');
                }
            });
        }

        if (app_vars['captcha_contact'] === 'yes' && $('#captchaContact').length) {
            $('#contact-form .btn-captcha').attr('disabled', 'disabled');
            captchaContact = grecaptcha.render('captchaContact', {
                'sitekey': app_vars['reCAPTCHA_site_key'],
                'callback': function (response) {
                    $('#contact-form .btn-captcha').removeAttr('disabled');
                }
            });
        }

        if (app_vars['captcha_signin'] === 'yes' && $('#captchaSignin').length) {
            $('#signin-form .btn-captcha').attr('disabled', 'disabled');
            captchaSignin = grecaptcha.render('captchaSignin', {
                'sitekey': app_vars['reCAPTCHA_site_key'],
                'callback': function (response) {
                    $('#signin-form .btn-captcha').removeAttr('disabled');
                }
            });
        }

        if (app_vars['captcha_signup'] === 'yes' && $('#captchaSignup').length) {
            $('#signup-form .btn-captcha').attr('disabled', 'disabled');
            captchaSignup = grecaptcha.render('captchaSignup', {
                'sitekey': app_vars['reCAPTCHA_site_key'],
                'callback': function (response) {
                    $('#signup-form .btn-captcha').removeAttr('disabled');
                }
            });
        }

        if (app_vars['captcha_forgot_password'] === 'yes' &&
            $('#captchaForgotpassword').length) {
            $('#forgotpassword-form .btn-captcha').attr('disabled', 'disabled');
            captchaForgotpassword = grecaptcha.render('captchaForgotpassword', {
                'sitekey': app_vars['reCAPTCHA_site_key'],
                'callback': function (response) {
                    $('#forgotpassword-form .btn-captcha').removeAttr('disabled');
                },
            });
        }
    }

    if (app_vars['captcha_type'] === 'invisible-recaptcha') {
        if (app_vars['user_id'] === null &&
            app_vars['captcha_short_anonymous'] === '1' &&
            $('#captchaShort').length) {
            invisibleCaptchaShort = grecaptcha.render('captchaShort', {
                'sitekey': app_vars['invisible_reCAPTCHA_site_key'],
                'size': 'invisible',
                'callback': function (response) {
                    if (grecaptcha.getResponse(invisibleCaptchaShort)) {
                        $('#shorten').addClass('captcha-done').submit();
                    }
                }
            });

            $('#shorten').submit(function (event) {
                if (!grecaptcha.getResponse(invisibleCaptchaShort)) {
                    event.preventDefault(); //prevent form submit before captcha is completed
                    grecaptcha.execute(invisibleCaptchaShort);
                }
            });
        }

        if (app_vars['captcha_contact'] === 'yes' && $('#captchaContact').length) {
            invisibleCaptchaContact = grecaptcha.render('captchaContact', {
                'sitekey': app_vars['invisible_reCAPTCHA_site_key'],
                'size': 'invisible',
                'callback': function (response) {
                    if (grecaptcha.getResponse(invisibleCaptchaContact)) {
                        $('#contact-form').addClass('captcha-done').submit();
                    }
                }
            });

            $('#contact-form').submit(function (event) {
                if (!grecaptcha.getResponse(invisibleCaptchaContact)) {
                    event.preventDefault(); //prevent form submit before captcha is completed
                    grecaptcha.execute(invisibleCaptchaContact);
                }
            });
        }

        if (app_vars['captcha_signin'] === 'yes' && $('#captchaSignin').length) {
            invisibleCaptchaSignin = grecaptcha.render('captchaSignin', {
                'sitekey': app_vars['invisible_reCAPTCHA_site_key'],
                'size': 'invisible',
                'callback': function (response) {
                    $('#signin-form').submit();
                }
            });

            $('#signin-form').submit(function (event) {
                if (!grecaptcha.getResponse(invisibleCaptchaSignin)) {
                    event.preventDefault(); //prevent form submit before captcha is completed
                    grecaptcha.execute(invisibleCaptchaSignin);
                }
            });
        }

        if (app_vars['captcha_signup'] === 'yes' && $('#captchaSignup').length) {
            invisibleCaptchaSignup = grecaptcha.render('captchaSignup', {
                'sitekey': app_vars['invisible_reCAPTCHA_site_key'],
                'size': 'invisible',
                'callback': function (response) {
                    $('#signup-form').submit();
                }
            });

            $('#signup-form').submit(function (event) {
                if (!grecaptcha.getResponse(invisibleCaptchaSignup)) {
                    event.preventDefault(); //prevent form submit before captcha is completed
                    grecaptcha.execute(invisibleCaptchaSignup);
                }
            });
        }

        if (app_vars['captcha_forgot_password'] === 'yes' &&
            $('#captchaForgotpassword').length) {
            invisibleCaptchaForgotpassword = grecaptcha.render('captchaForgotpassword', {
                'sitekey': app_vars['invisible_reCAPTCHA_site_key'],
                'size': 'invisible',
                'callback': function (response) {
                    $('#forgotpassword-form').submit();
                }
            });

            $('#forgotpassword-form').submit(function (event) {
                if (!grecaptcha.getResponse(invisibleCaptchaForgotpassword)) {
                    event.preventDefault(); //prevent form submit before captcha is completed
                    grecaptcha.execute(invisibleCaptchaForgotpassword);
                }
            });
        }
    }

};

/////////////////////

$("body").one("focus", "#shorten input#url", function (e) {
    $('#shorten .form-group.captcha').slideDown("slow");
});


$(document).ready(function () {

    var url_href = window.location.href;
    if (url_href.substr(-1) === '#') {
        history.pushState("", document.title, url_href.substr(0, url_href.length - 1));
    }


    var url = window.location.href;
    $('.sidebar-menu a').filter(function () {
        return this.href === url;
        //} ).closest( 'li' ).addClass( 'active' );
    }).parents('.sidebar-menu li').addClass('active');

    function fixHeight()
    {
        var headerHeight = $("header.main-header").outerHeight();
        $("#frame").attr("height", (($(window).height() - 0) - headerHeight) + 'px');
    }

    $(window).resize(function () {
        fixHeight();
    }).resize();

    function shortenButton()
    {
        var short_box = $('.box-short');
        var short_button = $('button.shorten-button');
        if (jQuery(window).width() <= 767) {
            short_box.css("display", "block");
            short_button.css("display", "none");
        } else {
            short_box.css("display", "none");
            short_button.css("display", "block");
        }
    }

    $(window).resize(function () {
        shortenButton();
    }).resize();

    $('button.shorten-button').click(function (e) {
        e.preventDefault();
        $('.box-short').toggle("fast");
    });

    $('.select2').select2({
        width: "100%",
        closeOnSelect: false
    });
});

$('button.advanced').click(function () {
    $('.advanced-div').toggle("fast");
});

/**
 * Bootstrap 3: Keep selected tab on page refresh
 */
// store the currently selected tab in the localStorage
$('#form-settings a[data-toggle="tab"]').on("shown.bs.tab", function (e) {
    var id = $(e.target).attr("href").substr(1);
    localStorage.setItem('settings_selectedTab', id);
});

// on load of the page: switch to the currently selected tab
var selectedTab = localStorage.getItem('settings_selectedTab');

if ($('#form-settings').length && selectedTab !== null) {
    $('#form-settings a[data-toggle="tab"][href="#' + selectedTab + '"]').tab('show');
} else {
    $('#form-settings a[data-toggle="tab"]:first').tab('show');
}


/**
 *  Member Area Shorten
 */
$(".shorten-member #shorten").on("submit", function (e) {
    e.preventDefault();
    var shortenForm = $(this);
    var shortenContainer = shortenForm.closest('.box-short');
    var submitButton = shortenForm.find('button.btn-submit');
    var submitButtoHTML = submitButton.html();

    $.ajax({
        dataType: 'json', // The type of data that you're expecting back from the server.
        type: 'POST', // he HTTP method to use for the request
        url: shortenForm.attr('action'),
        data: shortenForm.serialize(), // Data to be sent to the server.
        beforeSend: function (xhr) {

            submitButton.attr("disabled", "disabled").html('<i class="fa fa-spinner fa-spin"></i>');
            $('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>').insertAfter(
                shortenContainer.find('.box-body'));

        },
        success: function (result, status, xhr) {

            if (result.url) {
                var short_url_html = '<div class="form-group"><div class="input-group"><input class="form-control input-lg" value="' + result.url + '" readonly onfocus="javascript:this.select()" ><div class="input-group-addon copy-it" data-clipboard-text="' + result.url + '" data-toggle="tooltip" data-placement="left" title="' + app_vars['copy'] + '"><i class="fa fa-clone"></i></div></div></div>';
                $(".shorten.add-link-result").html(short_url_html).slideDown();
                $('[data-toggle="tooltip"]').tooltip();
            } else {
                var success_message = '<div class="form-group"><p></p><div class="alert alert-danger" role="alert">' + result.message + '</div></div>';
                $(".shorten.add-link-result").html(success_message).slideDown();
                //alert( result.message );
            }

        },
        error: function (xhr, status, error) {

            alert("An error occured: " + xhr.status + " " + xhr.statusText);

        },
        complete: function (xhr, status) {

            shortenContainer.find('.overlay').remove();
            submitButton.removeAttr("disabled").html(submitButtoHTML);

        }
    });
});

/**
 * Home Page Shorten
 */
$(".shorten #shorten").on("submit", function (e) {
    e.preventDefault();
    if (app_vars['user_id'] === null &&
        app_vars['home_shortening_register'] === 'yes') {
        window.location.href = app_vars['base_url'] + 'auth/signup';
        return;
    }

    if (app_vars['captcha_type'] === 'invisible-recaptcha') {
        if (app_vars['enable_captcha'] === 'yes' &&
            app_vars['captcha_short_anonymous'] === '1' &&
            $('#captchaShort').length) {
            if (!$(this).hasClass('captcha-done')) {
                return false;
            }
        }
    }

    var shortenForm = $(this);
    var submitButton = shortenForm.find('button');
    var submitButtoHTML = submitButton.html();

    $.ajax({
        dataType: 'json', // The type of data that you're expecting back from the server.
        type: 'POST', // he HTTP method to use for the request
        url: shortenForm.attr('action'),
        data: shortenForm.serialize(), // Data to be sent to the server.
        beforeSend: function (xhr) {
            submitButton.attr("disabled", "disabled");
            $('<div class="shorten loader"></div>').insertAfter(shortenForm);
        },
        success: function (result, status, xhr) {
            //console.log( result );
            if (result.url) {
                shortenForm.slideUp();
                var short_url_html = '<div class="form-group"><div class="input-group"><input class="form-control input-lg" value="' + result.url + '" readonly onfocus="javascript:this.select()" ><div class="input-group-addon copy-it" data-clipboard-text="' + result.url + '" data-toggle="tooltip" data-placement="bottom" title="' + app_vars['copy'] + '"><i class="fa fa-clone"></i></div><div class="input-group-addon reshort" data-toggle="tooltip" data-placement="bottom" title="Reshort"><i class="fa fa-refresh"></i></div></div></div>';
                $(".shorten.add-link-result").html(short_url_html).slideDown();
            } else {
                shortenForm.slideUp();
                var success_message = '<div class="form-group"><div class="input-group"><input class="form-control input-lg" value="' + result.message + '" readonly ><div class="input-group-addon reshort" data-toggle="tooltip" data-placement="bottom" title="Reshort"><i class="fa fa-refresh"></i></div></div></div>';
                $(".shorten.add-link-result").html(success_message).slideDown();
            }
        },
        error: function (xhr, status, error) {
            alert("An error occured: " + xhr.status + " " + xhr.statusText);
        },
        complete: function (xhr, status) {
            $('[data-toggle="tooltip"]').tooltip();
            submitButton.removeAttr("disabled");
            $('.shorten.loader').remove();
            shortenForm[0].reset();
            try {
                grecaptcha.reset(captchaShort);
            } catch (e) {
            }
            try {
                ACPuzzle.reload('captchaShort');
            } catch (e) {
            }
        }
    });
});

$(document).ready(function () {
    $(".genius_wrap").sortable({
        handle: ".handle",
        items: "> div",
        opacity: 0.5
    });
    //$( "#sortable" ).disableSelection();

    var wrapper = $(".genius_wrap"); //Fields wrapper
    var add_button = $(".add_field_button"); //Add button ID
    var box = wrapper.html(); //box

    //console.log(box);

    var x = $('.genius_fields').length; //initlal text box count

    $(add_button).click(function (e) { //on add input button click
        e.preventDefault();

        x++; //text box increment

        new_box = box.replace(/smart\[0\]/g, "smart[" + x + "]").replace(/smart-0-/g, "smart-" + x + "-");

        $(this).closest('.genius_box').find('.genius_wrap').append(new_box); //add input box
    });

    $(wrapper).on("click", ".delete-target", function (e) { //user click on remove text
        e.preventDefault();
        var result = confirm("Want to delete?");
        if (result) {
            $(this).closest('.genius_fields').remove();
        }
    });

    $(wrapper).on("click", ".duplicate", function (e) { //user click on remove text
        e.preventDefault();
        /*x++; //text box increment
        var current_div = $(this).closest('.genius_fields');
        var new_html = current_div.clone();
        var new_html_content = current_div.clone();
        //console.log( new_html );
        current_div.after(new_html).next().html(new_html_content);*/
    });
});

$("header.shorten").on('click', '.reshort', function (e) {
    $(".shorten.add-link-result").html('').slideUp();
    $(".shorten #shorten").slideDown();
});

// Tooltip

$('[data-toggle="tooltip"]').tooltip();

// Clipboard
var clipboard = new ClipboardJS('.copy-it');

clipboard.on('success', function (e) {
    setTooltip(e.trigger, app_vars['copied']);
});

function setTooltip(btn, message)
{
    $(btn).attr('data-original-title', message).tooltip('show');
}

function setCookie(cname, cvalue, exdays)
{
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = 'expires=' + d.toUTCString();
    document.cookie = cname + '=' + cvalue + ';' + expires + ';path=/';
}

function getCookie(cname)
{
    var name = cname + '=';
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) === ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) === 0) {
            return c.substring(name.length, c.length);
        }
    }
    return '';
}

function cookie_accept()
{
    var cookie_html = '<div id="cookie-pop">' +
        '<div class="container-fluid">' +
        '<div class="col-xs-9">' +
        '<div class="cookie-message">' + app_vars['cookie_message'] + '</div>' +
        '</div>' +
        '<div class="col-xs-3">' +
        '<div class="cookie-confirm">' +
        '<button id="got-cookie" class="btn btn-default" type="submit">' +
        app_vars['cookie_button'] + '</button>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>';

    $('body').append(cookie_html);
}

if (app_vars['cookie_notification_bar']) {
    if (getCookie('cookieLaw') === '') {
        cookie_accept();

        $('#cookie-pop').show();

        $('#got-cookie').click(function () {
            setCookie('cookieLaw', 'got_it', 365);
            $('#cookie-pop').remove();
        });
    }
}
