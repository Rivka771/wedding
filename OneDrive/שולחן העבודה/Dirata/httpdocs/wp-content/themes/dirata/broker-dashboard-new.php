<?php
/* Template Name: Broker Dashboard New */
get_header();
if ( ! is_user_logged_in() ) {
    wp_redirect( wp_login_url( get_permalink() ) );
    exit;
}

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
$num_of_uploaded_apartments = get_field('num_of_posts', 'user_' . $user_id);;
$num_of_max_apartments = get_field('max_posts', 'user_' . $user_id);;
if ($user_role == 'broker') {
    $office_name = get_field('office_name', 'user_' . $user_id);
}

?>

<!-- כאן יהיה כל הHTML החדש -->
<!-- update details advertisement area start -->
<div class="update_details_advertisement_wrap private_apartment_form_wrapper">
    <div class="close_icon"><i class="fal fa-times"></i></div>
    <div class="form_wrap text-right">
        <h2 class="private_apartment_form_head">עדכון פרטים לפרסום דירה למכירה:</h2>
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
                    <label for="d">מס' בית<span>*</span></label>
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
                    <label for="f">מס' חדרים<span>*</span></label>
                    <input id="f" type="text" placeholder="4">
                </div>
            </div>
            <div class="col-md-4 col-sm-6 col-4 mt_20 custom_col02">
                <div class="input_box">
                    <label for="g">גודל דירה (מ"ר)</label>
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
                            <input id="i" type="text" placeholder="יא תמוז תשפ"ה">
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
                                <div class="image-cancel" data-no="1"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/delete_icon.svg" alt="delete_icon"></div>
                                <div class="image-zone"><img id="pro-img-1" src="<?php echo get_template_directory_uri(); ?>/assets/img/upload_img01.svg" alt="upload_img01"></div>
                                <div class="tools-edit-image"><a href="javascript:void(0)" data-no="1" class="btn-edit-image"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/replacable_icon.svg" alt="replacable_icon">להחלפה</a></div>
                            </div>
                            <div class="preview-image preview-show-2">
                                <div class="image-cancel" data-no="2"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/delete_icon.svg" alt="delete_icon"></div>
                                <div class="image-zone"><img id="pro-img-2" src="<?php echo get_template_directory_uri(); ?>/assets/img/upload_img02.svg" alt="upload_img02"></div>
                            </div>
                            <div class="preview-image preview-show-3">
                                <div class="image-cancel" data-no="3"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/delete_icon.svg" alt="delete_icon"></div>
                                <div class="image-zone"><img id="pro-img-3" src="<?php echo get_template_directory_uri(); ?>/assets/img/upload_img02.svg" alt="upload_img03"></div>
                            </div>
                            <div class="preview-image preview-show-4">
                                <div class="image-cancel" data-no="4"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/delete_icon.svg" alt="delete_icon"></div>
                                <div class="image-zone"><img id="pro-img-4" src="<?php echo get_template_directory_uri(); ?>/assets/img/upload_img02.svg" alt="upload_img03"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <a href="javascript:void(0)" onclick="$('#pro-image').click()"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/upload_icon.svg" alt="upload_icon"><span>תמונות נוספות</span></a>
                            <input type="file" id="pro-image" name="pro-image" multiple>
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
                <input checked type="checkbox" id="check01">
                <label for="check01">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/apartment_furniture_icon01.svg" alt="apartment_furniture_icon01">
                    <span>ריהוט</span>
                </label>
            </div>
            <div class="apartment_furniture_item">
                <input type="checkbox" id="check02">
                <label for="check02">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/apartment_furniture_icon02.svg" alt="apartment_furniture_icon02">
                    <span>סוכה</span>
                </label>
            </div>
            <div class="apartment_furniture_item">
                <input type="checkbox" id="check03">
                <label for="check03">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/apartment_furniture_icon03.svg" alt="apartment_furniture_icon01">
                    <span>ריהוט</span>
                </label>
            </div>
            <div class="apartment_furniture_item">
                <input checked type="checkbox" id="check04">
                <label for="check04">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/apartment_furniture_icon04.svg" alt="apartment_furniture_icon02">
                    <span>סוכה</span>
                </label>
            </div>
            <div class="apartment_furniture_item">
                <input type="checkbox" id="check05">
                <label for="check05">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/apartment_furniture_icon05.svg" alt="apartment_furniture_icon01">
                    <span>ריהוט</span>
                </label>
            </div>
            <div class="apartment_furniture_item">
                <input type="checkbox" id="check06">
                <label for="check06">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/apartment_furniture_icon06.svg" alt="apartment_furniture_icon02">
                    <span>סוכה</span>
                </label>
            </div>
            <div class="apartment_furniture_item">
                <input type="checkbox" id="check07">
                <label for="check07">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/apartment_furniture_icon07.svg" alt="apartment_furniture_icon01">
                    <span>ריהוט</span>
                </label>
            </div>
            <div class="apartment_furniture_item">
                <input type="checkbox" id="check08">
                <label for="check08">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/apartment_furniture_icon08.svg" alt="apartment_furniture_icon02">
                    <span>סוכה</span>
                </label>
            </div>
            <div class="apartment_furniture_item">
                <input checked type="checkbox" id="check09">
                <label for="check09">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/apartment_furniture_icon09.svg" alt="apartment_furniture_icon01">
                    <span>ריהוט</span>
                </label>
            </div>
            <div class="apartment_furniture_item">
                <input type="checkbox" id="check10">
                <label for="check10">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/apartment_furniture_icon10.svg" alt="apartment_furniture_icon02">
                    <span>סוכה</span>
                </label>
            </div>
            <div class="apartment_furniture_item">
                <input type="checkbox" id="check11">
                <label for="check11">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/apartment_furniture_icon11.svg" alt="apartment_furniture_icon01">
                    <span>ריהוט</span>
                </label>
            </div>
            <div class="apartment_furniture_item">
                <input type="checkbox" id="check12">
                <label for="check12">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/apartment_furniture_icon12.svg" alt="apartment_furniture_icon02">
                    <span>סוכה</span>
                </label>
            </div>
        </div>
        <a class="detail_payment_btn mt_20" href="Apartment_rental_upload_form_details_stage_2.html">פרטים ותשלום ←</a>
    </div>
</div>
<!-- update details advertisement area end -->

<!-- Broker personal area start -->
<div class="broker_personal_wrapper overflow-hidden">
    <div>
        <div class="row">
            <!-- התפריט בימין -->
            <div class="col-lg-2">
                <div class="broker_personal_nav">
                    <img class="broker_shape" src="<?php echo get_template_directory_uri(); ?>/assets/img/broker_shape.svg" alt="broker_shape">

                    <ul class="nav" id="pills-tab" role="tablist">
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
                                        <p class="view_popup"><i class="fal fa-times"></i>יש דירה חדשה שמתאים לחיפוש שלך, לצפיה <a href="#">לחץ כאן </a></p>
                                        <div class="row custom_row">
                                            <div class="col-xl-7 col-lg-6 custom_col">
                                                <div class="row custom_row">
                                                    <div class="col-lg-12 col-md-6 custom_col">
                                                        <div class="subscription_item default_item mt_20">
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <h3 class="head_text02"> <?php echo $first_name . ' ' . $last_name; ?></h3>
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
                                                                <li>
                                                                    <p>משה ויסרשטיין</p>
                                                                    <p>0548439972</p>
                                                                    <p>בני ברק</p>
                                                                    <p>קריית הרצוג</p><span>רבי עקיבא 8 בני ברק</span>
                                                                </li>
                                                                <li class="mt_8">
                                                                    <p>שלמה ברויאר</p>
                                                                    <p>0548439972</p>
                                                                    <p>מודיעין עילית</p>
                                                                    <p>שיכון ה'</p><span>רבי עקיבא 8 בני ברק</span>
                                                                </li>
                                                                <li class="mt_8">
                                                                    <p>משה ויסרשטיין</p>
                                                                    <p>0548439972</p>
                                                                    <p>בני ברק</p>
                                                                    <p>קריית הרצוג</p><span>רבי עקיבא 8 בני ברק</span>
                                                                </li>
                                                                <li class="mt_8">
                                                                    <p>שלמה ברויאר</p>
                                                                    <p>0548439972</p>
                                                                    <p>מודיעין עילית</p>
                                                                    <p>שיכון ה'</p><span>רבי עקיבא 8 בני ברק</span>
                                                                </li>

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
                                                        <div class="col-lg-12 custom_col col-md-6 col-sm-10">
                                                            <div class="card_item03 mt_10">
                                                                <div class="card_edit_view_icon">
                                                                    <a href="#"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon023.svg" alt="icon023"></a>
                                                                    <a href="#"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon024.svg" alt="icon024"></a>
                                                                </div>
                                                                <img class="img-fluid apartment_img" src="<?php echo get_template_directory_uri(); ?>/assets/img/apartment_img01.svg" alt="apartment_img01">
                                                                <div class="card_item03_content">
                                                                    <div class="d-flex align-items-center justify-content-between">
                                                                        <span class="sale_text">מכירה</span>
                                                                        <a class="location_icon" href="#"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon21.svg" alt="icon21"></a>
                                                                    </div>
                                                                    <p class="head_para mt_13"><a target="_blank" href="Single_article_page.html">רבי עקיבא 8, בני ברק</a></p>
                                                                    <p class="retail_para_resposive">מכירה | 5 חדרים | קומה 2 | 110 מ"ר</p>
                                                                    <p class="font400 mt_8">2,000,000 ₪</p>
                                                                    <div class="retail_responsive_none">
                                                                        <span><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon16.svg" alt="icon16"> ירושלים</span>
                                                                        <span><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon17.svg" alt="icon17">סנהדריה המורחבת</span>
                                                                        <span><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon18.svg" alt="icon18">5 חדרים</span>
                                                                        <span><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon19.svg" alt="icon19">קומה 6</span>
                                                                        <span><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon20.svg" alt="icon20">125 מ"ר</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12 custom_col col-md-6 col-sm-10">
                                                            <div class="card_item03 mt_10">
                                                                <div class="card_edit_view_icon">
                                                                    <a href="#"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon023.svg" alt="icon023"></a>
                                                                    <a href="#"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon024.svg" alt="icon024"></a>
                                                                </div>
                                                                <img class="img-fluid apartment_img" src="<?php echo get_template_directory_uri(); ?>/assets/img/apartment_img01.svg" alt="apartment_img01">
                                                                <div class="card_item03_content">
                                                                    <div class="d-flex align-items-center justify-content-between">
                                                                        <span class="sale_text">מכירה</span>
                                                                        <a class="location_icon" href="#"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon21.svg" alt="icon21"></a>
                                                                    </div>
                                                                    <p class="head_para mt_13"><a target="_blank" href="Single_article_page.html">רבי עקיבא 8, בני ברק</a></p>
                                                                    <p class="retail_para_resposive">מכירה | 5 חדרים | קומה 2 | 110 מ"ר</p>
                                                                    <p class="font400 mt_8">2,000,000 ₪</p>
                                                                    <div class="retail_responsive_none">
                                                                        <span><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon16.svg" alt="icon16"> ירושלים</span>
                                                                        <span><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon17.svg" alt="icon17">סנהדריה המורחבת</span>
                                                                        <span><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon18.svg" alt="icon18">5 חדרים</span>
                                                                        <span><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon19.svg" alt="icon19">קומה 6</span>
                                                                        <span><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon20.svg" alt="icon20">125 מ"ר</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12 custom_col col-md-6 col-sm-10">
                                                            <div class="card_item03 mt_10">
                                                                <div class="card_edit_view_icon">
                                                                    <a href="#"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon023.svg" alt="icon023"></a>
                                                                    <a href="#"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon024.svg" alt="icon024"></a>
                                                                </div>
                                                                <img class="img-fluid apartment_img" src="<?php echo get_template_directory_uri(); ?>/assets/img/apartment_img01.svg" alt="apartment_img01">
                                                                <div class="card_item03_content">
                                                                    <div class="d-flex align-items-center justify-content-between">
                                                                        <span class="sale_text">מכירה</span>
                                                                        <a class="location_icon" href="#"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon21.svg" alt="icon21"></a>
                                                                    </div>
                                                                    <p class="head_para mt_13"><a target="_blank" href="Single_article_page.html">רבי עקיבא 8, בני ברק</a></p>
                                                                    <p class="retail_para_resposive">מכירה | 5 חדרים | קומה 2 | 110 מ"ר</p>
                                                                    <p class="font400 mt_8">2,000,000 ₪</p>
                                                                    <div class="retail_responsive_none">
                                                                        <span><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon16.svg" alt="icon16"> ירושלים</span>
                                                                        <span><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon17.svg" alt="icon17">סנהדריה המורחבת</span>
                                                                        <span><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon18.svg" alt="icon18">5 חדרים</span>
                                                                        <span><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon19.svg" alt="icon19">קומה 6</span>
                                                                        <span><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon20.svg" alt="icon20">125 מ"ר</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12 custom_col col-md-6 col-sm-10">
                                                            <div class="card_item03 mt_10">
                                                                <div class="card_edit_view_icon">
                                                                    <a href="#"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon023.svg" alt="icon023"></a>
                                                                    <a href="#"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon024.svg" alt="icon024"></a>
                                                                </div>
                                                                <img class="img-fluid apartment_img" src="<?php echo get_template_directory_uri(); ?>/assets/img/apartment_img01.svg" alt="apartment_img01">
                                                                <div class="card_item03_content">
                                                                    <div class="d-flex align-items-center justify-content-between">
                                                                        <span class="sale_text">מכירה</span>
                                                                        <a class="location_icon" href="#"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon21.svg" alt="icon21"></a>
                                                                    </div>
                                                                    <p class="head_para mt_13"><a target="_blank" href="Single_article_page.html">רבי עקיבא 8, בני ברק</a></p>
                                                                    <p class="retail_para_resposive">מכירה | 5 חדרים | קומה 2 | 110 מ"ר</p>
                                                                    <p class="font400 mt_8">2,000,000 ₪</p>
                                                                    <div class="retail_responsive_none">
                                                                        <span><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon16.svg" alt="icon16"> ירושלים</span>
                                                                        <span><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon17.svg" alt="icon17">סנהדריה המורחבת</span>
                                                                        <span><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon18.svg" alt="icon18">5 חדרים</span>
                                                                        <span><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon19.svg" alt="icon19">קומה 6</span>
                                                                        <span><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon20.svg" alt="icon20">125 מ"ר</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
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
                            // $user_id = get_current_user_id();
                            // Get saved areas from the user
                            $saved_areas = get_field('saved_areas', 'user_' . $user_id);
                            // if ($saved_areas && is_array($saved_areas)) {
                            //     $saved_areas = array_map(function($area) {
                            //         return is_object($area) ? $area->term_id : intval($area);
                            //     }, $saved_areas);
                            // } else {
                            //     $saved_areas = array();
                            // }

                            if ($user_id) {
                                $args = array(
                                    'post_type'      => 'apartment',
                                    'posts_per_page' => -1,
                                    'post_status'    => 'publish',
                                    'author'         => $user_id, // ← זה הסינון לפי משתמש נוכחי
                                );
                                $query = new WP_Query($args);
                                if ( $query->have_posts() ) :
                            ?>
                                    <div class="row justify-content-start custom_row4 mt_20">
                                        <?php
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
                                            <div class="col-xl-4 col-md-6 col-sm-8 custom_col4">
                                                <div class="card_item03 mt_10">
                                                    <div class="card_edit_view_icon">
                                                        <a href="#"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon023.svg" alt="icon023"></a>
                                                        <a href="#"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon024.svg" alt="icon024"></a>
                                                    </div>
                                                    <img class="img-fluid apartment_img" src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($title); ?>">
                                                    <div class="card_item03_content w-100">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <span class="sale_text"><?php echo esc_html($city); ?></span>
                                                            <a class="location_icon" href="#"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon21.svg" alt="icon21"></a>
                                                        </div>
                                                        <p class="font400 mt_40"><a target="_blank" href="<?php the_permalink(); ?>"><?php echo esc_html($title); ?></a></p>
                                                        <span><?php echo esc_html($listing_type); ?> | <?php echo esc_html($rooms); ?> חדרים | קומה <?php echo esc_html($floor); ?> | <?php echo esc_html($size); ?> מ"ר</span>
                                                        <p class="font400 mt_8"><?php echo number_format_i18n($price); ?> ₪</p>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                        endwhile;
                                        wp_reset_postdata();
                                        ?>
                                    </div>
                                <?php
                                else :
                                    echo '<p>אין דירות שפרסמת
                                    ולכן לא מציג, התנאי בPHP לא מתקיים.
                                    </p>';
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
                                        <li>חפש לפי דירה: <a href="#">רבי עקיבא 8 בני ברק (203)</a></li>
                                    </ul>
                                </div>
                                <nav>
                                    <p>שם</p>
                                    <p>טלפון</p>
                                    <p>עיר</p>
                                    <p>שכונה</p>
                                    <p>דירה</p>
                                    <p>חתימת תיווך</p>
                                </nav>
                                <div class="accordion" id="accordionExample">
                                    <div class="card mt_15">
                                        <div class="card-header" id="headingOne">
                                            <h2 data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                                <div class="custom_justify d-flex align-items-center justify-content-between flex-wrap">
                                                    <p>משה ויסרשטיין</p>
                                                    <p>0548439972</p>
                                                    <p>בני ברק</p>
                                                    <p>קריית הרצוג</p>
                                                    <p class="active">רבי עקיבא 8 בני ברק</p>
                                                    <p class="card_width"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon025.svg" alt="icon025"></p>
                                                </div>
                                            </h2>
                                            <img class="arrow_icon" src="<?php echo get_template_directory_uri(); ?>/assets/img/icon026.svg" alt="icon026">
                                        </div>
                                        <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                                            <div class="card-body">
                                                <div class="default_item mt_10">
                                                    <div class="my_lead_card_sub">
                                                        <p class="font400">עדכון 1:</p>
                                                        <span class="mt_8">הקלטת שיחה 1</span>
                                                    </div>
                                                    <div class="my_lead_bottom_para">
                                                        <p>הלקוח התקשר למערכת הטלפונית וסוכם על פגישה בדירה |</p>
                                                    </div>
                                                </div>
                                                <div class="default_item mt_8">
                                                    <div class="my_lead_card_sub">
                                                        <p class="font400">עדכון 2:</p>
                                                        <span class="mt_8">הקלטת שיחה 1</span>
                                                    </div>
                                                    <div class="my_lead_bottom_para">
                                                        <p>נפגשו בדירה והתרשמו מאוד, צריך לתאם פגישה נוספת עם ההורים, לנסות ללחוץ על המוכר קצת</p>
                                                    </div>
                                                </div>
                                                <a class="mini_btn mt_13" href="#">הוסף עדכון נוסף ←</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card mt_13">
                                        <div class="card-header" id="headingTwo">
                                            <h2 data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                <div class="custom_justify d-flex align-items-center justify-content-between flex-wrap">
                                                    <p>משה ויסרשטיין</p>
                                                    <p>0548439972</p>
                                                    <p>בני ברק</p>
                                                    <p>קריית הרצוג</p>
                                                    <p class="active">רבי עקיבא 8 בני ברק</p>
                                                    <p class="card_width"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon025.svg" alt="icon025"></p>
                                                </div>
                                            </h2>
                                            <img class="arrow_icon" src="<?php echo get_template_directory_uri(); ?>/assets/img/icon026.svg" alt="icon026">
                                        </div>
                                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                            <div class="card-body">
                                                <div class="default_item mt_10">
                                                    <div class="my_lead_card_sub">
                                                        <p class="font400">עדכון 1:</p>
                                                        <span class="mt_8">הקלטת שיחה 1</span>
                                                    </div>
                                                    <div class="my_lead_bottom_para">
                                                        <p>הלקוח התקשר למערכת הטלפונית וסוכם על פגישה בדירה |</p>
                                                    </div>
                                                </div>
                                                <div class="default_item mt_8">
                                                    <div class="my_lead_card_sub">
                                                        <p class="font400">עדכון 2:</p>
                                                        <span class="mt_8">הקלטת שיחה 1</span>
                                                    </div>
                                                    <div class="my_lead_bottom_para">
                                                        <p>נפגשו בדירה והתרשמו מאוד, צריך לתאם פגישה נוספת עם ההורים, לנסות ללחוץ על המוכר קצת</p>
                                                    </div>
                                                </div>
                                                <a class="mini_btn mt_13" href="#">הוסף עדכון נוסף ←</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card mt_13">
                                        <div class="card-header" id="headingThree">
                                            <h2 data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                <div class="custom_justify d-flex align-items-center justify-content-between flex-wrap">
                                                    <p>משה ויסרשטיין</p>
                                                    <p>0548439972</p>
                                                    <p>בני ברק</p>
                                                    <p>קריית הרצוג</p>
                                                    <p class="active">רבי עקיבא 8 בני ברק</p>
                                                    <p class="card_width"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon025.svg" alt="icon025"></p>
                                                </div>
                                            </h2>
                                            <img class="arrow_icon" src="<?php echo get_template_directory_uri(); ?>/assets/img/icon026.svg" alt="icon026">
                                        </div>
                                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                                            <div class="card-body">
                                                <div class="default_item mt_10">
                                                    <div class="my_lead_card_sub">
                                                        <p class="font400">עדכון 1:</p>
                                                        <span class="mt_8">הקלטת שיחה 1</span>
                                                    </div>
                                                    <div class="my_lead_bottom_para">
                                                        <p>הלקוח התקשר למערכת הטלפונית וסוכם על פגישה בדירה |</p>
                                                    </div>
                                                </div>
                                                <div class="default_item mt_8">
                                                    <div class="my_lead_card_sub">
                                                        <p class="font400">עדכון 2:</p>
                                                        <span class="mt_8">הקלטת שיחה 1</span>
                                                    </div>
                                                    <div class="my_lead_bottom_para">
                                                        <p>נפגשו בדירה והתרשמו מאוד, צריך לתאם פגישה נוספת עם ההורים, לנסות ללחוץ על המוכר קצת</p>
                                                    </div>
                                                </div>
                                                <a class="mini_btn mt_13" href="#">הוסף עדכון נוסף ←</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card mt_13">
                                        <div class="card-header" id="headingFour">
                                            <h2 data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                                <div class="custom_justify d-flex align-items-center justify-content-between flex-wrap">
                                                    <p>משה ויסרשטיין</p>
                                                    <p>0548439972</p>
                                                    <p>בני ברק</p>
                                                    <p>קריית הרצוג</p>
                                                    <p class="active">רבי עקיבא 8 בני ברק</p>
                                                    <p class="card_width"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon025.svg" alt="icon025"></p>
                                                </div>
                                            </h2>
                                            <img class="arrow_icon" src="<?php echo get_template_directory_uri(); ?>/assets/img/icon026.svg" alt="icon026">
                                        </div>
                                        <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordionExample">
                                            <div class="card-body">
                                                <div class="default_item mt_10">
                                                    <div class="my_lead_card_sub">
                                                        <p class="font400">עדכון 1:</p>
                                                        <span class="mt_8">הקלטת שיחה 1</span>
                                                    </div>
                                                    <div class="my_lead_bottom_para">
                                                        <p>הלקוח התקשר למערכת הטלפונית וסוכם על פגישה בדירה |</p>
                                                    </div>
                                                </div>
                                                <div class="default_item mt_8">
                                                    <div class="my_lead_card_sub">
                                                        <p class="font400">עדכון 2:</p>
                                                        <span class="mt_8">הקלטת שיחה 1</span>
                                                    </div>
                                                    <div class="my_lead_bottom_para">
                                                        <p>נפגשו בדירה והתרשמו מאוד, צריך לתאם פגישה נוספת עם ההורים, לנסות ללחוץ על המוכר קצת</p>
                                                    </div>
                                                </div>
                                                <a class="mini_btn mt_13" href="#">הוסף עדכון נוסף ←</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card activeToggle mt_13">
                                        <div class="card-header" id="headingFive">
                                            <h2 data-toggle="collapse" data-target="#collapseFive" aria-expanded="true" aria-controls="collapseFive">
                                                <div class="custom_justify d-flex align-items-center justify-content-between flex-wrap">
                                                    <p>משה ויסרשטיין</p>
                                                    <p>0548439972</p>
                                                    <p>בני ברק</p>
                                                    <p>קריית הרצוג</p>
                                                    <p class="active">רבי עקיבא 8 בני ברק</p>
                                                    <p class="card_width"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon025.svg" alt="icon025"></p>
                                                </div>
                                            </h2>
                                            <img class="arrow_icon" src="<?php echo get_template_directory_uri(); ?>/assets/img/icon026.svg" alt="icon026">
                                        </div>
                                        <div id="collapseFive" class="collapse show" aria-labelledby="headingFive" data-parent="#accordionExample">
                                            <div class="card-body">
                                                <div class="default_item mt_10">
                                                    <div class="my_lead_card_sub">
                                                        <p class="font400">עדכון 1:</p>
                                                        <span class="mt_8">הקלטת שיחה 1</span>
                                                    </div>
                                                    <div class="my_lead_bottom_para">
                                                        <p>הלקוח התקשר למערכת הטלפונית וסוכם על פגישה בדירה |</p>
                                                    </div>
                                                </div>
                                                <div class="default_item mt_8">
                                                    <div class="my_lead_card_sub">
                                                        <p class="font400">עדכון 2:</p>
                                                        <span class="mt_8">הקלטת שיחה 1</span>
                                                    </div>
                                                    <div class="my_lead_bottom_para">
                                                        <p>נפגשו בדירה והתרשמו מאוד, צריך לתאם פגישה נוספת עם ההורים, לנסות ללחוץ על המוכר קצת</p>
                                                    </div>
                                                </div>
                                                <a class="mini_btn mt_13" href="#">הוסף עדכון נוסף ←</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card mt_13">
                                        <div class="card-header" id="headingSix">
                                            <h2 data-toggle="collapse" data-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                                <div class="custom_justify d-flex align-items-center justify-content-between flex-wrap">
                                                    <p>משה ויסרשטיין</p>
                                                    <p>0548439972</p>
                                                    <p>בני ברק</p>
                                                    <p>קריית הרצוג</p>
                                                    <p class="active">רבי עקיבא 8 בני ברק</p>
                                                    <p class="card_width"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon025.svg" alt="icon025"></p>
                                                </div>
                                            </h2>
                                            <img class="arrow_icon" src="<?php echo get_template_directory_uri(); ?>/assets/img/icon026.svg" alt="icon026">
                                        </div>
                                        <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordionExample">
                                            <div class="card-body">
                                                <div class="default_item mt_10">
                                                    <div class="my_lead_card_sub">
                                                        <p class="font400">עדכון 1:</p>
                                                        <span class="mt_8">הקלטת שיחה 1</span>
                                                    </div>
                                                    <div class="my_lead_bottom_para">
                                                        <p>הלקוח התקשר למערכת הטלפונית וסוכם על פגישה בדירה |</p>
                                                    </div>
                                                </div>
                                                <div class="default_item mt_8">
                                                    <div class="my_lead_card_sub">
                                                        <p class="font400">עדכון 2:</p>
                                                        <span class="mt_8">הקלטת שיחה 1</span>
                                                    </div>
                                                    <div class="my_lead_bottom_para">
                                                        <p>נפגשו בדירה והתרשמו מאוד, צריך לתאם פגישה נוספת עם ההורים, לנסות ללחוץ על המוכר קצת</p>
                                                    </div>
                                                </div>
                                                <a class="mini_btn mt_13" href="#">הוסף עדכון נוסף ←</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card mt_13">
                                        <div class="card-header" id="headingSeven">
                                            <h2 data-toggle="collapse" data-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                                                <div class="custom_justify d-flex align-items-center justify-content-between flex-wrap">
                                                    <p>משה ויסרשטיין</p>
                                                    <p>0548439972</p>
                                                    <p>בני ברק</p>
                                                    <p>קריית הרצוג</p>
                                                    <p class="active">רבי עקיבא 8 בני ברק</p>
                                                    <p class="card_width"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon025.svg" alt="icon025"></p>
                                                </div>
                                            </h2>
                                            <img class="arrow_icon" src="<?php echo get_template_directory_uri(); ?>/assets/img/icon026.svg" alt="icon026">
                                        </div>
                                        <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#accordionExample">
                                            <div class="card-body">
                                                <div class="default_item mt_10">
                                                    <div class="my_lead_card_sub">
                                                        <p class="font400">עדכון 1:</p>
                                                        <span class="mt_8">הקלטת שיחה 1</span>
                                                    </div>
                                                    <div class="my_lead_bottom_para">
                                                        <p>הלקוח התקשר למערכת הטלפונית וסוכם על פגישה בדירה |</p>
                                                    </div>
                                                </div>
                                                <div class="default_item mt_8">
                                                    <div class="my_lead_card_sub">
                                                        <p class="font400">עדכון 2:</p>
                                                        <span class="mt_8">הקלטת שיחה 1</span>
                                                    </div>
                                                    <div class="my_lead_bottom_para">
                                                        <p>נפגשו בדירה והתרשמו מאוד, צריך לתאם פגישה נוספת עם ההורים, לנסות ללחוץ על המוכר קצת</p>
                                                    </div>
                                                </div>
                                                <a class="mini_btn mt_13" href="#">הוסף עדכון נוסף ←</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- עד פה הלידים -->
                            </div>
                        </div>
                    </div>

                    <!-- Tab Pane: פרסום דירה -->
                    <div class="tab-pane fade col-lg-10" id="pills-publish" role="tabpanel" aria-labelledby="pills-publish-tab">
                        <!-- כאן הוספתי קלאס כדי שיעבוד לי העיצוב של הטופס כמו שעמוד פרסום דירה -->
                        <div class="broker_personal_apartment_advertised_wrap custom_padd private_apartment_form_wrapper form_wrap ">
                            <h2 class="custom_head head_text02">פרסום דירה</h2>
                            <?php // get_template_part('upload_apartment_for_sale'); // Temporarily commented out for debugging ?>
                            <!-- מכאן מתחיל באמת הטופס -->
                            <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                            <input type="hidden" name="action" value="create_apartment_post">
                            <input type="hidden" id="latitude" name="latitude" value="this_latitude">
                            <input type="hidden" id="longitude" name="longitude" value="this_longitude">


                            <!-- מכאן מתחיל באמת הטופס -->
                            <div class="row custom_row02 mt_8">
                                <div class="col-sm-6 mt_20 custom_col02">
                                <div class="input_box">
                                    <label for="a">עיר  <span>*</span></label>
                                    <input id="a" name="city" type="text" placeholder="באיזה עיר הדירה?">
                                </div>
                                </div>
                                <div class="col-sm-6 mt_20 custom_col02">
                                <div class="input_box">
                                    <label for="b">אזור  <span>*</span></label>
                                    <input id="b" name="area" type="text" placeholder="באיזה אזור הדירה?">
                                </div>
                                </div>
                            </div>
                            <div class="row custom_row02">
                                <div class="col-md-9 col-sm-8 mt_20 custom_col02">
                                <div class="input_box">
                                    <label for="c">רחוב<span>*</span></label>
                                    <input id="c" name="street" type="text" placeholder="שם הרחוב">
                                </div>
                                </div>
                                <div class="col-md-3 col-sm-4 mt_20 custom_col02">
                                <div class="input_box">
                                    <label for="d">מס’ בית<span>*</span></label>
                                    <input id="d" name="house_num" type="text" placeholder="מספר בית">
                                </div>
                                </div>
                            </div>
                            <div class="row custom_row02 mt_8">
                                <div class="col-md-4 col-sm-6 mt_20 custom_col02">
                                <div class="input_box">
                                    <label for="e">קומה<span>*</span></label>
                                    <input id="e"  name="floor" type="text" placeholder="מספר קומה">
                                </div>
                                </div>
                                <div class="col-md-4 col-sm-6 mt_20 custom_col02">
                                <div class="input_box">
                                    <label for="f">מס’ חדרים<span>*</span></label>
                                    <input id="f" name="num_of_rooms" type="text" placeholder="מספר חדרים">
                                </div>
                                </div>
                                <div class="col-md-4 col-sm-6 mt_20 custom_col02">
                                <div class="input_box">
                                    <label for="g">גודל דירה (מ”ר)</label>
                                    <input id="g" name="apartment_size_in_meters" type="text" placeholder="גודל דירה">
                                </div>
                                </div> 
                            </div>
                            <div class="row custom_row02">
                                <div class="col-md-3 col-sm-12 custom_col02">
                                <div class="row custom_row02">
                                    <div class="col-md-12 col-sm-6 mt_20 custom_col02">
                                    <div class="input_box">
                                        <label for="h">מחיר<span>*</span></label>
                                        <input id="h" name="price" class="text-left pb_15" type="text" placeholder="₪">
                                    </div>
                                    </div>
                                    <div class="col-md-12 col-sm-6 mt_20 custom_col02">
                                    <div class="input_box">
                                        <label for="i">תאריך כניסה<span>*</span></label>
                                        <input id="i" name="entrance_date" type="text">
                                    </div>
                                    </div>
                                </div>
                                </div>
                                <div class="col-md-9 custom_col02 mt_20">
                                <div class="input_box">
                                    <label for="j">על הדירה:<span>*</span></label>
                                    <textarea id="j" name="about_the_apartment" placeholder="ספרו על הדירה..."></textarea>
                                </div>
                                </div>
                                <div class="col-12 custom_col02 mt_20">
                                <div class="input_box">
                                    <label for="k">אופציות והיתרי בניה</label>
                                    <input id="k" name="other_options" type="text" placeholder="פרט האם יש לדירה אופציות או היתרי בניה מיוחדים">
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
                            <!-- מה יש בדירה -->
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
                                <button type="submit" class="detail_payment_btn mt_20">פרסם דירה ←</button> <!-- שינוי טקסט כפתור הגשה? -->
                            </div>
                            <!-- כאן נגמר הטופס -->
                            </form>

              

                        </div>
                    </div>
                    <!-- Tab Pane: החשבון שלי -->
                    <div class="tab-pane fade col-lg-10" id="pills-my-acount" role="tabpanel" aria-labelledby="pills-my-acount-tab">                       
                            <div class="broker_personal_my_account_wrap custom_padd">
                                <h2 class="custom_head head_text02">החשבון שלי</h2>
                                <form>
                                    <div class="my_account_form default_item mt_30">
                                    <h2 class="form_head">עריכת פרטים</h2>
                                    <div class="row custom_row3 mt_30">
                                        <div class="col-sm-6 custom_col3">
                                            <div>
                                                <label for="a">שם:</label>
                                                <input class="input_info mt_13" id="a" type="text" placeholder="יוסי ברואיר">
                                            </div>
                                        </div>
                                        <div class="col-sm-6 custom_col3 custom_mt14">
                                            <div>
                                                <label for="b">שם המשרד:</label>
                                                <input class="input_info mt_13" id="b" type="text" placeholder="טאלאנט">
                                            </div>
                                        </div>
                                        <div class="col-sm-4 custom_col3 mt_30">
                                            <div>
                                                <label for="c">אימייל:</label>
                                                <input class="input_info mt_13" id="c" type="text" placeholder="a0548439972@gmail.com">
                                            </div>
                                        </div>
                                        <div class="col-sm-4 custom_col3 mt_30">
                                            <div>
                                                <label for="d">טלפון:</label>
                                                <input class="input_info mt_13" id="d" type="text" placeholder="0548439972">
                                            </div>
                                        </div>
                                        <div class="col-sm-4 custom_col3 mt_30">
                                            <div>
                                                <label for="e">ווצאפ:</label>
                                                <input class="input_info mt_13" id="e" type="text" placeholder="0548439972">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-sm-6 mt_30 custom_col3">
                                            <label for="ImageMedias">לוגו המשרד:</label>
                                            <div class="d-flex align-items-center mt_8">
                                                <div class="upload_img" id="divImageMediaPreview"><img class="w-100" src="<?php echo get_template_directory_uri(); ?>/assets/img/upload_img.svg" alt="upload_img"></div>
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
                                    <h2 class="form_head">עריכת פרטים</h2>
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
                            
                </div> <!-- סוף tab-content -->
            </div>
        </div>
    </div>
</div>
<!-- Broker personal area end -->

<!-- עד כאן HTML -->
<!-- הוספת הגדרת משתנה בסיס לתמונות JS -->
<script>
    const dirataImgBase = '<?php echo get_template_directory_uri(); ?>/assets/img/';
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // When a city is selected, fetch its related areas
        const citySelect = document.getElementById('city-select');
        if (citySelect) {
            citySelect.addEventListener('change', function() {
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
        }

        const updateBtn = document.getElementById('update-notifications');
        if (updateBtn) {
            updateBtn.addEventListener('click', function() {
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
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
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

        // When the "add area" button is clicked
        const addAreaBtn = document.getElementById('add-area-btn');
        if (addAreaBtn) {
            addAreaBtn.addEventListener('click', function(event) {
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
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
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

        // Function to refresh the saved areas section
        function refreshSavedAreas() {
            var userId = '<?php echo get_current_user_id(); ?>';
            fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
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

        // Reattach delete events after refreshing the saved areas
        function attachDeleteEvents() {
            document.querySelectorAll('.delete-area-btn').forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    var areaId = this.getAttribute('data-area-id');
                    if (confirm("האם אתה בטוח שברצונך להסיר את האזור הזה?")) {
                        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded'
                                },
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
                const editAreasTab = document.getElementById('pills-home-tab4');
                if(editAreasTab) {
                    editAreasTab.click();
                }
            });
        }

        const updateDetailsBtn = document.querySelector('.custom_btn.mt_30');
        if(updateDetailsBtn) {
            updateDetailsBtn.addEventListener('click', function(e) {
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
        }

        document.querySelectorAll('.save_apartment_btn').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
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

        // Ensure clicks on the 'My Account' list item trigger the tab
        const myAccountListItem = document.getElementById('pills-my-acount-tab')?.closest('li'); 
        if (myAccountListItem) {
            myAccountListItem.addEventListener('click', function(event) {
                // Find the anchor tag within the list item
                const anchorTag = this.querySelector('a[data-toggle="pill"]');
                if (anchorTag && typeof(jQuery) !== 'undefined') { // Check jQuery exists
                    // Use Bootstrap's jQuery method to show the tab
                    jQuery(anchorTag).tab('show');
                } else if (anchorTag && typeof bootstrap !== 'undefined') {
                    // Fallback for Bootstrap 5 vanilla JS (adjust if needed)
                    const tab = new bootstrap.Tab(anchorTag);
                    tab.show();
                }
                // Optional: Prevent default anchor behavior if it interferes
                // event.preventDefault(); 
            });
        }
    });
</script>
<script src="<?php echo get_template_directory_uri(); ?>/js/jquery-3.4.1.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/jquery-ui.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/plugins.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/main.js"></script>

<script>

    jQuery(document).ready(function() {
        document.getElementById('pro-image').addEventListener('change', readImage, false);
        
        jQuery( ".preview-images-zone" ).sortable();
        
        jQuery(document).on('click', '.image-cancel', function() {
            let no = jQuery(this).data('no');
            jQuery(".preview-image.preview-show-"+no).remove();
        });
    });


    var num = 0;
    function readImage() {
        if (window.File && window.FileList && window.FileReader) {
            var files = event.target.files; //FileList object
            var output = jQuery(".preview-images-zone");

            for (let i = 0; i < files.length; i++) {
                var file = files[i];
                // מצא את מספר התמונה הגבוה ביותר הקיים + 1, כדי למנוע התנגשויות ID אם תמונות נמחקות
                let existingNumbers = [];
                output.find('.preview-image').each(function() {
                    let currentNum = jQuery(this).attr('class').match(/preview-show-(\d+)/);
                    if (currentNum && currentNum[1]) {
                        existingNumbers.push(parseInt(currentNum[1], 10));
                    }
                });
                num = existingNumbers.length > 0 ? Math.max(...existingNumbers) + 1 : 1;


                var checkdiv = output.find('div.preview-image').length; 
                // Limit line - עדיין נשמור על הגבלה כללית ל-10 תמונות
                if (checkdiv < 10){  
                
                if (!file.type.match('image.*')) { // התאמה משופרת לכל סוגי התמונות
                    console.warn("Skipping non-image file:", file.name);
                    continue;
                }
                
                var picReader = new FileReader();
                
                picReader.addEventListener('load', function (event) {
                    var picFile = event.target;
                    // ודא ש-dirataImgBase מוגדר
                    if (typeof dirataImgBase === 'undefined') {
                        console.error("JavaScript variable 'dirataImgBase' is not defined.");
                        return; // עצור אם המשתנה לא מוגדר
                    }
                    var html =  '<div class="preview-image preview-show-' + num + '">' + // הסרת 'claOpen Workspace Settings (JSON)ss=' מיותר
                    '<div class="image-cancel" data-no="' + num + '"><img src="' + dirataImgBase + 'delete_icon.svg" alt="delete_icon"></div>' +
                    '<div class="image-zone"><img id="pro-img-' + num + '" src="' + picFile.result + '"></div>' +
                    '<div class="tools-edit-image"><a href="javascript:void(0)" data-no="' + num + '" class="btn-edit-image">להחלפה<img src="' + dirataImgBase + 'replacable_icon.svg" alt="replacable_icon"></a></div>' +
                    '</div>';

                    output.append(html);
                    // num = num + 1; // המספור הדינאמי מטפל בזה
                });
                picReader.readAsDataURL(file); // קריאת הקובץ הנוכחי בלולאה
                } else {
                alert("ניתן להעלות עד 10 תמונות.");
                break; // צא מהלולאה אם הגענו למגבלה
                }
            } // סוף הלולאה
            // נקה את ה-input כדי לאפשר העלאה חוזרת של אותו קובץ אם צריך
            const proImageInput = document.getElementById('pro-image');
            if (proImageInput) {
                proImageInput.value = ''; 
            }
        } else {
            console.log('Browser not support');
        }
    }


    // ⚙️ Mapbox - קבלת קואורדינטות מהכתובת
        async function getCoordinatesFromMapbox() {
        const city = document.getElementById("a").value;
        const area = document.getElementById("b").value;
        const street = document.getElementById("c").value;
        const houseNum = document.getElementById("d").value;

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

        // ⌨️ כשהמשתמש עוזב את אחד משדות הכתובת
        ['a', 'b', 'c', 'd'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('blur', getCoordinatesFromMapbox);
        }
        });

    //   קוד ללחיצה על קישורים פנימיים בטאבים
    document.addEventListener('DOMContentLoaded', function() {
        // תופס את כל האלמנטים עם על פי הקלאסים
        const linksToMyAcount = document.querySelectorAll('.to-my-acount');
        const linksToMyLeads = document.querySelectorAll('.to-my-leads');
        const linksToMyApartments = document.querySelectorAll('.to-my-apartments');

        // my acount
        linksToMyAcount.forEach(link => {
            link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // לוחצים עליו → מפעילים את הטאב
            const myAccountTabButton = document.querySelector('#pills-my-acount-tab');
            const myAccountTabContent = document.querySelector('#pills-my-acount');
            
            if (myAccountTabButton && myAccountTabContent) {
                
                // מסירים active מכל שאר הלחצנים
                document.querySelectorAll('[data-toggle="pill"]').forEach(btn => btn.classList.remove('active'));
                
                // מסירים active מכל שאר הטאבים
                document.querySelectorAll('.tab-pane').forEach(tab => tab.classList.remove('active', 'show'));
                
                // מפעילים את הטאב של החשבון שלי
                myAccountTabButton.classList.add('active');
                myAccountTabContent.classList.add('active', 'show');
                
                // אם צריך, אפשר גם לגלול לאזור הטאבים
                // document.getElementById('pills-tabContent').scrollIntoView({ behavior: 'smooth' });
            }
            });
        });


        // my leads
        linksToMyLeads.forEach(link => {
            link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // לוחצים עליו → מפעילים את הטאב
            const myAccountTabButton = document.querySelector('#pills-leads-tab');
            const myAccountTabContent = document.querySelector('#pills-leads');
            
            if (myAccountTabButton && myAccountTabContent) {
                
                // מסירים active מכל שאר הלחצנים
                document.querySelectorAll('[data-toggle="pill"]').forEach(btn => btn.classList.remove('active'));
                
                // מסירים active מכל שאר הטאבים
                document.querySelectorAll('.tab-pane').forEach(tab => tab.classList.remove('active', 'show'));
                
                // מפעילים את הטאב של החשבון שלי
                myAccountTabButton.classList.add('active');
                myAccountTabContent.classList.add('active', 'show');
                
                // אם צריך, אפשר גם לגלול לאזור הטאבים
                // document.getElementById('pills-tabContent').scrollIntoView({ behavior: 'smooth' });
            }
            });
        });

        // my apartments
        linksToMyApartments.forEach(link => {
            link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // לוחצים עליו → מפעילים את הטאב
            const myAccountTabButton = document.querySelector('#pills-apartments-tab');
            const myAccountTabContent = document.querySelector('#pills-apartments');
            
            if (myAccountTabButton && myAccountTabContent) {
                
                // מסירים active מכל שאר הלחצנים
                document.querySelectorAll('[data-toggle="pill"]').forEach(btn => btn.classList.remove('active'));
                
                // מסירים active מכל שאר הטאבים
                document.querySelectorAll('.tab-pane').forEach(tab => tab.classList.remove('active', 'show'));
                
                // מפעילים את הטאב של החשבון שלי
                myAccountTabButton.classList.add('active');
                myAccountTabContent.classList.add('active', 'show');
                
                // אם צריך, אפשר גם לגלול לאזור הטאבים
                // document.getElementById('pills-tabContent').scrollIntoView({ behavior: 'smooth' });
            }
            });
        });            
    });

</script>


<?php
get_footer();
?>

