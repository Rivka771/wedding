<?php
/**
 * Template Part: Apartment Item
 *
 * Displays a single apartment listing.
 */
?>
<!-- <div class="col-sm-4 col-lg-4 custom_col apartment-item"
     data-apartment-id="<?php echo esc_attr(get_the_ID()); ?>"
     data-url="<?php echo esc_url(get_permalink()); ?>"
     data-longitude="<?php echo esc_attr(get_post_meta(get_the_ID(), 'longitude', true)); ?>"
     data-latitude="<?php echo esc_attr(get_post_meta(get_the_ID(), 'latitude', true)); ?>">
    <div class="apartment-link">
        <div class="apartment_item mt_20">
            <figure class="position-relative m-0">
                <a class="love_icon" href="#" data-post-id="<?php echo esc_attr(get_the_ID()); ?>">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon12.svg" alt="icon12">
                </a>
                <?php 
                // $bg_image = get_field('main_img', get_the_ID() , true);
                $bg_image_id = get_post_meta(get_the_ID(), 'main_img', true);
                $bg_image_url = wp_get_attachment_url($bg_image_id);
                if ($bg_image_url == ''){
                  $bg_image_url = get_template_directory_uri() . '/assets/img/default-apartment.svg';
                }
                 
                ?>
                <div class="apartment_img" style="background-image: url('<?php echo esc_url($bg_image_url); ?>');"></div>
                <span>
                  <?php
                  $cities = get_the_terms(get_the_ID(), 'city');
                  if ($cities && !is_wp_error($cities)) {
                      $city_names = wp_list_pluck($cities, 'name');
                      echo implode(', ', $city_names);
                  }
                  ?>
                </span>
            </figure>
            <div class="apartment_content">
                <h4><?php the_title(); ?></h4>
                <?php
                    $listing_type = get_field('listing_type', get_the_ID());
                    $rooms        = get_post_meta(get_the_ID(), 'rooms', true);
                    $floor        = get_post_meta(get_the_ID(), 'floor', true);
                    $size         = get_post_meta(get_the_ID(), 'size', true);
                ?>
                <p>
                    <?php echo esc_html($listing_type); ?> | 
                    <?php echo esc_html($rooms); ?> חדרים | 
                    קומה <?php echo esc_html($floor); ?> | 
                    <?php echo esc_html($size); ?> מ"ר
                </p>
                <?php $price = get_post_meta(get_the_ID(), 'price', true); ?>
                <span><?php echo safe_price($price); ?> ₪</span>

            </div>
        </div>
    </div>
</div> -->

<!-- העברתי להערה בתאריך 11.6.25 -->
<!-- <div class="col-sm-12 col-lg-12 custom_col apartment-item mt-3"
     data-apartment-id="<?php echo esc_attr(get_the_ID()); ?>"
     data-url="<?php echo esc_url(get_permalink()); ?>"
     data-longitude="<?php echo esc_attr(get_post_meta(get_the_ID(), 'longitude', true)); ?>"
     data-latitude="<?php echo esc_attr(get_post_meta(get_the_ID(), 'latitude', true)); ?>">

    <div class="card_item03 mt_10">
        <?php 
        $bg_image_id = get_post_meta(get_the_ID(), 'main_img', true);
        $bg_image_url = wp_get_attachment_url($bg_image_id);
        if (!$bg_image_url) {
            $bg_image_url = get_template_directory_uri() . '/assets/img/default-apartment.svg';
        }
        ?>
        <img class="img-fluid apartment_img" src="<?php echo esc_url($bg_image_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">

        <div class="card_item03_content">
            <div class="d-flex align-items-center justify-content-between">
                <?php $listing_type = get_field('listing_type', get_the_ID()); ?>
                <span class="sale_text"><?php echo esc_html($listing_type); ?></span>
                <a class="location_icon save_apartment_btn" href="#" data-post-id="<?php echo get_the_ID(); ?>"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon21.svg" alt="location icon"></a>
            </div>

            <p class="head_para mt_13">
                <a target="_blank" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </p>

            <?php
                $rooms = get_post_meta(get_the_ID(), 'rooms', true);
                $floor = get_post_meta(get_the_ID(), 'floor', true);
                $size  = get_post_meta(get_the_ID(), 'size', true);
                $price = get_post_meta(get_the_ID(), 'price', true);
            ?>
            <p class="retail_para_resposive">
                <?php echo esc_html($listing_type); ?> |
                <?php echo esc_html($rooms); ?> חדרים |
                קומה <?php echo esc_html($floor); ?> |
                <?php echo esc_html($size); ?> מ"ר
            </p>

            <p class="font400 mt_8" style="font-weight: 600!important"><?php echo safe_price($price); ?> ₪</p>

            <div class="apartment-details-grid">
                <?php
                    $cities = get_the_terms(get_the_ID(), 'city');
                    $city_name = ($cities && !is_wp_error($cities)) ? $cities[0]->name : 'עיר לא זמינה';

                    $areas = get_the_terms(get_the_ID(), 'area');
                    $area_name = ($areas && !is_wp_error($areas)) ? $areas[0]->name : 'אזור לא זמין';
                ?>
                <span class="d-flex"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon16.svg" alt="icon16"> <?php echo esc_html($city_name); ?></span>
                <span class="d-flex"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon17.svg" alt="icon17"> <?php echo esc_html($area_name); ?></span>
                <span class="d-flex"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon18.svg" alt="icon18"> <?php echo esc_html($rooms); ?> חדרים</span>
                <span class="d-flex"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon19.svg" alt="icon19"> קומה <?php echo esc_html($floor); ?></span>
                <span class="d-flex"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon20.svg" alt="icon20"> <?php echo esc_html($size); ?> מ"ר</span>
            </div>
        </div>
    </div>
</div> -->

<?php
$img_id = get_post_meta(get_the_ID(), 'main_img', true);
$img_url = wp_get_attachment_url($img_id);
if (!$img_url) {
    $img_url = get_template_directory_uri() . '/assets/img/logo-for-default.png';
}

$terms = get_the_terms(get_the_ID(), 'city');
$city = ($terms && !is_wp_error($terms)) ? $terms[0]->name : 'לא צוינה עיר';

$title = get_the_title();
$rooms = get_post_meta(get_the_ID(), 'rooms', true);
$floor = get_post_meta(get_the_ID(), 'floor', true);
$size  = get_post_meta(get_the_ID(), 'size', true);
$price = get_field('price');

// if (safe_price($price)) {
//     $price = safe_price($price);
// } else {
//     $price = '—';
// }

$listing_type = get_field('listing_type');
$col_width = get_query_var('col_width', 12);
?>

<div class="col-lg-<?php echo esc_attr($col_width); ?> custom_col col-md-6 col-sm-10 mt_10">
    <div class="card_item03 mt_10 apartment-card" data-href="<?php the_permalink(); ?>" data-apartment-id="<?php echo get_the_ID(); ?>">
    <?php
        $show_edit_icons = get_query_var('show_edit_icons', false);
        if($show_edit_icons){
    ?>

        <div class="card_edit_view_icon" data-id="<?php echo get_the_ID(); ?>">
            <a href="#"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon023.svg" alt="icon023"></a>
            <a href="#"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon024.svg" alt="icon024"></a>
        </div>
    <?php
    }
    ?>

        <img class="img-fluid apartment_img" src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($title); ?>">

        <div class="card_item03_content">
            <div class="d-flex align-items-center justify-content-between">
                <span class="sale_text"><?php echo esc_html($listing_type); ?></span>
                <a class="location_icon save_apartment_btn" href="#" data-post-id="<?php echo get_the_ID(); ?>">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon21.svg" alt="icon21">
                </a>
            </div>

            <p class="head_para mt_13">
                <a target="_blank" href="<?php the_permalink(); ?>"><?php echo esc_html($title); ?></a>
            </p>

            <p class="retail_para_resposive">
                <?php echo esc_html($listing_type); ?> |
                <?php echo esc_html($rooms); ?> חדרים |
                קומה <?php echo esc_html($floor); ?> |
                <?php echo esc_html($size); ?> מ"ר
            </p>

            <p class="font400 mt_8" style="font-weight: 600!important"><?php echo safe_price($price); ?> ₪</p>


            <div class="retail_responsive_none">
                <span><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon16.svg" alt="icon16"> <?php echo esc_html($city); ?></span>
                <span><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon18.svg" alt="icon18"> <?php echo esc_html($rooms); ?> חדרים</span>
                <span><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon19.svg" alt="icon19"> קומה <?php echo esc_html($floor); ?></span>
                <span><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon20.svg" alt="icon20"> <?php echo esc_html($size); ?> מ"ר</span>
            </div>
        </div>
    </div>
</div>


