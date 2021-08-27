// Agency Theme JavaScript

(function ($) {
    "use strict"; // Start of use strict


    // data-wow-duration="1s" data-wow-delay="0s" data-wow-offset="100"  data-wow-iteration="1"
    var wow = new WOW(
        {
            boxClass: 'wow', // animated element css class (default is wow)
            animateClass: 'animated', // animation css class (default is animated)
            offset: 150, // distance to the element when triggering the animation (default is 0)
            mobile: false, // trigger animations on mobile devices (default is true)
            live: true // act on asynchronously loaded content (default is true)
        }
    );
    wow.init();

    function onScrollInit(items, trigger)
    {
        items.each(function () {
            var osElement = $(this),
                osAnimationClass = osElement.attr('data-os-animation'),
                osAnimationDelay = osElement.attr('data-os-animation-delay');
            osElement.css({
                '-webkit-animation-delay': osAnimationDelay,
                '-moz-animation-delay': osAnimationDelay,
                'animation-delay': osAnimationDelay
            });
            var osTrigger = (trigger) ? trigger : osElement;
            osTrigger.waypoint(function () {
                osElement.addClass('animated').addClass(osAnimationClass);
            }, {
                triggerOnce: true,
                offset: '90%'
            });
        });
    }

    $(document).ready(function () {
        onScrollInit($('.os-animation'));
        // www.jqueryscript.net/animation/jQuery-Plugin-For-Particle-System-Background-with-Parallax-Effect-Particleground.html
        $('header.shorten').particleground({
            //minSpeedX: 0.1,
            //maxSpeedX: 0.7,
            //minSpeedY: 0.1,
            //maxSpeedY: 0.7,
            //directionX: 'center', // 'center', 'left' or 'right'. 'center' = dots bounce off edges
            //directionY: 'center', // 'center', 'up' or 'down'. 'center' = dots bounce off edges
            density: 15000, // How many particles will be generated: one particle every n pixels
            dotColor: '#fcfcfc',
            lineColor: '#fcfcfc',
            particleRadius: 1.5, // Dot size
            lineWidth: 0.25,
            curvedLines: false,
            proximity: 100, // How close two dots need to be before they join
            parallax: true,
            parallaxMultiplier: 10, // The lower the number, the more extreme the parallax effect
            onInit: function () {
            },
            onDestroy: function () {
            }
        });

        $(".testimonials").owlCarousel({
            items: 1,
            margin: 10,
            loop: true
        });
    });

    // Highlight the top nav as scrolling occurs
    /*
     $( 'body' ).scrollspy( {
     target: '.navbar-fixed-top',
     offset: 51
     } );
     */

    // Closes the Responsive Menu on Menu Item Click
    $('.navbar-collapse ul li a:not(.dropdown-toggle)').click(function () {
        $('.navbar-toggle:visible').click();
    });

    /**
     * Contact Form
     */
    $("#contact-form").submit(function (e) {

        e.preventDefault();

        if (app_vars['captcha_type'] === 'invisible-recaptcha') {
            if (app_vars['enable_captcha'] === 'yes' &&
                app_vars['captcha_contact'] === 'yes' &&
                $('#captchaContact').length) {
                if (!$(this).hasClass('captcha-done')) {
                    return false;
                }
            }
        }

        var contactForm = $(this);
        var contactFormHTML = $(this).html();
        var submitButton = contactForm.find('button');
        var submitButtonHTML = submitButton.html();

        //console.log( homeForm.serialize() );

        $.ajax({
            dataType: 'json', // The type of data that you're expecting back from the server.
            type: 'POST', // he HTTP method to use for the request
            url: contactForm.attr('action'), // A string containing the URL to which the request is sent.
            data: contactForm.serialize(), // Data to be sent to the server.
            cache: false,
            beforeSend: function (xhr) {
                submitButton.attr("disabled", true).html('<i class="fa fa-spinner fa-spin"></i>');
                //homeForm.slideUp();
                $('<div class="loader"></div>').insertAfter(contactForm);

            },
            success: function (result, status, xhr) {
                //console.log( result );
                if (result.status === 'success') {
                    contactForm.slideUp();
                    var success_message = '<div class="alert alert-success" role="alert">' + result.message + '</div>';
                    $("#contact .contact-result").html(success_message).slideDown();
                } else {
                    contactForm.slideUp();
                    var success_message = '<div class="alert alert-danger" role="alert"><b>Error!</b> ' + result.message + '</div>';
                    $("#contact .contact-result").html(success_message).slideDown();
                }

            },
            error: function (xhr, status, error) {
                //console.table( xhr );
                alert("An error occured: " + xhr.status + " " + xhr.statusText);
            },
            complete: function (xhr, status) {
                $('#contact .loader').remove();
            }
        });

    });

    /**
     * Popup for share icons
     */
    $('a.popup').on('click', function (e) {
        e.preventDefault();
        var width = 575,
            height = 400,
            left = ($(window).width() - width) / 2,
            top = ($(window).height() - height) / 2,
            url = this.href,
            opts = 'status=1' + ',width=' + width + ',height=' + height + ',top=' + top + ',left=' + left;

        window.open(url, 'share', opts);
    });

})(jQuery); // End of use strict
