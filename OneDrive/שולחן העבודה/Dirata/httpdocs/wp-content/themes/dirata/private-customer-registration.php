<?php
/* Template Name: Private customer registration page */
get_header();
?>
    <!-- home area start -->
    <div class="form_wrapper position-relative overflow-hidden" style="background-image: url(./img/bg01.svg);">
        <img class="form_shape img-fluid position-absolute" src="<?php echo get_template_directory_uri(); ?>/assets/img/bg02.svg" alt="bg02">
        <img class="form_shape_mobile" src="<?php echo get_template_directory_uri(); ?>/assets/img/bg02_mini.svg" alt="bg02_mini">
        <div class="container">
        <div class="text-center">
            <h3 class="small_head_text">
                    בוחרים אזור חיפוש
                    <span>></span>
                    מקבלים עדכונים חמים
                    <span>></span>
                </h3>
            <h1 class="head_text">מוצאים דירה בקלות<span>!</span>
            </h1>
                <a class="form_para mt_30" href="/login/">כבר בחרת אזור? מפה נכנסים לאזור האישי ←</a>
                <br>
                <br>
                <a href="/phone-login/">נרשמת דרך המערכת הטלפונית? קבל גישה לחשבון שלך ←</a>
        </div>
        <?php
                    $areas = get_terms(array(
                        'taxonomy' => 'area',
                        'hide_empty' => false,
                    ));
                    $area_data = [];
                    
                    $cities = get_terms(array(
                      'taxonomy' => 'city',
                      'hide_empty' => false,
                    ));
                    ?>
        <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
            <input type="hidden" name="action" value="register_custom_user">
            <!-- <input class="input_info" type="text" placeholder="בחר עיר:"> -->
            <label for="a">עיר \ יישוב <span>*</span></label>
                <select id="a" name="city" class="custon-select" required>
                    <option value="">בחר עיר \ יישוב</option>
                    <?php
                    if (!is_wp_error($cities) && !empty($cities)) {
                        foreach ($cities as $city) {
                            if (is_object($city)) { // בדיקה נוספת שזה אובייקט
                                echo '<option value="' . esc_attr($city->term_id) . '">' . esc_html($city->name) . '</option>';
                            }
                        }
                    }
                    ?>
                </select>
            <br>
            <br>
            <label for="b">אזור <span>*</span></label>
                <select id="b" name="area" class="custon-select" required>
                    <option value="">בחר אזור</option>
                    <?php
                    if (!is_wp_error($areas) && !empty($areas)) {
                        foreach ($areas as $area) {
                            if (is_object($area)) { // בדיקה נוספת שזה אובייקט
                            $related_city = get_term_meta($area->term_id, 'associated_city', true);  
                            if ($related_city) {
                                $area_data[] = array(
                                    'id' => $area->term_id,
                                    'name' => $area->name,
                                    'city_id' => $related_city,
                                );
                            }
                            echo '<option value="' . esc_attr($area->term_id) . '">' . esc_html($area->name) . '</option>';
                            }
                        }
                    }
                    ?>
                </select>
             <!-- מעביר מערך של כל האזורים שמקושרים לעיר מסויימת -->
            <script>
                const areasData = <?php echo json_encode($area_data); ?>;
            </script>
            <div class="d-flex flex-row mt_30" style="align-items: center; padding: 0px;">
            <label class="col-md-5 form-label" style="padding: 0px;">על אילו דירות תרצו לקבל עדכונים?</label>
            <div class="col-md-7 btn-group toggle-btn-group" role="group" aria-label="בחירת סוג דירה">        
                <input type="radio" class="btn-check" name="sale_or_rent" id="sale" value="sale" checked>
                <label class="btn toggle-btn" for="sale">דירות למכירה</label>

                <input type="radio" class="btn-check" name="sale_or_rent" id="rent" value="rent">
                <label class="btn toggle-btn" for="rent">דירות להשכרה</label>
            </div>
            </div>

                <h2 class="mt_30">איך תרצו לקבל את המידע?</h2>
                <div class="d-flex align-items-center checkbox_wrap mt_22">
                    <div class="checkbox01">
                        <input type="checkbox" id="d" name="notification_method[]" value="phone">
                        <label for="d"><span><i class="fas fa-check"></i></span> צינתוק לטלפון</label>
                    </div>
                    <div class="checkbox01">
                        <input type="checkbox" id="e" name="notification_method[]" value="email">
                        <label for="e"><span><i class="fas fa-check"></i></span> אימייל</label>
                    </div>
                    <div class="checkbox01">
                        <input type="checkbox" id="f" name="notification_method[]" value="whatsapp">
                        <label for="f"><span><i class="fas fa-check"></i></span> הודעת טקסט - וואצאפ</label>
                    </div>
                </div>
                <h2 class="mt_30">תדירות קבלת עדכונים</h2>
                <div class="d-flex align-items-center checkbox_wrap_single checkbox_wrap mt_22">
                    <div class="checkbox01">
                        <input type="checkbox" id="g" name="notification_frequency[]" value="immediate">
                        <label for="g"><span><i class="fas fa-check"></i></span>מיידי</label>
                    </div>
                    <div class="checkbox01">
                        <input type="checkbox" id="h" name="notification_frequency[]" value="daily">
                        <label for="h"><span><i class="fas fa-check"></i></span>פעם ביום</label>
                    </div>
                    <div class="checkbox01">
                        <input type="checkbox" id="i" name="notification_frequency[]" value="weekly">
                        <label for="i"><span><i class="fas fa-check"></i></span>פעם בשבוע</label>
                    </div>
                </div>
                <input class="input_info mt_30" type="text" name="client_name" placeholder="שם:">
                <div class="row">
                    <div class="col-lg-6">
                        <input class="input_info mt_22" type="text" name="phone" placeholder="טלפון:">
                    </div>
                    <div class="col-lg-6">
                        <input class="input_info mt_22" type="text" name="email" placeholder="אימייל:">
                    </div>
                </div>
                <div class="password_info position-relative mt_30">
                    <input id="password" class="input_info" type="password" name="password" placeholder="בחר סיסמא:">
                    <span class="eye" onclick="showEye1()">
                        <i id="hide1" class="fas fa-eye"></i>
                        <i id="hide2" class="fas fa-eye-slash"></i>
                    </span>
                </div>
                <div class="password_info position-relative mt_30">
                    <input id="confirm_password" class="input_info" type="password" name="confirm_password" placeholder="אימות סיסמא:">
                    <span class="eye" onclick="showEye2()">
                        <i id="hide1" class="fas fa-eye"></i>
                        <i id="hide2" class="fas fa-eye-slash"></i>
                    </span>
                </div>
                <!-- הודעת שגיאה -->
                <div id="password-error" style="display:none;">
                <i class="fas fa-exclamation-triangle"></i>
                סיסמאות לא תואמות
                </div>
                <div class="mt_30">
                    <div class="checkbox01 checkbox02">
                        <input type="checkbox" id="j" name="terms_agreement">
                        <label for="j"><span><i class="fas fa-check"></i></span>קראתי ואישרתי את  <p>תנאי השימוש  </p>ואת  <p>מדיניות הפרטיות </p></label>
                    </div>
                    <div class="checkbox01 checkbox02 mt_13">
                        <input type="checkbox" id="k" name="is_mailing">
                        <label for="k"><span><i class="fas fa-check"></i></span>אני רוצה לקבל דיוור ישיר למייל עם חידושים, עדכונים וטיפים.</label>
                    </div>
                </div>
                <!-- <a href="Private_customer_login_page.html" class="form_btn mt_30">סיום ושמירת אזור</a> -->
                <button type="submit" class="form_btn mt_30">סיום ושמירת אזור</button>

                <!-- <a class="form_para mt_30" href="#">כבר בחרת אזור? מפה נכנסים לאזור האישי ></a> -->
        </form>
        </div>
    </div>
    <!-- home area end -->

    <!-- all js here -->
    <script src="js/jquery-3.4.1.min.js"></script>
    <script src="js/plugins.js"></script>
    <script src="js/main.js"></script>
    <script>

        // All jQuery section Include: 

        //01. password hide show start
        //02. password hide show 02 start



        //01. password hide show start
        function showEye1(){
        var x = document.getElementById("password");
        var y = document.getElementById("hide1");
        var z = document.getElementById("hide2");

        if (x.type === 'password') {
            x.type = "text";
            y.style.display = "block";
            z.style.display = "none";
            
        }
        else {
            x.type = "password";
            y.style.display = "none";
            z.style.display = "block";
        }
        }


        //02. password hide show 02 start
        function showEye2(){
        var x = document.getElementById("confirm_password");
        var y = document.getElementById("hide11");
        var z = document.getElementById("hide22");

        if (x.type === 'password') {
            x.type = "text";
            y.style.display = "block";
            z.style.display = "none";
            
        }
        else {
            x.type = "password";
            y.style.display = "none";
            z.style.display = "block";
        }
        }
        document.addEventListener('DOMContentLoaded', function () {
        const citySelect = document.querySelector('#a');
        const areaSelect = document.querySelector('#b');

        function populateAreasForCity(cityId) {
          areaSelect.innerHTML = '<option value="">בחר אזור</option>';

          areasData.forEach(function (area) {
            if (area.city_id == cityId) {
              const option = document.createElement('option');
              option.value = area.id;
              option.textContent = area.name;
              areaSelect.appendChild(option);
            }
          });
        }

        citySelect.addEventListener('change', function () {
          const selectedCityId = this.value;
          populateAreasForCity(selectedCityId);
        });
      });

      

    </script>
    <!-- קוד שגורם לצקבוקס בתדירות קבלת עדכונים לתפקד כמו רדיו כדי שתהיה בחירה יחידה ועדיין יישאר העיצוב -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // const checkboxes = document.querySelectorAll('.checkbox_wrap input[type="checkbox"]');
            const checkboxes = document.querySelectorAll('.checkbox_wrap_single input[type="checkbox"]');


            checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                checkboxes.forEach(cb => {
                    if (cb !== this) cb.checked = false;
                });
                }
            });
            });
        });


        // קוד לאימות טופס
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('confirm_password');
            const passwordErrorDiv = document.getElementById('password-error');
            const termsCheckbox = document.getElementById('j');
            const form = document.querySelector('form');

            // בדיקה שכל האלמנטים קיימים
            if (!passwordInput || !confirmPasswordInput || !passwordErrorDiv || !termsCheckbox || !form) {
                return;
            }

            // יצירת הודעת שגיאה לתנאי השימוש
            const termsErrorDiv = document.createElement('div');
            termsErrorDiv.id = 'terms-error';
            termsErrorDiv.style.display = 'none';
            termsErrorDiv.style.color = '#dc3545';
            termsErrorDiv.style.marginTop = '10px';
            termsErrorDiv.style.fontWeight = 'bold';
            termsErrorDiv.innerHTML = '<i class="fas fa-exclamation-triangle"></i> יש לאשר את תנאי השימוש ומדיניות הפרטיות';
            
            // הוספת הודעת השגיאה אחרי ה-checkbox של תנאי השימוש
            const termsContainer = termsCheckbox.closest('.checkbox01');
            if (termsContainer) {
                termsContainer.insertAdjacentElement('afterend', termsErrorDiv);
            }

            // פונקציה לבדיקת סיסמאות
            function checkPasswordsMatch() {
                if (confirmPasswordInput.value !== passwordInput.value) {
                    passwordErrorDiv.style.display = 'block';
                    return false;
                } else {
                    passwordErrorDiv.style.display = 'none';
                    return true;
                }
            }

            // בדיקה תוך כדי הקלדה
            confirmPasswordInput.addEventListener('input', checkPasswordsMatch);
            passwordInput.addEventListener('input', checkPasswordsMatch);

            // הסתרת הודעת השגיאה כאשר המשתמש מסמן את תנאי השימוש
            termsCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    termsErrorDiv.style.display = 'none';
                }
            });

            // בדיקה מאוחדת בעת שליחת הטופס
            form.addEventListener('submit', function(e) {
                let hasErrors = false;

                // בדיקת סיסמאות
                if (!checkPasswordsMatch()) {
                    hasErrors = true;
                    confirmPasswordInput.focus();
                }

                // בדיקת תנאי השימוש
                if (!termsCheckbox.checked) {
                    hasErrors = true;
                    termsErrorDiv.style.display = 'block';
                    
                    // גלילה להודעת השגיאה
                    if (passwordErrorDiv.style.display !== 'block') {
                        setTimeout(() => {
                            termsErrorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }, 100);
                    }
                } else {
                    termsErrorDiv.style.display = 'none';
                }

                // אם יש שגיאות - עצור שליחת הטופס
                if (hasErrors) {
                    e.preventDefault();
                    return false;
                }
            });
        });


    </script>

<?php
get_footer();
?>

