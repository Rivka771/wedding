<?php
/**
 * Template Part: Additional Filters
 * This file contains the markup for the additional filters.
 */
?>


<?php
  // שליפת כל הדירות
  $apartments = get_posts(array(
      'post_type' => 'apartment',
      'posts_per_page' => -1,
      'fields' => 'ids',
  ));

  $prices = $rooms_list = $floors = $sizes = [];

  foreach ($apartments as $id) {
      $price = get_post_meta($id, 'price', true);
      $rooms = get_post_meta($id, 'rooms', true);
      $floor = get_post_meta($id, 'floor', true);
      $size = get_post_meta($id, 'size', true);

      if (is_numeric($price)) $prices[] = (float)$price;
      if ($rooms !== '' && !in_array($rooms, $rooms_list)) $rooms_list[] = $rooms;
      if (is_numeric($floor)) $floors[] = (int)$floor;
      if (is_numeric($size)) $sizes[] = (int)$size;
  }

  $min_price = !empty($prices) ? min($prices) : 1;
  $max_price = !empty($prices) ? max($prices) : 5530000;

  $min_floor = !empty($floors) ? min($floors) : 0;
  $max_floor = !empty($floors) ? max($floors) : 30;

  $min_size = !empty($sizes) ? min($sizes) : 20;
  $max_size = !empty($sizes) ? max($sizes) : 2000;

  sort($rooms_list);
?>

<div class="dropdown_menu dropdown-menu">
  <!-- Price Filter -->
  <div class="dropdown_menu_single_filter">
    <p>מחיר הדירה</p>
    <div class="input_main_wrap">
      <input type="number" class="price-min" name="price_min" min="<?php echo $min_price; ?>" max="<?php echo $max_price; ?>" value="<?php echo $min_price; ?>" />
      <input type="number" class="price-max" name="price_max" min="<?php echo $min_price; ?>" max="<?php echo $max_price; ?>" value="<?php echo $max_price; ?>" />
    </div>
    <div class="custom-price-slider" dir="rtl">
      <input type="range" min="<?php echo $min_price; ?>" max="<?php echo $max_price; ?>" value="<?php echo $min_price; ?>" class="price-slider-min">
      <input type="range" min="<?php echo $min_price; ?>" max="<?php echo $max_price; ?>" value="<?php echo $max_price; ?>" class="price-slider-max">
      <div class="price-slider-track"></div>
    </div>
  </div>

  <!-- Number of Rooms Filter -->
  <div class="dropdown_menu_single_filter">
    <p>מס’ חדרים</p>
    <div class="number_checkbox_wrap d-flex align-items-center flex-wrap justify-content-between">
      <?php foreach ($rooms_list as $room): ?>
        <div class="number_checkbox d-flex align-items-center">
          <input type="checkbox" name="rooms[]" value="<?php echo esc_attr($room); ?>" id="room-<?php echo esc_attr($room); ?>">
          <label for="room-<?php echo esc_attr($room); ?>"><?php echo esc_html($room); ?></label>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Floor Filter -->
  <div class="dropdown_menu_single_filter">
    <p>קומה</p>
    <div class="select_main_wrap">
      <select class="floor-min" id="floor-min" name="floor_min">
        <?php for ($i = $min_floor; $i <= $max_floor; $i++): ?>
          <option value="<?php echo $i; ?>" <?php selected($i, $min_floor); ?>><?php echo $i; ?></option>
        <?php endfor; ?>
      </select>
      <select class="floor-max" id="floor-max" name="floor_max">
        <?php for ($i = $min_floor; $i <= $max_floor; $i++): ?>
          <option value="<?php echo $i; ?>" <?php selected($i, $max_floor); ?>><?php echo $i; ?></option>
        <?php endfor; ?>
      </select>
    </div>
    <div class="custom-floor-slider" dir="rtl">
      <input type="range" min="<?php echo $min_floor; ?>" max="<?php echo $max_floor; ?>" value="<?php echo $min_floor; ?>" class="floor-slider-min">
      <input type="range" min="<?php echo $min_floor; ?>" max="<?php echo $max_floor; ?>" value="<?php echo $max_floor; ?>" class="floor-slider-max">
      <div class="floor-slider-track"></div>
    </div>
  </div>

  <!-- Size Filter -->
  <div class="dropdown_menu_single_filter">
    <p>גודל (מ"ר)</p>
    <div class="input_main_wrap">
      <input type="number" class="size-min" name="size_min" min="<?php echo $min_size; ?>" max="<?php echo $max_size; ?>" value="<?php echo $min_size; ?>" />
      <input type="number" class="size-max" name="size_max" min="<?php echo $min_size; ?>" max="<?php echo $max_size; ?>" value="<?php echo $max_size; ?>" />
    </div>
    <div class="custom-size-slider custom-price-slider" dir="rtl">
      <input type="range" min="<?php echo $min_size; ?>" max="<?php echo $max_size; ?>" value="<?php echo $min_size; ?>" class="size-slider-min">
      <input type="range" min="<?php echo $min_size; ?>" max="<?php echo $max_size; ?>" value="<?php echo $max_size; ?>" class="size-slider-max">
      <div class="size-slider-track price-slider-track"></div>
    </div>
  </div>
</div>
