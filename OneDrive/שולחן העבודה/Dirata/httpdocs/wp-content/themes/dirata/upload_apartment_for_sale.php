<?php
/**
 * Template Name: Upload Apartment For Sale
 */

 if ( ! is_user_logged_in() ) {
    wp_redirect( wp_login_url( get_permalink() ) ); // מפנה לעמוד ההתחברות, ואחר כך מחזיר לעמוד הזה
    exit;
}
// get_header();

?>
<?php wp_head(); ?>

<script>
            window.dirataImgBase = window.dirataImgBase || '<?php echo get_template_directory_uri(); ?>/assets/img/';
</script>

      <!-- upload new apartment form start -->
      <div class="private_apartment_form_wrapper">
        <div class="container">
          <div class="row no-gutters">
            <div class="col-lg-9 private_apartment_form_order1">
              <div class="form_wrap text-right">
                <!-- מכאן מתחיל באמת הטופס -->
                <form id ="apartment-form">
                  <!-- <input type="hidden" name="action" value="create_apartment_post"> -->
                
                  <input type="hidden" id="latitude" name="latitude" value="">
                  <input type="hidden" id="longitude" name="longitude" value="">
                  <input type="hidden" name="main_img" id="main_img" value="">
                  <input type="hidden" name="img_gallery" id="img_gallery" value="">
                  <div class="col-md-7 btn-group toggle-btn-group" role="group" aria-label="בחירת סוג דירה">        
                      <input type="radio" class="btn-check" name="sale_or_rent" id="sale" value="sale" checked>
                      <label class="btn toggle-btn" for="sale">דירה למכירה</label>

                      <input type="radio" class="btn-check" name="sale_or_rent" id="rent" value="rent">
                      <label class="btn toggle-btn" for="rent">דירה להשכרה</label>
                  </div>

                  <!-- מכאן מתחיל באמת הטופס -->
                  <div class="row custom_row02 mt_8">
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
                    <div class="col-sm-6 mt_20 custom_col02">
                      <div class="input_box">
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
                      </div>
                    </div>

                  
                    <div class="col-sm-6 mt_20 custom_col02">
                      <div class="input_box">
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
                      </div>
                    </div>

                  </div>
                  <div class="row custom_row02">
                    <div class="col-md-9 col-sm-8 mt_20 custom_col02">
                      <div class="input_box">
                        <label for="c">רחוב<span>*</span></label>
                        <input id="c" name="street" type="text" placeholder="שם הרחוב" required>
                      </div>
                    </div>
                    <div class="col-md-3 col-sm-4 mt_20 custom_col02">
                      <div class="input_box">
                        <label for="d">מס’ בית (לא חובה)</label>
                        <input id="d" name="house_num" type="text" placeholder="מספר בית">
                      </div>
                    </div>
                  </div>
                  <div class="row custom_row02 mt_8">
                    <div class="col-md-4 col-sm-6 mt_20 custom_col02">
                      <div class="input_box">
                        <label for="e">קומה<span>*</span></label>
                        <input id="e"  name="floor" type="text" placeholder="מספר קומה" required>
                      </div>
                    </div>
                    <div class="col-md-4 col-sm-6 mt_20 custom_col02">
                      <div class="input_box">
                        <label for="f">מס’ חדרים<span>*</span></label>
                        <input id="f" name="num_of_rooms" type="text" placeholder="מספר חדרים" required>
                      </div>
                    </div>
                    <div class="col-md-4 col-sm-6 mt_20 custom_col02">
                      <div class="input_box">
                        <label for="g">גודל דירה (מ”ר)<span>*</span></label>
                        <input id="g" name="apartment_size_in_meters" type="text" placeholder="גודל דירה" required>
                      </div>
                    </div> 
                  </div>
                  <div class="row custom_row02">
                    <div class="col-md-3 col-sm-12 custom_col02">
                      <div class="row custom_row02">
                      <div class="col-md-12 col-sm-6 mt_20 custom_col02">
                        <div class="input_box price_input_wrapper">
                          <label for="h">מחיר<span>*</span></label>
                          <span class="currency_symbol">₪</span>
                          <input id="h" name="price" class="text-left pb_15" type="text" inputmode="numeric" required>
                        </div>
                      </div>

                        <div class="col-md-12 col-sm-6 mt_20 custom_col02">
                          <div class="input_box">
                            <label for="i">תאריך כניסה</label>
                            <input id="i" name="entrance_date" type="date">
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-9 custom_col02 mt_20">
                      <div class="input_box">
                        <label for="j">על הדירה:<span>*</span></label>
                        <textarea id="j" name="about_the_apartment" placeholder="ספרו על הדירה..." required></textarea>
                      </div>
                    </div>
                    <div class="col-12 custom_col02 mt_20">
                      <div class="input_box">
                        <label for="k">אופציות והיתרי בניה</label>
                        <input id="k" name="other_options" type="text" placeholder="פרט האם יש לדירה אופציות או היתרי בניה מיוחדים">
                      </div>


                      <div class="row custom_row02">
                        <div class="col-sm-6 mt_20 custom_col02">
                          <div class="input_box">
                            <label for="brokerage_fees">אחוזים לדמי תיווך (אם יש)</label>
                            <input id="brokerage_fees" name="brokerage_fees" type="number" placeholder="מהם אחוזי דמי התיווך?">
                          </div>
                        </div>
                        <div class="col-sm-6 mt_20 custom_col02">
                          <div>
                            <input id="is_vat" name="is_vat" type="checkbox">
                            <label for="is_vat">המחיר כולל מע"מ</label>
                          </div>
                        </div>
                    </div>
                      <div class="file_upload_box mt_20">
                        <label>העלאת תמונות</label>
                        <div class="preview-images-zone-wrap">
                        <div class="preview-images-zone">
                          <!-- התמונות ייכנסו לפה דרך JS -->
                        </div>

                  <div class="d-flex justify-content-between">
                    <div class="form-group">
                      <a href="javascript:void(0)" onclick="jQuery('#pro-image').click()">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/upload_icon.svg" alt="upload_icon"><span>תמונות נוספות</span>
                      </a>
                      <input type="file" id="pro-image" name="pro-image[]" multiple accept="image/*" style="cursor: pointer;">
                    </div>
                    
                    <button type="button" id="clear-all-images" class="clear_images_btn align-self-end p-2 mb-2" style="display: none;">
                    מחק את כל התמונות
                    </button>
                  </div>


                  </div>
                  <!-- <label class="mt_8">עד 10 תמונות</label> -->

                        <label class="mt_8">עד 10 תמונות</label>
                      </div>
                    </div>
                  </div>
                  <!-- מה יש בדירה -->
                  <div class="apartment_furniture_wrap mt_20">
                    <h3>מה יש בדירה</h3>
                    <div class="apartment_furniture_box">
                      <div class="apartment_furniture_item">
                        <input type="checkbox" id="check01" name="whats_inside[]" value="ריהוט">
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
                        <input type="checkbox" id="check04" name="whats_inside[]" value="מטבח כשר">
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
                        <input type="checkbox" id="check09" name="whats_inside[]" value="חניה">
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
                    <button type="submit" class="detail_payment_btn mt_20"> לפרסום הדירה למכירה ←</button>
                  </div>
                  <!-- כאן נגמר הטופס -->
                </form>
                <div id="post-limit-message" style="display: none;">
                  <div class="dirata-limit-wrapper">
                    <div class="dirata-limit-icon">
                      <img src="<?php echo get_template_directory_uri(); ?>/assets/img/notAllowedUploadApartment.svg" alt="limit icon" />
                    </div>
                    <div class="dirata-limit-text">
                      <h2>עברת את מכסת הפרסומים</h2>
                      <p>לפי סוג המנוי שלך, אינך יכול להעלות דירות נוספות כרגע.</p>
                      <a href="https://dirata.co.il/חשבון-רשום/your-profile/">שדרוג המנוי ←</a>
                    </div>
                  </div>
                </div>


              <!-- כאן נגמר הטופס -->
            </div>
            <!-- הבאנר בצד בעלות 150₪ -->
            <div class="col-lg-3 private_apartment_form_order2 private_apartment_form_ab">
            </div>
          </div>
        </div>
      </div>
      <!-- upload new apartment form end -->



      
      <!-- all js here -->
      <script src="<?php echo get_template_directory_uri(); ?>/assets/js/jquery-3.4.1.min.js"></script>
      <script src="<?php echo get_template_directory_uri(); ?>/assets/js/jquery-ui.js"></script>
      <script src="<?php echo get_template_directory_uri(); ?>/assets/js/plugins.js"></script>
      <script src="<?php echo get_template_directory_uri(); ?>/assets/js/main.js"></script>

  <script>
        
        jQuery(document).ready(function() {
          // document.getElementById('pro-image').addEventListener('change', readImage, false);
          jQuery('input[name="pro-image[]"]').on('change', readImage);
          
          // jQuery( ".preview-images-zone" ).sortable();
          
          jQuery(document).on('click', '.image-cancel', function() {
              let no = jQuery(this).data('no');
              jQuery(".preview-image.preview-show-"+no).remove();
          });
        });


        var num = 0;
        var galleryImages = [];

        function readImage(event) {
          console.log('🎯 נכנסתי ל־readImage');
          if (window.File && window.FileList && window.FileReader) {
            var files = event.target.files;
            var output = jQuery(".preview-images-zone");
            var existingImagesCount = jQuery('.preview-image').length;

            if (existingImagesCount + files.length > 10) {
              alert("ניתן להעלות עד 10 תמונות בלבד.");
              return;
            }

            for (let i = 0; i < files.length; i++) {
              var file = files[i];
              if (!file.type.match('image')) continue;

              var picReader = new FileReader();

              picReader.addEventListener('load', function (event) {
                var picFile = event.target;

                // בדיקה כמה תמונות קיימות כרגע
                var existingImagesCount = jQuery('.preview-image').length;
                var currentNum = existingImagesCount + 1;
                var isMainImage = existingImagesCount === 0;

                // עדכון hidden fields
                if (isMainImage) {
                  jQuery('#main_img').val(picFile.result);
                } else {
                  galleryImages.push(picFile.result);
                  jQuery('#img_gallery').val(JSON.stringify(galleryImages));
                }

                // יצירת בלוק HTML
                var html = `
                  <div class="preview-image preview-show-${currentNum}" style="width: ${isMainImage ? '100%' : '30%'}">
                    ${isMainImage ? '<span class="image_head">תמונה ראשית</span>' : ''}
                    <div class="image-cancel" data-no="${currentNum}">
                      <img src="${dirataImgBase}delete_icon.svg" alt="delete_icon">
                    </div>
                    <div class="image-zone">
                      <img id="pro-img-${currentNum}" src="${picFile.result}" />
                    </div>
                    <div class="tools-edit-image">
                      <a href="javascript:void(0)" data-no="${currentNum}" class="btn-edit-image">
                        <img src="${dirataImgBase}replacable_icon.svg" alt="replacable_icon">להחלפה
                      </a>
                    </div>
                  </div>`;


                output.append(html);
                jQuery('#clear-all-images').show();
              });

              picReader.readAsDataURL(file);
            }

            // jQuery("#pro-image").val('');
            jQuery('input[name="pro-image[]"]').val('');

          } else {
            console.log('Browser not support');
          }
        }
        jQuery(document).on('click', '#clear-all-images', function () {
            jQuery('.preview-images-zone').empty();
            jQuery('#main_img').val('');
            jQuery('#img_gallery').val('');
            galleryImages = [];

            jQuery('#clear-all-images').hide();
        });



      jQuery(document).on('click', '.image-cancel', function () {
          var no = jQuery(this).data('no');

          jQuery(".preview-image.preview-show-" + no).remove();

          // עדכון הגלריה מחדש
          galleryImages = [];
          jQuery('.preview-image').each(function (i) {
            var imgSrc = jQuery(this).find('img').first().attr('src');
            if (i !== 0) {
              galleryImages.push(imgSrc);
            }
          });
          jQuery('#img_gallery').val(JSON.stringify(galleryImages));
          
          // הגדרת תמונה ראשית חדשה אם צריך
          if (jQuery('.preview-image').length > 0) {
            var firstImage = jQuery('.preview-image').first();
            var newMainSrc = firstImage.find('img').first().attr('src');
            jQuery('#main_img').val(newMainSrc);

            // הסרה והוספה של תגית "תמונה ראשית"
            jQuery('.image_head').remove();
            firstImage.prepend('<span class="image_head">תמונה ראשית</span>');
            firstImage.css('width', '100%');
          }
          if (jQuery('.preview-image').length === 0) {
            jQuery('#clear-all-images').hide();
          }


          // כל שאר התמונות = שליש
          jQuery('.preview-image').not(':first').css('width', '30%');
      });





        // ⚙️ Mapbox - קבלת קואורדינטות מהכתובת
      async function getCoordinatesFromMapbox() {
        const citySelect = document.getElementById("a");
        const city = citySelect.options[citySelect.selectedIndex].text;
        const areaSelect = document.getElementById("b");
        const area = areaSelect.options[areaSelect.selectedIndex].text;
        const street = document.getElementById("c").value;
        const houseNum = document.getElementById("d").value;

        const fullAddress = `${street} ${houseNum}, ${city}, ${area}`;
        const url = `https://api.mapbox.com/geocoding/v5/mapbox.places/${encodeURIComponent(fullAddress)}.json?access_token=pk.eyJ1IjoieW9zZWZicm95ZXIiLCJhIjoiY20wMjZ6eXphMXd5ZzJtczI1bHV0d3RldSJ9.BcyqG0lbHzv--0oJdVbJAA`;


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

      document.addEventListener('DOMContentLoaded', function () {
      const priceInput = document.getElementById('h');

      if (priceInput) {
        priceInput.addEventListener('input', function () {
          let val = priceInput.value.replace(/[^0-9]/g, ''); // הסרת כל מה שאינו ספרה
          if (val === '') {
            priceInput.value = '';
            return;
          }

          // פורמט עם פסיקים
          priceInput.value = Number(val).toLocaleString('he-IL');
        });

        // אופציונלי – בפוקוס להסיר פורמט, שלא יפריע להקלדה
        priceInput.addEventListener('focus', function () {
          const val = priceInput.value.replace(/[^0-9]/g, '');
          priceInput.value = val;
        });

        // ביציאה מהשדה – להחזיר פורמט
        priceInput.addEventListener('blur', function () {
          const val = priceInput.value.replace(/[^0-9]/g, '');
          if (val) {
            priceInput.value = Number(val).toLocaleString('he-IL');
          }
        });
      }
      });
      document.addEventListener('DOMContentLoaded', function () {
        const priceLabel = document.querySelector('label[for="h"]');
        const saleOrRentRadios = document.querySelectorAll('input[name="sale_or_rent"]');

        function updatePriceLabel() {
          if (!priceLabel) return;
          const selected = document.querySelector('input[name="sale_or_rent"]:checked');
          const text = selected && selected.value === 'rent' ? 'מחיר חודשי' : 'מחיר';
          priceLabel.innerHTML = text + '<span>*</span>';
        }

        saleOrRentRadios.forEach(function(radio) {
          radio.addEventListener('change', updatePriceLabel);
        });

        updatePriceLabel();
      });
      document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('apartment-form');

        let isSubmitting = false;

        form.addEventListener('submit', function (e) {
          e.preventDefault();
          if (isSubmitting) return;
          isSubmitting = true;

          const formData = new FormData(form);
          formData.append('action', 'create_apartment_post');

          fetch(dirataVars.ajaxurl, {
            method: 'POST',
            body: formData
          })
            .then(res => res.json())
            .then(response => {
              isSubmitting = false;
              if (response.success) {
                form.innerHTML = `
                <div class="success-message">
                  <h3>${response.data.message}</h3>
                  <a href="${response.data.post_url}" target="_blank">לצפיה בדירה →</a><br>
                  <a href="${response.data.my_apartments_url}">לכל הדירות שלי →</a>
                </div>
              `;
              } else {
                alert('משהו השתבש, נסה שוב.');
              }
            })
            .catch(error => {
              isSubmitting = false;
              alert('שגיאה כללית בשליחה.');
            });
        });
      });
  </script>