<html>
   <body>      
      <!-- Footer Start -->
      <div class="footer_wrapper overflow-hidden d-flex"  style="margin-top: 5%; padding: 5% 5% 1% 5%; align-items: center; justify-content: center; background-image: url('<?php echo get_template_directory_uri(); ?>/assets/img/bg01.svg');">
         <div class="container position-absolute shadow-sm py-5 px-4"
            style="left: 50%; transform: translateX(-50%); width: 100%; max-width: 1200px;">
            <div class="row text-end otef-cols">
                  <!-- עמודה: לוגו וקריאה לפעולה -->
                  <div class="col-md-3 col-12 mb-4">
                     <div class="footer_logo_box text-center text-md-start p-5">
                        <a href="https://dirata.co.il/" target="_blank">
                           <img src="/wp-content/themes/dirata/assets/img/logo.svg" alt="Dirata Logo">
                        </a>
                        
                        <br>
                        <div class="mt-4" style="justify-self: center; background-color: #142c4f; height: 2px; width: 34px;"></div>
                        <br>
                        <p class="small mt-4" style="line-height: 17px;">
                           <span class="fw-bold">הישארו מעודכנים</span>
                           <br>
                           באזור האישי באתר,
                           <br>                           
                           במערכת הטלפונית ובמייל
                           <br>
                           <br>
                        </p>

                        <a href="/contact" class="footer_btn select_search_btn my-2 text-decoration-none">ליצירת קשר ←</a>
                        <a href="#" class="footer_btn select_search_btn text-decoration-none" style="display: none; background-color: #76C092; color: #142c4f;">טופס אישור תיווך</a>
                     </div>
                  </div>
                  <!-- עמודה: מה בדירתא -->
                  <div class="col-md-3 col-6 mb-4 text-right d-flex flex-wrap" style="align-content: center; flex-direction: column; justify-content: flex-start; margin-top: 5%;">
                     <h6 class="fw-bold mb-3" style="color: #27303C;">מה בדירתא</h6>
                     <ul class="pe-3">
                        <li><a href="/apartments-search/?listing_type=sale&floor_min=0&floor_max=85" class="text-decoration-none text-dark">• מאגר דירתא מכירה</a></li>
                        <li><a href="/apartments-search/?listing_type=rent&floor_min=0&floor_max=85" class="text-decoration-none text-dark">• מאגר דירתא השכרה</a></li>
                        <li><a href="/about-dirata/" class="text-decoration-none text-dark">• איך קונים דירה</a></li>
                        <li><a href="/about-dirata/" class="text-decoration-none text-dark">• איך מוכרים דירה</a></li>
                        <li><a href="/profile-page" class="text-decoration-none text-dark">• אזור אישי</a></li>
                        <li><a href="/apartments-search/" class="text-decoration-none text-dark">• בחירת אזור חיפוש</a></li>
                        <li><a href="/profile-page/?tab=publish" class="text-decoration-none text-dark">• פרסום דירה</a></li>
                     </ul>
                  </div>
                   <!-- עמודה: מידע -->
                   <div class="col-md-3 col-6 mb-4 text-right d-flex flex-wrap" style="align-content: center; flex-direction: column; justify-content: flex-start; margin-top: 5%;">
                     <h6 class="fw-bold mb-3" style="color: #27303C;">מידעתא</h6>
                     <ul class="pe-3">
                        <?php
                        $articles = get_field('selected_articles', 'option'); // שדה מתוך עמוד הניהול
                        $index = 0;
                        if ($articles && $index < 7) :
                           foreach ($articles as $post) :
                                 setup_postdata($post); ?>
                                 <li><a href="<?php the_permalink(); ?>" class="text-decoration-none text-dark">• <?php the_title(); ?> </a></li>                  
                           <?php 
                           $index++;
                           endforeach;
                           wp_reset_postdata();
                        endif;
                        ?>
                       
                     </ul>
                  </div>
                  

                  <!-- עמודה: דירות מומלצות -->
                  <div class="col-md-3 col-6 mb-4 text-right d-flex flex-wrap" style="align-content: center; flex-direction: column; justify-content: flex-start; margin-top: 5%;">
                     <h6 class="fw-bold mb-3" style="color: #27303C;">הדירות החמות של דירתא</h6>
                     <ul class="pe-3">
                                        <?php
                        $selected_cities = get_field('selected_home_cities', 'option');
                        $index = 0;
                        if ($selected_cities && $index < 5) :
                           foreach ($selected_cities as $city_data) :
                                 // בדוק אם זה אובייקט או ID
                                 if (is_object($city_data)) {
                                     $city = $city_data;
                                 } else {
                                     $city = get_term($city_data, 'city');
                                 }
                                 
                                 // וודא שקיבלנו אובייקט תקין
                                 if ($city && !is_wp_error($city)) : ?>
                                     <li><a href="/apartments-search/?city=<?php echo esc_attr($city->term_id); ?>" class="text-decoration-none text-dark">• דירות ב<?php echo esc_html($city->name); ?> </a></li>                  
                           <?php 
                                 endif;
                           $index++;
                           endforeach;
                        endif;
                        ?>
                       
                        <!-- <li><a href="#" class="text-decoration-none text-dark">• דירות בירושלים </a></li>
                        <li><a href="#" class="text-decoration-none text-dark">• דירות בבני ברק </a></li>
                        <li><a href="#" class="text-decoration-none text-dark">• דירות בבית שמש </a></li>
                        <li><a href="#" class="text-decoration-none text-dark">• דירות באלעד </a></li>
                        <li><a href="#" class="text-decoration-none text-dark">• דירות בביתר עילית </a></li>
                        <li><a href="#" class="text-decoration-none text-dark">• דירות במודיעין עילית </a></li>
                        <li><a href="#" class="text-decoration-none text-dark">• דירות באשדוד </a></li>                     -->
                     </ul>
                  </div>
                 
                  

 
            </div>
            <div class="mt-4" style="justify-self: center; background-color: #142c4f; height: 1px; width: 100%;"></div>

            <!-- שורת תחתית -->
            <div class="text-decoration-none text-dark row pt-3 mt-4 text-center text-md-end small">
                  <div class="col-md-8" style="text-align: right;">
                     <a class="mx-2 text-decoration-none text-dark"> כל הזכויות שמורות לדירתא</a> 
                     <a href="/map" class="mx-2 text-decoration-none text-dark">מפת האתר</a> 
                     <a href="/toc" class="mx-2 text-decoration-none text-dark">תקנון האתר</a> 
                     <a href="/accessibility-statement" class="mx-2 text-decoration-none text-dark">הצהרת נגישות</a>
                     <a href="/privacy" class="mx-2 text-decoration-none text-dark">מדיניות פרטיות</a>
                     <a href="/contact" class="mx-2 text-decoration-none text-dark">יצירת קשר</a>
                  </div>
                  <div class="col-md-4" style="text-align: left;">
                     <a href="https://haimklain.com/" target="_blank" class="mx-2 text-decoration-none text-dark">
                     אפיון ועיצוב ע”י 
                     <span class="fw-bold">
                        חיים קליין
                     </span>
                        – עסקים חרדים בדיגיטל
                     </a>
                  
                  </div>                
            </div>
         </div>
         <img class="form_shape img-fluid align-self-center" src="<?php echo get_template_directory_uri(); ?>/assets/img/bg02.svg" alt="bg02" style="z-index: -1;">
         <img class="form_shape_mobile align-self-center" src="<?php echo get_template_directory_uri(); ?>/assets/img/bg02_mini.svg" alt="bg02_mini" style="z-index: -1;">
         
         

         <!-- כפתור צור קשר צף -->
         <div class="phone-tzaf position-fixed end-0 m-3 d-flex shadow-lg text-white rounded-pill px-3 py-2"
            style="z-index: 111111111; bottom: 70px; right: 0; left: auto; flex-direction: row; background-color: #20315F;">
            <img src="/wp-content/themes/dirata/assets/img/phone_tzaf.svg" alt="Dirata Phone">
            <div class="text-right">
               <span>המערכת הקולית של דירתא:</span>
               <br>
               <a href="tel:0533333333" class="fw-bold text-white fw-bold ms-2">053.333.3333</a>
            </div>
         
         </div>

      </div>
      <!-- Footer End -->
      <!-- תפריט מובייל צף -->
      <nav class="mobile-footer-nav d-flex justify-content-around align-items-center position-fixed w-100 px-4 py-2" style="bottom: 20px; z-index: 9999;">
      <a href="/" class="text-center text-dark text-decoration-none">
         <img src="/wp-content/themes/dirata/assets/img/icon-home.svg" alt="בית" width="28">
         <div class="small mt-2">בית</div>
      </a>
      <a href="/apartments-search" class="text-center text-dark text-decoration-none">
         <img src="/wp-content/themes/dirata/assets/img/icon-search.svg" alt="המאגר" width="28">
         <div class="small mt-2">המאגר</div>
      </a>
      <a href="/profile-page" class="text-center text-dark text-decoration-none">
         <img src="/wp-content/themes/dirata/assets/img/icon-user.svg" alt="אזור אישי" width="28">
         <div class="small mt-2">אזור אישי</div>
      </a>
      <a href="#" id="mobileMenuToggle" class="text-center text-dark text-decoration-none">
         <img src="/wp-content/themes/dirata/assets/img/icon-grid-2.png" alt="תפריט" width="28">
         <div class="small mt-2">תפריט</div>
      </a>
      </nav>


      <!-- רקע כהה + תפריט צף למובייל -->
      <div id="mobileOverlay" class="d-none"></div>

      <div id="mobileExtraMenu" class="d-none">
      <!-- כפתור המערכת הקולית -->
      <div class="voice-button position-fixed end-0 m-3 d-flex shadow-lg text-white rounded-pill px-3 py-2"
            style="z-index: 111111111; bottom: 70px; right: 20px; left: auto; flex-direction: row; background-color: #20315F;">
         <img src="/wp-content/themes/dirata/assets/img/phone_tzaf.svg" alt="Dirata Phone">
         <div class="text-right">
            <span>המערכת הקולית של דירתא:</span><br>
            <a href="tel:0533333333" class="fw-bold text-white ms-2">053.333.3333</a>
         </div>
      </div>

      <!-- תפריט נוסף -->
      <div class="nav-menu-open position-fixed w-100 text-center px-4 py-3 shadow-lg"
            style="bottom: 130px; z-index: 111111110;">
         <a href="/about-dirata/" class="d-block text-dark py-1">איך קונים דירה</a>
         <a href="/about-dirata/" class="d-block text-dark py-1">איך מוכרים דירה</a>
         <a href="/contact" class="d-block text-dark py-1">צור קשר</a>
      </div>
      </div>

      <script>

         // All jQuery section Include: 

         //01. password hide show start
         //02. add form item after click




         // 01. password hide show start
         function myfunction(){
            var x = document.getElementById("myInput");
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


         //02. add form item after click
         $(document).ready(function(){
   
            $(".additional_btn ").click(function(e){
               e.preventDefault();
               $("#extend-field").append('<div class="update_selection_item"><div><label for="aac">בחר עיר:</label><input class="input_info" type="text" id="aac"></div><div class="mt_22"><label for="bbc">בחר שכונה/אזור:</label><input class="input_info" type="text" id="bbc"></div><p class="remove-extend-field"><i class="far fa-times"></i></p></div>');
            });

            $("#extend-field").on("click",".remove-extend-field",function(e){
               e.preventDefault();
               $(this).parent('div').remove();
            });

            
         });

         // קוד לתפריט נפתח במובייל
         document.addEventListener("DOMContentLoaded", function () {
         const menuBtn = document.getElementById("mobileMenuToggle");
         const mobileMenu = document.getElementById("mobileExtraMenu");
         const mobileOverlay = document.getElementById("mobileOverlay");

         function toggleMenu(show) {
            if (show) {
               mobileMenu.classList.remove("d-none");
               mobileOverlay.classList.remove("d-none");
               mobileOverlay.classList.add("d-block");
            } else {
               mobileMenu.classList.add("d-none");
               mobileOverlay.classList.remove("d-block");
               mobileOverlay.classList.add("d-none");
            }
         }

         if (menuBtn) {
            menuBtn.addEventListener("click", function (e) {
               e.preventDefault();
               const isVisible = !mobileMenu.classList.contains("d-none");
               toggleMenu(!isVisible);
            });
         }

         // סגירה בלחיצה על הרקע
         if (mobileOverlay) {
            mobileOverlay.addEventListener("click", function () {
               toggleMenu(false);
            });
         }
         });



      </script>

      <?php wp_footer(); ?> 
   </body>
</html>