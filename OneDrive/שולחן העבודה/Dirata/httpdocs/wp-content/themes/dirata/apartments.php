<?php
/**
 * Template Name: Apartments Page
 * Description: A template for displaying apartment listings.
 */

get_header(); ?>

<?php
$selected_city   = isset($_GET['city']) ? absint($_GET['city']) : '';
$selected_area = isset($_GET['area']) ? absint($_GET['area']) : '';
?>

<div class="general_search_combined_gallery_map_filter_wrapper overflow-hidden" style="background-image: url(<?php echo get_template_directory_uri(); ?>/assets/img/bg01.svg);">
    <div class="general_search_wrap01">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between flex-wrap">
                <nav class="d-flex align-items-center flex-wrap">
                    <!-- Additional Filters Mobile Button (shown only on mobile) -->
                    <div class="dropdown_item dropdown mt_14 ml_10">
                      <button class="mobile-additional-filters-btn">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/filter_icon.svg" alt="סינונים נוספים">
                      </button>
                      <div class="mobile-additional-filters-menu">
                        <?php 
                        
                        get_template_part('template-parts/additional-filters'); 
                        ?>
                      </div>
                    </div>
                    
                    <!-- City Filter -->
                    <div class="single_filter mt_14 ml_14">
                      <?php
                      $selected_city = isset($_GET['city']) ? absint($_GET['city']) : '';
                      ?>
                      <select class="nice-select" id="city-filter">
                          <option value=""><?php _e('עיר:', 'dirata'); ?></option>
                          <?php
                          $cities = get_terms(array('taxonomy' => 'city', 'hide_empty' => false));
                          if (!is_wp_error($cities) && !empty($cities)) {
                              foreach ($cities as $city) {
                                  $selected_attr = ($selected_city == $city->term_id) ? 'selected="selected"' : '';
                                  echo '<option value="' . esc_attr($city->term_id) . '" ' . $selected_attr . '>' . esc_html($city->name) . '</option>';
                              }
                          }
                          ?>
                      </select>
                    </div>
                      
                    <!-- Area Filter -->
                    <div class="single_filter mt_14 ml_14">
                      <select class="nice-select" id="area-filter" name="area">
                          <option value=""><?php _e('אזור/שכונה:', 'dirata'); ?></option>
                      </select>
                    </div>
                      
                    <!-- Listing Type (Rent/Buy) Filter - desktop only -->
                    <div class="mt_14 ml_14 listing_type_toggle desktop-only">
                      <?php
                      $selected_listing_type = isset($_GET['listing_type']) ? sanitize_text_field($_GET['listing_type']) : 'sale';
                      ?>
                      <div class="rent-buy-toggle">
                          <input type="radio" id="sale-option" name="listing_type" value="sale" <?php checked($selected_listing_type, 'sale'); ?> />
                          <label for="sale-option"><?php _e('מכירה', 'dirata'); ?></label>
                          <input type="radio" id="rent-option" name="listing_type" value="rent" <?php checked($selected_listing_type, 'rent'); ?> />
                          <label for="rent-option"><?php _e('השכרה', 'dirata'); ?></label>
                      </div>
                    </div>
                      
                    <!-- Additional Filters Container (common to both desktop and mobile) -->
                    <div class="dropdown_item dropdown mt_14 ml_14 additional-filters-container">
                      <!-- Desktop Additional Filters Button -->
                      <button class="dropdown_toggle dropdown-toggle desktop-additional-btn" data-toggle="dropdown" aria-haspopup="false" aria-expanded="false" type="button">
                        סינונים נוספים
                      </button>
                      <?php 
                      set_query_var('col_width', 6);
                      get_template_part('template-parts/additional-filters'); ?>
                    </div>
                            
                    <a class="search_btn mt_14" id="ajax-filter-btn" href="#">חיפוש ←</a>
                    <a href="" class="desktop-only" id="save-area-btn">לשמירת החיפוש ←</a>
                </nav>
            </div>
        </div>
    </div>

    <div class="general_search_wrap02">
      <button class="genereal_search_button_hide mobile-only">לתצוגת מפה</button>

        <div class="row custom_row">
            <!-- Apartment Listings -->
            <div class="col-lg-7 general_order1 custom_col">
                <div class="general_search_item_wrap">
                  <div id="apartment-listings" class="row custom_row">
                    <?php
                    $selected_city = isset($_GET['city']) ? absint($_GET['city']) : '';
                    $selected_area = isset($_GET['area']) ? absint($_GET['area']) : '';
                                        
                    $args = array(
                        'post_type'      => 'apartment',
                        'posts_per_page' => 30,
                        'post_status' => 'publish',
                    );
                  
                    if ($selected_city || $selected_area) {
                        $args['tax_query'] = array('relation' => 'AND');
                    
                        if ($selected_city) {
                            $args['tax_query'][] = array(
                                'taxonomy' => 'city',
                                'field'    => 'term_id',
                                'terms'    => $selected_city,
                            );
                        }
                      
                        if ($selected_area) {
                            $args['tax_query'][] = array(
                                'taxonomy' => 'area',
                                'field'    => 'term_id',
                                'terms'    => $selected_area,
                            );
                        }
                    }
                  
                    echo dirata_list_apartments($args);
                    ?>
                  </div>
                </div>
            </div>

            <!-- Map Section -->
            <div class="col-lg-5 general_order2 position-sticky custom_col">
                <div id="map"></div>
            </div>
        </div>
    </div>
</div>
<script>
  // document.querySelectorAll('.love_icon').forEach(function (iconLink) {
  //   iconLink.addEventListener('click', function(e) {
  //     e.preventDefault();
  //     e.stopPropagation();

  //     // if already processing this element, return
  //     if (this.dataset.processing === "true") {
  //       return;
  //     }
  //     this.dataset.processing = "true";

  //     const postId = this.dataset.postId;
  //     const iconElement = this.querySelector('i');

  //     // Perform the AJAX request to save the apartment:
  //     fetch('<?php //echo admin_url("admin-ajax.php"); ?>', {
  //       method: 'POST',
  //       credentials: 'same-origin',
  //       headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
  //       body: new URLSearchParams({
  //         action: 'save_apartment_to_user',
  //         post_id: postId,
  //       })
  //     })
  //     .then(response => response.json())
  //     .then(data => {
  //       this.dataset.processing = "";
  //       if (data.success) {
  //         alert('הדירה נוספה לרשימה שלך!');
  //       } else {
  //         alert(data.data || 'שגיאה לא ידועה');
  //       }
  //     })
  //     .catch(error => {
  //       console.error(error);
  //       this.dataset.processing = "";
  //     });
  //   });
  // });
  // document.querySelectorAll('.save_apartment_btn').forEach(function (btn) {
  //             btn.addEventListener('click', function (e) {
  //                 e.preventDefault();
  //                 const postId = this.dataset.postId;

  //                 fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
  //                     method: 'POST',
  //                     credentials: 'same-origin',
  //                     headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
  //                     body: new URLSearchParams({
  //                         action: 'save_apartment_to_user',
  //                         post_id: postId,
  //                     })
  //                 })
  //                 .then(response => response.json())
  //                 .then(data => {
  //                     if (data.success) {
  //                         alert('הדירה נוספה לרשימה שלך!');
  //                     } else {
  //                         alert(data.data || 'שגיאה לא ידועה');
  //                     }
  //                 });
  //             });
  // });
  if (!window.saveApartmentHandlerLoaded) {
  window.saveApartmentHandlerLoaded = true;

  document.addEventListener('DOMContentLoaded', function () {
    document.body.addEventListener('click', function (e) {
      const btn = e.target.closest('.save_apartment_btn');
      if (!btn) return;

      e.preventDefault();
      const postId = btn.dataset.postId;

      fetch('/wp-admin/admin-ajax.php', {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
          action: 'save_apartment_to_user',
          post_id: postId,
        })
      })
        .then(response => response.json())
        .then(data => {
          const message = data.success
            ? (data.data === 'already_saved' ? 'כבר שמרת את הדירה הזו' : 'הדירה נוספה למועדפים בהצלחה')
            : (data.data || 'שגיאה לא ידועה');

          showTooltip(btn, message);
        });
    });
  });

  function showTooltip(btn, message) {
    const existing = btn.parentElement.querySelector('.save-tooltip');
    if (existing) existing.remove();

    const tooltip = document.createElement('div');
    tooltip.className = 'save-tooltip';
    tooltip.textContent = message;
    btn.parentElement.appendChild(tooltip);

    // מיקום יחסי
    tooltip.style.position = 'absolute';
    tooltip.style.left = (btn.offsetLeft + btn.offsetWidth / 2) + 'px';
    tooltip.style.top = (btn.offsetTop - 10) + 'px';
    tooltip.style.transform = 'translateX(-10%)';

    setTimeout(() => tooltip.classList.add('show'), 10);
    setTimeout(() => tooltip.remove(), 1600);
  }
}



</script>
<?php get_footer(); ?>