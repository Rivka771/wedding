<?php
/**
 * Front Page Template for single article
 */
get_header(); ?>

<!-- single article page area start -->
<div class="single_article_wrapper">
  <div class="container">

    <!-- category image box -->
    <div class="category_img_box">
      <?php if (has_post_thumbnail()) : ?>
        <?php the_post_thumbnail('full', ['class' => 'category_img img-fluid']); ?>
      <?php else : ?>
        <img class="category_img img-fluid" src="<?php echo get_template_directory_uri(); ?>/assets/img/category_img.svg" alt="category_img">
      <?php endif; ?>

      <div class="category_img_content text-center">
        <img class="category_shape01" src="<?php echo get_template_directory_uri(); ?>/assets/img/category_shape01.svg" alt="category_shape01">
        <img class="category_shape02" src="<?php echo get_template_directory_uri(); ?>/assets/img/category_shape02.svg" alt="category_shape02">

        <?php
        // $category = get_the_category();
        // if (!empty($category)) {
          // echo '<p>' . esc_html($category[0]->name) . '</p>';
        // }
        ?>

        <h2 class="head_text02 font400 mt_5"><?php the_title(); ?></h2>
      </div>
    </div>

    <!-- article content -->
    <div class="article_box default_item">
      <?php the_content(); ?>
    </div>

    <!-- more articles section -->
    <div class="mt_55">
      <div class="text-right">
        <h2 class="head_text02">מאמרים נוספים</h2>
      </div>
      <!-- <div class="owl-carousel card_slider02">
        <?php
        $related_posts = new WP_Query([
          'post_type' => 'post',
          'posts_per_page' => 6,
          'post__not_in' => [get_the_ID()],
        ]);
        while ($related_posts->have_posts()) : $related_posts->the_post();
        ?>
          <div class="card_item04">
            <?php if (has_post_thumbnail()) : ?>
              <?php the_post_thumbnail('medium', ['class' => 'card_img']); ?>
            <?php else : ?>
              <img class="card_img" src="<?php echo get_template_directory_uri(); ?>/assets/img/card_img.svg" alt="card_img">
            <?php endif; ?>

            <div class="card_item04_content">
              <p class="font400 mt_30"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>
            </div>
          </div>
        <?php endwhile;
        wp_reset_postdata(); ?>
      </div> -->
      <div class="card_slider_wrapper">
        <div class="col-12 card_slider_order3">
                <div class="card_slider mt_30">
                    <?php
                    $args = array(
                          'post_type'      => 'post',
                          'posts_per_page' => 6,
                          'post__not_in'   => array(433 , get_the_ID()),
                    );
                    $guides_query = new WP_Query($args);

                    if ($guides_query->have_posts()) :
                          while ($guides_query->have_posts()) : $guides_query->the_post();
                            $thumb_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                            if (!$thumb_url) {
                                $thumb_url = get_template_directory_uri() . '/assets/img/card_img02.svg';
                            }
                            ?>
                            <div class="card_item02">
                                <figure class="m-0">
                                      <img src="<?php echo esc_url($thumb_url); ?>" alt="<?php the_title_attribute(); ?>">
                                </figure>
                                <div class="card_content02">
                                      <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                </div>
                            </div>
                            <?php
                          endwhile;
                          wp_reset_postdata();
                    else :
                          echo '<p>לא נמצאו מדריכים.</p>';
                    endif;
                    ?>
                </div>
              </div>

          </div>
        </div>
      </div>

  </div>
</div>
<!-- single article page area end -->

<script>
//01. card header active toggle class
$(".card-header").click(function () {
  $(this).parent().toggleClass("activeToggle").siblings().removeClass("activeToggle");
});

//02. card slider 02 slider start
function card_slider02_carouselInit() {
  $('.owl-carousel.card_slider02').owlCarousel({
    dots: false,
    loop: true,
    margin: 10,
    stagePadding: 0,
    autoWidth: true,
    autoplay: true,
    rtl: true,
    autoplayTimeout: 3000,
    smartSpeed: 3000,
    autoplayHoverPause: true,
  });
}
card_slider02_carouselInit();
</script>

<?php get_footer(); ?>
