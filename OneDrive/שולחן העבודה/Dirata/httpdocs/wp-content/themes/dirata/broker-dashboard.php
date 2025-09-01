<?php
/* Template Name: Broker Dashboard */
get_header();
?>
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/style.css">

<?php
$user_id = get_current_user_id();
$user_info = get_userdata($user_id);
$user_data  = get_userdata($user_id);
$first_name = $user_data->first_name;
$last_name  = $user_data->last_name;
$email      = $user_data->user_email;
$phone      = get_user_meta($user_id, 'phone', true);
$notification_methods = get_field('notification_method', 'user_' . $user_id) ?: [];
$notification_frequency = get_field('notification_frequency', 'user_' . $user_id);
$profile_image = get_avatar_url($user_id);
$user_role = $user_data->roles[0];
$office_name = 'אין משרד למשתמש זה כי הוא לא מתווך';
if($user_role == 'broker'){
    $office_name = get_field('office_name', 'user_' . $user_id);
}
?>

<!-- Personal Area Registered area start -->
<div class="personal_registered_wrapper overflow-hidden position-relative" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/img/bg01.svg');">
   <img class="bg_shape position-absolute" src="<?php echo get_template_directory_uri(); ?>/assets/img/bg02.svg" alt="bg02">
   <img class="form_shape_mobile" src="<?php echo get_template_directory_uri(); ?>/assets/img/bg02_mini.svg" alt="bg02_mini">
   <div class="container">
      <div class="text-center">
         <h2 class="head_text"><span>שלום </span> <?php echo $first_name; ?><span>!</span></h2>
      </div>
      <form class="personal_registered_wrap position-relative">
         <div class="personal_registered_nav">
            <ul class="nav" id="pills-tab" role="tablist">
               <li role="presentation">
                <!-- שימי לב שכאן הלוגיקה מבוססת על כפתור, ושם על קישור - זה בגדול אותו הדבר אבל, יש לך data-target, יש data-toggle, יש ID -->
                 <!-- כן שמתי לב ובשני זה על רשימות וUL  -->
                  <button class="active" id="pills-home-tab1" data-toggle="pill" data-target="#pills-home1" type="button" role="tab" aria-controls="pills-home1" aria-selected="true">
                     מבט <br>מלמעלה
                  </button>
               </li>
               <li role="presentation">
                  <button id="pills-home-tab2" data-toggle="pill" data-target="#pills-home2" type="button" role="tab" aria-controls="pills-home2" aria-selected="false">
                     הדירות <br> <br>שפרסמתי 
                  </button>
               </li>
               <li role="presentation">
                  <button class="personal_registered_border" id="pills-home-tab3" data-toggle="pill" data-target="#pills-home3" type="button" role="tab" aria-controls="pills-home3" aria-selected="false">
                     הלידים<br> שלי 
                  </button>
               </li>
               <li role="presentation">
                  <button id="pills-home-tab4" data-toggle="pill" data-target="#pills-home4" type="button" role="tab" aria-controls="pills-home4" aria-selected="false">
                     פרסום<br>  דירה 
                  </button>
               </li>

               <li role="presentation">
                  <button id="pills-home-tab5" data-toggle="pill" data-target="#pills-home5" type="button" role="tab" aria-controls="pills-home5" aria-selected="false">
                      עריכת <br>  פרטי מנוי
                  </button>
               </li>
               <li role="presentation">
                  <button id="pills-home-tab6" data-toggle="pill" data-target="#pills-home6" type="button" role="tab" aria-controls="pills-home6" aria-selected="false">

                  <div class="d-flex align-items-center">
                            <img class="broker_man img-fluid rounded-circle" src="<?php echo esc_html($profile_image); ?>" alt="broker_man" width="40" height="40">
                            <div>
                               <p> <?php  echo esc_html( $first_name . ' ' . $last_name );  ?></p>
                               <a class="mt_5" href="Broker_Personal_Area_Page_My_Account.html">החשבון שלי </a>
                            </div>
                  </div>
                  </button>
               </li>
            </ul>
            <div class="text-center">
               <a class="disconnection_btn mt_55" href="#">התנתקות</a>
            </div>
         </div>

         <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-home1" role="tabpanel" aria-labelledby="pills-home-tab1">
               <div class="text-right">
                  <p class="view_popup"><i class="fal fa-times"></i>יש  ליד חדש שמחכה למענה שלך, לצפיה <span>לחץ כאן </span></p>
                  
                  <div class="main_view mt_20">
                    <div class="main_view_details">
                        <div class="green_border personal_registered_profile">
                            <h3 class="personal_registered_head">
                                <?php echo esc_html($first_name . ' ' . $last_name); ?>
                            </h3>
                            <div class="social_icon">
                                <div class="mt_8">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon01.svg" alt="icon01">
                                    <?php if ($phone) : ?>
                                        <span><?php echo esc_html($phone); ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="mt_8">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon02.svg" alt="icon02">
                                    <?php if ($email) : ?>
                                        <span><?php echo esc_html($email); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="edit_link">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/saved_areas_edit_icon.png" alt="עריכה">
                                <a id="pills-home-tab7" data-toggle="pill" data-target="#pills-home7" href="#" role="tab" aria-controls="pills-home7" aria-selected="false">
                                    לעריכה ←
                                </a>
                            </div>
                        </div>
                        <div class="main_view_details_half">
                        <div class="green_border">
                          <div class="flex_heading">
                            <h3>התראות</h3>
                            <div class="edit_link">
                              <img src="<?php echo get_template_directory_uri(); ?>/assets/img/saved_areas_edit_icon.png" alt="עריכה">
                              <a id="pills-home-tab3" data-toggle="pill" data-target="#pills-home3" href="#" role="tab" aria-controls="pills-home3" aria-selected="false">
                                לעריכה ←
                              </a>
                            </div>
                          </div>
                          <?php
                          $methods = [
                            'phone' => 'הודעה קולית',
                            'email' => 'אימייל',
                            'whatsapp' => 'וואטסאפ'
                          ];
                          $user_methods = get_field('notification_method', 'user_' . get_current_user_id()) ?: [];
                          ?>
                          <div class="d-flex align-items-center flex-wrap checkbox_container">
                            <?php foreach ($methods as $value => $label) : ?>
                              <div class="checkbox01">
                                <input type="checkbox" id="method_view_<?php echo esc_attr($value); ?>"
                                       <?php echo in_array($value, $user_methods) ? 'checked' : ''; ?> disabled>
                                <label for="method_view_<?php echo esc_attr($value); ?>"><span><i class="fas fa-check"></i></span><?php echo esc_html($label); ?></label>
                              </div>
                            <?php endforeach; ?>
                          </div>
                          <div class="flex_heading mt_14">
                            <h3>תדירות</h3>
                            <div class="edit_link">
                              <img src="<?php echo get_template_directory_uri(); ?>/assets/img/saved_areas_edit_icon.png" alt="עריכה">
                              <a id="pills-home-tab3" data-toggle="pill" data-target="#pills-home3" href="#" role="tab" aria-controls="pills-home3" aria-selected="false">
                                לעריכה ←
                              </a>
                            </div>
                          </div>
                          <?php
                          $frequencies = [
                            'immediate' => 'מיידי',
                            'daily' => 'פעם ביום',
                            'every_3_days' => 'פעם ב 3 ימים'
                          ];
                          $user_frequencies = get_field('notification_frequency', 'user_' . get_current_user_id()) ?: [];
                          if (!is_array($user_frequencies)) {
                            $user_frequencies = [$user_frequencies];
                          }
                          ?>
                          <div class="d-flex align-items-center flex-wrap checkbox_container">
                            <?php foreach ($frequencies as $value => $label) : ?>
                              <div class="checkbox01">
                                <input type="checkbox" id="freq_view_<?php echo esc_attr($value); ?>"
                                       <?php echo in_array($value, $user_frequencies) ? 'checked' : ''; ?> disabled>
                                <label for="freq_view_<?php echo esc_attr($value); ?>"><span><i class="fas fa-check"></i></span><?php echo esc_html($label); ?></label>
                              </div>
                            <?php endforeach; ?>
                          </div>
                        </div>
                            <div class="green_border">
                                <div class="flex_heading">
                                    <h3>אזורי חיפוש</h3>
                                    <div class="edit_link">
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/saved_areas_edit_icon.png" alt="עריכה">
                                        <a id="pills-home-tab4" data-toggle="pill" data-target="#pills-home4" href="#" role="tab" aria-controls="pills-home4" aria-selected="false">
                                            לעריכה ←
                                        </a>
                                    </div>
                                </div>
                                <?php
                                $user_id = get_current_user_id();
                                $saved_areas = get_field('saved_areas', 'user_' . $user_id);

                                if ($saved_areas && is_array($saved_areas)) {
                                    $saved_areas = array_map(function($area) {
                                        return is_object($area) ? $area->term_id : intval($area);
                                    }, $saved_areas);
                                }
                            
                                if ($saved_areas) {
                                    $saved_areas = array_slice($saved_areas, 0, 3);
                                    foreach ($saved_areas as $area_id) {
                                        $area = get_term($area_id, 'area'); // Get term object
                                        if (!$area || is_wp_error($area)) continue;
                                    
                                        $associated_city_id = get_term_meta($area_id, 'associated_city', true);
                                        $associated_city = get_term_by('id', $associated_city_id, 'city');
                                    
                                        // Display each saved area
                                        echo '<div class="saved_area">' .
                                                ($associated_city ? esc_html($associated_city->name) . ' | ' : '') .
                                                esc_html($area->name) .
                                             '</div>';
                                    }
                                } else {
                                    echo '<p>לא נבחרו אזורים.</p>';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="green_border">
                            <div class="flex_heading">
                                <h3>הדירות שסימנתי</h3>
                                <!-- <div class="edit_link">
                                    <a id="pills-home-tab5" data-toggle="pill" data-target="#pills-home5" href="#" role="tab" aria-controls="pills-home5" aria-selected="false">
                                        לכל הדירות שסימנתי ←
                                    </a>
                                </div> -->
                                
                            </div>
                            <?php
                            $user_id = get_current_user_id();
                            $saved_apartments = get_field('saved_apartments', 'user_' . $user_id);
                            if ($saved_apartments) {
                                if (!is_array($saved_apartments)) {
                                    $saved_apartments = array($saved_apartments);
                                }
                                // Limit to a maximum of 3 apartments
                                $saved_apartments = array_slice($saved_apartments, 0, 3);
                                foreach ($saved_apartments as $apartment_id) {
                                    // Get apartment details
                                    $title     = get_the_title($apartment_id);
                                    $permalink = get_permalink($apartment_id);
                                    $img_url   = get_the_post_thumbnail_url($apartment_id, 'full');
                                    if (!$img_url) {
                                        $img_url = get_template_directory_uri() . '/assets/img/apartment_img01.svg';
                                    }
                                    $city_terms = get_the_terms($apartment_id, 'city');
                                    $city = ($city_terms && !is_wp_error($city_terms)) ? $city_terms[0]->name : 'לא ידוע';
                                    $sale_type = get_field('listing_type', $apartment_id);
                                    $rooms = get_post_meta($apartment_id, 'rooms', true);
                                    $floor = get_post_meta($apartment_id, 'floor', true);
                                    $size  = get_post_meta($apartment_id, 'size', true);
                                    $price = get_post_meta($apartment_id, 'price', true);
                                    ?>
                                    <div class="saved_apartments saved_apartments_main_view mt_8">
                                        <div class="apartment_item_saved">
                                            <img class="apartment_img img-fluid" src="<?php echo esc_url($img_url); ?>" alt="apartment_img01">
                                            <div class="apartment_content">
                                                <div class="apartment_content_row">
                                                    <a class="apartment_name" href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($title); ?></a>
                                                    <h4><?php echo number_format($price); ?> ₪</h4>
                                                </div>
                                                <div class="apartment_details">
                                                    <span><?php echo esc_html($city); ?></span>
                                                    <span><?php echo esc_html($sale_type); ?></span>
                                                </div>
                                                <div class="apartment_details">
                                                    <span><?php echo esc_html($rooms); ?> חדרים</span>
                                                    <span>קומה <?php echo esc_html($floor); ?></span>
                                                    <span><?php echo esc_html($size); ?> מ"ר</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            } else {
                                echo '<p>לא סימנת דירות עדיין.</p>';
                            }
                            ?>
                        </div>
                    </div>

                    <div class="green_border main_view_apartments apartment_match_wrap">
                       <div class="flex_heading">
                          <h3>הדירות שפרסמתי</h3>
                          <div class="edit_link">
                              <a id="pills-home-tab2" data-toggle="pill" data-target="#pills-home2" href="#" role="tab" aria-controls="pills-home2" aria-selected="false">
                                לכל הדירות המתאימות ←
                              </a>
                          </div>
                       </div>
                       <div class="row apartment_slider custom_row">
                          <?php
                          $user_id = get_current_user_id();
                          // Get saved areas from the user
                          $saved_areas = get_field('saved_areas', 'user_' . $user_id);
                          if ($saved_areas && is_array($saved_areas)) {
                              $saved_areas = array_map(function($area) {
                                  return is_object($area) ? $area->term_id : intval($area);
                              }, $saved_areas);
                          } else {
                              $saved_areas = array();
                          }
                       
                          if (!empty($saved_areas)) {
                              $args = array(
                                  'post_type'      => 'apartment',
                                  'posts_per_page' => -1,
                                  'post_status'    => 'publish',
                                  'tax_query'      => array(
                                      array(
                                          'taxonomy' => 'area',
                                          'field'    => 'term_id',
                                          'terms'    => $saved_areas,
                                      ),
                                  ),
                              );
                              $query = new WP_Query($args);
                              if ( $query->have_posts() ) :
                                  while ( $query->have_posts() ) : $query->the_post();
                                      $img_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
                                      if (!$img_url) {
                                          $img_url = get_template_directory_uri() . '/assets/img/card_img.svg';
                                      }
                                      $terms = get_the_terms(get_the_ID(), 'city');
                                      $city = ($terms && !is_wp_error($terms)) ? $terms[0]->name : 'בני ברק';
                                      $title = get_the_title();
                                      $rooms = get_post_meta(get_the_ID(), 'rooms', true);
                                      $floor = get_post_meta(get_the_ID(), 'floor', true);
                                      $size  = get_post_meta(get_the_ID(), 'size', true);
                                      $price = get_post_meta(get_the_ID(), 'price', true);
                                      $listing_type = get_field('listing_type');
                                      ?>
                                      <div class="col-md-6 mt_15 custom_col">
                                         <div class="card_item">
                                            <figure class="m-0">
                                               <img class="card_img" src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($title); ?>">
                                            </figure>
                                            <div class="card_content">
                                               <div class="custom_card_space">
                                                  <a class="category_text" href="#"><?php echo esc_html($city); ?></a>
                                                  <a class="card_icon save_apartment_btn" href="#" data-post-id="<?php echo get_the_ID(); ?>">
                                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon12.svg" alt="Save Apartment">
                                                  </a>
                                               </div>
                                               <div>
                                                  <h2><a href="<?php the_permalink(); ?>"><?php echo esc_html($title); ?></a></h2>
                                                  <p><?php echo esc_html($listing_type); ?>  |  <?php echo esc_html($rooms); ?> חדרים  |  קומה <?php echo esc_html($floor); ?>  |  <?php echo esc_html($size); ?> מ"ר</p>
                                                  <h3><?php echo number_format($price); ?> ₪</h3>
                                               </div>
                                              <!-- <div class="card_edit_view_icon">
                                                  <a href="#"><img src="img/icon023.svg" alt="icon023">היי</a>
                                                  <a href="#"><img src="img/icon024.svg" alt="icon024">היי</a>
                                              </div> -->
                                            </div>
                                         </div>
                                      </div>
                                      <?php
                                  endwhile;
                                  wp_reset_postdata();
                              else :
                                  echo '<p>אין דירות שמתאימות לאזורים שבחרת.</p>';
                              endif;
                          } else {
                              echo '<p>לא נבחרו אזורים, אנא הוסף אזור שמעניין אותך.</p>';
                          }
                          ?>
                     </div>
                  </div>
                  </div>
               </div>
            </div>

            <div class="tab-pane fade" id="pills-home2" role="tabpanel" aria-labelledby="pills-home-tab2">
              <!-- מכאן התוכן החדש -->
              <div class="apartment_search_wrap published_apartments">
        <div class="d-flex align-items-center justify-content-between flex-wrap">
            <h3 class="personal_registered_sub_head position-relative">הדירות שפירסמתי</h3>
            <a class="header_btn select_search_btn" href="/upload-new-apartment-for-sale/">
                פרסום דירה ←
            </a>
        </div>
        <div class="d-flex align-items-center justify-content-between flex-wrap">
            <h6 class="position-relative">עד עכשיו פרסמת 14 דירות מתוך 35</h6>
        </div>

        <!-- מכאן קוד דינאמי -->
        <?php
        $current_user_id = get_current_user_id();

        $args = array(
            'post_type'      => 'apartment',
            'posts_per_page' => -1,
            'author'         => $current_user_id,
            'post_status'    => 'draft',
        );

        $query = new WP_Query($args);

        if ($query->have_posts()) : ?>
            <div class="row apartment_search_slider custom_row mt_30">
                <?php while ($query->have_posts()) : $query->the_post();

                    // שדות מותאמים אישית
                    $current_apartment_id = get_the_ID();
                    $city = get_field('city', $current_apartment_id, true);
                    $floor = get_field('floor', $current_apartment_id, true);
                    $rooms = get_field('rooms', $current_apartment_id, true);
                    $size = get_field('size', $current_apartment_id, true);
                    $price = get_field('price', $current_apartment_id, true);
                    $price = $price !== null ? (float)$price : 0;
                    $main_img = get_field('main_img', $current_apartment_id, true);

                    if (is_array($main_img) && isset($main_img['url'])) {
                        $main_img_url = $main_img['url'];
                    } elseif (is_string($main_img)) {
                        $main_img_url = $main_img;
                    } else {
                        $main_img_url = 'https://dirata.co.il/wp-content/uploads/2025/03/אמרי-ברוך-6-3-1.jpeg';
                    }

                    $post_link = get_permalink();
                    ?>
                    <div class="col-lg-3 col-md-4 mt_15 custom_col">
                        <div class="card_item">
                            <figure class="m-0">
                                <img class="card_img" src="<?php echo esc_url($main_img_url); ?>" alt="<?php the_title(); ?>">
                            </figure>
                            <div class="card_content">
                                <div class="custom_card_space">
                                    <a class="category_text" href="#"><?php echo esc_html($city); ?></a>
                                    <a class="card_icon" href="#">
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon12.svg" alt="icon12">
                                    </a>
                                </div>
                                <div>
                                    <h2><a href="<?php echo esc_url($post_link); ?>"><?php the_title(); ?></a></h2>
                                    <p>מכירה | <?php echo esc_html($rooms); ?> חדרים | קומה <?php echo esc_html($floor); ?> | <?php echo esc_html($size); ?> מ"ר</p>
                                    <h3><?php echo number_format($price); ?> ₪</h3>
                                    <div class="apartment_posted_actions ">
                                        <button type="button" class="card_edit_view_icon">
                                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/saved_areas_edit_icon.png" alt="עריכה"> עריכה
                                        </button>
                                        <button type="button" class="delete-area-btn" data-post-id="<?php echo get_the_ID(); ?>">
                                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/saved_areas_delete_icon.png" alt="מחיקה"> מחיקה
                                        </button>
                                        <!-- <div class="card_edit_view_icon">
                                          <a href="#"><img src="img/icon023.svg" alt="icon023"></a>
                                          <a href="#"><img src="img/icon024.svg" alt="icon024"></a>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else : ?>
            <p>אין דירות שפורסמו עדיין.</p>
        <?php endif;

        wp_reset_postdata();
        ?>
    </div>
    <!-- עד כאן -->
          
            </div>

            <div class="tab-pane fade" id="pills-home3" role="tabpanel" aria-labelledby="pills-home-tab3">
               <div class="notification_management_wrap text-right">
                  <h3 class="personal_registered_sub_head position-relative">ניהול התראות</h3>
                  <div>
                  <h4>איך תרצו לקבל את המידע?</h4>
                    <div class="d-flex align-items-center">
                       <?php
                       $methods = [
                           'phone' => 'הודעה קולית',
                           'email' => 'אימייל',
                           'whatsapp' => 'וואטסאפ'
                       ];
                       foreach ($methods as $value => $label) {
                           $checked = in_array($value, $notification_methods) ? 'checked' : '';
                           echo '
                           <div class="checkbox01 mt_13">
                               <input type="checkbox" id="method_' . esc_attr($value) . '" value="' . esc_attr($value) . '" ' . $checked . '>
                               <label for="method_' . esc_attr($value) . '"><span><i class="fas fa-check"></i></span>' . esc_html($label) . '</label>
                           </div>';
                       }
                       ?>
                    </div>
                    <h4>באיזה תדירות תרצו לקבל את המידע?</h4>
                    <div>
                      <?php
                      $frequencies = [
                        'immediate' => 'מיידי - ברגע שעולה דירה שמתאים לי',
                        'daily' => 'פעם ביום',
                        'every_3_days' => 'פעם ב 3 ימים'
                      ];
                      if (!is_array($notification_frequency)) {
                        $notification_frequency = [$notification_frequency]; // convert to array if single string
                      }
                      foreach ($frequencies as $value => $label) {
                        $checked = in_array($value, $notification_frequency) ? 'checked' : '';
                        echo '
                        <div class="checkbox01 mt_13">
                          <input type="checkbox" name="notification_frequency[]" id="freq_' . esc_attr($value) . '" value="' . esc_attr($value) . '" ' . $checked . '>
                          <label for="freq_' . esc_attr($value) . '"><span><i class="fas fa-check"></i></span>' . esc_html($label) . '</label>
                        </div>';
                      }
                      ?>
                    </div>
                    <button type="button" id="update-notifications" class="custom_btn mt_45">לעדכון ←</button>
                  </div>
               </div>
            </div>

            <div class="tab-pane fade" id="pills-home4" role="tabpanel" aria-labelledby="pills-home-tab4">
               <div class="update_selection_wrap text-right">
                  <h3 class="personal_registered_sub_head position-relative">עדכון בחירת אזור</h3>
                  <!-- Display User's Saved Areas -->
                  <div class="saved_areas">
                    <?php
                    $user_id = get_current_user_id();
                    $saved_areas = get_field('saved_areas', 'user_' . $user_id);

                    if ($saved_areas && is_array($saved_areas)) {
                        $saved_areas = array_map(function($area) {
                            return is_object($area) ? $area->term_id : intval($area);
                        }, $saved_areas);
                    }
                       
                    if ($saved_areas) {
                        foreach ($saved_areas as $area_id) {
                            $area = get_term($area_id, 'area'); // Get term object
                            if (!$area || is_wp_error($area)) continue;
                        
                            $associated_city_id = get_term_meta($area_id, 'associated_city', true);
                            $associated_city = get_term_by('id', $associated_city_id, 'city');
                        
                            echo '<div class="update_saved_area">' .
                               '<div class="saved_area_name">' .
                                   ($associated_city ? esc_html($associated_city->name) . ' | ' : '') .
                                   esc_html($area->name) .
                               '</div>' .
                               '<div class="saved_area_btns">' .
                                   '<button class="edit-area-btn" data-area-id="' . esc_attr($area_id) . '">
                                       <img src="' . get_template_directory_uri() . '/assets/img/saved_areas_edit_icon.png" alt="עריכה"> עריכה
                                   </button>' .
                                   '<button class="delete-area-btn" data-area-id="' . esc_attr($area_id) . '">
                                       <img src="' . get_template_directory_uri() . '/assets/img/saved_areas_delete_icon.png" alt="מחיקה"> מחיקה
                                   </button>' .
                               '</div>' .
                            '</div>';
                        }
                    } else {
                        echo '<p>לא נבחרו אזורים.</p>';
                    }
                    ?>
                 </div>

                 <div id="extend-field">
                   <div class="update_selection_item">
                     <div>
                       <select class="input_info" id="city-select">
                         <option value="">בחר עיר</option>
                         <?php
                         $cities = get_terms(array(
                             'taxonomy'   => 'city',
                             'hide_empty' => false,
                         ));
                         if (!empty($cities) && !is_wp_error($cities)) {
                           foreach ($cities as $city) {
                             echo '<option value="' . esc_attr($city->term_id) . '">' . esc_html($city->name) . '</option>';
                           }
                         }
                         ?>
                       </select>
                     </div>
                     <div class="mt_22">
                       <select class="input_info" id="area-select">
                         <option value="">בחר שכונה/אזור</option>
                       </select>
                     </div>
                   </div>
                 </div>
                 <a class="additional_btn mt_13" href="#" id="add-area-btn">לבחירת אזור נוסף ←</a>
                 <div class="mt_30">
                   <button class="custom_btn" type="submit">לעדכון ←</button>
                 </div>
               </div>
            </div>

            <div class="tab-pane fade" id="pills-home5" role="tabpanel" aria-labelledby="pills-home-tab5">
               <div class="update_selection_wrap text-right">
                  <h3 class="personal_registered_sub_head position-relative">דירות מסומנות</h3>
                  <div class="saved_apartments_list">
                      <?php
                      $user_id = get_current_user_id();
                      $saved_apartments = get_field('saved_apartments', 'user_' . $user_id);
                      if ($saved_apartments) {
                          if (!is_array($saved_apartments)) {
                              $saved_apartments = array($saved_apartments);
                          }
                          foreach ($saved_apartments as $apartment_id) {
                              // Get dynamic data for the apartment
                              $title     = get_the_title($apartment_id);
                              $permalink = get_permalink($apartment_id);
                              $img_url   = get_the_post_thumbnail_url($apartment_id, 'full');
                              if (!$img_url) {
                                  $img_url = get_template_directory_uri() . '/assets/img/apartment_img01.svg';
                              }
                              // Get meta data for details
                              $rooms = get_post_meta($apartment_id, 'rooms', true);
                              $floor = get_post_meta($apartment_id, 'floor', true);
                              $size  = get_post_meta($apartment_id, 'size', true);
                              $price = get_post_meta($apartment_id, 'price', true);
                              $listing_type = get_field('listing_type', $apartment_id);
                              $city_terms = get_the_terms($apartment_id, 'city');
                              $city = ($city_terms && !is_wp_error($city_terms)) ? $city_terms[0]->name : 'לא ידוע';
                              ?>
                              <div class="saved_apartments mt_20">
                                  <div class="apartment_item_saved">
                                      <img class="apartment_img img-fluid" src="<?php echo esc_url($img_url); ?>" alt="apartment_img01">
                                      <div>
                                          <div class="apartment_content_row">
                                              <a class="apartment_name" href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($title); ?></a>
                                              <h4><?php echo number_format($price); ?> ₪</h4>
                                          </div>
                                          <div class="apartment_details">
                                              <span><?php echo esc_html($city); ?></span>
                                              <span><?php echo esc_html($listing_type); ?></span>
                                              <span><?php echo esc_html($rooms); ?> חדרים</span>
                                              <span>קומה <?php echo esc_html($floor); ?></span>
                                              <span><?php echo esc_html($size); ?> מ"ר</span>
                                          </div>
                                          <div class="apartment_content_row">
                                              <button>להצגת מספר טלפון</button>
                                              <div class="edit_link">
                                                  <a id="pills-home-tab5" data-toggle="pill" data-target="#pills-home5" href="<?php echo esc_url($permalink); ?>" role="tab" aria-controls="pills-home5" aria-selected="false">
                                                      לפרטי הדירה ←
                                                  </a>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              <?php
                          }
                      } else {
                          echo '<p>לא סימנת דירות עדיין.</p>';
                      }
                      ?>
                  </div>
               </div>
            </div>
            
<div class="tab-pane fade" id="pills-home6" role="tabpanel" aria-labelledby="pills-home-tab6">
  <!-- טאב 6 הריק -->
  <h3 class="personal_registered_sub_head position-relative text-right">החשבון שלי </h3>
  <div class="personal_detail_box text-right green_border">
                <h2 class="position-relative">עריכת פרטים</h2>
                <div class="row">
                    <div class="col-sm-6">
                      <label for="a"> שם:</label>
                      <input class="input_info mt_13" id="user_fullname" type="text" value="<?php echo esc_attr($first_name . ' ' . $last_name); ?>">
                    </div>
                    <div class="col-sm-6">
                      <label for="a"> שם המשרד:</label>
                      <input class="input_info mt_13" id="user_office_name" type="text" value="<?php echo esc_attr($office_name); ?>">
                    </div>
                    <div class="col-4 mt_40">
                      <label for="b">אימייל:</label>
                      <input class="input_info mt_13" id="user_email" type="email" value="<?php echo esc_attr($email); ?>">
                    </div>
                    <div class="col-4 mt_40">
                      <label for="c">טלפון:</label>
                      <input class="input_info mt_13" id="user_phone" type="text" value="<?php echo esc_attr($phone); ?>">
                    </div>
                    <div class="col-4 mt_40">
                      <label for="c">וואצאפ:</label>
                      <input class="input_info mt_13" id="user_whatsapp" type="text" value="<?php echo esc_attr('כאן יהיה מספר הוואצאפ'); ?>">
                    </div>
                    <div class="col-12">
                      <button type="submit" class="custom_btn mt_30">לעדכון ←</button>
                    </div>
                </div>
  </div>
  <form>
                      <div class="my_account_form default_item mt_30">                       
                          <div class="row align-items-center">
                            <div class="col-sm-6 mt_30 custom_col3">
                                <label for="ImageMedias">לוגו המשרד:</label>
                                <div class="d-flex align-items-center mt_8">
                                  <div class="upload_img" id="divImageMediaPreview"><img class="w-100" src="img/upload_img.svg" alt="upload_img"></div>
                                  <div class="upload_text">
                                  להחלפת הלוגו לחץ כאן
                                      <input id="ImageMedias" multiple="multiple" name="ImageMedias" type="file" accept=".jfif,.jpg,.jpeg,.png,.gif" value="">
                                  </div>
                                </div>
                            </div>
                            <div class="col-sm-6 text-left mt_30">
                                <button class="custom_btn">שמירה</button>
                            </div>
                          </div>
                      </div>
                      <div class="my_account_form my_account_form02 default_item mt_30">
                          <h2 class="form_head">הגדרת מנוי</h2>
                          <div class="my_account_form_sub custom_width d-flex align-items-center justify-content-between flex-wrap">
                            <div class="devide_item mt_37">
                                <p>מסלול</p>
                                <span>Best</span>
                            </div>
                            <div class="devide_item mt_37">
                                <p>מחזור חיוב</p>
                                <span>שנתי</span>
                            </div>
                            <div class="devide_item mt_37">
                                <p>בתוקף עד</p>
                                <span>27/02/2026</span>
                            </div>
                            <div class="devide_item mt_37">
                                <p>דירות שהעלו</p>
                                <span>7/35</span>
                            </div>
                          </div>
                          <div class="mt_40 text-left">
                            <button class="custom_btn">צרו קשר לשדרוג המנוי</button>
                          </div>
                      </div>
                    </form>
</div>

          <!-- </div> -->

            <div class="tab-pane fade" id="pills-home7" role="tabpanel" aria-labelledby="pills-home-tab7">
               <div class="personal_detail_box text-right">
                  <h3 class="personal_registered_sub_head position-relative">עריכת פרטים אישיים</h3>
                  <div class="row">
                     <div class="col-12">
                        <label for="a">מה השם?</label>
                        <input class="input_info mt_13" id="user_fullname" type="text" value="<?php echo esc_attr($first_name . ' ' . $last_name); ?>">
                     </div>
                     <div class="col-sm-6 mt_40">
                        <label for="b">והאימייל?</label>
                        <input class="input_info mt_13" id="user_email" type="email" value="<?php echo esc_attr($email); ?>">
                     </div>
                     <div class="col-sm-6 mt_40">
                        <label for="c">והטלפון?</label>
                        <input class="input_info mt_13" id="user_phone" type="text" value="<?php echo esc_attr($phone); ?>">
                     </div>
                     <div class="col-12">
                        <button type="submit" class="custom_btn mt_30">לעדכון ←</button>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </form>
   </div>
</div>
<!-- Personal Area Registered area end -->


<!-- הפופאפ שמציג את הדירה לעריכה -->
<!-- update details advertisement area start -->
  <div class="update_details_advertisement_wrap private_apartment_form_wrapper">
  <div class="close_icon"><i class="fal fa-times"></i></div>
  <div class="form_wrap text-right">
    <h2 class="private_apartment_form_head">עדכון דירה למכירה:</h2>
    <div class="row custom_row02 mt_8">
      <div class="col-sm-6 mt_20 custom_col02">
        <div class="input_box">
          <label for="a">עיר<span>*</span></label>
          <input id="a" type="text" placeholder="בני ברק">
        </div>
      </div>
      <div class="col-sm-6 mt_20 custom_col02">
        <div class="input_box">
          <label for="b">אזור<span>*</span></label>
          <input id="b" type="text" placeholder="שיכון ה">
        </div>
      </div>
    </div>
    <div class="row custom_row02">
      <div class="col-md-9 col-sm-8 col-8 mt_20 custom_col02">
        <div class="input_box">
          <label for="c">רחוב<span>*</span></label>
          <input id="c" type="text" placeholder="רבי עקיבא 8">
        </div>
      </div>
      <div class="col-md-3 col-sm-4 col-4 mt_20 custom_col02">
        <div class="input_box">
          <label for="d">מס’ בית<span>*</span></label>
          <input id="d" type="text" placeholder="מספר בית">
        </div>
      </div>
    </div>
    <div class="row custom_row02 mt_8">
      <div class="col-md-4 col-sm-6 col-4 mt_20 custom_col02">
        <div class="input_box">
          <label for="e">קומה<span>*</span></label>
          <input id="e" type="text" placeholder="8">
        </div>
      </div>
      <div class="col-md-4 col-sm-6 col-4 mt_20 custom_col02">
        <div class="input_box">
          <label for="f">מס’ חדרים<span>*</span></label>
          <input id="f" type="text" placeholder="4">
        </div>
      </div>
      <div class="col-md-4 col-sm-6 col-4 mt_20 custom_col02">
        <div class="input_box">
          <label for="g">גודל דירה (מ”ר)</label>
          <input id="g" type="text" placeholder="165">
        </div>
      </div> 
    </div>
    <div class="row custom_row02">
      <div class="col-md-3 col-sm-12 custom_col02">
        <div class="row custom_row02">
          <div class="col-md-12 col-sm-6 mt_20 custom_col02">
            <div class="input_box">
              <label for="h">מחיר<span>*</span></label>
              <input id="h" class="text-left pb_15" type="text" placeholder="1,320,000">
            </div>
          </div>
          <div class="col-md-12 col-sm-6 mt_20 custom_col02">
            <div class="input_box">
              <label for="i">תאריך כניסה<span>*</span></label>
              <input id="i" type="text" placeholder="יא תמוז תשפ”ה">
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-9 custom_col02 mt_20">
        <div class="input_box">
          <label for="j">על הדירה:<span>*</span></label>
          <textarea id="j" placeholder="לורם איפסום דולור סיט אמט, קונסקטורר אדיפיסינג אלית הועניב היושבב שערש שמחויט - שלושע ותלברו חשלו שעותלשך וחמחויט - שלושע ותלברו חשלו שעותלשך וחאית נובש ערששף. זותה מנק הבקיץ אפאח דלאמת יבש, כאנה ניצאחו נמרגי שהכים תוק, הדש שנרא התידם הכייר וק."></textarea>
        </div>
      </div>
      <div class="col-12 custom_col02 mt_20">
        <div class="input_box">
          <label for="k">אופציות והיתרי בניה</label>
          <input id="k" type="text" placeholder="כן אופציה לבניה מאושר כבר מוועדת התיכנון והבניה בלבלבלבל">
        </div>
        <div class="file_upload_box mt_20">
        <label>העלאת תמונות</label>
        <div class="preview-images-zone-wrap">
          <div class="preview-images-zone">
            <div class="preview-image preview-show-1">
              <span class="image_head">תמונה ראשית</span>
              <div class="image-cancel" data-no="1">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/delete_icon.svg" alt="delete_icon">
              </div>
              <div class="image-zone"><img id="pro-img-1" src="https://dirata.co.il/wp-content/uploads/2025/02/apartment_big_img01.svg" alt="upload_img01"></div>
              <div class="tools-edit-image">
                <a href="javascript:void(0)" data-no="1" class="btn-edit-image">
                  <img src="<?php echo get_template_directory_uri(); ?>/assets/img/replacable_icon.svg" alt="replacable_icon">להחלפה
                </a>
              </div>
            </div>
            <div class="preview-image preview-show-2">
              <div class="image-cancel" data-no="2"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/delete_icon.svg" alt="delete_icon"></div>
              <div class="image-zone"><img id="pro-img-2" src="https://dirata.co.il/wp-content/uploads/2025/02/apartment_big_img01.svg" alt="upload_img02"></div>
            </div>
            <div class="preview-image preview-show-3">
              <div class="image-cancel" data-no="3"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/delete_icon.svg" alt="delete_icon"></div>
              <div class="image-zone"><img id="pro-img-3" src="https://dirata.co.il/wp-content/uploads/2025/02/apartment_big_img01.svg" alt="upload_img03"></div>
            </div>
            <div class="preview-image preview-show-4">
              <div class="image-cancel" data-no="4"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/delete_icon.svg" alt="delete_icon"></div>
              <div class="image-zone"><img id="pro-img-4" src="https://dirata.co.il/wp-content/uploads/2025/02/apartment_big_img01.svg" alt="upload_img03"></div>
            </div>
          </div>
          <div class="form-group">
          <a href="javascript:void(0)" onclick="jQuery('#pro-image').click()">
              <img src="https://dirata.co.il/wp-content/uploads/2025/04/Vector-1.svg" alt="upload_icon"><span>תמונות נוספות</span>
            </a>
            <input type="file" id="pro-image" name="pro-image" multiple accept="image/*">
          </div>
        </div>
        <label class="mt_8">עד 10 תמונות</label>
      </div>
      </div>
    </div>
  </div>
  <div class="apartment_furniture_wrap mt_20">
    <h3>מה יש בדירה</h3>
    <div class="apartment_furniture_box">
      <div class="apartment_furniture_item">
        <input checked type="checkbox" id="check01" name="whats_inside[]" value="ריהוט">
        <label for="check01">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/img/apartment_furniture_icon01.svg" alt="apartment_furniture_icon01">
          <span>ריהוט</span>
        </label>
      </div>
      <div class="apartment_furniture_item">
        <input type="checkbox" id="check02" name="whats_inside[]" value="סוכה">
        <label for="check02">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/img/apartment_furniture_icon02.svg" alt="apartment_furniture_icon02">
          <span>סוכה</span>
        </label>
      </div>
      <div class="apartment_furniture_item">
        <input type="checkbox" id="check03" name="whats_inside[]" value="מעלית">
        <label for="check03">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/img/apartment_furniture_icon03.svg" alt="apartment_furniture_icon01">
          <span>מעלית</span>
        </label>
      </div>
      <div class="apartment_furniture_item">
        <input checked type="checkbox" id="check04" name="whats_inside[]" value="מטבח כשר">
        <label for="check04">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/img/apartment_furniture_icon04.svg" alt="apartment_furniture_icon02">
          <span>מטבח כשר</span>
        </label>
      </div>
      <div class="apartment_furniture_item">
        <input type="checkbox" id="check05" name="whats_inside[]" value="חצר">
        <label for="check05">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/img/apartment_furniture_icon05.svg" alt="apartment_furniture_icon01">
          <span>חצר</span>
        </label>
      </div>
      <div class="apartment_furniture_item">
        <input type="checkbox" id="check06" name="whats_inside[]" value="מזגנים">
        <label for="check06">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/img/apartment_furniture_icon06.svg" alt="apartment_furniture_icon02">
          <span>מזגנים</span>
        </label>
      </div>
      <div class="apartment_furniture_item">
        <input type="checkbox" id="check07" name="whats_inside[]" value="יחידת הורים">
        <label for="check07">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/img/apartment_furniture_icon07.svg" alt="apartment_furniture_icon01">
          <span>יחידת הורים</span>
        </label>
      </div>
      <div class="apartment_furniture_item">
        <input type="checkbox" id="check08" name="whats_inside[]" value="ממד">
        <label for="check08">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/img/apartment_furniture_icon08.svg" alt="apartment_furniture_icon02">
          <span>ממ”ד</span>
        </label>
      </div>
      <div class="apartment_furniture_item">
        <input checked type="checkbox" id="check09" name="whats_inside[]" value="חניה">
        <label for="check09">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/img/apartment_furniture_icon09.svg" alt="apartment_furniture_icon01">
          <span>חניה</span>
        </label>
      </div>
      <div class="apartment_furniture_item">
        <input type="checkbox" id="check10" name="whats_inside[]" value="גישה לנכים">
        <label for="check10">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/img/apartment_furniture_icon10.svg" alt="apartment_furniture_icon02">
          <span>גישה לנכים</span>
        </label>
      </div>
      <div class="apartment_furniture_item">
        <input type="checkbox" id="check11" name="whats_inside[]" value="משופצת">
        <label for="check11">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/img/apartment_furniture_icon11.svg" alt="apartment_furniture_icon01">
          <span>משופצת</span>
        </label>
      </div>
      <div class="apartment_furniture_item">
        <input type="checkbox" id="check12" name="whats_inside[]" value="מחסן">
        <label for="check12">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/img/apartment_furniture_icon12.svg" alt="apartment_furniture_icon02">
          <span>מחסן</span>
        </label>
      </div>
    </div>
    <a class="detail_payment_btn mt_20" href="Apartment_rental_upload_form_details_stage_2.html">פרטים ותשלום ←</a>
  </div>
</div>
<!-- update details advertisement area end -->
  <!-- פה נגמר הפופאפ שמעדכן דירה -->


<script>
document.addEventListener('DOMContentLoaded', function() {
  // When a city is selected, fetch its related areas
  document.getElementById('city-select').addEventListener('change', function() {
    var cityId = this.value;
    var areaSelect = document.getElementById('area-select');
    areaSelect.innerHTML = '<option value="">בחר אזור</option>';
    if (cityId) {
      fetch('<?php echo admin_url('admin-ajax.php'); ?>?action=get_areas_by_city&city=' + cityId)
      .then(response => response.json())
      .then(data => {
        if (data.success && data.data.length > 0) {
          data.data.forEach(function(area) {
            var opt = document.createElement('option');
            opt.value = area.id;
            opt.innerText = area.name;
            areaSelect.appendChild(opt);
          });
        }
      });
    }
  });

  const updateBtn = document.getElementById('update-notifications');

  updateBtn.addEventListener('click', function () {
    const methods = Array.from(document.querySelectorAll('[id^="method_"]:checked')).map(cb => cb.value);
    const frequencies = Array.from(document.querySelectorAll('input[name="notification_frequency[]"]:checked')).map(cb => cb.value);
  
    const data = new URLSearchParams({
      action: 'update_notification_preferences',
      user_id: '<?php echo get_current_user_id(); ?>',
      methods: JSON.stringify(methods),
      frequency: JSON.stringify(frequencies)
    });
  
    fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: data
    })
      .then(res => res.json())
      .then(res => {
        if (res.success) {
          alert('ההתראות עודכנו בהצלחה');
        } else {
          alert('שגיאה בעדכון: ' + res.message);
        }
      });
  });

  // When the "add area" button is clicked
  document.getElementById('add-area-btn').addEventListener('click', function(event) {
    event.preventDefault();
    var areaSelect = document.getElementById('area-select');
    var selectedArea = areaSelect.value;
    if (!selectedArea) {
      alert('אנא בחר אזור');
      return;
    }
    var userId = '<?php echo get_current_user_id(); ?>';
    fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
      method: 'POST',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: new URLSearchParams({
        'action': 'add_user_area',
        'area_id': selectedArea,
        'user_id': userId
      })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        refreshSavedAreas();
      } else {
        alert('שגיאה: ' + data.message);
      }
    });
  });

  // Function to refresh the saved areas section
  function refreshSavedAreas() {
    var userId = '<?php echo get_current_user_id(); ?>';
    fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
      method: 'POST',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: new URLSearchParams({
        'action': 'get_user_saved_areas',
        'user_id': userId
      })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success && data.data.html) {
        document.querySelector('.saved_areas').innerHTML = data.data.html;
        attachDeleteEvents();
      }
    });
  }

  // Reattach delete events after refreshing the saved areas
  function attachDeleteEvents() {
    document.querySelectorAll('.delete-area-btn').forEach(button => {
      button.addEventListener('click', function(event) {
        event.preventDefault();
        var areaId = this.getAttribute('data-area-id');
        if (confirm("האם אתה בטוח שברצונך להסיר את האזור הזה?")) {
          fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: new URLSearchParams({
              'action': 'delete_user_area',
              'area_id': areaId,
              'user_id': '<?php echo get_current_user_id(); ?>'
            })
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              refreshSavedAreas();
            } else {
              alert('שגיאה: ' + data.message);
            }
          });
        }
      });
    });
  }

  // Attach delete events on page load
  attachDeleteEvents();

  // Attach click event for the link that opens the "Edit Saved Areas" tab.
  const openEditAreasLink = document.querySelector('.new_search_area');
  if (openEditAreasLink) {
    openEditAreasLink.addEventListener('click', function(event) {
      event.preventDefault();
      document.getElementById('pills-home-tab4').click();
    });
  }

  document.querySelector('.custom_btn.mt_30').addEventListener('click', function (e) {
    e.preventDefault();
  
    const fullName = document.getElementById('user_fullname').value.trim();
    const email = document.getElementById('user_email').value.trim();
    const phone = document.getElementById('user_phone').value.trim();
  
    // Split full name
    const nameParts = fullName.split(' ');
    const firstName = nameParts[0] || '';
    const lastName = nameParts.length > 1 ? nameParts.slice(1).join(' ') : '';
  
    const data = new URLSearchParams({
      action: 'update_user_personal_details',
      user_id: '<?php echo get_current_user_id(); ?>',
      first_name: firstName,
      last_name: lastName,
      email: email,
      phone: phone
    });
  
    fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: data
    })
    .then(res => res.json())
    .then(res => {
      if (res.success) {
        alert('הפרטים עודכנו בהצלחה');
      } else {
        alert('שגיאה בעדכון: ' + res.message);
      }
    });
  });

  document.querySelectorAll('.save_apartment_btn').forEach(function (btn) {
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      const postId = this.dataset.postId;
      const icon = this.querySelector('img');

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
});
</script>
<!--  הסקריפט -->
<script src="<?php echo get_template_directory_uri(); ?>/assets/js/main.js"></script>

<?php
get_footer();
?>
