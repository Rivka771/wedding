<?php
/**
 * Template Name: Success Published
 */

get_header();

// לוג של כל הפרמטרים שחזרו מהתשלום
if (!empty($_GET)) {
    error_log('[dirata][success_published] GET params: ' . print_r($_GET, true));
}

if (isset($_GET['ref'])) {
    $ref = sanitize_text_field($_GET['ref']);
    $statusCode = isset($_GET['statusCode']) ? intval($_GET['statusCode']) : -1;

    $args = [
        'post_type'   => 'apartment',
        'post_status' => 'waiting_payment',
        'meta_key'    => 'payment_reference',
        'meta_value'  => $ref,
        'numberposts' => 1
    ];

    $posts = get_posts($args);

    if ($posts) {
        $post_id = $posts[0]->ID;

        if ($statusCode === 0) { // הצלחה
            wp_update_post([
                'ID'          => $post_id,
                'post_status' => 'publish'
            ]);

            update_post_meta($post_id, 'payment_status', 'paid');
            update_post_meta($post_id, 'transaction_id', isset($_GET['transactionInternalNumber']) ? sanitize_text_field($_GET['transactionInternalNumber']) : '');
            update_post_meta($post_id, 'takbull_uniqId', isset($_GET['uniqId']) ? sanitize_text_field($_GET['uniqId']) : '');
            update_post_meta($post_id, 'takbull_token', isset($_GET['token']) ? sanitize_text_field($_GET['token']) : '');

            // הפניה לעמוד תודה / פרופיל
            wp_redirect(site_url('/profile-page'));
            exit;

        } else { 
            // כישלון תשלום
            update_post_meta($post_id, 'payment_status', 'failed');

            echo '<div class="container text-center my-5">';
            echo '<h2>❌ התשלום נכשל</h2>';
            echo '<p>אנא נסו שוב או פנו לתמיכה.</p>';
            echo '</div>';
        }
    }
}
?>

<?php get_footer(); ?>
