/** Live events carousel — only runs when #events_carousel has .owl-carousel (legacy). */
function initEventsCarousel($root) {
    var $el = $root && $root.length ? $root : $('#events_carousel');
    if (!$el.length || !$el.hasClass('owl-carousel') || typeof $el.owlCarousel !== 'function') {
        return;
    }
    if ($el.hasClass('owl-loaded')) {
        return;
    }
    var slideCount = $el.children('.item').length;
    if (!slideCount) {
        return;
    }
    var navIcPrev = '<span class="events-owl__nav-ic" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22" focusable="false"><path fill="currentColor" d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/></svg></span>';
    var navIcNext = '<span class="events-owl__nav-ic" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22" focusable="false"><path fill="currentColor" d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg></span>';
    $el.owlCarousel({
        loop: slideCount > 2,
        margin: 20,
        nav: true,
        dots: true,
        navText: [navIcPrev, navIcNext],
        smartSpeed: 450,
        responsive: {
            0: { items: 1, stagePadding: 24, margin: 16 },
            576: { items: 2, stagePadding: 0, margin: 18 },
            992: { items: 3, margin: 20 }
        }
    });
    $el.find('.owl-nav button.owl-prev').attr({ 'aria-label': 'Previous events', title: 'Previous' });
    $el.find('.owl-nav button.owl-next').attr({ 'aria-label': 'Next events', title: 'Next' });
}
window.initEventsCarousel = initEventsCarousel;

$(document).ready(function() {

//animation
setTimeout(function(){
    $('body').addClass('loaded');
}, 3000);
setTimeout(function(){
    $('.animate').bind('inview', function(event, isInView) {
        if (isInView) {

            var animate = $(this).attr('data-animation');
            var speedDuration = $(this).attr('data-duration');

            var $t = $(this);
            setTimeout(function(){
                $t.addClass(animate + ' animated');
            }, speedDuration);
        }
    });

}, 3200);
//end

//owlcarousel — live events
    initEventsCarousel($('#events_carousel'));


//Sticky menu
        $(window).scroll(function() {
            if ($(this).scrollTop() > 1){
                $('header').addClass("sticky");
            }
            else{
                $('header').removeClass("sticky");
            }
        });
//end





//bxslider
    $(document).ready(function(){
        $('.bxslider').bxSlider({
            mode: 'fade'
        });
    });
//end

//map

    if (document.body.contains(document.getElementById('map-canvas'))) {
        var stockholm = new google.maps.LatLng(24.697027, 46.682260);
        var parliament = new google.maps.LatLng(24.697027, 46.682260);

        var marker;
        var map;
        var image = '/assets/images/map-pin.png';

        function initialize() {

            var mapOptions = {
                zoom: 16,
                scrollwheel: false,
                draggable: false,
                center: stockholm,
            };


            map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

            var contentString = '<div id="content">' +
                '<h3 class="firstHeading" class="firstHeading">3rd - 5th May 2015</a></h3>' +
                '<div class="bodyContent">' +
                '<p>Madinat Jumeirah<br>' +
                'Dubai, UAE<br>' +
                '</div>' +
                '</div>';

            var infowindow = new google.maps.InfoWindow({
                content: contentString
            });

            marker = new google.maps.Marker({
                map: map,
                icon: image,
                draggable: false,
                animation: google.maps.Animation.DROP,
                position: parliament
            });
            marker.addListener('click', function () {
                infowindow.open(map, marker);
                $('.gm-style-iw').prev().hide();
                $('.gm-style-iw').parent().addClass('main-popup');
                $('.gm-style-iw').next().addClass('close-btn');


                $('.close-btn').click(function () {
                    $('.main-popup').hide();
                });

            });
        }


        google.maps.event.addDomListener(window, 'load', initialize);
    }

//end

    //form validation
    var emailRegex = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    var letters = /^[A-Za-z]+$/;
    $('#contact').formValidation({
        framework: 'bootstrap',
        excluded: ':disabled',
        icon: {
            valid: 'fa fa-check',
            invalid: 'fa fa-remove',
            validating: 'fa fa-refresh'
        },
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: ' '
                    },
                    regexp: {
                        regexp: letters,
                        message: ' '
                    }
                }
            },
            email: {
                validators: {
                    notEmpty: {
                        message: ' '
                    }, regexp: {
                        regexp: emailRegex,
                        message: ' '
                    }
                }
            },
            message: {
                validators: {
                    notEmpty: {
                        message: ' '
                    }
                }
            },
            captcha: {
                validators: {
                    identical: {
                        field: 'captcha-match',
                        message: ' '
                    }
                }
            }
        },
        onSuccess: function (e) {
            e.preventDefault();
            var $form = $(e.target),
                formId = '#' + $form[0].id;
            $('body,html').animate({
                scrollTop: $(formId).offset().top - 100
            }, 800);
            $(formId).addClass('loading').append('<div class="loader"></div>');
            $.ajax({
                type: 'post',
                url: '',
                data: $(formId).serialize(),
                success: function () {
                    setTimeout(function () {
                        var fields = $(formId).data('formValidation').getOptions().fields,
                            $parent, $icon;
                        for (var field in fields) {
                            $parent = $('[name="' + field + '"]').parents('.form-group');
                        }
                        // Then reset the form
                        $('.alert-success').addClass('in');
                        $(formId).data('formValidation').resetForm(true);
                        $("input[type=text], textarea").val("");
                        $('.thanks').show();
                        $(formId).removeClass('loading');
                        $(formId).find('.loader').remove();
                        setTimeout(function () {
                            $('.thanks').hide();
                            $(formId).data('formValidation').resetForm(true);
                        }, 2000);
                    }, 1500);
                }
            });
        }
    });

    $('.captcha').click(function () {
        refresh();
    });
    makeid();
    refresh();
    function makeid() {
        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        for (var i = 0; i < 5; i++)
            text += possible.charAt(Math.floor(Math.random() * possible.length));
        return text;
    }
    function refresh() {
        var textRand = makeid();
        $('.captcha').html(textRand);
        $("#onecp").val(textRand);
    }


//Back To Top

    $('.back-to-top').on('click', function (e) {
        e.preventDefault();
        $('html,body').animate({
            scrollTop: 0
        }, 700);
    });


//responsive menu
    $('.menu-bottom').on('click', function(){
        $('html').toggleClass('menu-open');
    });

    $("#content-1").mCustomScrollbar({
        axis:"x",
        theme:"inset",
        advanced:{
            autoExpandHorizontalScroll:true
        },
        scrollButtons:{
            enable:true,
            scrollType:"stepped"
        },
        keyboard:{scrollType:"stepped"}
    });

    $("#content-2").mCustomScrollbar({
        axis:"x",
        theme:"inset",
        advanced:{
            autoExpandHorizontalScroll:true
        },
        scrollButtons:{
            enable:true,
            scrollType:"stepped"
        },
        keyboard:{scrollType:"stepped"}
    });

    $("#content-3").mCustomScrollbar({
        axis:"x",
        theme:"inset",
        advanced:{
            autoExpandHorizontalScroll:true
        },
        scrollButtons:{
            enable:true,
            scrollType:"stepped"
        },
        keyboard:{scrollType:"stepped"}
    });
});