/*
 * Template Name: Apartment - Retail html Template
 * Author: shalevlevi - (https://www.fiverr.com/shalevlevi)
 * Version: 1.0
 * Copyright 2025 shalevlevi

===========================================
    
    All jQuery section Include: 

    01. prealoder start
    02. active like start
    03. filter arrow start
    04. love react start
    05. apartment slider start
    06. apartment search slider start

    
===========================================


*/


(function ($) {
    "use strict";

    $(window).on('load', function(){

        //01. prealoder start
        $("#preloader").delay(1800).fadeOut("slow");

    });

    $(document).ready(function () {

        //02. active like start
        $('.love_icon, .love_icon02').on('click', function (event) {
            event.preventDefault();
            $(this).toggleClass('add_class');
        });

        //03. filter arrow start
        $('.filter_arrow').on('click', function (event) {
            event.preventDefault();
            $('.filter_arrow').toggleClass('open');
            $('.filter_wrap').toggleClass('open');
        });

        //04. love react start
        $('.card_item03 .card_edit_view_icon, .close_icon, .mini_btn').on('click', function (event) {
            event.preventDefault();
            $('.update_details_advertisement_wrap').toggleClass('open');
        });

        if (window.matchMedia('(max-width: 767px)').matches) {

            //05. apartment slider start
            function apartment_carouselInit() {
                $('.owl-carousel.apartment_slider').owlCarousel({
                    dots: false,
                    loop: true,
                    margin: 0,
                    stagePadding: 0,
                    autoWidth:true,
                    autoplay: true,
                    rtl: true,
                    autoplayTimeout: 2500,
                    smartSpeed:3000,
                    autoplayHoverPause: false,
                    // responsive: {
                    //     0: {
                    //         items: 1
                    //     },
                    //     768: {
                    //         items: 2,
                    //     },
                    //     992: {
                    //         items: 5
                    //     }
                    // }
                });
            }
            apartment_carouselInit();

            //06. apartment search slider start
            function apartment_search_carouselInit() {
                $('.owl-carousel.apartment_search_slider').owlCarousel({
                    dots: false,
                    loop: true,
                    margin: 0,
                    stagePadding: 0,
                    autoWidth:true,
                    autoplay: true,
                    rtl: true,
                    autoplayTimeout: 2500,
                    smartSpeed:3000,
                    autoplayHoverPause: false,
                    // responsive: {
                    //     0: {
                    //         items: 1
                    //     },
                    //     768: {
                    //         items: 2,
                    //     },
                    //     992: {
                    //         items: 5
                    //     }
                    // }
                });
            }
            apartment_search_carouselInit();


        }




    });

})(jQuery);