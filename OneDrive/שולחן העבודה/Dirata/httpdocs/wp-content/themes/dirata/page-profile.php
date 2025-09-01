<?php
/* Template Name: Profile Page */
get_header();
?>

<?php
   $user_id = get_current_user_id();
   $user_info = get_userdata($user_id);
   $user_data = get_userdata($user_id);
   $user_roles = $user_data->roles;
   $first_name = $user_data->first_name;
   $last_name = $user_data->last_name;
   $email = $user_data->user_email;
   $phone = get_user_meta($user_id, 'phone', true);
   $notification_methods = get_field('notification_method', 'user_' . $user_id) ?: [];
   $notification_frequency = get_field('notification_frequency', 'user_' . $user_id);
   $profile_image = get_avatar_url($user_id);
   
   $user_role = $user_data->roles[0];
   $num_of_uploaded_apartments = get_field('num_of_posts', 'user_' . $user_id);
   $num_of_max_apartments = get_field('max_posts', 'user_' . $user_id);
   $subscriber_admin = true;
   if ($user_role == 'broker') {
    $subscriber_admin = false;
    $office_name = get_field('office_name', 'user_' . $user_id);
    $office_logo = get_field('office_logo', 'user_' . $user_id);
    $personalDirataPhone = get_field('personal_dirata_phone', 'user_' . $user_id);
    $mainCityOfActivity_id = get_field('main_city_of_activity', 'user_' . $user_id);
    // $city_id = get_field('apartment_city', $related_apartment_id);

    if ($mainCityOfActivity_id) {
        $term = get_term($mainCityOfActivity_id, 'city');
        $mainCityOfActivity = ($term && !is_wp_error($term)) ? $term->name : 'עיר לא תקפה';
    } else {
        $mainCityOfActivity = 'לא צוינה';
    }
    $srcLogo = '';
    if($office_logo){
        $srcLogo = $office_logo;
    } 
    else{
      $srcLogo = get_template_directory_uri() . '/assets/img/upload_img.svg';
    }
   }

  $args_leads = array(
      'post_type'      => 'lead',
      'posts_per_page' => 40,
      'post_status'    => 'publish',
      'orderby'        => 'date', // מיון לפי תאריך פרסום
      'order'          => 'DESC'  // החדשים ביותר ראשונים
  );

  $leads = get_posts($args_leads);
    ?>

    <!-- להוסיף פה סימן קריאה כשמסיימת להכניס את כל המידע -->
<?php if (!$subscriber_admin): ?>
<!-- אם הוא מתווך אז מציג את להלן -->

<div id="popup-edit-apartment"></div>
<!-- הפופאפ של שדרוג מנוי -->
<!-- פופאפ יצירת קשר לשדרוג מנוי -->
<?php 
  $current_user = wp_get_current_user();
  $full_name = trim($current_user->first_name . ' ' . $current_user->last_name);
  $phone = get_user_meta($current_user->ID, 'phone', true); // או כל שם השדה שלך
?>
<div class="upgrade_contact_popup private_apartment_form_wrapper" id="upgradeContactPopup">

    <div class="close_icon"><i class="fal fa-times"></i></div>
    <div class="form_wrap text-right">
        <h2 class="private_apartment_form_head">יצירת קשר לשדרוג מנוי מתווך</h2>
        <p class="mt_8">נא השאירו פרטים ונחזור אליכם בהקדם.</p>

        <div class="row custom_row02 mt_20">
            <div class="col-sm-6 custom_col02">
                <div class="input_box">
                    <label for="full_name">שם מלא<span>*</span></label>
                    <input id="full_name" type="text" placeholder="שם מלא" value="<?php echo esc_attr($full_name); ?>">
                </div>
            </div>
            <div class="col-sm-6 custom_col02">
                <div class="input_box">
                    <label for="phone">טלפון<span>*</span></label>
                    <input id="phone" type="text" placeholder="050-1234567" value="<?php echo esc_attr($phone); ?>">
                </div>
            </div>
        </div>

        <div class="row custom_row02 mt_20">
            <div class="col-sm-12 custom_col02">
                <div class="input_box">
                    <label for="message">הערות נוספות</label>
                    <textarea id="message" placeholder="אפשר לרשום כאן כל דבר נוסף..." value="אשמח להצעה לשדרוג המנוי שלי כמתווך בדירתא."></textarea>
                </div>
            </div>
        </div>

        <div class="mt_20 text-left">
            <button type="button" class="custom_btn" id="send_upgrade_request">שלחו פנייה</button>
        </div>
        <div id="upgrade_form_msg" class="form_message mt_20"></div>

    </div>
    
</div>

<!-- Broker personal area start -->

<div class="broker_personal_wrapper overflow-hidden">
    <div>
        <div class="row">
            <!-- התפריט בימין -->
            <div class="col-lg-2">
                <div class="broker_personal_nav">
                    <img class="broker_shape" src="<?php echo get_template_directory_uri(); ?>/assets/img/broker_shape.svg" alt="broker_shape">
                    <div class="sticky-tabs-wrapper">
                        <ul class="nav sticky-tabs" id="pills-tab" role="tablist">
                            <li class="w-100" role="presentation">
                                <a class="active" id="pills-overview-tab" data-toggle="pill" data-target="#pills-overview" role="tab" aria-controls="pills-overview" aria-selected="true">מבט מלמעלה</a>
                            </li>
                            <li class="w-100" role="presentation">
                                <a  id="pills-apartments-tab" data-toggle="pill" data-target="#pills-apartments" role="tab" aria-controls="pills-apartments" aria-selected="false">הדירות שפרסמתי</a>
                            </li>
                            <li class="w-100" role="presentation">
                                <a id="pills-leads-tab" data-toggle="pill" data-target="#pills-leads" role="tab" aria-controls="pills-leads" aria-selected="false">הלידים שלי</a>
                            </li>
                            <li class="w-100" role="presentation">
                                <a id="pills-publish-tab" data-toggle="pill" data-target="#pills-publish" role="tab" aria-controls="pills-publish" aria-selected="false">פרסום דירה</a>
                            </li>
                            <li class="w-100" role="presentation">
                                <a id="pills-my-acount-tab" data-toggle="pill" data-target="#pills-my-acount" role="tab" aria-controls="pills-my-acount" aria-selected="false">
                                <div class="broker_account_item">
                                <div class="d-flex align-items-center">
                                    <img class="broker_man img-fluid rounded-circle" src="<?php echo esc_html($profile_image); ?>" alt="broker_man" width="40" height="40">
                                    <div>
                                    <p><?php echo esc_html($first_name . ' ' . $last_name); ?></p>
                                    <span class="mt_5">החשבון שלי</span>
                                    </div>
                                </div>
                                </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <!-- Reverted to original structure, removed separate logout button -->
                     <div class="broker_account_item"> 
                         <div class="text-center mt_60"> 
                         <a class="mt_60" href="<?php echo wp_logout_url(home_url()); ?>">התנתקות</a>
                         </div>
                     </div> 
                </div>
            </div>
            <!-- התוכן בשמאל -->
            <div class="col-lg-10">
                <div class="tab-content" id="pills-tabContent">
                    <!-- Tab Pane: מבט מלמעלה -->
                    <div class="tab-pane fade show active" id="pills-overview" role="tabpanel" aria-labelledby="pills-overview-tab">
                        <div class="broker_personal_apartment_advertised_wrap custom_padd">
                            <h2 class="custom_head head_text02">מבט מלמעלה</h2>
                            <!-- <p>כאן את אמורה להכניס את התוכן מהHTML הרלוונטי.</p> -->
                            <div class="col-lg-10 custom_col_space">
                                <div class="broker_personal_wrap">
                                    <div class="mt_45">
                                        <?php
                                        $is_non_watched_lead = get_field('is_non_watched_lead' , 'user_' . $user_id);
                                        ?>
                                        <!-- <p class="view_popup"><?php echo $is_non_watched_lead; ?></p> -->
                                        <?php
                                        if($is_non_watched_lead){
                                            ?>
                                            <p class="view_popup">
                                            <i class="fal fa-times saw_lead"></i>
                                                 ליד חדש נכנס למערכת! לצפיה <span class="to-my-leads saw_lead" style="cursor: pointer;">לחץ כאן </span>
                                            </p>
                                            <?php
                                        }
                                        else {
                                            ?>
                                            <p></p>
                                            <?php
                                        }
                                        
                                        ?>
                                        

                                        <div class="row custom_row">
                                            <div class="col-xl-7 col-lg-6 custom_col">
                                                <div class="row custom_row">
                                                    <div class="col-lg-12 col-md-6 custom_col">
                                                        <div class="subscription_item default_item mt_20">
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <h3 class="head_text02"> <?php echo $first_name . ' ' . $last_name; ?>
                                                                
                                                              </h3>
                                                                <a class="mini_btn to-my-acount"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon15.svg" alt="icon15">לעריכה ←</a>
                                                            </div>
                                                            <div class="mt_20">
                                                                <a class="link" href="tel:0548439972"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon01.svg" alt="icon01"><?php echo $phone; ?></a>
                                                                <a class="link mt_8" href="mailto:a0548439972@gmail.com"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon02.svg" alt="icon02"><?php echo $email; ?></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-md-6 custom_col">
                                                        <div class="subscription_item default_item mt_20">
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <h3 class="head_text02">פרטי מנוי</h3>
                                                                <a class="mini_btn to-my-acount" >לשדרוג מנוי ←</a>
                                                                </div>
                                                            <div class="custom_width d-flex align-items-center justify-content-between flex-wrap">
                                                                <div class="devide_item text-right mt_30">
                                                                    <h4>מסלול</h4>
                                                                    <p class="mt_5">Best</p>
                                                                </div>
                                                                <div class="devide_item text-right mt_30">
                                                                    <h4>מחזור חיוב</h4>
                                                                    <p class="mt_5">שנתי</p>
                                                                </div>
                                                                <div class="devide_item text-right mt_30">
                                                                    <h4>בתוקף עד</h4>
                                                                    <p class="mt_5">27/02/2026</p>
                                                                </div>
                                                                <div class="devide_item text-right mt_30">
                                                                    <h4>דירות שהעלו</h4>
                                                                    <p class="mt_5"><?php echo $num_of_uploaded_apartments . '/' . $num_of_max_apartments ; ?></p>
                                                                </div>
                                                            </div>
                                                            <div class="subscription_btn_wrap text-right mt_22">
                                                                <a class="subscription_btn to-my-acount">לפרטי מנוי מלאים</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 custom_col">
                                                        <div class="lead_item default_item mt_20">
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <h3 class="head_text02">הלידים</h3>
                                                                <a class="mini_btn to-my-leads">לכל הלידים ←</a>
                                                            </div>
                                                            <ul class="mt_20">
                                                              <?php 
                                                              $num_of_leads_i_have_showed = 0;
                                                              if ($leads) {
                                                                $this_user_id = get_current_user_id();
                                                                $has_leads = false;
                                                                foreach ($leads as $lead) {
                                                                    $lead_post = is_object($lead) ? $lead : get_post($lead);
                                                                    setup_postdata($lead_post);
                                                                    $this_lead_id = $lead->ID;                   
                                                                  
                                                                    $this_leads_owner = get_post_meta($this_lead_id, 'this_leads_owner', true) ?: '';
                                                            
                                                                    if ($this_leads_owner == $this_user_id && $num_of_leads_i_have_showed <8) {
                                                                        $t = '';
                                                                        $has_leads = true; // מצאנו ליד ששייך למשתמש
                                                                        $lead_phone = get_post_meta($this_lead_id, 'leader_phone', true) ?: 'לא צוין טלפון';
                                                                        // $lead_city = get_post_meta($this_lead_id, 'leader_city', true) ?: 'לא צוינה עיר';
                                                                        // $lead_neighborhood = get_post_meta($this_lead_id, 'leader_neighberhood', true) ?: 'לא צוינה שכונה';
                                                                        // $lead_house_num = get_post_meta($this_lead_id, 'leader_house_num', true) ?: 'לא צויין מספר בית';
                                                                        $related_apartment_id = get_post_meta($this_lead_id, 'this_lead_apartment', true) ?: 'לא קושרה לליד זה אף דירה';
                                                                        $related_apartment_title = $related_apartment_id ? get_the_title($related_apartment_id) : 'לא צוינה דירה';
                                                                        // $current_apartment_city = get_field('apartment_city', $related_apartment_id) ?: 'לא צוינה עיר לדירה זו';
                                                                        $city_id = get_field('apartment_city', $related_apartment_id);

                                                                        if ($city_id) {
                                                                            $term = get_term($city_id[0], 'city');
                                                                            $current_apartment_city = ($term && !is_wp_error($term)) ? $term->name : 'עיר לא תקפה';
                                                                        } else {
                                                                            $current_apartment_city = 'לא צוינה עיר לדירה זו';
                                                                        }
                                                                        // $current_apartment_neighborhood = get_field('apartment_neighborhood', $related_apartment_id) ?: 'לא צוינה שכונה לדירה זו';
                                                                        $neighborhoods = wp_get_post_terms($related_apartment_id, 'area', ['fields' => 'names']);
                                                                        $current_apartment_neighborhood = !empty($neighborhoods) ? implode(', ', $neighborhoods) : 'לא צוינה';
                                                                        $current_apartment_house_num = get_field('apartment_house_num', $related_apartment_id) ?: 'לא צויין';
                                                                        
                                                                        ?>
                                                                        <li class="mt_8">
                                                                            <p><?php echo get_the_title($this_lead_id); ?></p>
                                                                            <p><?php echo $lead_phone; ?></p>
                                                                            <p><?php echo $current_apartment_city; ?></p>
                                                                            <p><?php echo $current_apartment_neighborhood; ?></p>
                                                                            <p><?php echo $current_apartment_house_num; ?></p>
                                                                            <span><?php echo $related_apartment_title; ?></span>
                                                                        </li>
                                                                        <?php
                                                                        $num_of_leads_i_have_showed++;
                                                                    }
                                                                    wp_reset_postdata();
                                                                }
                                                            
                                                                if (!$has_leads) {
                                                                    ?>
                                                                    <li><p>עוד לא קיבלת לידים מדירות שפרסמת.</p></li>
                                                                    <?php
                                                                }
                                                            
                                                                wp_reset_postdata();
                                                              }
                                                             else {
                                                                  // הודעה במקרה שאין לידים
                                                                  ?>
                                                                  <li><p>אין לידים להצגה כרגע.</p></li>
                                                                  <?php
                                                              }
                                                              ?>                                                          
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-5 col-lg-6 custom_col">
                                                <div class="card_item03_main default_item mt_20">
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <h2>הדירות שפרסמתי</h2>
                                                        <a class="mini_btn to-my-apartments">לכל הדירות ←</a>
                                                    </div>
                                                    <div class="row custom_row">
                                                    <?php
                                                        if ($user_id) {
                                                            $args_apartments = array(
                                                                'post_type'      => 'apartment',
                                                                'posts_per_page' => 4,
                                                                'post_status'    => 'publish',
                                                                'author'         => $user_id,
                                                            );

                                                            $query = new WP_Query($args_apartments);
                                                            if ($query->have_posts()) :
                                                                echo '<div class="row g-4 justify-content-start">';
                                                                while ($query->have_posts()) : $query->the_post();
                                                                $img_id = get_post_meta(get_the_ID(), 'main_img', true);
                                                                $img_url = wp_get_attachment_url($img_id);
                                                                    if (!$img_url) {
                                                                        $img_url = get_template_directory_uri() . '/assets/img/apartment_img01.svg';
                                                                    }
                                                                    $terms = get_the_terms(get_the_ID(), 'city');
                                                                    $city = ($terms && !is_wp_error($terms)) ? $terms[0]->name : 'לא צוינה עיר';
                                                                    $title = get_the_title();
                                                                    $rooms = get_post_meta(get_the_ID(), 'rooms', true);
                                                                    $floor = get_post_meta(get_the_ID(), 'floor', true);
                                                                    $size  = get_post_meta(get_the_ID(), 'size', true);
                                                                    $price = get_post_meta(get_the_ID(), 'price', true);
                                                                    $price = get_field('price');
                                                                    if (is_numeric($price)) {
                                                                        $price = number_format_i18n($price);
                                                                    } else {
                                                                        $price = '—';
                                                                    }

                                                                    $listing_type = get_field('listing_type');
                                                                    // כאן שמתי רגע את הכרטיס מטמפלט כרטיס כדי לראות
                                                                    set_query_var('show_edit_icons', true);
                                                                    get_template_part('template-parts/apartment-item');
                                                                    
                                                                ?>
                                                              
                                                                <?php
                                                                endwhile;
                                                                wp_reset_postdata();
                                                                echo '</div>';
                                                            else :
                                                                echo '<p>עדיין לא פרסמת דירות במערכת.</p>';
                                                            endif;
                                                        } else {
                                                            echo '<p>לא נמצאו דירות</p>';
                                                        }
                                                        ?>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Pane: הדירות שפרסמתי -->
                    <div class="tab-pane fade " id="pills-apartments" role="tabpanel" aria-labelledby="pills-apartments-tab">
                        <div class="broker_personal_apartment_advertised_wrap custom_padd">
                            <h2 class="custom_head head_text02">הדירות שפרסמתי</h2>
                            <?php

                            if ($user_id) {
                                $args_apartments = array(
                                    'post_type'      => 'apartment',
                                    'posts_per_page' => -1,
                                    'post_status'    => 'publish',
                                    'author'         => $user_id, // ← זה הסינון לפי משתמש נוכחי
                                );

                                $query = new WP_Query($args_apartments);
                                if ( $query->have_posts() ) :
                            ?>
                                    <div class="row g-4 justify-content-start custom_row4 mt_20">
                                        <?php
                                        while ( $query->have_posts() ) : $query->the_post();
                                        $img_id = get_post_meta(get_the_ID(), 'main_img', true);
                                        $img_url = wp_get_attachment_url($img_id);
                                            if (!$img_url) {
                                                $img_url = get_template_directory_uri() . '/assets/img/card_img.svg';
                                            }
                                            $terms = get_the_terms(get_the_ID(), 'city');
                                            $city = ($terms && !is_wp_error($terms)) ? $terms[0]->name : 'לא צוינה עיר';
                                            $title = get_the_title();
                                            $rooms = get_post_meta(get_the_ID(), 'rooms', true);
                                            $floor = get_post_meta(get_the_ID(), 'floor', true);
                                            $size  = get_post_meta(get_the_ID(), 'size', true);
                                            $price = get_post_meta(get_the_ID(), 'price', true);
                                            $listing_type = get_field('listing_type');
                                            set_query_var('show_edit_icons', true);
                                            set_query_var('col_width', 4);
                                            get_template_part('template-parts/apartment-item');
                                        ?>
                                          

                                        <?php
                                        endwhile;
                                        wp_reset_postdata();
                                        ?>
                                    </div>
                                <?php
                                else :
                                    echo '<p>עדיין לא פרסמת דירות במערכת.</p>';
                                endif;
                            } else {
                                echo '<p>לא נמצאו דירות</p>';
                            }
                                ?>
                        </div>
                    </div>

                    <!-- Tab Pane: הלידים שלי -->
                    <div class="tab-pane fade col-lg-10" id="pills-leads" role="tabpanel" aria-labelledby="pills-leads-tab">
                        <div class="broker_personal_apartment_advertised_wrap custom_padd">
                            <!-- <p>תוכן עבור הלידים שלי.</p> -->
                            <div class="my_leads_personal_broker_wrap custom_padd">
                                <div class="custom_direction d-flex align-items-center">
                                    <h2 class="custom_head head_text02">הלידים שלי</h2>
                                    <ul class="search_nav">
                                    <li>חפש לפי דירה: 
                                    <?php
                                    $this_user_id = get_current_user_id();
                                    $args = array(
                                        'post_type'      => 'apartment',
                                        'posts_per_page' => -1,
                                        'author'         => $this_user_id,
                                        'post_status'    => 'publish'
                                    );
                                    $apartments = get_posts($args);
                                    ?>
                                    <select id="filter-by-apartment" style="width: 250px;" class="select2">
                                        <option value="">הצג את כל הדירות</option>
                                        <?php foreach ($apartments as $apartment): ?>
                                            <option value="<?php echo esc_attr($apartment->ID); ?>">
                                                <?php echo esc_html($apartment->post_title); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>

                                      </li>
                                    </ul>
                                </div>
                                <nav>
                                    <p>שם</p>
                                    <p>טלפון</p>
                                    <p>עיר</p>
                                    <p>שכונה</p>
                                    <p>דירה</p>
                                    <!-- <p>חתימת תיווך</p> -->
                                </nav>
                                <p id="no-leads-message" class= "mt_13" style="display:none;">עדיין לא נכנסו לידים לדירה זו.</p>
                                <h3 id="no-overall-leads-message" class="mt_13" style="display:none;">
                                    עדיין לא נכנסו לידים לדירות שלך.
                                    <span>תעלה עוד דירות למערכת כדי להגיע לכמה שיותר לידים.</span>

                                        </h3>

                                <div class="accordion" id="accordionExample">
                                  <?php 
                                  $num_of_leads_i_have_showed = 0;
                                  if ($leads) {
                                    $this_user_id = get_current_user_id();
                                    $has_leads = false;
                                    foreach ($leads as $lead) {
                                        $lead_post = is_object($lead) ? $lead : get_post($lead);
                                        setup_postdata($lead_post);
                                        $this_lead_id = $lead->ID;                            
                                        $this_leads_owner = get_post_meta($this_lead_id, 'this_leads_owner', true) ?: '';
                                        $activeToggle = ' ';
                                        $filter_apartment_id = isset($_GET['filter_apartment']) ? sanitize_text_field($_GET['filter_apartment']) : '';
                                        $related_apartment_id = get_post_meta($this_lead_id, 'this_lead_apartment', true) ?: '';
                                        // if ($this_leads_owner == $this_user_id 
                                        //     && $num_of_leads_i_have_showed < 40
                                        //     && (empty($filter_apartment_id) || $related_apartment_id == $filter_apartment_id)) {

                                        if ($this_leads_owner == $this_user_id && $num_of_leads_i_have_showed <40) {
                                            
                                            if($num_of_leads_i_have_showed == 0){
                                              $activeToggle = 'activeToggle';
                                            }
                                            else{
                                              $activeToggle = ' ';
                                            }
                                            $has_leads = true; // מצאנו ליד ששייך למשתמש
                                            $lead_phone = get_post_meta($this_lead_id, 'leader_phone', true) ?: 'לא צוין טלפון';
                                            // $lead_city = get_post_meta($related_apartment_id, 'apartment_city', true) ?: 'לא צוינה עיר';
                                            $lead_street = get_post_meta($related_apartment_id, 'apartment_street', true) ?: 'לא צוינה רחוב';
                                            // $lead_house_num = get_post_meta($related_apartment_id, 'apartment_house_num', true) ?: 'לא צויין מספר בית';
                                            // $lead_neighborhood = get_post_meta($related_apartment_id, 'apartment_neighborhood', true) ?: 'לא צוינה שכונה';
                                            // $related_apartment_id = get_post_meta($this_lead_id, 'this_lead_apartment', true) ?: 'לא קושרה לליד זה אף דירה';
                                            $related_apartment_title = $related_apartment_id ? get_the_title($related_apartment_id) : 'לא צוינה דירה';
                                            $events_timeline = get_field('events_timeline', $this_lead_id) ?: [];

                                            // $current_apartment_city = get_field('apartment_city', $related_apartment_id) ?: 'לא צוינה עיר לדירה זו';
                                            $city_id = get_field('apartment_city', $related_apartment_id);

                                            if ($city_id) {
                                                $term = get_term($city_id[0], 'city');
                                                $current_apartment_city = ($term && !is_wp_error($term)) ? $term->name : 'עיר לא תקפה';
                                            } else {
                                                $current_apartment_city = 'לא צוינה עיר לדירה זו';
                                            }
                                            $area_id = get_field('apartment_neighborhood', $related_apartment_id);

                                            if ($area_id) {
                                                $term = get_term($area_id[0], 'area');
                                                $current_apartment_neighborhood = ($term && !is_wp_error($term)) ? $term->name : 'שכונה לא תקפה';
                                            } else {
                                                $current_apartment_neighborhood = 'לא צוינה';
                                            }



                                            // $current_apartment_neighborhood = get_field('apartment_neighborhood', $related_apartment_id) ?: 'לא צוינה שכונה לדירה זו';
                                            $current_apartment_house_num = get_field('apartment_house_num', $related_apartment_id) ?: 'לא צויין';

                                            ?>
                                            <div class="card <?php echo $activeToggle; ?> mt_13" data-apartment-id="<?php echo esc_attr($related_apartment_id); ?>">
                                                <div class="card-header" id="heading-<?php echo $this_lead_id; ?>">
                                                    <h2 data-toggle="collapse" data-target="#collapse-<?php echo $this_lead_id; ?>" aria-expanded="false" aria-controls="collapse-<?php echo $this_lead_id; ?>">
                                                        <div class="custom_justify d-flex align-items-center justify-content-between flex-wrap">
                                                            <p><?php echo get_the_title($this_lead_id); ?></p>
                                                            <p><?php echo $lead_phone; ?></p>
                                                            <p><?php echo $current_apartment_city; ?></p>
                                                            <p><?php echo $current_apartment_neighborhood; ?></p>
                                                            <p><?php echo $current_apartment_house_num?></p>
                                                            <p class="active"><?php echo $related_apartment_title; ?></p>
                                                            <!-- <p class="card_width"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon025.svg" alt="icon025"></p> -->
                                                        </div>
                                                    </h2>
                                                    <img class="arrow_icon" src="<?php echo get_template_directory_uri(); ?>/assets/img/icon026.svg" alt="icon026">
                                                </div>

                                                <div id="collapse-<?php echo $this_lead_id; ?>" class="collapse" aria-labelledby="heading-<?php echo $this_lead_id; ?>" data-parent="#accordionExample">
                                                    <div class="card-body">
                                                        <p> hi </p>
                                                        <?php foreach($events_timeline as $event){ 
                                                           $this_event_title = $event['event_title'] ? $event['event_title'] : 'לא צוין כותרת';
                                                           $this_event_time = $event['event_time'] ? $event['event_time'] : 'לא צוין זמן';
                                                           $this_event_attachment = $event['event_attachment'] ? $event['event_attachment'] : 'לא צוין קובץ';
                                                           $this_event_details = $event['event_details'] ? $event['event_details'] : 'לא צוין פרטים';
                                                                                                                  
                                                        ?>
                                                        <div class="default_item mt_10">
                                                            <div class="my_lead_card_sub">
                                                                <p class="font400"><?php echo $this_event_title; ?></p>
                                                                <span class="mt_8"><?php echo $this_event_time; ?></span>
                                                            </div>
                                                            <div class="my_lead_bottom_para">
                                                                <p><?php echo $this_event_details; ?> </p>
                                                            </div>
                                                        </div>
                                                        <?php } ?>
                                                        <a class="mini_btn mt_13 add_update" href="#">הוסף עדכון נוסף ←</a>                                                           
                                                        <div class="add-update-section">
                                                            <form data-lead-id="<?php echo $this_lead_id; ?>">
                                                                <div class="row custom_row3 mt_30">
                                                                    <div class="col-sm-4 custom_col3">
                                                                        <div>
                                                                            <label for="update_date">תאריך העדכון:</label>
                                                                            <input class="input_info mt_13" id="update_date" name="update_date" type="datetime-local" placeholder="" value="">
                                                                        </div> 
                                                                    </div>                                                               
                                                                    <div class="col-sm-8 custom_col3">
                                                                        <div>
                                                                            <label for="update_title">כותרת העדכון:</label>
                                                                            <input class="input_info mt_13" id="update_title" name="update_title" type="text" placeholder="" value="">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row custom_row3 mt_30">
                                                                    <div class="col-sm-12 custom_col3">
                                                                        <div>
                                                                            <label for="update_content">תוכן העדכון:</label>
                                                                            <input class="input_info mt_13" id="update_content" name="update_content" type="text" placeholder="" value="">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row custom_row3 mt_30">
                                                                    <div class="col-sm-12 custom_col3">
                                                                        <div>
                                                                            <label for="update_file">קובץ מצורף:</label>
                                                                            <input class="input_info mt_13" id="update_file" name="update_file" type="file" placeholder="" value="">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="text-right mt_30">
                                                                    <div class="update-msg-box"></div>
                                                                    <input type="button" class="custom_btn" id="save_update" value="שמירת עדכון" onclick="saveUpdate(this)" />
                                                                </div>                                                                
                                                            </form>
                                                        </div>                                                   
                                                    </div>
                                                </div>
                                            </div>

                                            <?php
                                            $num_of_leads_i_have_showed++;
                                        }
                                        wp_reset_postdata();
                                    }
                                
                                    if (!$has_leads) {
                                        ?>
                                        <li><p>עוד לא קיבלת לידים מדירות שפרסמת.</p></li>
                                        <?php
                                    }
                                
                                    wp_reset_postdata();
                                  }
                                  else {
                                      // הודעה במקרה שאין לידים
                                      ?>
                                      <li><p>אין לידים להצגה כרגע.</p></li>
                                      <?php
                                  }
                                  ?>
                                </div>
                                <div class="mt_20 d-flex align-items-left">
                                <div id="leads-count"></div>
                                <div id="pagination" class="mt_20 text-left">
                                    <button type="button" id="prev-page" disabled>→</button> 
                                    <button type="button" id="next-page">←</button>                                                                      
                                </div>
                                </div>

                                <div id="loading-message" style="display: none; text-align:center;">טוען לידים נוספים...</div>
                                <!-- עד פה הלידים -->
                            </div>
                        </div>
                    </div>

                    <!-- Tab Pane: פרסום דירה -->
                    <div class="tab-pane fade col-lg-10" id="pills-publish" role="tabpanel" aria-labelledby="pills-publish-tab">
                        <!-- כאן הוספתי קלאס כדי שיעבוד לי העיצוב של הטופס כמו שעמוד פרסום דירה -->
                        <div class="broker_personal_apartment_advertised_wrap custom_padd private_apartment_form_wrapper form_wrap">
                            <h2 class="custom_head head_text02">פרסום דירה</h2>
                            <?php get_template_part('upload_apartment_for_sale'); ?>
                        </div>
                    </div>
            </div> <!-- סוף tab-content -->
                    <!-- Tab Pane: החשבון שלי -->
                    <div class="tab-pane fade col-lg-10" id="pills-my-acount" role="tabpanel" aria-labelledby="pills-my-acount-tab">
                            <div class="broker_personal_my_account_wrap custom_padd">
                                <h2 class="custom_head head_text02">החשבון שלי</h2>
                                <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" enctype="multipart/form-data">
                                    <input type="hidden" name="action" value="save_broker_profile">
                                    <input type="hidden" name="user_id" value="<?php echo get_current_user_id(); ?>">

                                    <div class="my_account_form default_item mt_30">
                                        <h2 class="form_head">עריכת פרטים</h2>
                                        <div class="row custom_row3 mt_30">
                                            <!-- שם המתווך אינפוט A  -->
                                            <div class="col-sm-4 custom_col3">
                                                <div>
                                                    <label for="a">שם:</label>
                                                    <input class="input_info mt_13" id="a" name="a" type="text" placeholder="שם המתווך" value="<?php echo esc_html($first_name . ' ' . $last_name); ?>">
                                                </div>
                                            </div>
                                            <!-- שם המשרד אינפוט B  -->
                                            <div class="col-sm-4 custom_col3 custom_mt14">
                                                <div>
                                                    <label for="b">שם המשרד:</label>
                                                    <input class="input_info mt_13" id="b" name="b" type="text" placeholder="שם המשרד" value="<?php echo esc_html($office_name); ?>">
                                                </div>
                                            </div>
                                            <!-- F המספר הוירטואלי -->
                                            <div class="col-sm-4 custom_col3 custom_mt14">
                                                <div>
                                                    <label for="f">המספר הוירטואלי שלי</label>
                                                    <input class="input_info mt_13" id="f" name="f" type="text" placeholder="המספר הוירטואלי שלי" value="<?php echo esc_html($personalDirataPhone); ?>" 
                                                    style="border: 0px!important; font-weight: 600!important;" readonly>
                                                </div>
                                            </div>
                                            <!-- C אימייל -->
                                            <div class="col-sm-4 custom_col3 mt_30">
                                                <div>
                                                    <label for="c">אימייל:</label>
                                                    <input class="input_info mt_13" id="c" name="c" type="text" placeholder="example@example.com" value="<?php echo esc_html($email); ?>">
                                                </div>
                                            </div>
                                            <!-- D טלפון -->
                                            <div class="col-sm-4 custom_col3 mt_30">
                                                <div>
                                                    <label for="d">טלפון:</label>
                                                    <input class="input_info mt_13" id="d" name="d" type="text" placeholder="000-000-0000" value="<?php echo esc_html($phone); ?>">
                                                </div>
                                            </div>
                                            <!-- E ווצאפ -->
                                            <div class="col-sm-4 custom_col3 mt_30">
                                                <div>
                                                    <label for="e">ווצאפ:</label>
                                                    <input class="input_info mt_13" id="e" name="e" type="text" placeholder="+97250-000-0000" value="<?php echo '+972' .esc_html($phone); ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row align-items-center">
                                            <!-- G עיר פעילות עיקרית -->
                                            <div class="col-sm-4 custom_col3 mt_30">
                                                    <div>
                                                        <label for="g">עיר פעילות עיקרית:</label>
                                                        <!-- <input class="input_info mt_13" id="g" type="text" placeholder="אשדוד" value="<?php echo esc_html($city); ?>"> -->
                                                        <select id="g" name="g" class="custon-select" required>
                                                            <option value="<?php echo esc_html($mainCityOfActivity_id); ?>"><?php echo esc_html($mainCityOfActivity); ?></option>
                                                            <?php
                                                            $cities = get_terms(array(
                                                                'taxonomy'   => 'city',
                                                                'hide_empty' => false,
                                                                'exclude' => $mainCityOfActivity_id,
                                                            ));
                                                            if (!empty($cities) && !is_wp_error($cities)) {
                                                            foreach ($cities as $city) {
                                                                echo '<option value="' . esc_attr($city->term_id) . '">' . esc_html($city->name) . '</option>';
                                                            }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                            </div>
                                            <!-- ImageMedias לוגו המשרד -->
                                            <div class="col-sm-4 mt_30 custom_col3">
                                                <label for="ImageMedias">לוגו המשרד:</label>
                                                <div class="d-flex align-items-center mt_8">
                                                    <div class="upload_img" id="divImageMediaPreview"><img class="w-100" src="<?php echo $srcLogo;?>" alt="upload_img"></div>
                                                    <div class="upload_text" style="cursor: pointer!important;">
                                                    להחלפת הלוגו לחץ כאן
                                                    <!-- <input id="ImageMedias" multiple="multiple" name="ImageMedias" type="file" accept=".jfif,.jpg,.jpeg,.png,.gif,.webp" value=""> -->
                                                    <input id="ImageMedias" name="ImageMedias" type="file" accept=".jfif,.jpg,.jpeg,.png,.gif,.webp">

                                                    </div>
                                                </div>
                                            </div>
                                            <!-- כפתור שמירת פרטים -->
                                            <div class="col-sm-4 text-left mt_30">
                                                <button class="custom_btn">שמירת פרטים</button>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if (isset($_GET['updated']) && $_GET['updated'] === 'true') : ?>
                                        <div class="alert alert-success mt_20">🎉 הפרטים נשמרו בהצלחה!</div>
                                    <?php endif; ?>

                                </form>
                                <form>
                                    <!-- פרטי מנוי -->
                                    <div class="my_account_form my_account_form02 default_item mt_30">
                                        <h2 class="form_head">פרטי מנוי</h2>
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
                                                <span><?php echo $num_of_uploaded_apartments . '/' . $num_of_max_apartments ; ?></span>
                                            </div>
                                        </div>
                                        <div class="mt_40 text-left">
                                            <button type="button" id="upgradeContactBtn" class="custom_btn">צרו קשר לשדרוג המנוי</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            
                    </div>
                
            </div>
        </div>
    </div>
</div>

<!-- Broker personal area end -->
<?php endif; ?>

<!-- Personal Area Registered area start -->
<?php if ($subscriber_admin): ?>
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
                  <button class="active" id="pills-home-tab1" data-toggle="pill" data-target="#pills-home1" type="button" role="tab" aria-controls="pills-home1" aria-selected="true">
                     מבט <br>מלמעלה
                  </button>
               </li>
               <li role="presentation">
                  <button id="pills-home-tab2" data-toggle="pill" data-target="#pills-home2" type="button" role="tab" aria-controls="pills-home2" aria-selected="false">
                     הדירות <br>שמתאימות <br>לחיפוש שלי
                  </button>
               </li>
               <li role="presentation">
                  <button class="personal_registered_border" id="pills-home-tab3" data-toggle="pill" data-target="#pills-home3" type="button" role="tab" aria-controls="pills-home3" aria-selected="false">
                     ניהול <br>התראות
                  </button>
               </li>
               <li role="presentation">
                  <button id="pills-home-tab4" data-toggle="pill" data-target="#pills-home4" type="button" role="tab" aria-controls="pills-home4" aria-selected="false">
                     עדכון <br>בחירת אזור
                  </button>
               </li>

               <li role="presentation">
                  <button id="pills-home-tab5" data-toggle="pill" data-target="#pills-home5" type="button" role="tab" aria-controls="pills-home5" aria-selected="false">
                     דירות <br>מסומנות
                  </button>
               </li>
               <li role="presentation">
                  <button id="pills-home-tab6" data-toggle="pill" data-target="#pills-home6" type="button" role="tab" aria-controls="pills-home6" aria-selected="false">
                     דירות <br>שפירסמתי
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
                  <!-- <p class="view_popup">
                    <i class="fal fa-times"></i> 
                    יש דירה חדשה שמתאימה לחיפוש שלך, לצפיה  <span>לחץ כאן </span>
                    </p> -->
                  
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
                            'phone' => 'צינתוק לטלפון',
                            'email' => 'אימייל',
                            // 'whatsapp' => 'וואטסאפ'
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
                                <div class="edit_link">
                                    <a id="pills-home-tab5" data-toggle="pill" data-target="#pills-home5" href="#" role="tab" aria-controls="pills-home5" aria-selected="false">
                                        לכל הדירות שסימנתי ←
                                    </a>
                                </div>
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
                                                    <h4><?php echo safe_price($price); ?> ₪</h4>
                                                </div>
                                                <div class="apartment_details">
                                                    <span><?php echo esc_html($city); ?></span>
                                                    <span><?php echo esc_html($sale_type); ?></span>
                                                </div>
                                                <div class="apartment_details">
                                                    <span><?php echo esc_html($rooms); ?> חדרים</span>
                                                    <span>קומה <?php echo esc_html($floor); ?></span>
                                                    <span><?php echo esc_html($size); ?> מ”ר</span>
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
                          <h3>הדירות שמתאימות לחיפוש שלי</h3>
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
                                      $city = ($terms && !is_wp_error($terms)) ? $terms[0]->name : 'לא צוינה עיר';
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
               <div class="apartment_search_wrap">
                  <div class="d-flex align-items-center justify-content-between flex-wrap">
                     <h3 class="personal_registered_sub_head position-relative">הדירות שמתאימות לחיפוש שלי</h3>
                     <a class="new_search_area" href="#" class="">לבחירת אזור חיפוש חדש ←</a>
                  </div>
                  <div class="row apartment_search_slider custom_row mt_30">
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
                                 $city = ($terms && !is_wp_error($terms)) ? $terms[0]->name : 'לא צוינה עיר';
                                 $title = get_the_title();
                                 $rooms = get_post_meta(get_the_ID(), 'rooms', true);
                                 $floor = get_post_meta(get_the_ID(), 'floor', true);
                                 $size  = get_post_meta(get_the_ID(), 'size', true);
                                 $price = get_post_meta(get_the_ID(), 'price', true);
                                 $listing_type = get_field('listing_type');
                                 ?>
                                 <div class="col-lg-3 col-md-4 mt_15 custom_col">
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
                                             <p> <?php echo esc_html($listing_type); ?>  |  <?php echo esc_html($rooms); ?> חדרים  |  קומה <?php echo esc_html($floor); ?>  |  <?php echo esc_html($size); ?> מ"ר</p>
                                             <h3><?php echo number_format($price); ?> ₪</h3>
                                          </div>
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

            <div class="tab-pane fade" id="pills-home3" role="tabpanel" aria-labelledby="pills-home-tab3">
               <div class="notification_management_wrap text-right">
                  <h3 class="personal_registered_sub_head position-relative">ניהול התראות</h3>
                  <div>
                  <h4>איך תרצו לקבל את המידע?</h4>
                    <div class="d-flex align-items-center">
                       <?php
                       $methods = [
                           'phone' => 'צינתוק לטלפון',
                           'email' => 'אימייל',
                        //    'whatsapp' => 'וואטסאפ'
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
                  <h3 class="personal_registered_sub_head position-relative">הדירות שסימנתי</h3>
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
                                              <h4><?php echo safe_price($price); ?> ₪</h4>
                                          </div>
                                          <div class="apartment_details">
                                              <span><?php echo esc_html($city); ?></span>
                                              <span><?php echo esc_html($listing_type); ?></span>
                                              <span><?php echo esc_html($rooms); ?> חדרים</span>
                                              <span>קומה <?php echo esc_html($floor); ?></span>
                                              <span><?php echo esc_html($size); ?> מ”ר</span>
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
                <div class="apartment_search_wrap published_apartments">
                    <div class="d-flex align-items-center justify-content-between flex-wrap">
                        <h3 class="personal_registered_sub_head position-relative">הדירות שפירסמתי</h3>
                    </div>
                    <div class="row apartment_search_slider custom_row mt_30">
                        <div class="col-lg-3 col-md-4 mt_15 custom_col">
                            <div class="card_item">
                                <figure class="m-0">
                                    <img class="card_img" src="<?php echo get_template_directory_uri(); ?>/assets/img/apartment_img01.svg" alt="דירה 1">
                                </figure>
                                <div class="card_content">
                                    <div class="custom_card_space">
                                        <a class="category_text" href="#">תל אביב</a>
                                        <a class="card_icon" href="#">
                                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon12.svg" alt="icon12">
                                        </a>
                                    </div>
                                    <div>
                                        <h2><a href="#">דירה 1</a></h2>
                                        <p>מכירה  |  3 חדרים  |  קומה 2  |  120 מ"ר</p>
                                        <h3>1,200,000 ₪</h3>
                                        <div class="apartment_posted_actions">
                                            <button class="edit-area-btn">
                                                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/saved_areas_edit_icon.png" alt="עריכה"> עריכה
                                            </button>
                                            <button class="delete-area-btn">
                                                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/saved_areas_delete_icon.png" alt="מחיקה"> מחיקה
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 mt_15 custom_col">
                            <div class="card_item">
                                <figure class="m-0">
                                    <img class="card_img" src="<?php echo get_template_directory_uri(); ?>/assets/img/apartment_img01.svg" alt="דירה 2">
                                </figure>
                                <div class="card_content">
                                    <div class="custom_card_space">
                                        <a class="category_text" href="#">חיפה</a>
                                        <a class="card_icon" href="#">
                                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon12.svg" alt="icon12">
                                        </a>
                                    </div>
                                    <div>
                                        <h2><a href="#">דירה 2</a></h2>
                                        <p>מכירה  |  4 חדרים  |  קומה 3  |  150 מ"ר</p>
                                        <h3>1,500,000 ₪</h3>
                                        <div class="apartment_posted_actions">
                                            <button class="edit-area-btn">
                                                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/saved_areas_edit_icon.png" alt="עריכה"> עריכה
                                            </button>
                                            <button class="delete-area-btn">
                                                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/saved_areas_delete_icon.png" alt="מחיקה"> מחיקה
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 mt_15 custom_col">
                            <div class="card_item">
                                <figure class="m-0">
                                    <img class="card_img" src="<?php echo get_template_directory_uri(); ?>/assets/img/apartment_img01.svg" alt="דירה 3">
                                </figure>
                                <div class="card_content">
                                    <div class="custom_card_space">
                                        <a class="category_text" href="#">ירושלים</a>
                                        <a class="card_icon" href="#">
                                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon12.svg" alt="icon12">
                                        </a>
                                    </div>
                                    <div>
                                        <h2><a href="#">דירה 3</a></h2>
                                        <p>מכירה  |  2 חדרים  |  קומה 1  |  90 מ"ר</p>
                                        <h3>900,000 ₪</h3>
                                        <div class="apartment_posted_actions">
                                            <button class="edit-area-btn">
                                                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/saved_areas_edit_icon.png" alt="עריכה"> עריכה
                                            </button>
                                            <button class="delete-area-btn">
                                                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/saved_areas_delete_icon.png" alt="מחיקה"> מחיקה
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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


<?php endif; ?>
 <!-- Personal Area Registered area end -->
 <!-- חלק 1/2 — הגדרות גלובליות + ערים ואזורים + התראות + עדכון פרטים + שמירת דירות + קואורדינטות: -->
<!-- <script>
    // const dirataImgBase = '<?php echo get_template_directory_uri(); ?>/assets/img/';
    window.dirataImgBase = window.dirataImgBase || '<?php echo get_template_directory_uri(); ?>/assets/img/';


    document.addEventListener('DOMContentLoaded', function() {
    // --- ערים ואזורים ---
    const citySelect = document.getElementById('city-select');
    if (citySelect) {
        citySelect.addEventListener('change', function () {
            var cityId = this.value;
            var areaSelect = document.getElementById('area-select');
            areaSelect.innerHTML = '<option value="">בחר אזור</option>';
            if (cityId) {
                fetch('<?php echo admin_url('admin-ajax.php'); ?>?action=get_areas_by_city&city=' + cityId)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.data.length > 0) {
                            data.data.forEach(function (area) {
                                var opt = document.createElement('option');
                                opt.value = area.id;
                                opt.innerText = area.name;
                                areaSelect.appendChild(opt);
                            });
                        }
                    });
            }
        });
    }

    const updateBtn = document.getElementById('update-notifications');
    if (updateBtn) {
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
    }

    const addAreaBtn = document.getElementById('add-area-btn');
    if (addAreaBtn) {
        addAreaBtn.addEventListener('click', function (event) {
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
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
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
    }

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
                const savedAreasContainer = document.querySelector('.saved_areas');
    if (savedAreasContainer) {
        savedAreasContainer.innerHTML = data.data.html;
    attachDeleteEvents();
                }
            }
        });
    }

    function attachDeleteEvents() {
        document.querySelectorAll('.delete-area-btn').forEach(button => {
            button.addEventListener('click', function (event) {
                event.preventDefault();
                var areaId = this.getAttribute('data-area-id');
                if (confirm("האם אתה בטוח שברצונך להסיר את האזור הזה?")) {
                    fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
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

    attachDeleteEvents();

    const openEditAreasLink = document.querySelector('.new_search_area');
    if (openEditAreasLink) {
        openEditAreasLink.addEventListener('click', function (event) {
            event.preventDefault();
            const editAreasTab = document.getElementById('pills-home-tab4');
            if (editAreasTab) {
                editAreasTab.click();
            }
        });
    }

    const updateDetailsBtn = document.querySelector('.custom_btn.mt_30');
    if (updateDetailsBtn) {
        updateDetailsBtn.addEventListener('click', function (e) {
            e.preventDefault();

            const fullName = document.getElementById('user_fullname').value.trim();
            const email = document.getElementById('user_email').value.trim();
            const phone = document.getElementById('user_phone').value.trim();

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
    }

    document.querySelectorAll('.save_apartment_btn').forEach(function(btn) {
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

    // --- קואורדינטות עם Mapbox ---
    async function getCoordinatesFromMapbox() {
    const city = document.getElementById("a")?.value || '';
    const area = document.getElementById("b")?.value || '';
    const street = document.getElementById("c")?.value || '';
    const houseNum = document.getElementById("d")?.value || '';

    const fullAddress = `${street} ${houseNum}, ${city}, ${area}`;
    const url = `https://api.mapbox.com/geocoding/v5/mapbox.places/${encodeURIComponent(fullAddress)}.json?access_token=pk.eyJ1Ijoic2hhbDN2IiwiYSI6ImNtNW10OXluNzA0Y2wybHM3Zm0xaGo3bncifQ.jZ4d6Zqe-z-afJt6g7beCQ&language=he&limit=1`;

    console.log("📍 מנסה למצוא כתובת:", fullAddress);

    try {
            const response = await fetch(url);
    const data = await response.json();

            if (data.features && data.features.length > 0) {
                const [lon, lat] = data.features[0].center;
    document.getElementById("latitude").value = lat;
    document.getElementById("longitude").value = lon;
    console.log("🎯 קואורדינטות נמצאו:", lat, lon);
            } else {
        console.warn("⚠️ לא נמצאה כתובת מתאימה ב-Mapbox");
            }
        } catch (err) {
        console.error("❌ שגיאה בקבלת קואורדינטות:", err);
        }
    }

    ['a', 'b', 'c', 'd'].forEach(id => {
        const el = document.getElementById(id);
    if (el) {
        el.addEventListener('blur', getCoordinatesFromMapbox);
        }
    });

});
</script> -->
<script>
    // const dirataImgBase = '<?php echo get_template_directory_uri(); ?>/assets/img/';
    window.dirataImgBase = window.dirataImgBase || '<?php echo get_template_directory_uri(); ?>/assets/img/';

    document.addEventListener('DOMContentLoaded', function () {
        // --- ערים ואזורים ---
        const citySelect = document.getElementById('city-select');
        if (citySelect) {
            citySelect.addEventListener('change', function () {
                var cityId = this.value;
                var areaSelect = document.getElementById('area-select');
                areaSelect.innerHTML = '<option value="">בחר אזור</option>';
                if (cityId) {
                    fetch('<?php echo admin_url('admin-ajax.php'); ?>?action=get_areas_by_city&city=' + cityId)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success && data.data.length > 0) {
                                data.data.forEach(function (area) {
                                    var opt = document.createElement('option');
                                    opt.value = area.id;
                                    opt.innerText = area.name;
                                    areaSelect.appendChild(opt);
                                });
                            }
                        });
                }
            });
        }

        const updateBtn = document.getElementById('update-notifications');
        if (updateBtn) {
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
        }

        const addAreaBtn = document.getElementById('add-area-btn');
        if (addAreaBtn) {
            addAreaBtn.addEventListener('click', function (event) {
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
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
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
        }

        function refreshSavedAreas() {
            var userId = '<?php echo get_current_user_id(); ?>';
            fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({
                    'action': 'get_user_saved_areas',
                    'user_id': userId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data.html) {
                    const savedAreasContainer = document.querySelector('.saved_areas');
                    if (savedAreasContainer) {
                        savedAreasContainer.innerHTML = data.data.html;
                        attachDeleteEvents();
                    }
                }
            });
        }

        function attachDeleteEvents() {
            document.querySelectorAll('.delete-area-btn').forEach(button => {
                button.addEventListener('click', function (event) {
                    event.preventDefault();
                    var areaId = this.getAttribute('data-area-id');
                    if (confirm("האם אתה בטוח שברצונך להסיר את האזור הזה?")) {
                        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
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

        attachDeleteEvents();

        const openEditAreasLink = document.querySelector('.new_search_area');
        if (openEditAreasLink) {
            openEditAreasLink.addEventListener('click', function (event) {
                event.preventDefault();
                const editAreasTab = document.getElementById('pills-home-tab4');
                if (editAreasTab) {
                    editAreasTab.click();
                }
            });
        }

       

        const updateDetailsBtn = document.querySelector('.custom_btn.mt_30');
        if (updateDetailsBtn) {
            updateDetailsBtn.addEventListener('click', function (e) {
                e.preventDefault();

                const fullName = document.getElementById('user_fullname').value.trim();
                const email = document.getElementById('user_email').value.trim();
                const phone = document.getElementById('user_phone').value.trim();

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
        }
// שמתי בהערה
        // document.querySelectorAll('.save_apartment_btn').forEach(function (btn) {
        //     btn.addEventListener('click', function (e) {
        //         e.preventDefault();
        //         const postId = this.dataset.postId;

        //         fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
        //             method: 'POST',
        //             credentials: 'same-origin',
        //             headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        //             body: new URLSearchParams({
        //                 action: 'save_apartment_to_user',
        //                 post_id: postId,
        //             })
        //         })
        //         .then(response => response.json())
        //         .then(data => {
        //             if (data.success) {
        //                 alert('הדירה נוספה לרשימה שלך!');
        //             } else {
        //                 alert(data.data || 'שגיאה לא ידועה');
        //             }
        //         });
        //     });
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
        // // --- קואורדינטות עם Mapbox ---
        // async function getCoordinatesFromMapbox() {
        //     // const citySelect = document.getElementById("a");
        //     // const city = citySelect.options[citySelect.selectedIndex].text;
        //     // const areaSelect = document.getElementById("b");
        //     // const area = areaSelect.options[areaSelect.selectedIndex].text;
        //     // const street = document.getElementById("c")?.value || '';
        //     // const houseNum = document.getElementById("d")?.value || '';

        //     // const fullAddress = `${street} ${houseNum}, ${city}, ${area}`;

        //     const street = document.getElementById("c")?.value || '';
        //     const houseNum = document.getElementById("d")?.value || '';
        //     const citySelect = document.getElementById("a");
        //     const city = citySelect.options[citySelect.selectedIndex]?.text || '';
        //     const areaSelect = document.getElementById("b");
        //     const area = areaSelect.options[areaSelect.selectedIndex]?.text || '';

        //     const fullAddress = `${street} ${houseNum}, שכונת ${area}, עיר ${city}, ישראל`;

        //     const url = `https://api.mapbox.com/geocoding/v5/mapbox.places/${encodeURIComponent(fullAddress)}.json?access_token=pk.eyJ1Ijoic2hhbDN2IiwiYSI6ImNtNW10OXluNzA0Y2wybHM3Zm0xaGo3bncifQ.jZ4d6Zqe-z-afJt6g7beCQ&language=he&limit=1&autocomplete=false&types=address`;

        //     console.log("📍 מנסה למצוא כתובת:", fullAddress);

        //     try {
        //         const response = await fetch(url);
        //         const data = await response.json();

        //         if (data.features && data.features.length > 0) {
        //             const [lon, lat] = data.features[0].center;
        //             document.getElementById("latitude").value = lat;
        //             document.getElementById("longitude").value = lon;
        //             console.log("🎯 קואורדינטות נמצאו:", lat, lon);
        //         } else {
        //             console.warn("⚠️ לא נמצאה כתובת מתאימה ב-Mapbox");
        //         }
        //     } catch (err) {
        //         console.error("❌ שגיאה בקבלת קואורדינטות:", err);
        //     }
        // }

        // ['a', 'b', 'c', 'd'].forEach(id => {
        //     const el = document.getElementById(id);
        //     if (el) {
        //         el.addEventListener('blur', getCoordinatesFromMapbox);
        //     }
        // });

    }); // סגירת DOMContentLoaded
</script>


<!-- חלק 2/2 — ניהול תמונות, שליטה בטאבים, טעינת קבצי JS חיצוניים: -->
<script src="<?php echo get_template_directory_uri(); ?>/assets/js/jquery-3.4.1.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/assets/js/jquery-ui.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/assets/js/plugins.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/assets/js/main.js"></script>
<!-- הקוד של פתיחת הפופאפ לשדרוג מנוי של מתווך -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  const openBtn = document.getElementById('upgradeContactBtn');

  if (openBtn) {
    openBtn.addEventListener('click', function () {
      const popup = document.getElementById('upgradeContactPopup');
      if (popup) {
        popup.classList.add('open');

        // מאזין לסגירה – כל פעם מחדש כדי לא להיתקע
        const closeBtn = popup.querySelector('.close_icon');
        if (closeBtn && !closeBtn.dataset.bound) {
          closeBtn.addEventListener('click', function () {
            popup.classList.remove('open');
          });
          closeBtn.dataset.bound = "true"; // למנוע ריבוי האזנות
        }

        popup.addEventListener('click', function (e) {
          if (e.target === popup) {
            popup.classList.remove('open');
          }
        });
      } else {
        console.log('❌ לא נמצא הפופאפ בלחיצה');
      }
    });
  } else {
    console.log('❌ לא נמצא כפתור פתיחה');
  }
});
// הקוד של שליחת הטופס של יצירת קשר לשדרוג מנוי מתווך
document.addEventListener('DOMContentLoaded', function () {
  const sendBtn = document.getElementById('send_upgrade_request');
  const msgBox = document.getElementById('upgrade_form_msg');

  sendBtn?.addEventListener('click', function () {
    const name = document.getElementById('full_name')?.value;
    const phone = document.getElementById('phone')?.value;
    const message = document.getElementById('message')?.value;

    const data = new FormData();
    data.append('action', 'send_upgrade_contact_form');
    data.append('name', name);
    data.append('phone', phone);
    data.append('message', message);

    fetch('/wp-admin/admin-ajax.php', {
      method: 'POST',
      body: data
    })
    .then(res => res.text())
    .then(res => {
      msgBox.innerHTML = '<div class="success_msg">הפנייה נשלחה בהצלחה 🎉</div>';
      msgBox.style.color = 'green';

      // איפוס הטופס אם תרצי
      document.getElementById('message').value = '';
    })
    .catch(err => {
      console.error(err);
      msgBox.innerHTML = '<div class="error_msg">אירעה שגיאה בשליחה ❌</div>';
      msgBox.style.color = 'red';
    });
  });
});





</script>


<script>
    // jQuery(document).ready(function() {
    //     // --- העלאת תמונות ---
    //     document.getElementById('pro-image')?.addEventListener('change', readImage, false);

    //     jQuery(".preview-images-zone").sortable();

    //     jQuery(document).on('click', '.image-cancel', function() {
    //         let no = jQuery(this).data('no');
    //         jQuery(".preview-image.preview-show-" + no).remove();
    //     });
    // });

    // var num = 0;
    // function readImage() {
    //     if (window.File && window.FileList && window.FileReader) {
    //         var files = event.target.files;
    //         var output = jQuery(".preview-images-zone");

    //         for (let i = 0; i < files.length; i++) {
    //             var file = files[i];

    //             let existingNumbers = [];
    //             output.find('.preview-image').each(function() {
    //                 let currentNum = jQuery(this).attr('class').match(/preview-show-(\d+)/);
    //                 if (currentNum && currentNum[1]) {
    //                     existingNumbers.push(parseInt(currentNum[1], 10));
    //                 }
    //             });
    //             num = existingNumbers.length > 0 ? Math.max(...existingNumbers) + 1 : 1;

    //             var checkdiv = output.find('div.preview-image').length; 
    //             if (checkdiv < 10) {
    //                 if (!file.type.match('image.*')) {
    //                     console.warn("Skipping non-image file:", file.name);
    //                     continue;
    //                 }

    //                 var picReader = new FileReader();
    //                 picReader.addEventListener('load', function (event) {
    //                     var picFile = event.target;
    //                     if (typeof dirataImgBase === 'undefined') {
    //                         console.error("JavaScript variable 'dirataImgBase' is not defined.");
    //                         return;
    //                     }
    //                     var html =  '<div class="preview-image preview-show-' + num + '">' +
    //                     '<div class="image-cancel" data-no="' + num + '"><img src="' + dirataImgBase + 'delete_icon.svg" alt="delete_icon"></div>' +
    //                     '<div class="image-zone"><img id="pro-img-' + num + '" src="' + picFile.result + '"></div>' +
    //                     '<div class="tools-edit-image"><a href="javascript:void(0)" data-no="' + num + '" class="btn-edit-image">להחלפה<img src="' + dirataImgBase + 'replacable_icon.svg" alt="replacable_icon"></a></div>' +
    //                     '</div>';

    //                     output.append(html);
    //                 });
    //                 picReader.readAsDataURL(file);
    //             } else {
    //                 alert("ניתן להעלות עד 10 תמונות.");
    //                 break;
    //             }
    //         }
    //         const proImageInput = document.getElementById('pro-image');
    //         if (proImageInput) {
    //             proImageInput.value = '';
    //         }
    //     } else {
    //         console.log('Browser not support');
    //     }
    // }

    // --- שליטה בטאבים דרך קישורים ---
    document.addEventListener('DOMContentLoaded', function() {
        // קישורים לטאבים אישיים
        const linksToMyAcount = document.querySelectorAll('.to-my-acount');
        const linksToMyLeads = document.querySelectorAll('.to-my-leads');
        const linksToMyApartments = document.querySelectorAll('.to-my-apartments');
        const brokerSawTheLead = document.querySelectorAll('.saw_lead');

        linksToMyAcount.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const myAccountTabButton = document.querySelector('#pills-my-acount-tab');
                const myAccountTabContent = document.querySelector('#pills-my-acount');

                if (myAccountTabButton && myAccountTabContent) {
                    document.querySelectorAll('[data-toggle="pill"]').forEach(btn => btn.classList.remove('active'));
                    document.querySelectorAll('.tab-pane').forEach(tab => tab.classList.remove('active', 'show'));
                    myAccountTabButton.classList.add('active');
                    myAccountTabContent.classList.add('active', 'show');
                }
            });
        });

        linksToMyLeads.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const myAccountTabButton = document.querySelector('#pills-leads-tab');
                const myAccountTabContent = document.querySelector('#pills-leads');

                if (myAccountTabButton && myAccountTabContent) {
                    document.querySelectorAll('[data-toggle="pill"]').forEach(btn => btn.classList.remove('active'));
                    document.querySelectorAll('.tab-pane').forEach(tab => tab.classList.remove('active', 'show'));
                    myAccountTabButton.classList.add('active');
                    myAccountTabContent.classList.add('active', 'show');
                }
            });
        });

        linksToMyApartments.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const myAccountTabButton = document.querySelector('#pills-apartments-tab');
                const myAccountTabContent = document.querySelector('#pills-apartments');

                if (myAccountTabButton && myAccountTabContent) {
                    document.querySelectorAll('[data-toggle="pill"]').forEach(btn => btn.classList.remove('active'));
                    document.querySelectorAll('.tab-pane').forEach(tab => tab.classList.remove('active', 'show'));
                    myAccountTabButton.classList.add('active');
                    myAccountTabContent.classList.add('active', 'show');
                }
            });
        });

        jQuery(document).ready(function($) {
            $('.saw_lead').on('click', function(e) {
                e.preventDefault();

                $.post('<?php echo admin_url('admin-ajax.php'); ?>', {
                action: 'mark_user_saw_lead'
                }, function(response) {
                if (response.success) {
                    console.log('✅ המשתמש סומן כמי שצפה בהודעה');
                    $('.view_popup').hide(); // או this.remove() או כל פעולה אחרת
                } else {
                    console.warn('⚠️ שגיאה:', response.message);
                }
                });
            });
        });

        // כפתור לכניסה לטאב 'החשבון שלי'
        const myAccountListItem = document.getElementById('pills-my-acount-tab')?.closest('li'); 
        if (myAccountListItem) {
            myAccountListItem.addEventListener('click', function(event) {
                const anchorTag = this.querySelector('a[data-toggle="pill"]');
                if (anchorTag && typeof(jQuery) !== 'undefined') {
                    jQuery(anchorTag).tab('show');
                } else if (anchorTag && typeof bootstrap !== 'undefined') {
                    const tab = new bootstrap.Tab(anchorTag);
                    tab.show();
                }
            });
        }
    });
</script>
<!-- סקריפט שקשור לעימוד הלידים באזור האישי של מתווך -->
<!-- <script>
document.addEventListener('DOMContentLoaded', function() {
    let currentPage = 1;
    const accordion = document.getElementById('accordionExample');
    const prevBtn = document.getElementById('prev-page');
    const nextBtn = document.getElementById('next-page');
    const loadingMessage = document.getElementById('loading-message');
    const leadsCount = document.getElementById('leads-count');

    function loadLeads(page) {
        const formData = new FormData();
        formData.append('action', 'load_leads');
        formData.append('page', page);

        // מציגים "טוען..."
        loadingMessage.style.display = 'block';

        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json()) // במקום .text()
        .then(data => {
            accordion.innerHTML = data.html;

            // חישוב מספרי הלידים המוצגים
            const start = (currentPage - 1) * data.per_page + 1;
            const end = Math.min(currentPage * data.per_page, data.total);
            leadsCount.textContent = `${start} עד ${end} מתוך ${data.total} לידים`;

            // ניהול הכפתורים
            if (currentPage === 1) {
                prevBtn.disabled = true;
            } else {
                prevBtn.disabled = false;
            }

            if (end >= data.total) {
                nextBtn.disabled = true;
            } else {
                nextBtn.disabled = false;
            }
        })
        .finally(() => {
            // מסתירים את "טוען..."
            loadingMessage.style.display = 'none';
        });
    }

    nextBtn.addEventListener('click', function() {
        currentPage++;
        loadLeads(currentPage);
    });

    prevBtn.addEventListener('click', function() {
        if (currentPage > 1) {
            currentPage--;
            loadLeads(currentPage);
        }
    });

    // טעינה ראשונית
    loadLeads(currentPage);
});
</script> -->
<script>
    window.totalLeads = <?php echo count($leads); ?>;
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let currentPage = 1;
        const accordion = document.getElementById('accordionExample');
        const prevBtn = document.getElementById('prev-page');
        const nextBtn = document.getElementById('next-page');
        const loadingMessage = document.getElementById('loading-message');
        const leadsCount = document.getElementById('leads-count');
        const select = document.getElementById("filter-by-apartment");
        const noLeadsMsg = document.getElementById("no-leads-message");
        const noOverallLeadsMsg = document.getElementById("no-overall-leads-message");
        if (typeof window.totalLeads !== "undefined" && window.totalLeads === 0) {
            // הסתר הכל
            accordion.style.display = 'none';
            leadsCount.style.display = 'none';
            prevBtn.style.display = 'none';
            nextBtn.style.display = 'none';
            // document.querySelector("nav")?.style.display = 'none';
            noLeadsMsg.style.display = 'none';
            
            if (noOverallLeadsMsg) noOverallLeadsMsg.style.display = 'block';
        }

        // הפעלת select2 פעם אחת
        if (typeof jQuery !== "undefined" && jQuery().select2) {
            jQuery('.select2').select2();
        }

        function filterLeads() {
            const selectedId = select?.value || "";
            const cards = document.querySelectorAll('.card[data-apartment-id]');
            let shown = 0;

            cards.forEach(card => {
                const match = !selectedId || card.dataset.apartmentId === selectedId;
                card.style.display = match ? "block" : "none";
                if (match) shown++;
            });

            if (noLeadsMsg) {
                noLeadsMsg.style.display = shown === 0 ? "block" : "none";
            }

            if (leadsCount) {
                leadsCount.textContent = shown ? `1 עד ${shown} מתוך ${shown} לידים` : `0 לידים`;
            }
        }

        function loadLeads(page) {
            const formData = new FormData();
            formData.append('action', 'load_leads');
            formData.append('page', page);

            loadingMessage.style.display = 'block';

            fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                accordion.innerHTML = data.html;

                // סינון על התוצאה החדשה
                filterLeads();

                // ניהול כפתורים
                const start = (currentPage - 1) * data.per_page + 1;
                const end = Math.min(currentPage * data.per_page, data.total);

                if (!select?.value) {
                    leadsCount.textContent = `${start} עד ${end} מתוך ${data.total} לידים`;
                }

                prevBtn.disabled = currentPage === 1;
                nextBtn.disabled = end >= data.total;
            })
            .finally(() => {
                loadingMessage.style.display = 'none';
            });
        }

        // ניווט בין עמודים
        nextBtn.addEventListener('click', function() {
            currentPage++;
            loadLeads(currentPage);
        });

        prevBtn.addEventListener('click', function() {
            if (currentPage > 1) {
                currentPage--;
                loadLeads(currentPage);
            }
        });

        // שינוי סינון לפי דירה
        select?.addEventListener('change', filterLeads);

        // טעינה ראשונית
        loadLeads(currentPage);
    });

    jQuery(document).ready(function ($) {
    const urlParams = new URLSearchParams(window.location.search);
    const tab = urlParams.get('tab');

    if (tab === 'publish') {
        const targetTab = $('a[data-toggle="pill"][data-target="#pills-publish"]');
        if (targetTab.length) {
        targetTab.tab('show');
        }
    }
    });

</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('ImageMedias');
    const previewImg = document.querySelector('#divImageMediaPreview img');

    input.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (e) {
            previewImg.src = e.target.result;
        }
        reader.readAsDataURL(file);
    });
});

// document.addEventListener('scroll', function () {
//   const el = document.querySelector('#pills-tab');
//   if (!el) return;

//   if (window.scrollY > 150) {
//     el.style.position = 'sticky';
//     el.style.top = '150px';
//     el.style.zIndex = '999';
//     // el.style.background = 'white';
//   } else {
//     el.style.position = '';
//     el.style.top = '';
//     el.style.zIndex = '';
//     // el.style.background = '';
//   }
// });

</script>

<script>
   function saveUpdate(button) {    
    const parentForm = button.closest('form');
    const umsgBox = parentForm.querySelector('.update-msg-box');
    const leadId = parentForm?.dataset.leadId || parentForm?.getAttribute('data-lead-id');
    console.log("Button with Lead ID:", leadId);

    //get update fields
    const update_date = parentForm.querySelector('#update_date')?.value.trim();
    const update_title = parentForm.querySelector('#update_title')?.value.trim();
    const update_content = parentForm.querySelector('#update_content')?.value.trim();

    console.log("got form values: " + update_date + " , " + update_title);

    const update_file_input = parentForm.querySelector('#update_file');
    const update_file = update_file_input?.files[0];

    const formData = new FormData();
    formData.append('action', 'save_update_to_lead');
    formData.append('lead_id', leadId);
    formData.append('update_date', update_date);
    formData.append('update_title', update_title);
    formData.append('update_content', update_content);
    formData.append('update_file', update_file);

    fetch('/wp-admin/admin-ajax.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(res => {
        console.log("add lead got res: " + res);
        if(res.success){
            umsgBox.innerHTML = `<div class="success_msg">העדכון נשמר בהצלחה!</div>`;
            umsgBox.style.color = 'green';
            parentForm.reset(); 
        } else {
            umsgBox.innerHTML = `<div class="error_msg">התרחשה שגיאה בשמירת העדכון</div>`;
            umsgBox.style.color = 'red';
        }
    })
    .catch(err => {
        console.error(err);
        umsgBox.innerHTML = `<div class="error_msg">התרחשה שגיאה בשמירת העדכון</div>`;
        umsgBox.style.color = 'red';
    });
}

jQuery(document).ready(function($) {
    $('body').on('click' , '.add_update' , function(e) {
        e.preventDefault();
        console.log("in click function");
        const $formSection = $(this).next('.add-update-section');
        $formSection.slideToggle();       
        const isVisible = $formSection.is(':visible');      
    });
});

</script>


<?php
get_footer();
?>