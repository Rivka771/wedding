<?php
/**
 * Archive Template for Apartments
 */

get_header(); ?>

<div class="general_search_combined_gallery_map_filter_wrapper overflow-hidden" style="background-image: url(<?php echo get_template_directory_uri(); ?>/assets/img/bg01.svg);">
    <div class="general_search_wrap01">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between flex-wrap">
                <nav class="d-flex align-items-center flex-wrap">
                    <!-- Filters (City, Area, Price, etc.) -->
                    <div class="mt_14 ml_14">
                        <select class="nice-select" id="select1">
                            <option value=""><?php _e('עיר:', 'dirata'); ?></option>
                            <!-- Add Cities Dynamically -->
                            <?php
                            $cities = get_terms(array('taxonomy' => 'city', 'hide_empty' => false));
                            foreach ($cities as $city) {
                                echo '<option value="' . esc_attr($city->slug) . '">' . esc_html($city->name) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <!-- Add more filters dynamically as needed -->
                </nav>
            </div>
        </div>
    </div>
    <div class="general_search_wrap02">
        <div class="row custom_row">
            <!-- Apartment Listings -->
            <div class="col-lg-6 general_order1 custom_col">
                <div class="general_search_item_wrap">
                    <div class="row custom_row">
                        <?php if (have_posts()) : ?>
                            <?php while (have_posts()) : the_post(); ?>
                                <div class="col-sm-4 col-6 custom_col">
                                    <div class="apartment_item mt_20">
                                        <figure class="position-relative m-0">
                                            <a class="love_icon" href="#"><i class="far fa-heart"></i></a>
                                            <?php if (has_post_thumbnail()) : ?>
                                                <img class="apartment_img img-fluid" src="<?php the_post_thumbnail_url('medium'); ?>" alt="<?php the_title_attribute(); ?>">
                                            <?php else : ?>
                                                <img class="apartment_img img-fluid" src="<?php echo get_template_directory_uri(); ?>/assets/img/default-apartment.svg" alt="<?php the_title_attribute(); ?>">
                                            <?php endif; ?>
                                            <span><?php echo get_the_term_list(get_the_ID(), 'city', '', ', ', ''); ?></span>
                                        </figure>
                                        <div class="apartment_content">
                                            <h4><?php the_title(); ?></h4>
                                            <p><?php echo get_post_meta(get_the_ID(), 'details', true); ?></p>
                                            <span><?php echo get_post_meta(get_the_ID(), 'price', true); ?> ₪</span>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <p><?php _e('לא נמצאו דירות.', 'dirata'); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Map Section -->
            <div class="col-lg-6 general_order2 position-relative custom_col">
                <div id="map"></div>
                <a class="online_update_btn" href="#"><?php _e('לבחירת אזור לעדכונים אונליין ←', 'dirata'); ?></a>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>