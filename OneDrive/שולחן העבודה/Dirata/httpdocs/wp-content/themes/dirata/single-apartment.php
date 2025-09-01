<?php
/**
 * Single Apartment Template for Dirata Theme
 */
get_header(); ?>

<!-- Preloader Start -->
<!-- <div id="preloader">
    <div class="loader3">
        <span></span>
        <span></span>
    </div>
</div> -->
<!-- Preloader End -->

<div class="apartment_wrapper overflow-hidden">
    <div class="container">
        <div class="row custom_row">
            <div class="col-xl-9 custom_col">
                <!-- <div class="product_slide_wrap d-flex align-items-start position-relative">
                    <div id="SlickPhotoswipGallery" itemscope>
                        <?php
                         $is_for_sale = get_field('listing_type', get_the_ID());
                         $city = get_the_terms(get_the_ID(), 'city'); 
                         $city_name = $city && !is_wp_error($city) ? $city[0]->name : 'לא ידועה עיר';
                         $area = get_the_terms(get_the_ID(), 'area'); 
                         $area_name =  $area && !is_wp_error($area) ? $area[0]->name : ' לא ידועה שכונה';
                         $about_the_apartment = get_field('על_הדירה', get_the_ID());
                         $options = get_field('building_options', get_the_ID());
                         $entrance_date = get_field('entry_date', get_the_ID());
                        // $is_for_sale = get_field('sale_type', get_the_ID());
                        $city = get_the_terms(get_the_ID(), 'city'); 
                        $city_name = $city && !is_wp_error($city) ? $city[0]->name : 'לא ידועה עיר';
                        $area = get_the_terms(get_the_ID(), 'area'); 
                        $area_name =  $area && !is_wp_error($area) ? $area[0]->name : ' לא ידועה שכונה';
                        $about_the_apartment = get_field('על_הדירה', get_the_ID());
                        // $about_the_surrounding = get_field('על_הסביבה', get_the_ID());
                        $featured_img = get_field('main_img', get_the_ID());
                        $featured_img_url = '';

                        
                        if (is_array($featured_img) && isset($featured_img['url'])) {
                            $featured_img_url = $featured_img['url'];
                        } elseif (is_numeric($featured_img)) {
                            $featured_img_url = wp_get_attachment_url($featured_img);
                        } else {
                            $featured_img_url = get_template_directory_uri() . '/assets/img/logo-for-default.png';
                        }                
                         // $additional_images = get_attached_media('image', get_the_ID());
                        $additional_images = get_field('img_gallery', get_the_ID());
                        $image_urls = [];

    
                        if ($featured_img_url) {
                            $image_urls[] = $featured_img_url;
                        }
                        if ($additional_images) {
                            foreach ($additional_images as $image) {
                                // $image_urls[] = wp_get_attachment_image_src($image->ID, 'large')[0];
                                if (is_array($image) && isset($image['url'])) {
                                    $image_urls[] = $image['url'];
                                } elseif (is_numeric($image)) {
                                    $image_urls[] = wp_get_attachment_url($image);
                                }
                                
                            }
                        }
                        // 🧠 כאן מתחילה התוספת החדשה שלך:
                        $default_img = get_template_directory_uri() . '/assets/img/logo-for-default.png';
                        $image_urls = array_filter($image_urls, function($url) use ($default_img) {
                            return $url !== $default_img;
                        });

                        $image_count = count($image_urls);
                        $gallery_class = 'gallery-' . $image_count;
                    
                        foreach ($image_urls as $index => $image_url) :
                        ?>
                            <figure class="m-0" itemprop="associatedMedia" data-slideId="<?php echo $index; ?>" itemscope itemtype="http://schema.org/ImageObject">
                                <a href="<?php echo esc_url($image_url); ?>" class="hover" data-size="1024x1024">
                                    <img src="<?php echo esc_url($image_url); ?>" itemprop="thumbnail" alt="Apartment Image <?php echo $index + 1; ?>" />
                                </a>
                            </figure>
                        <?php endforeach; ?>
                    </div>
                        
                    <div class="product_mini_slider">
                        <?php foreach ($image_urls as $index => $image_url) : ?>
                            <img class="apartment_mini_img" src="<?php echo esc_url($image_url); ?>" alt="Apartment Mini Image <?php echo $index + 1; ?>">
                        <?php endforeach; ?>
                    </div>
                </div> -->
                <?php if (!empty($image_urls)) : ?>
                <div class="product_slide_wrap d-flex align-items-start position-relative <?php echo $gallery_class; ?>">
                    <div id="SlickPhotoswipGallery" itemscope>
                        <?php
                        $is_for_sale = get_field('listing_type', get_the_ID());
                        $city = get_the_terms(get_the_ID(), 'city'); 
                        $city_name = $city && !is_wp_error($city) ? $city[0]->name : 'לא ידועה עיר';
                        $area = get_the_terms(get_the_ID(), 'area'); 
                        $area_name =  $area && !is_wp_error($area) ? $area[0]->name : ' לא ידועה שכונה';
                        $about_the_apartment = get_field('על_הדירה', get_the_ID());
                        $options = get_field('building_options', get_the_ID());
                        $entrance_date = get_field('entry_date', get_the_ID());
                        $featured_img = get_field('main_img', get_the_ID());
                        $featured_img_url = '';

                        if (is_array($featured_img) && isset($featured_img['url'])) {
                            $featured_img_url = $featured_img['url'];
                        } elseif (is_numeric($featured_img)) {
                            $featured_img_url = wp_get_attachment_url($featured_img);
                        } else {
                            $featured_img_url = get_template_directory_uri() . '/assets/img/logo-for-default.png';
                        }

                        $additional_images = get_field('img_gallery', get_the_ID());
                        $image_urls = [];

                        if ($featured_img_url) {
                            $image_urls[] = $featured_img_url;
                        }

                        if ($additional_images) {
                            foreach ($additional_images as $image) {
                                if (is_array($image) && isset($image['url'])) {
                                    $image_urls[] = $image['url'];
                                } elseif (is_numeric($image)) {
                                    $image_urls[] = wp_get_attachment_url($image);
                                }
                            }
                        }

                        foreach ($image_urls as $index => $image_url) :
                        ?>
                            <figure class="m-0" itemprop="associatedMedia" data-slideId="<?php echo $index; ?>" itemscope itemtype="http://schema.org/ImageObject">
                                <a href="<?php echo esc_url($image_url); ?>"data-pswp-width="1200" data-pswp-height="900">
                                    <img src="<?php echo esc_url($image_url); ?>" itemprop="thumbnail" alt="Apartment Image <?php echo $index + 1; ?>" />
                                </a>
                            </figure>
                        <?php endforeach; ?>
                    </div>

                    <div class="product_mini_slider">
                        <?php foreach ($image_urls as $index => $image_url) : ?>
                            <img class="apartment_mini_img" src="<?php echo esc_url($image_url); ?>" alt="Apartment Mini Image <?php echo $index + 1; ?>">
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                <!-- עד כאן חדש -->

                <div class="product_content_wrap text-right"> 
                    <div class="d-flex justify-content-between">
                        <div class="text-right">
                            <?php
                            // echo $is_for_sale;
                            echo '<span style="font-size: var(--font04);">' . ($is_for_sale == "מכירה" ? 'דירה למכירה' : 'דירה להשכרה') . '</span>';
                            ?>
                            <h3 class="head_text02"><?php echo esc_html(get_the_title()); ?></h3> 
                        </div>
                        <span type="button" class="btn-save-apartment">
                            <a class="save_apartment_btn" href="#" data-post-id="<?php echo get_the_ID(); ?>">
                            לשמירת הדירה
                            </a>
                           
                            <span class="icon-circle">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/bookmark-icon.svg" alt="לשמירת הדירה">
                            </span>
                        </span>
                    </div>
    
                    <div class="apartment_product_item apartment_product_item01">
                        <p>
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon16.svg" alt="icon16"> 
                            <span>עיר: <?php echo $city_name; ?></span>
                            
                        </p>
                        <p>
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon17.svg" alt="icon17">
                            אזור: <?php echo $area_name; ?>
                        </p>
                        <p>
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon18.svg" alt="icon18">
                            <?php echo esc_html(get_post_meta(get_the_ID(), 'rooms', true)); ?> חדרים
                        </p>
                        <p>
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon19.svg" alt="icon19">
                            קומה <?php echo esc_html(get_post_meta(get_the_ID(), 'floor', true)); ?>
                        </p>
                        <p>
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon20.svg" alt="icon20">
                            <?php echo esc_html(get_post_meta(get_the_ID(), 'size', true)); ?> מ”ר
                        </p>
                    </div>

                    <div class="apartment_product_item_para mt_37 p-3" style="border: 1px solid var(--bg-green); border-radius: 10px;">
                        <h3 class="head_text02">על הדירה</h3>
                        <p class="para01 mt_13">
                        <?php echo $about_the_apartment; ?>
                        </p>
                    </div>
                    <div class="row g-3 mt_37">
                        <div class="col-12 col-md-8">
                            <div class="apartment_product_item_para p-3" style="border: 1px solid var(--bg-green); border-radius: 10px;">
                                <h3 class="head_text02">אופציות והיתרי בניה </h3>
                                <p class="para01 mt_13">
                                    <?php echo $options ?: 'לא צויין בפרסום הדירה'; ?>
                                </p>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="apartment_product_item_para p-3" style="border: 1px solid var(--bg-green); border-radius: 10px;">
                                <h3 class="head_text02">תאריך כניסה לדירה</h3>
                                <p class="para01 mt_13">
                                    <?php echo $entrance_date ?: 'לא צויין בפרסום הדירה'; ?>
                                </p>

                            </div>
                        </div>
                    </div>
                    
                    <div class="apartment_product_item_wrap mt_37 p-3" style="border: 1px solid var(--bg-green); border-radius: 10px;">
                        <h3 class="head_text02">מה יש בדירה</h3>
                        <div >
                            <?php 
                            $whats_inside = get_field('whats_inside', get_the_ID());

                            $apartment_features = array(
                                'apartment_furniture_icon01.svg' => 'ריהוט',
                                'apartment_product_icon001.svg' => 'מעלית',
                                'apartment_product_icon002.svg' => 'חצר',
                                'apartment_product_icon003.svg' => 'דוד שמש',
                                'apartment_product_icon004.svg' => 'ממד',
                                'apartment_product_icon05.svg' => 'מזגנים',
                                'apartment_product_icon06.svg' => 'חניה',
                                'apartment_product_icon07.svg' => 'אמבטיה',
                                'apartment_product_icon08.svg' => 'משופצת',
                                'apartment_product_icon09.svg' => 'סורגים',
                                'apartment_product_icon10.svg' => 'גישה לנכים',
                                'apartment_product_icon11.svg' => 'יחידת הורים',
                                'apartment_product_icon12.svg' => 'דלתות רב בריח',
                                'apartment_product_icon13.svg' => 'מרפסת',
                                'apartment_product_icon14.svg' => 'מחסן',
                                'apartment_product_icon15.svg' => 'מטבח כשר'
                                
                            );
                            

                            if (is_array($whats_inside)) {
                                foreach ($apartment_features as $icon => $label) {
                                    if (in_array($label, $whats_inside)) {
                                        ?>
                                        <div class="apartment_furniture_item_in_single_apartment">
                                        <p class="feature-active">
                                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/<?php echo esc_attr($icon); ?>" alt="<?php echo esc_attr($label); ?>">
                                            <?php echo esc_html($label); ?>
                                        </p>

                                        </div>
                                    
                                        <?php
                                    }
                                }
                            }
                            ?>
                        </div>
                    </div>


                    <!-- קו מפריד בין הדירות לדירות נוספות -->
                    <div class="mt_60" style="height: 1px; background-color: var(--bg-green);"> </div>
                    <h3 class="head_text02 mt_60">דירות נוספות של משרד התיווך</h3>
                    <div class="apartment_product_item_slider g-3">
                        <?php
                        $current_author_id = get_post_field('post_author', get_the_ID());
                        $first_name_author = get_user_meta($current_author_id, 'first_name', true);
                        $last_name_author  = get_user_meta($current_author_id, 'last_name', true);

                        $full_name_author = trim($first_name_author . ' ' . $last_name_author);
                        $office_name = get_field('office_name', 'user_' . $current_author_id);
                        $phone_author = get_field('phone', 'user_' . $current_author_id);
                        $whatsapp_author = get_field('broker_whatsapp', 'user_' . $current_author_id);
                        // $current_author_name = 'יוסי';
                        $apartments_query = new WP_Query(array(
                            'post_type'      => 'apartment',
                            'status'      => 'publish',
                            'posts_per_page' => -1, // Pull all apartments
                            'post__not_in'   => array(get_the_ID()),
                        ));
                        $current_apartment_area = get_the_terms(get_the_ID(), 'area');
                        if ($apartments_query->have_posts()) :
                            while ($apartments_query->have_posts()) : $apartments_query->the_post();
                            if (get_post_field('post_author', get_the_ID()) != $current_author_id){
                                continue;
                            }
                                $img_id = get_post_meta(get_the_ID(), 'main_img', true);
                                $img_url = wp_get_attachment_url($img_id) ?: get_template_directory_uri() . '/assets/img/logo-for-default.png';
                                // $image = has_post_thumbnail() ? $img_url : get_template_directory_uri() . '/assets/img/default-apartment.svg';
                                $location = get_the_terms(get_the_ID(), 'city'); 
                                $location_name = $location && !is_wp_error($location) ? $location[0]->name : 'לא ידועה עיר';
                                $area = get_the_terms(get_the_ID(), 'area'); 
                                $area_name =  $area && !is_wp_error($area) ? $area[0]->name : ' לא ידועה שכונה';
                                $address = get_the_title();
                                $type = get_post_meta(get_the_ID(), 'listing_type', true);
                                if($type == 'rent') {
                                    $type = 'השכרה';
                                } else {
                                    $type = 'מכירה';
                                }
                                $rooms = get_post_meta(get_the_ID(), 'rooms', true);
                                $floor = get_post_meta(get_the_ID(), 'floor', true);
                                $size = get_post_meta(get_the_ID(), 'size', true);
                                $price = get_post_meta(get_the_ID(), 'price', true);
                                ?>
                                <!-- <div class="apartment_item mt_30">
                                    <figure class="position-relative m-0">
                                        <a class="love_icon" href="#"><i class="far fa-heart"></i></a>
                                        <a class="apartment_img" href="<?php the_permalink(); ?>">
                                            <img class="img-fluid" src="<?php echo esc_url($img_url); ?>" alt="Apartment">
                                        </a>
                                        <span><?php echo esc_html($location_name); ?></span>
                                    </figure>
                                    <div class="apartment_content">
                                        <h4><a href="<?php the_permalink(); ?>"><?php echo esc_html($address); ?></a></h4>
                                        <p><?php echo esc_html($type); ?> - <?php echo esc_html($rooms); ?> חדרים - קומה <?php echo esc_html($floor); ?> - <?php echo esc_html($size); ?> מ”ר</p>
                                        <span><?php echo esc_html($price); ?> ₪</span>
                                    </div>
                                </div> -->
                                <div class="card_item03 mt_10 apartment-card" data-href="<?php the_permalink(); ?>">
                                    <img class="img-fluid apartment_img" src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($address); ?>">
                                    <div class="card_item03_content">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span class="sale_text"><?php echo esc_html($type); ?></span>
                                            <a class="location_icon" href="#"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon21.svg" alt="location icon"></a>
                                        </div>
                                        <p class="head_para mt_13">
                                            <a target="_blank" href="<?php the_permalink(); ?>"><?php echo esc_html($address); ?></a>
                                        </p>
                                        <p class="retail_para_resposive"><?php echo esc_html($type); ?> | <?php echo esc_html($rooms); ?> חדרים | קומה <?php echo esc_html($floor); ?> | <?php echo esc_html($size); ?> מ"ר</p>
                                        <p class="font400 mt_8"><?php echo safe_price($price); ?> ₪</p>
                                        <div class="apartment-details-grid">
                                            <span class="d-flex"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon16.svg" alt="icon16"> <?php echo esc_html($location_name); ?></span>
                                            <span class="d-flex"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon17.svg" alt="icon17"> <?php echo esc_html($area_name); ?></span>
                                            <span class="d-flex"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon18.svg" alt="icon18"> <?php echo esc_html($rooms); ?> חדרים</span>
                                            <span class="d-flex"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon19.svg" alt="icon19"> קומה <?php echo esc_html($floor); ?></span>
                                            <span class="d-flex"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon20.svg" alt="icon20"> <?php echo esc_html($size); ?> מ"ר</span>
                                        </div>
                                    </div>
                                </div>

                            <?php endwhile;
                            wp_reset_postdata();
                        else : ?>
                            <p>אין דירות זמינות</p>
                        <?php endif; ?>
                    </div>
                        
                    <h3 class="head_text02 mt_60">עוד דירות למכירה באזור</h3>
                    <div class="apartment_product_item_slider">
                        <?php
                        if ($apartments_query->have_posts()) :
                            while ($apartments_query->have_posts()) : $apartments_query->the_post();
                                $img_id = get_post_meta(get_the_ID(), 'main_img', true);
                                $img_url = wp_get_attachment_url($img_id) ?: get_template_directory_uri() . '/assets/img/logo-for-default.png';
                                // $image = has_post_thumbnail() ? $img_url : get_template_directory_uri() . '/assets/img/default-apartment.svg';
                                $location = get_the_terms(get_the_ID(), 'city'); 
                                $location_name = $location && !is_wp_error($location) ? $location[0]->name : 'לא ידועה עיר';
                                $area = get_the_terms(get_the_ID(), 'area'); 
                                $area_name =  $area && !is_wp_error($area) ? $area[0]->name : ' לא ידועה שכונה';
                                $address = get_the_title();
                                $type = get_post_meta(get_the_ID(), 'listing_type', true);
                                if($type == 'rent') {
                                    $type = 'השכרה';
                                } else {
                                    $type = 'מכירה';
                                }
                                $rooms = get_post_meta(get_the_ID(), 'rooms', true);
                                $floor = get_post_meta(get_the_ID(), 'floor', true);
                                $size = get_post_meta(get_the_ID(), 'size', true);
                                $price = get_post_meta(get_the_ID(), 'price', true);
                                $belong_to_this_area = false;
                                $author_img_url = get_field('office_logo', 'user_'.$current_author_id);
                                // $author_img_url = wp_get_attachment_url($author_img_id) ?: get_template_directory_uri() . '/assets/img/logo-for-default.png';
                                if($area == $current_apartment_area){
                                    $belong_to_this_area = true;
                                }
                                if($belong_to_this_area){                               
                                ?>
                                <!-- <div class="apartment_item mt_30">
                                    <figure class="position-relative m-0">
                                        <a class="love_icon" href="#"><i class="far fa-heart"></i></a>
                                        <a class="apartment_img" href="<?php the_permalink(); ?>">
                                            <img class="img-fluid" src="<?php echo esc_url($img_url); ?>" alt="Apartment">
                                        </a>
                                        <span><?php echo esc_html($location_name); ?></span>
                                    </figure>
                                    <div class="apartment_content">
                                        <h4><a href="<?php the_permalink(); ?>"><?php echo esc_html($address); ?></a></h4>
                                        <p><?php echo esc_html($type); ?> - <?php echo esc_html($rooms); ?> חדרים - קומה <?php echo esc_html($floor); ?> - <?php echo esc_html($size); ?> מ”ר</p>
                                        <span><?php echo esc_html($price); ?> ₪</span>
                                    </div>
                                </div> -->

                                <div class="card_item03 mt_10 apartment-card" data-href="<?php the_permalink(); ?>">
                                    <img class="img-fluid apartment_img" src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($address); ?>">
                                    <div class="card_item03_content">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span class="sale_text"><?php echo esc_html($type); ?></span>
                                            <a class="location_icon" href="#"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon21.svg" alt="location icon"></a>
                                        </div>
                                        <p class="head_para mt_13">
                                            <a target="_blank" href="<?php the_permalink(); ?>"><?php echo esc_html($address); ?></a>
                                        </p>
                                        <p class="retail_para_resposive"><?php echo esc_html($type); ?> | <?php echo esc_html($rooms); ?> חדרים | קומה <?php echo esc_html($floor); ?> | <?php echo esc_html($size); ?> מ"ר</p>
                                        <p class="font400 mt_8"><?php echo safe_price($price); ?> ₪</p>
                                        <div class="apartment-details-grid">
                                            <span class="d-flex"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon16.svg" alt="icon16"> <?php echo esc_html($location_name); ?></span>
                                            <span class="d-flex"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon17.svg" alt="icon17"> <?php echo esc_html($area_name); ?></span>
                                            <span class="d-flex"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon18.svg" alt="icon18"> <?php echo esc_html($rooms); ?> חדרים</span>
                                            <span class="d-flex"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon19.svg" alt="icon19"> קומה <?php echo esc_html($floor); ?></span>
                                            <span class="d-flex"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon20.svg" alt="icon20"> <?php echo esc_html($size); ?> מ"ר</span>
                                        </div>
                                    </div>
                                </div>
                            <?php } 
                            else{
                                echo "לא מצאנו דירות נוספות באזור זה";
                            }
                            endwhile;
                            wp_reset_postdata();
                        else : ?>
                            <p>אין דירות זמינות</p>
                        <?php endif; ?>
                    </div>

                </div>
            </div>

            <div class="col-xl-3 custom_col">
                <div class="row">
                    <div class="col-xl-12 col-lg-4 col-sm-6">
                        <div class="apartment_map_box">
                            <div id="map"></div>
                        </div>
                    </div>

                    <!-- Apartment Form Box -->
                    <div class="col-xl-12 col-lg-4 col-sm-6 mt_30">
                        <div class="apartment_form_box">
                            <span style="position: absolute; top: 10px; left: 30px; font-size: 14px;"><?php echo 'מספר דירה (' . get_the_ID() . ')'; ?></span>
                            <p class="mt-3">מחיר הדירה</p>
                            <h3 class="head_text02 mt_8">₪ <?php echo safe_price(get_post_meta(get_the_ID(), 'price', true)); ?></h3>

                            <p class="mt_37">מפרסם הדירה</p>
                            <div class="d-flex align-items-center mt_13">
                                <!-- <img class="apartment_form_logo img-fluid" src="<?php echo $author_img_url; ?>" alt="broker_logo"> -->
                                 
                                <?php if ( !empty($author_img_url) ) : ?>
                                    <img class="apartment_form_logo img-fluid rounded-circle object-fit-cover" src="<?php echo esc_url($author_img_url); ?>" alt="broker_logo">
                                <?php endif; ?>
                                <span>
                                    <?php 
                                    echo $full_name_author . ' - ' . $office_name;
                                    ?>
                                </span>
                            </div>
                            <a class="number_btn custom_btn mt_20" href="#" data-phone="<?php echo esc_attr($phone_author); ?>">להצגת מספר הטלפון</a>
                            <!-- <a class="whatsapp_btn custom_btn mt_20" href="<?php echo esc_attr($whatsapp_author); ?>">לשליחת הודעה בוואצאפ</a> -->
                            <?php
                            if (!empty($whatsapp_author)) :
                            // ניקוי מספר (להסרת מקפים ורווחים, אם יש)
                            $clean_number = preg_replace('/\D/', '', $whatsapp_author);
                            $whatsapp_url = 'https://wa.me/' . $clean_number;
                            ?>
                            <a class="whatsapp_btn custom_btn mt_20 text-nowrap" href="<?php echo esc_url($whatsapp_url); ?>" target="_blank" rel="noopener">
                                לשליחת הודעה בוואצאפ
                            </a>
                        <?php endif; ?>
                            <p class="mt_37">שליחת הודעה למפרסם</p>
                            <form class="mt_45" method="post" id="apartment-lead-form">
                                <input type="hidden" name="action" value="save_apartment_lead">
                                <input type="hidden" name="apartment_id" value="<?php echo get_the_ID(); ?>">
                               
                                <!-- <div class="d-flex align-items-end">
                                    <input type="text" dir="rtl" name="name" placeholder="שם:" required="" class="ms-2 ml-1" style="border: 1px solid #20315f;">
                                    <input type="tel" dir="rtl" name="phone" placeholder="טלפון:" required="" class="mt_15" style="border: 1px solid #20315f;">
                                </div> -->
                                <div class="d-flex justify-content-between flex-wrap" style="gap: 1rem;">


                                    <div style="flex: 1;">
                                        <label for="name" style="display: flex; text-align: right;">
                                         שם
                                         <span style="color: red;">*</span>
                                        </label>
                                        <input type="text" id="name" name="name" dir="rtl" placeholder="שם" required
                                        style="border-radius: 3px; border: 1px solid #20315f; padding: 10px;">
                                    </div>
                                    <div style="flex: 1;">
                                        <label for="phone" style="display: flex; text-align: right;">
                                         טלפון
                                         <span style="color: red;">*</span>
                                        </label>
                                        <input type="tel" id="phone" name="phone" dir="rtl" placeholder="טלפון" required
                                        style="border-radius: 3px; border: 1px solid #20315f; padding: 10px;">
                                    </div>
                                </div>

                                <button type="submit" class="custom_btn mt_20">שליחה ←</button>
                            </form>
                            <span id="lead-success-message" style="display:none; color: green;"></span>
                            <span id="lead-error-message" style="display:none; color: red;"></span>
                        </div>
                    </div>
                    <!-- <div class="col-xl-12 col-lg-4 col-sm-6 mt_30">
                        <div class="join_box">
                            <img class="join_logo" src="<?php echo get_template_directory_uri(); ?>/assets/img/join_logo.svg" alt="join_logo">
                            <h2 class="head_text02">הצטרפו לקבוצת הווצאפ של דירתא</h2>
                            <a href="#" class="custom_btn mt_14">להצטרפות ←</a>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$longitude = get_post_meta(get_the_ID(), 'longitude', true);
$latitude = get_post_meta(get_the_ID(), 'latitude', true);
?>

<script>
    var singleApartmentData = {
        id: "<?php echo get_the_ID(); ?>",
        longitude: "<?php echo esc_js($longitude); ?>",
        latitude: "<?php echo esc_js($latitude); ?>"
    };



// ממציג את מספר הטלפון של המתווך כשלוחצים על "להצגת מספר הטלפון"
    document.addEventListener('DOMContentLoaded', function () {
    const numberBtn = document.querySelector('.number_btn');
    if (numberBtn) {
        numberBtn.addEventListener('click', function (e) {
        e.preventDefault();
        const phone = this.getAttribute('data-phone');
        this.textContent = phone;
        this.setAttribute('href', 'tel:' + phone);
        });
    }
    });

// שולח את הנתונים של טופס שליחת ליד למתווך
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('apartment-lead-form');

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(form);
        formData.append('action', 'save_apartment_lead');

        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(response => {
            if (response.success) {
                document.getElementById('lead-success-message').innerText = response.data;
                document.getElementById('lead-success-message').style.display = 'block';
                document.getElementById('lead-error-message').style.display = 'none';
                form.reset();
            } else {
                document.getElementById('lead-error-message').innerText = response.data;
                document.getElementById('lead-error-message').style.display = 'block';
                document.getElementById('lead-success-message').style.display = 'none';
            }
        })
        .catch(error => {
            document.getElementById('lead-error-message').innerText = 'שגיאה כללית בשליחה.';
            document.getElementById('lead-error-message').style.display = 'block';
            document.getElementById('lead-success-message').style.display = 'none';
        });
    });
});


document.querySelectorAll('.save_apartment_btn').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const postId = this.dataset.postId;

                fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
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
                    if (data.success) {
                        alert('הדירה נוספה לרשימה שלך!');
                    } else {
                        alert(data.data || 'שגיאה לא ידועה');
                    }
                });
            });
});

</script>

<!-- PhotoSwipe 5 via jsDelivr -->
<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/photoswipe@5.3.8/dist/photoswipe.css" />
<script src="https://cdn.jsdelivr.net/npm/photoswipe@5.3.8/dist/photoswipe.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/photoswipe@5.3.8/dist/photoswipe-lightbox.umd.min.js"></script> -->



<?php get_footer(); ?>
