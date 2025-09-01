<?php
// require_once('../../../wp-load.php');
$apartment_id = intval($_GET['id'] ?? 0);
$main_img = get_field('main_img' , $apartment_id);
$img_gallery = get_field('img_gallery', $apartment_id); // אם זה אראי, אז ודאי שהשדה מוגדר ככה
$gallery_imgs = is_array($img_gallery) ? $img_gallery : explode(',', $img_gallery); // לשם גיבוי

// פה את יכולה לטעון מידע מהדירה לפי ID אם את רוצה
echo 'main_img>>>' .$main_img;
echo 'img_gallery>>>' .$img_gallery;

?>
 <!-- update details advertisement area start -->
 
 <div class="update_details_advertisement_wrap private_apartment_form_wrapper">
    <div class="close_icon"><i class="fal fa-times"></i></div>
    <div class="form_wrap text-right">
        <h2 class="private_apartment_form_head">עדכון פרטים לפרסום דירה למכירה:</h2>
        <div class="row custom_row02 mt_8"> 
                <input type="hidden" id="popup_apartment_id">
                <input type="hidden" id="main_img" name="main_img" value="<?php echo esc_attr($main_img); ?>">
                <input type="hidden" id="img_gallery" name="img_gallery" value="<?php echo esc_attr(json_encode($gallery_imgs)); ?>">

                
            <div class="col-sm-6 mt_20 custom_col02">
                <div class="input_box">
                    <label for="a">עיר<span>*</span></label>
                    <input id="a" type="text" placeholder="עיר הדירה שלך">
                </div>
            </div> 
            <div class="col-sm-6 mt_20 custom_col02">
                <div class="input_box">
                    <label for="b">אזור<span>*</span></label>
                    <input id="b" type="text" placeholder="שכונת הדירה שלך">
                </div>
            </div>
        </div>
        <div class="row custom_row02">
            <div class="col-md-9 col-sm-8 col-8 mt_20 custom_col02">
                <div class="input_box">
                    <label for="c">רחוב<span>*</span></label>
                    <input id="c" type="text" placeholder="רחוב הדירה שלך">
                </div>
            </div>
            <div class="col-md-3 col-sm-4 col-4 mt_20 custom_col02">
                <div class="input_box">
                    <label for="d">מס' בית<span>*</span></label>
                    <input id="d" type="text" placeholder="מספר הבית של הדירה שלך">
                </div>
            </div>
        </div>
        <div class="row custom_row02 mt_8">
            <div class="col-md-4 col-sm-6 col-4 mt_20 custom_col02">
                <div class="input_box">
                    <label for="e">קומה<span>*</span></label>
                    <input id="e" type="text" placeholder="0">
                </div>
            </div>
            <div class="col-md-4 col-sm-6 col-4 mt_20 custom_col02">
                <div class="input_box">
                    <label for="f">מס' חדרים<span>*</span></label>
                    <input id="f" type="text" placeholder="0">
                </div>
            </div>
            <div class="col-md-4 col-sm-6 col-4 mt_20 custom_col02">
                <div class="input_box">
                    <label for="g">גודל דירה (מ"ר)</label>
                    <input id="g" type="text" placeholder="0">
                </div>
            </div>
        </div>
        <div class="row custom_row02">
            <div class="col-md-3 col-sm-12 custom_col02">
                <div class="row custom_row02">
                    <div class="col-md-12 col-sm-6 mt_20 custom_col02">
                        <div class="input_box">
                            <label for="h">מחיר<span>*</span></label>
                            <input id="h" class="text-left pb_15" type="text" placeholder="0">
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-6 mt_20 custom_col02">
                        <div class="input_box">
                            <label for="i">תאריך כניסה<span>*</span></label>
                            <input id="i" type="text" placeholder="05/05/2025">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9 custom_col02 mt_20">
                <div class="input_box">
                    <label for="j">על הדירה:<span>*</span></label>
                    <textarea id="j" 
                    placeholder="תיאור על הדירה שלך"></textarea>
                </div>
            </div>
            <div class="col-12 custom_col02 mt_20">
                <div class="input_box">
                    <label for="k">אופציות והיתרי בניה</label>
                    <input id="k" type="text" placeholder="אופציות והיתרי בניה">
                </div>
                <div class="file_upload_box mt_20">
                    <label>העלאת תמונות</label>
                    <div class="preview-images-zone-wrap">
                        <div class="preview-images-zone">
                            <!-- <div class="preview-image preview-show-1">
                                <span class="image_head">תמונה ראשית</span>
                                <div class="image-cancel" data-no="1">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/delete_icon.svg" alt="delete_icon">
                                </div>
                                <div class="image-zone">
                                    <img id="pro-img-1" src="<?php echo get_template_directory_uri(); ?>/assets/img/upload_img01.svg" alt="upload_img01">
                                </div>
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
                            </div> -->
                            <?php if (!empty($main_img)): ?>
                            <div class="preview-image preview-show-1">
                                <span class="image_head">תמונה ראשית</span>
                                <div class="image-cancel" data-no="1" data-role="main"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/delete_icon.svg" alt="delete_icon"></div>
                                <div class="image-zone"><img id="pro-img-1" src="<?php echo esc_url($main_img); ?>" alt="main image"></div>
                                <div class="tools-edit-image"><a href="javascript:void(0)" data-no="1" class="btn-edit-image" data-role="main"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/replacable_icon.svg" alt="replacable_icon">להחלפה</a></div>
                            </div>
                            <?php endif; ?>

                            <?php
                            $index = 1;
                            // foreach ($gallery_imgs as $img_url):
                                foreach ($gallery_imgs as $img_id):
                                    $img_url = wp_get_attachment_url($img_id);
                                    // if (empty($img_url)) continue;
                                if (empty($img_url)) continue;
                            ?>
                            <div class="preview-image preview-show-<?php echo $index; ?>">
                                <div class="image-cancel" data-no="<?php echo $index; ?>" data-role="gallery"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/delete_icon.svg" alt="delete_icon"></div>
                                <div class="image-zone">
                                    <img id="pro-img-<?php echo $index; ?>" src="<?php echo esc_url($img_url); ?>" alt="gallery image">
                                </div>
                                <div class="tools-edit-image"><a href="javascript:void(0)" data-no="<?php echo $index; ?>" class="btn-edit-image" data-role="gallery"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/replacable_icon.svg" alt="replacable_icon">להחלפה</a></div>
                            </div>
                            <?php $index++; endforeach; ?>

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
        <a class="detail_payment_btn mt_20" id="save_apartment_btn"> עדכן ושמור ←</a>
    </div>
</div>
<!-- update details advertisement area end -->
<script>
jQuery(document).ready(function () {
  let editGalleryImages = [];

  jQuery(document).on('change', '#pro-image', function (event) {
    if (window.File && window.FileList && window.FileReader) {
      const files = event.target.files;
      const output = jQuery(".preview-images-zone");
      const existing = jQuery('.preview-image').length;

      if (existing + files.length > 10) {
        alert("ניתן להעלות עד 10 תמונות בלבד.");
        return;
      }

      for (let i = 0; i < files.length; i++) {
        const file = files[i];
        if (!file.type.match('image')) continue;

        const reader = new FileReader();
        reader.onload = function (e) {
          const imgSrc = e.target.result;
          const count = jQuery('.preview-image').length + 1;
          const isMain = count === 1;

          const html = `
          <div class="preview-image preview-show-${count}" style="width: ${isMain ? '100%' : '30%'}">
            ${isMain ? '<span class="image_head">תמונה ראשית</span>' : ''}
            <div class="image-cancel" data-no="${count}">
              <img src="${dirataImgBase}delete_icon.svg" alt="delete_icon">
            </div>
            <div class="image-zone"><img id="pro-img-${count}" src="${imgSrc}" /></div>
            <div class="tools-edit-image">
              <a href="javascript:void(0)" data-no="${count}" class="btn-edit-image">
                <img src="${dirataImgBase}replacable_icon.svg" alt="replacable_icon">להחלפה
              </a>
            </div>
          </div>
          `;
          output.append(html);

          if (isMain) {
            jQuery('#main_img').val(imgSrc);
          } else {
            editGalleryImages.push(imgSrc);
            jQuery('#img_gallery').val(JSON.stringify(editGalleryImages));
          }
        };
        reader.readAsDataURL(file);
      }

      jQuery('#pro-image').val('');
    } else {
      alert("הדפדפן שלך לא תומך בהעלאת תמונות");
    }
  });

  jQuery(document).on('click', '.image-cancel', function () {
    const no = jQuery(this).data('no');
    jQuery(".preview-image.preview-show-" + no).remove();

    // ריענון מחדש של התמונות
    editGalleryImages = [];
    jQuery('.preview-image').each(function (i) {
      const imgSrc = jQuery(this).find('img').first().attr('src');
      if (i === 0) {
        jQuery('#main_img').val(imgSrc);
        jQuery('.image_head').remove();
        jQuery(this).prepend('<span class="image_head">תמונה ראשית</span>');
        jQuery(this).css('width', '100%');
      } else {
        editGalleryImages.push(imgSrc);
        jQuery(this).css('width', '30%');
      }
    });
    jQuery('#img_gallery').val(JSON.stringify(editGalleryImages));
  });

  jQuery(document).on('click', '.btn-edit-image', function () {
    const no = jQuery(this).data('no');
    const $fileInput = jQuery('<input type="file" accept="image/*" style="display:none">');
    $fileInput.on('change', function (e) {
      const file = this.files[0];
      if (!file || !file.type.match('image')) return;
      const reader = new FileReader();
      reader.onload = function (e) {
        const newSrc = e.target.result;
        jQuery("#pro-img-" + no).attr('src', newSrc);

        if (no == 1) {
          jQuery('#main_img').val(newSrc);
        } else {
          editGalleryImages = [];
          jQuery('.preview-image').each(function (i) {
            if (i !== 0) {
              const src = jQuery(this).find('img').first().attr('src');
              editGalleryImages.push(src);
            }
          });
          jQuery('#img_gallery').val(JSON.stringify(editGalleryImages));
        }
      };
      reader.readAsDataURL(file);
    });
    $fileInput.trigger('click');
  });
});
</script>

