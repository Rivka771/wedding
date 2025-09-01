<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

//16. שיחת אימות (OTP) להקראת קוד קולי, ללקוח שנרשם בטלפון
function yedaphone_generate_otp_code(WP_REST_Request $request) {
    $phone = $request->get_param('phone');

    // חיפוש יוזר לפי מספר טלפון
    $user_query = new WP_User_Query(array(
        'meta_key'     => 'phone',
        'meta_value'   => $phone,
        'meta_compare' => '=',
        'number'       => 1,
        'fields'       => 'ID',
    ));
    $users = $user_query->get_results();

    if (empty($users)) {
        return new WP_REST_Response([
            'status' => false,
            'message' => 'Customer does not exist'
        ], 404);
    }

    $user_id = $users[0];

    // יצירת קוד OTP רנדומלי (6 ספרות)
    $otp_code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    $timestamp = current_time('mysql'); // חותמת זמן בפורמט MySQL (לפי זמן האתר)

    // שמירת ה־OTP והזמן למטה־של המשתמש
    update_user_meta($user_id, 'otp_code', $otp_code);
    update_user_meta($user_id, 'otp_created_at', $timestamp);

    return new WP_REST_Response([
        'status'         => true,
        'otp_code'       => $otp_code,
        'otp_created_at' => $timestamp
    ], 200);
}
