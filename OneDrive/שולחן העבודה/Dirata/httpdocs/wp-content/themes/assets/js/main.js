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
    04. apartment slider start
    05. apartment search slider start
    06. product gallery slider start
    07. apartment product slider start

    apartment item click start


===========================================


*/


(function ($) {
  "use strict";

  $(window).on('load', function () {

    //01. prealoder start
    $("#preloader").delay(1800).fadeOut("slow");

  });

  $(document).ready(function () {

    //02. active like start
    $('.love_icon').on('click', function (event) {
      event.preventDefault();
      $(this).toggleClass('add_class');
    });

    //03. filter arrow start
    $('.filter_arrow').on('click', function (event) {
      event.preventDefault();
      $('.filter_arrow').toggleClass('open');
      $('.filter_wrap').toggleClass('open');
    });

    // open and close popup edit apartment

    // $('.card_edit_view_icon').on('click', function (event) {
    //     event.preventDefault();
    //     $('.update_details_advertisement_wrap').addClass('open');
    // });

    //   jQuery('.close_icon').on('click', function(e) {
    //       e.preventDefault();
    //       jQuery('.update_details_advertisement_wrap').removeClass('open');
    //   });

    // כאן הפכתי להערה
    // $('.card_edit_view_icon').on('click', function (event) {
    //   event.preventDefault();
    //   const apartmentId = $(this).data('id');

    //   $.ajax({
    //     url: dirataVars.ajaxurl,
    //     method: 'POST',
    //     data: {
    //       action: 'get_apartment_data',
    //       apartment_id: apartmentId
    //     },
    //     success: function (response) {
    //       console.log('response from get_apartment_data:', response);
    //       if (response.success) {
    //         const data = response.data;
    //         $('#a').val(data.apartment_city);
    //         $('#b').val(data.apartment_neighborhood);
    //         $('#c').val(data.apartment_street);
    //         $('#d').val(data.apartment_house_num);
    //         $('#e').val(data.floor);
    //         $('#f').val(data.rooms);
    //         $('#g').val(data.size);
    //         $('#h').val(data.price);
    //         $('#i').val(data.entry_date);
    //         $('#j').val(data.description);
    //         $('#k').val(data.options);
    //         // $('#pro-img-1').val(data.main_img_url);
    //         $('#pro-img-1').attr('src', data.main_img_url);
    //         $('#popup_apartment_id').val(apartmentId); // אל תשכחי את זה!
    //       }
    //     }
    //   });

    //   $('.update_details_advertisement_wrap').addClass('open');
    // });


    $('.card_edit_view_icon').on('click', function (event) {
      event.preventDefault();
      const apartmentId = $(this).closest('.card_edit_view_icon').data('id');
      console.log('ID:', apartmentId);

      // שלב 1 – טוענים את ה־HTML של הפופאפ
      $.ajax({
        url: dirataVars.ajaxurl,
        method: 'POST',
        data: {
          action: 'load_edit_apartment_popup',
          apartment_id: apartmentId
        },
        success: function (html) {
          $('#popup-edit-apartment').html(html);
          $('#popup-edit-apartment .update_details_advertisement_wrap').addClass('open');

          // שלב 2 – שולפים את הנתונים של הדירה לפי ID
          $.ajax({
            url: dirataVars.ajaxurl,
            method: 'POST',
            data: {
              action: 'get_apartment_data',
              apartment_id: apartmentId
            },
            success: function (response) {
              if (response.success) {
                const data = response.data;
                $('#a').val(data.apartment_city);
                $('#b').val(data.apartment_neighborhood);
                $('#c').val(data.apartment_street);
                $('#d').val(data.apartment_house_num);
                $('#e').val(data.floor);
                $('#f').val(data.rooms);
                $('#g').val(data.size);
                $('#h').val(data.price);
                $('#i').val(data.entry_date);
                $('#j').val(data.description);
                $('#k').val(data.options);
                $('#pro-img-1').attr('src', data.main_img_url);
                $('#popup_apartment_id').val(apartmentId);
              } else {
                console.error('שגיאה בטעינת נתונים:', response.data);
              }
            },
            error: function (xhr) {
              console.error('שגיאה בפנייה ל־get_apartment_data:', xhr);
            }
          });

        },
        error: function (xhr, status, error) {
          console.error('שגיאה בטעינת הפופאפ:', error);
        }
      });
    });




    // עד כאן קוד ניסיון חדש


    // $('.close_icon').on('click', function (event) {
    //   event.preventDefault();
    //   $('.update_details_advertisement_wrap').removeClass('open');
    // });

    $(document).on('click', '.close_icon', function () {
      $('.update_details_advertisement_wrap').fadeOut(200, function () {
        $('#popup-edit-apartment').empty();
      });
    });




    // end of open and close popup edit apartment

    // save edit apartment
    $('#save_apartment_btn').on('click', function () {
      const postId = $('#popup_apartment_id').val();

      const data = {
        action: 'save_apartment_data',
        post_id: postId,
        city: $('#a').val(),
        area: $('#b').val(),
        street: $('#c').val(),
        house_number: $('#d').val(),
        floor: $('#e').val(),
        rooms: $('#f').val(),
        size: $('#g').val(),
        price: $('#h').val(),
        entry_date: $('#i').val(),
        description: $('#j').val(),
        options: $('#k').val()
      };

      console.log("🔄 שולח:", data);

      $.ajax({
        url: dirataVars.ajaxurl,
        method: 'POST',
        data: data,
        success: function (response) {
          console.log("✅ קיבל תגובה מ־save_apartment_data:", response);
          if (response.success) {
            alert('נשמר בהצלחה!');
            $('.update_details_advertisement_wrap').removeClass('open');
          } else {
            alert('אירעה שגיאה בשמירה');
          }
        },
        error: function (xhr, status, error) {
          console.error("❌ AJAX Error:", status, error);
          alert('קרתה שגיאה בתקשורת עם השרת');
        }
      });
    });


    // end of save edit apartment

    if (window.matchMedia('(max-width: 767px)').matches) {

      //04. apartment slider start
      function apartment_carouselInit() {
        $('.owl-carousel.apartment_slider').owlCarousel({
          dots: false,
          loop: true,
          margin: 0,
          stagePadding: 0,
          autoWidth: true,
          autoplay: true,
          rtl: true,
          autoplayTimeout: 2500,
          smartSpeed: 3000,
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

      //05. apartment search slider start
      function apartment_search_carouselInit() {
        $('.owl-carousel.apartment_search_slider').owlCarousel({
          dots: false,
          loop: true,
          margin: 0,
          stagePadding: 0,
          autoWidth: true,
          autoplay: true,
          rtl: true,
          autoplayTimeout: 2500,
          smartSpeed: 3000,
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

    //06. product gallery slider start
    $('#SlickPhotoswipGallery').slick({
      infinite: true,
      slidesToShow: 1,
      slidesToScroll: 1,
      autoplay: false,
      autoplaySpeed: 2000,
      arrows: false,
      centerMode: false,
      focusOnSelect: true,
      centerPadding: '0',
      rtl: true,
      dots: false,
      fade: true,
      asNavFor: '.product_mini_slider'
    });
    $('.product_mini_slider').slick({
      asNavFor: '#SlickPhotoswipGallery',
      autoplay: false,
      arrows: true,
      dots: false,
      slidesToShow: 3,
      centerPadding: "0px",
      infinite: true,
      pauseOnHover: false,
      swipe: false,
      touchMove: false,
      vertical: true,
      verticalScrolling: true,
      speed: 1000,
      centerMode: false,
      focusOnSelect: true,
      autoplaySpeed: 2000,
      useTransform: true,
      prevArrow: '<div class="slick-prev"><img src="/wp-content/themes/dirata/assets/img/arrow_top_icon.svg"></div>',
      nextArrow: '<div class="slick-next"><img src="/wp-content/themes/dirata/assets/img/arrow_bottom_icon.svg"></div>',
      adaptiveHeight: true,
      responsive: [
        {
          breakpoint: 1199,
          settings: {
            slidesToShow: 4,
            slidesToScroll: 1,
          }
        },
        {
          breakpoint: 991,
          settings: {
            slidesToShow: 5,
            slidesToScroll: 1,
          }
        },
        {
          breakpoint: 768,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 1,
            vertical: false,
            verticalScrolling: false,
            useTransform: true,
            adaptiveHeight: true,
            infinite: false,
          }
        },
        {
          breakpoint: 450,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 1,
            vertical: false,
            verticalScrolling: false,
            useTransform: true,
            adaptiveHeight: true,
            infinite: false,
          }
        }
      ]
    });
    //07. apartment product slider start
    $('.apartment_product_item_slider').slick({
      infinite: true,
      slidesToScroll: 1,
      dots: false,
      autoplay: false,
      prevArrow: '<button type="button" class="slick-prev"><img src="/wp-content/themes/dirata/assets/img/apartment_arrow_left.svg" alt="left"></button>',
      nextArrow: '<button type="button" class="slick-next"><img src="/wp-content/themes/dirata/assets/img/apartment_arrow_right.svg" alt="right"></button>',
      autoplaySpeed: 3000,
      rtl: true,
      arrows: true,
      centerMode: false,
      variableWidth: true,
      focusOnSelect: true,
      centerPadding: '0',
      responsive: [
        {
          breakpoint: 992,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 2,
          }
        },
        {
          breakpoint: 767,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 2
          }
        }
      ]

    });

    //07. apartment product slider start
    $('.card_slider').slick({
      infinite: true,
      slidesToScroll: 1,
      dots: false,
      autoplay: false,
      prevArrow: '<button type="button" class="slick-prev"><img src="/wp-content/themes/dirata/assets/img/left_arrow.svg" alt="#"></button>',
      nextArrow: '<button type="button" class="slick-next"><img src="/wp-content/themes/dirata/assets/img/right_arrow.svg" alt="#"></button>',
      autoplaySpeed: 3000,
      rtl: true,
      arrows: true,
      centerMode: false,
      variableWidth: true,
      centerPadding: '0',
      responsive: [
        {
          breakpoint: 992,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 2,
          }
        },
        {
          breakpoint: 767,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 2
          }
        }
      ]

    });


  });

})(jQuery);

document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('apartment-form');
  const message = document.getElementById('post-limit-message');

  if (
    form && message &&
    typeof dirataLimits !== 'undefined' &&
    parseInt(dirataLimits.current_posts) >= parseInt(dirataLimits.max_posts)
  ) {
    // הסתרת טופס מראש
    form.style.display = 'none';
    message.style.display = 'block';
  }
});
// document.querySelectorAll('.save_apartment_btn').forEach(function (btn) {
//   btn.addEventListener('click', function (e) {
//     e.preventDefault();
//     const postId = this.dataset.postId;

//     fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
//       method: 'POST',
//       credentials: 'same-origin',
//       headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
//       body: new URLSearchParams({
//         action: 'save_apartment_to_user',
//         post_id: postId,
//       })
//     })
//       .then(response => response.json())
//       .then(data => {
//         if (data.success) {
//           alert('הדירה נוספה לרשימה שלך!');
//         } else {
//           alert(data.data || 'שגיאה לא ידועה');
//         }
//       });
//   });
// });




