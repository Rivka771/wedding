/*
===========================================
    
    // All jQuery section Include: 

    //01. Form Select start
    //02. MIN and MAX range slider start
    //03. dropdown hover start


===========================================
*/


(function ($) {
  "use strict";

  $(document).ready(function () {

    // 01. Form Select start
    $('#city-filter').niceSelect();
    $('#area-filter').niceSelect();
    $('.floor-min').niceSelect();
    $('.floor-max').niceSelect();

    if ($(window).width() > 768) {
      $('.mobile-additional-filters-menu').remove();
    }
    if ($(window).width() <= 768) {
      $('.general_order1').addClass('general-toggle-visible');
      $('.general_order2').addClass('general-toggle-hidden');
    }

    jQuery(document).on('click', '.apartment_img, .apartment_content h4', function (e) {
      e.preventDefault();
      var url = jQuery(this).closest('.apartment-item').data('url');
      if (url) {
        window.location.href = url;
      }
    });

    // 02. MIN and MAX range slider start
    var thumbsize = 14;

    function draw(slider, splitvalue) {

      /* set function vars */
      var min = slider.querySelector('.min');
      var max = slider.querySelector('.max');
      var lower = slider.querySelector('.lower');
      var upper = slider.querySelector('.upper');
      var legend = slider.querySelector('.legend');
      var thumbsize = parseInt(slider.getAttribute('data-thumbsize'));
      var rangewidth = parseInt(slider.getAttribute('data-rangewidth'));
      var rangemin = parseInt(slider.getAttribute('data-rangemin'));
      var rangemax = parseInt(slider.getAttribute('data-rangemax'));

      /* set min and max attributes */
      min.setAttribute('max', splitvalue);
      max.setAttribute('min', splitvalue);

      /* set css */
      min.style.width = parseInt(thumbsize + ((splitvalue - rangemin) / (rangemax - rangemin)) * (rangewidth - (2 * thumbsize))) + 'px';
      max.style.width = parseInt(thumbsize + ((rangemax - splitvalue) / (rangemax - rangemin)) * (rangewidth - (2 * thumbsize))) + 'px';
      min.style.left = '0px';
      max.style.left = parseInt(min.style.width) + 'px';
      min.style.top = lower.offsetHeight + 'px';
      max.style.top = lower.offsetHeight + 'px';
      legend.style.marginTop = min.offsetHeight + 'px';
      slider.style.height = (lower.offsetHeight + min.offsetHeight + legend.offsetHeight) + 'px';

      /* correct for 1 off at the end */
      if (max.value > (rangemax - 1)) max.setAttribute('data-value', rangemax);

      /* write value and labels */
      max.value = max.getAttribute('data-value');
      min.value = min.getAttribute('data-value');
      lower.innerHTML = min.getAttribute('data-value');
      upper.innerHTML = max.getAttribute('data-value');

      /* Update the legend numbers */
      var legend = slider.querySelector('.legend');
      var legendCount = legend.children.length;
      var currentMin = parseInt(min.getAttribute('data-value'));
      var currentMax = parseInt(max.getAttribute('data-value'));
      for (var i = 0; i < legendCount; i++) {
        var fraction = (legendCount - 1) ? i / (legendCount - 1) : 0;
        var newVal = Math.round(currentMin + fraction * (currentMax - currentMin));
        legend.children[i].innerHTML = newVal;
      }

    }

    function init(slider) {
      /* set function vars */
      var min = slider.querySelector('.min');
      var max = slider.querySelector('.max');
      var rangemin = parseInt(min.getAttribute('min'));
      var rangemax = parseInt(max.getAttribute('max'));
      var avgvalue = (rangemin + rangemax) / 2;
      var legendnum = slider.getAttribute('data-legendnum');

      /* set data-values */
      min.setAttribute('data-value', rangemin);
      max.setAttribute('data-value', rangemax);

      /* set data vars */
      slider.setAttribute('data-rangemin', rangemin);
      slider.setAttribute('data-rangemax', rangemax);
      slider.setAttribute('data-thumbsize', thumbsize);
      slider.setAttribute('data-rangewidth', slider.offsetWidth);

      /* write labels */
      var lower = document.createElement('span');
      var upper = document.createElement('span');
      lower.classList.add('lower', 'value');
      upper.classList.add('upper', 'value');
      lower.appendChild(document.createTextNode(rangemin));
      upper.appendChild(document.createTextNode(rangemax));
      slider.insertBefore(lower, min.previousElementSibling);
      slider.insertBefore(upper, min.previousElementSibling);

      /* write legend */
      var legend = document.createElement('div');
      legend.classList.add('legend');
      var legendvalues = [];
      for (var i = 0; i < legendnum; i++) {
        legendvalues[i] = document.createElement('div');
        var val = Math.round(rangemin + (i / (legendnum - 1)) * (rangemax - rangemin));
        legendvalues[i].appendChild(document.createTextNode(val));
        legend.appendChild(legendvalues[i]);

      }
      slider.appendChild(legend);

      /* draw */
      draw(slider, avgvalue);

      /* events */
      min.addEventListener("input", function () { update(min); });
      max.addEventListener("input", function () { update(max); });
    }

    function update(el) {
      /* set function vars */
      var slider = el.parentElement;
      var min = slider.querySelector('.min');
      var max = slider.querySelector('.max');
      var minvalue = Math.floor(min.value);
      var maxvalue = Math.floor(max.value);

      /* set inactive values before draw */
      min.setAttribute('data-value', minvalue);
      max.setAttribute('data-value', maxvalue);

      var avgvalue = (minvalue + maxvalue) / 2;

      /* draw */
      draw(slider, avgvalue);
    }

    var sliders = document.querySelectorAll('.min-max-slider');
    sliders.forEach(function (slider) {
      init(slider);
    });

    if ($(".size-slider").length) {
      var sizeMin = parseInt($(".size-slider").data("min")); // 20
      var sizeMax = parseInt($(".size-slider").data("max")); // 9999
      var initialSizeMin = parseInt($(".size-min").val());
      var initialSizeMax = parseInt($(".size-max").val());

      function updateSizeSliderBackground(maxValue) {
        var basePercentage = ((maxValue - sizeMin) / (sizeMax - sizeMin)) * 100;
        $(".size-slider").css("background",
          "linear-gradient(to right, #76C09280 0%, #76C09280 " + basePercentage + "%, var(--bg-green) " + basePercentage + "%, var(--bg-green) 100%)"
        );
      }

      updateSizeSliderBackground(initialSizeMax);

      $(".size-slider").slider({
        range: true,
        min: sizeMin,
        max: sizeMax,
        values: [initialSizeMin, initialSizeMax],
        slide: function (event, ui) {
          $(".size-min").val(ui.values[0]).trigger('change');
          $(".size-max").val(ui.values[1]).trigger('change');
          updateSizeSliderBackground(ui.values[1]);
        }
      });

      $(".size-min, .size-max").on("change", function () {
        var newSizeMin = parseInt($(".size-min").val().replace(" מ״ר", ""));
        var newSizeMax = parseInt($(".size-max").val().replace(" מ״ר", ""));
        $(".size-slider").slider("values", [newSizeMin, newSizeMax]);
        updateSizeSliderBackground(newSizeMax);
      });
    }

    function updateCustomPriceSlider() {
      const minInput = document.querySelector('.price-min');
      const maxInput = document.querySelector('.price-max');
      const rangeMin = document.querySelector('.price-slider-min');
      const rangeMax = document.querySelector('.price-slider-max');
      const track = document.querySelector('.price-slider-track');

      const minVal = parseInt(rangeMin.value);
      const maxVal = parseInt(rangeMax.value);

      // Swap if overlapping
      if (minVal > maxVal) {
        [rangeMin.value, rangeMax.value] = [rangeMax.value, rangeMin.value];
      }

      minInput.value = Math.min(minVal, maxVal);
      maxInput.value = Math.max(minVal, maxVal);

      updatePriceTrackVisual();
    }

    function updatePriceTrackVisual() {
      const minRange = document.querySelector('.price-slider-min');
      const maxRange = document.querySelector('.price-slider-max');
      const track = document.querySelector('.price-slider-track');

      const min = parseInt(minRange.value);
      const max = parseInt(maxRange.value);
      const minPercent = ((min - minRange.min) / (minRange.max - minRange.min)) * 100;
      const maxPercent = ((max - maxRange.min) / (maxRange.max - maxRange.min)) * 100;

      const start = Math.min(minPercent, maxPercent);
      const end = Math.max(minPercent, maxPercent);
      track.style.right = `${start}%`;
      track.style.width = `${end - start}%`;
    }

    const rangeMin = document.querySelector('.price-slider-min');
    const rangeMax = document.querySelector('.price-slider-max');
    if (rangeMin && rangeMax) {
      rangeMin.addEventListener('input', updateCustomPriceSlider);
      rangeMax.addEventListener('input', updateCustomPriceSlider);
    }
    // Sync back from inputs
    const priceMinInput = document.querySelector('.price-min');
    const priceMaxInput = document.querySelector('.price-max');
    
    if (priceMinInput && rangeMin) {
      priceMinInput.addEventListener('input', (e) => {
        rangeMin.value = e.target.value;
        updatePriceTrackVisual();
      });
    }
    
    if (priceMaxInput && rangeMax) {
      priceMaxInput.addEventListener('input', (e) => {
        rangeMax.value = e.target.value;
        updatePriceTrackVisual();
      });
    }

    updateCustomPriceSlider();

    function updateCustomSizeSlider() {
      const minInput = document.querySelector('.size-min');
      const maxInput = document.querySelector('.size-max');
      const rangeMin = document.querySelector('.size-slider-min');
      const rangeMax = document.querySelector('.size-slider-max');
      const track = document.querySelector('.size-slider-track');

      const minVal = parseInt(rangeMin.value);
      const maxVal = parseInt(rangeMax.value);

      if (minVal > maxVal) {
        [rangeMin.value, rangeMax.value] = [rangeMax.value, rangeMin.value];
      }

      minInput.value = Math.min(minVal, maxVal);
      maxInput.value = Math.max(minVal, maxVal);

      updateSizeTrackVisual();
    }

    function updateSizeTrackVisual() {
      const minRange = document.querySelector('.size-slider-min');
      const maxRange = document.querySelector('.size-slider-max');
      const track = document.querySelector('.size-slider-track');

      const min = parseInt(minRange.value);
      const max = parseInt(maxRange.value);
      const minPercent = ((min - minRange.min) / (minRange.max - minRange.min)) * 100;
      const maxPercent = ((max - maxRange.min) / (maxRange.max - maxRange.min)) * 100;

      const start = Math.min(minPercent, maxPercent);
      const end = Math.max(minPercent, maxPercent);
      track.style.right = `${start}%`;
      track.style.width = `${end - start}%`;
    }

    // Init + event binding
    const sizeRangeMin = document.querySelector('.size-slider-min');
    const sizeRangeMax = document.querySelector('.size-slider-max');
    if (sizeRangeMin && sizeRangeMax) {
      sizeRangeMin.addEventListener('input', updateCustomSizeSlider);
      sizeRangeMax.addEventListener('input', updateCustomSizeSlider);
    }

    // Sync back from inputs
    document.querySelector('.size-min').addEventListener('input', (e) => {
      sizeRangeMin.value = e.target.value;
      updateSizeTrackVisual();
    });
    document.querySelector('.size-max').addEventListener('input', (e) => {
      sizeRangeMax.value = e.target.value;
      updateSizeTrackVisual();
    });

    updateCustomSizeSlider();

    function updateCustomFloorSlider() {
      const minSelect = document.querySelector('.floor-min');
      const maxSelect = document.querySelector('.floor-max');
      const rangeMin = document.querySelector('.floor-slider-min');
      const rangeMax = document.querySelector('.floor-slider-max');

      const minVal = parseInt(rangeMin.value);
      const maxVal = parseInt(rangeMax.value);

      const sortedMin = Math.min(minVal, maxVal);
      const sortedMax = Math.max(minVal, maxVal);

      // Update selects
      $(minSelect).val(sortedMin).trigger('change').niceSelect('update');
      $(maxSelect).val(sortedMax).trigger('change').niceSelect('update');

      updateFloorTrackVisual();
    }

    function updateFloorTrackVisual() {
      const minRange = document.querySelector('.floor-slider-min');
      const maxRange = document.querySelector('.floor-slider-max');
      const track = document.querySelector('.floor-slider-track');

      const min = parseInt(minRange.value);
      const max = parseInt(maxRange.value);
      const minPercent = ((min - minRange.min) / (minRange.max - minRange.min)) * 100;
      const maxPercent = ((max - maxRange.min) / (maxRange.max - maxRange.min)) * 100;

      const start = Math.min(minPercent, maxPercent);
      const end = Math.max(minPercent, maxPercent);
      track.style.right = `${start}%`;
      track.style.width = `${end - start}%`;
    }

    // Init + bindings
    const floorRangeMin = document.querySelector('.floor-slider-min');
    const floorRangeMax = document.querySelector('.floor-slider-max');
    if (floorRangeMin && floorRangeMax) {
      floorRangeMin.addEventListener('input', updateCustomFloorSlider);
      floorRangeMax.addEventListener('input', updateCustomFloorSlider);
    }


    document.querySelector('.floor-min').addEventListener('change', (e) => {
      floorRangeMin.value = e.target.value;
      updateFloorTrackVisual();
    });

    document.querySelector('.floor-max').addEventListener('change', (e) => {
      floorRangeMax.value = e.target.value;
      updateFloorTrackVisual();
    });

    updateCustomFloorSlider();

    $('.mobile-additional-filters-btn').on('click', function (e) {
      e.preventDefault();
      e.stopPropagation();
      $('.additional-filters-container .dropdown_menu').toggleClass('mobile-visible');
    });

    $('#save-area-btn').on('click', function (e) {
      e.preventDefault();

      const userId = dirataVars.currentUserId || 0;
      const areaId = $('#area-filter').val();

      if (!userId) {
        alert('עליך להתחבר כדי לשמור אזורים');
        return;
      }

      if (!areaId) {
        alert('יש לבחור אזור לפני שמירה');
        return;
      }

      // First check if area already saved
      $.ajax({
        url: dirataVars.ajaxurl,
        method: 'POST',
        data: {
          action: 'get_user_saved_areas',
          user_id: userId
        },
        success: function (response) {
          if (response.success) {
            const html = $('<div>').html(response.data.html);
            const existingIds = html.find('.delete-area-btn').map(function () {
              return $(this).data('area-id').toString();
            }).get();

            if (existingIds.includes(areaId)) {
              alert('האזור כבר שמור אצלך');
            } else {
              // Save area
              $.post(dirataVars.ajaxurl, {
                action: 'add_user_area',
                user_id: userId,
                area_id: areaId
              }, function (res) {
                if (res.success) {
                  alert('האזור נשמר בהצלחה');
                } else {
                  alert(res.data?.message || 'שגיאה בשמירה');
                }
              });
            }
          }
        }
      });
    });

    //03. dropdown hover start
    $(".dropdown_item").hover(
      function () {
        $(this).parents('nav').addClass('open');
      },
      function () {
        $(this).parents('nav').removeClass('open');
      }
    );


    // Check URL parameters and auto-trigger filtering
    let urlParams = new URLSearchParams(window.location.search);
    let cityParam = urlParams.get('city');
    let areaParam = urlParams.get('area');

    if (cityParam) {
      $('#city-filter').val(cityParam).niceSelect('update');
    }
    if (areaParam) {
      $('#area-filter').val(areaParam).niceSelect('update');
    }

    $('#apartment-filter-form').on('submit', function (e) {
      e.preventDefault();
      filterApartments();
    });

    $(document).on('click', '#ajax-filter-btn', function (e) {
      e.preventDefault();
      e.stopPropagation();
      filterApartments();
    });


    // 05. fetch areas dynamically
    $('#city-filter').on('change', function () {
      let cityId = $(this).val();
      $.ajax({
        url: dirataVars.ajaxurl,
        type: 'GET',
        data: {
          action: 'get_areas_by_city',
          city: cityId
        },
        dataType: 'json',
        success: function (response) {
          console.log("Fetched Areas:", response);
          let areaFilter = $('#area-filter');
          let optionsHtml = '<option value="" selected>אזור/שכונה:</option>';

          if (response.success && response.data.length > 0) {
            response.data.forEach(area => {
              optionsHtml += `<option value="${area.id}">${area.name}</option>`;
            });
            areaFilter.prop('disabled', false);
          } else {
            console.log("No areas found for this city.");
            areaFilter.prop('disabled', true);
          }

          areaFilter.html(optionsHtml);
          areaFilter.niceSelect("destroy");
          areaFilter.niceSelect();
        },
        error: function (xhr, status, error) {
          console.error("Error fetching areas:", error);
        }
      });
    });

    $('.genereal_search_button_hide').on('click', function () {
      const $list = $('.general_order1');
      const $map = $('.general_order2');
      const isListVisible = $list.hasClass('general-toggle-visible');

      if (isListVisible) {
        $list.removeClass('general-toggle-visible').addClass('general-toggle-hidden');
        $map.removeClass('general-toggle-hidden').addClass('general-toggle-visible');
        $(this).text('לתצוגת רשימה');
      } else {
        $map.removeClass('general-toggle-visible').addClass('general-toggle-hidden');
        $list.removeClass('general-toggle-hidden').addClass('general-toggle-visible');
        $(this).text('לתצוגת מפה');
      }
    });


  });


  function filterApartments() {
    let filters = {
      action: 'dirata_ajax_filter_apartments',
      city: $('#city-filter').val(),
      area: $('#area-filter').val(),
      listing_type: $('input[name="listing_type"]:checked').val(),
      floor_min: $('.floor-min').val(),
      floor_max: $('.floor-max').val(),
      size_min: $('.mobile-additional-filters-menu .size-min').val() || $('.additional-filters-container .size-min').val(),
      size_max: $('.mobile-additional-filters-menu .size-max').val() || $('.additional-filters-container .size-max').val(),
      price_min: $('.mobile-additional-filters-menu .price-min').val() || $('.additional-filters-container .price-min').val(),
      price_max: $('.mobile-additional-filters-menu .price-max').val() || $('.additional-filters-container .price-max').val(),
      rooms: [],
      stage: []
    };

    $('div.additional-filters-container input[name="rooms[]"]:checked, div.mobile-additional-filters-menu input[name="rooms[]"]:checked').each(function () {
      filters.rooms.push($(this).val());
    });
    $('input[name="stage[]"]:checked').each(function () {
      filters.stage.push($(this).val());
    });

    $.ajax({
      url: dirataVars.ajaxurl,
      type: 'POST',
      data: filters,
      beforeSend: function () {
        const skeletons = Array.from({ length: 10 }).map(() => `
              <div class="col-sm-6 col-lg-6 custom_col apartment-skeleton">
                <div class="image"></div>
                <div class="desc">
                  <div class="buttons"></div>
                  <div class="desc-items">
                    <div class="title"></div>
                    <div class="details"></div>
                    <div class="price"></div>
                  </div>
                </div>
              </div>`).join('');
        $('#apartment-listings').html(skeletons);
      },
      success: function (response) {
        setTimeout(function () {
          $('#apartment-listings').html(response);
          history.replaceState(null, '', updateURL(filters));

          // Update the map markers using filters
          if (window.DirataMap) {
            window.DirataMap.disableMapEvents = true;
            if (typeof window.DirataMap.updateMarkersFromFilters === 'function') {
              window.DirataMap.updateMarkersFromFilters(filters);
            }
          }
        }, 200);
      },
      error: function () {
        console.error("AJAX Error: Could not fetch apartments.");
      }
    });
  }

  function updateURL(filters) {
    let params = new URLSearchParams();
    if (filters.city) params.set('city', filters.city);
    if (filters.area) params.set('area', filters.area);
    if (filters.listing_type) params.set('listing_type', filters.listing_type);
    if (filters.floor_min) params.set('floor_min', filters.floor_min);
    if (filters.floor_max) params.set('floor_max', filters.floor_max);
    return window.location.pathname + '?' + params.toString();
  }

})(jQuery);