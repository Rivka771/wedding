<?php
// Require the default PMPro Gateway Class.
require_once PMPRO_DIR . '/classes/gateways/class.pmprogateway.php';
//load classes init method
add_action('init', array(
    'PMProGateway_takbull',
    'init'
));

class PMProGateway_takbull extends PMProGateway
{
    function __construct($gateway = null)
    {
        $this->gateway = $gateway;
        return $this->gateway;
    }

    /**
     * Run on WP init
     *
     * @since 1.8
     */
    static function init()
    {
        add_filter('pmpro_gateways', array(
            'PMProGateway_takbull',
            'pmpro_gateways'
        ));

        //add fields to payment settings
        add_filter('pmpro_payment_options', array(
            'PMProGateway_takbull',
            'pmpro_payment_options'
        ));
        add_filter('pmpro_payment_option_fields', array(
            'PMProGateway_takbull',
            'pmpro_payment_option_fields'
        ), 10, 2);

        //code to add at checkout
        $gateway = pmpro_getGateway();
        if ($gateway == "takbull") {
            add_filter('pmpro_include_payment_information_fields', '__return_false');
            add_filter('pmpro_required_billing_fields', array(
                'PMProGateway_takbull',
                'pmpro_required_billing_fields'
            ));

            add_action('pmpro_checkout_after_form', array('PMProGateway_takbull', 'pmpro_checkout_preheader'));
            add_action('pmpro_checkout_after_form', array('PMProGateway_takbull', 'pmpro_checkout_after_form'));
        }
        add_filter('pmpro_checkout_before_change_membership_level', array(
            'PMProGateway_takbull',
            'pmpro_checkout_before_change_membership_level'
        ), 11, 2);

        add_action('wp_ajax_nopriv_pmpro_takbull_ipn_handler', array(
            'PMProGateway_takbull',
            'wp_ajax_pmpro_takbull_ipn_handler'
        ));
        add_action('wp_ajax_pmpro_takbull_ipn_handler', array(
            'PMProGateway_takbull',
            'wp_ajax_pmpro_takbull_ipn_handler'
        ));

        add_action('wp_ajax_nopriv_pmpro_takbull_get_redirect', array(
            'PMProGateway_takbull',
            'wp_ajax_pmpro_takbull_get_redirect'
        ));
        add_action('wp_ajax_pmpro_takbull_get_redirect', array(
            'PMProGateway_takbull',
            'wp_ajax_pmpro_takbull_get_redirect'
        ));
        add_filter('pmpro_gateways_with_pending_status', array(
            'PMProGateway_takbull',
            'pmpro_gateways_with_pending_status'
        ));


        add_action('wp_ajax_nopriv_pmpro_takbull_checkout_process', array(
            'PMProGateway_takbull',
            'pmpro_takbull_checkout_process'
        ));
        add_action('wp_ajax_pmpro_takbull_checkout_process', array(
            'PMProGateway_takbull',
            'pmpro_takbull_checkout_process'
        ));
    }



    /**
     * Send traffic to wp-admin/admin-ajax.php?action=pmpro_takbull_itn_handler to the ipn handler
     */
    static function wp_ajax_pmpro_takbull_ipn_handler()
    {

        require_once PMPRO_TAKBULLGATEWAY_DIR . '/includes/takbull_webhook.php';
        exit;
    }


    static function wp_ajax_pmpro_takbull_get_redirect()
    {
        global $pmpro_currency;
        global $user;
        $create_document = pmpro_getOption("create_invoice") == 1;
        $is_taxtable = pmpro_getOption("is_taxtable");
        $is_sub_date_same_as_charge = pmpro_getOption("is_sub_date_same_as_charge") == 1;
        $delay_to_next_period_after_initial_payment = pmpro_getOption("delay_to_next_period_after_initial_payment") == 1;
        $display_type = pmpro_getOption("display_type");

        if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce' . pmpro_getOption("gateway"))) {
            die('Busted!');
        }
        $req = $_POST['req'];
        parse_str($req['FormData'], $form_data);

        // error_log("FormData: " . print_r($req['FormData'], true));
        // error_log("form_data: " . print_r($form_data, true));
        $level_id = isset($form_data['pmpro_level']) ? intval($form_data['pmpro_level']) : 0;

        $username = isset($form_data['username']) ? sanitize_user($form_data['username']) : '';
        $email = isset($form_data['bemail']) ? sanitize_email($form_data['bemail']) : '';
        if (!empty($email)) {
            $existing_user = get_user_by('email', $email);
            if ($existing_user) {
                wp_send_json_error(array(
                    'message' => __('This email is already registered, please choose another one.', 'paid-memberships-pro')
                ));
                exit;
            }
        }
        // Try to find user by username or email
        $user = false;
        if ($username) {
            $user = get_user_by('login', $username);
        }
        if (!$user && $email) {
            $user = get_user_by('email', $email);
        }
        error_log("user: " . print_r($user, true));
        error_log("level_id: " . print_r($level_id, true));
        error_log("form_data: " . print_r($form_data, true));
        // If user is found, check if they already have the membership level

        if ($user && $level_id) {
            // Check if user already has this level
            $user_levels = pmpro_getMembershipLevelsForUser($user->ID);
            error_log("user_levels: " . print_r($user_levels, true));
            if (!empty($user_levels)) {
                foreach ($user_levels as $user_level) {
                    if ($user_level->id == $level_id) {
                        wp_send_json_error(array(
                            'message' => __('You already have this membership level.', 'paid-memberships-pro')
                        ));
                        exit;
                    }
                }
            }
        }


        $discount_code = isset($form_data['discount_code']) ? $form_data['discount_code'] : '';
        if (empty($discount_code) && isset($form_data['pmpro_discount_code'])) {
            $discount_code = $form_data['pmpro_discount_code'];
        }
        $pmpro_level = self::pmpro_getLevelAtTakbull($level_id, $discount_code);
        if (empty($pmpro_level->id)) {
            wp_redirect(pmpro_url("levels"));
            exit(0);
        }

        if (! empty($level) && $level->initial_payment <= 0 && $level->billing_amount <= 0 && $level->trial_amount <= 0) {
            wp_send_json_success("{
          'status':'success',
        }");
            exit;
        }

        $first_name = isset($form_data['bfirstname']) ? sanitize_text_field($form_data['bfirstname']) : '';
        $last_name = isset($form_data['blastname']) ? sanitize_text_field($form_data['blastname']) : '';
        $full_name = trim($first_name . ' ' . $last_name);
        if (empty($full_name)) {
            $first_name = isset($form_data['first_name']) ? sanitize_text_field($form_data['first_name']) : '';
            $last_name = isset($form_data['last_name']) ? sanitize_text_field($form_data['last_name']) : '';
        }
        $email = isset($form_data['bemail']) ? sanitize_email($form_data['bemail']) : '';
        $phone = isset($form_data['bphone']) ? sanitize_text_field($form_data['bphone']) : '';
        if (empty($phone)) {
            $phone = isset($form_data['user_phone_number']) ? sanitize_text_field($form_data['user_phone_number']) : '';
        }
        $request_data = $_POST['req'];
        $request_data['Language'] = get_locale();
        $amount = $pmpro_level->billing_amount;
        $customer = array(
            'CustomerFullName' => $full_name,
            'FirstName' => $first_name,
            'LastName' => $last_name,
            'Email' => $email,
            'PhoneNumber' => $phone,
        );
        $level3_data = array(
            // 'order_reference' => $order->code,
            'IPNAddress' => esc_url_raw(add_query_arg('action', 'pmpro_takbull_ipn_handler', admin_url('admin-ajax.php'))),
            'RedirectAddress' => esc_url_raw(add_query_arg('level', $level_id, pmpro_url("confirmation"))),
            'CancelReturnAddress' => esc_url_raw(pmpro_url("levels")),
            'Currency' => $pmpro_currency,
            'CustomerFullName' => $full_name,
            'Customer' => $customer,
            'OrderTotalSum' => $amount,
            'Language' => get_locale(),
            'DealType' => 1,
            'Comments' => $pmpro_level->name
        );

        $level3_data['InitialAmount'] = $pmpro_level->initial_payment;
        $level3_data['CreateDocument'] = $create_document;
        $level3_data['Taxtable'] = $is_taxtable == "1";
        $level3_data['DisplayType'] =  $display_type == 1 ? 'iframe' : 'redirect';
        $level3_data['PostProcessMethod'] =  $display_type == 1 ? 1 : 0;
        $level3_data['NumberOfPayments'] = $pmpro_level->billing_limit;


        $level3_data['Interval']         = $pmpro_level->cycle_number;
        switch ($pmpro_level->cycle_period) {
            case 'Day':
                $frequency = 1;
                $recurring_date = date('d M Y', strtotime('+' . $level3_data['Interval'] . ' day'));
                break;
            case 'Week':
                $frequency = 2;
                $recurring_date = date('d M Y', strtotime('+7 day'));
                break;
            case 'Month':
                if ($is_sub_date_same_as_charge) {
                    $level3_data['Interval']         = date('j');
                }
                $recurring_date = date('d M Y H:i', mktime(null, null, null, date('m'), $level3_data['Interval'], date('Y')));
                $frequency = 3;
                break;
            case 'Year':
                $frequency = 4;
                $recurring_date = date('d M Y', strtotime('+1 year'));
                break;
        }

        if (!empty($frequency)) {
            $regex = '/( [\x00-\x7F] | [\xC0-\xDF][\x80-\xBF] | [\xE0-\xEF][\x80-\xBF]{2} | [\xF0-\xF7][\x80-\xBF]{3} ) | ./x';
            $product_name = substr(strip_tags(preg_replace($regex, '$1', $pmpro_level->name)), 0, 200);
            $takbull_line_items = array(
                array(
                    // 'SKU' => (string)$order->code,
                    'Description' => $product_name,
                    'ProductName' => $product_name,
                    'Price' => $amount,
                    'Quantity' => 1,
                    'ProductType' => 1
                )
            );

            $level3_data['OrderTotalSum']  = $amount;
            $level3_data['DealType']         = 4;
            $level3_data['RecuringInterval']         = $frequency;

            while (strtotime($recurring_date) < strtotime(date("d M Y"))) {
                $recurring_date = date('d M Y H:i', strtotime($recurring_date . "+ 1 " . $pmpro_level->cycle_period));
            }

            $level3_data['RecuringDueDate']  = $recurring_date;
            if (!empty($order->membership_level->expiration_number) && !empty($pmpro_level->expiration_period)) {
                $level3_data['SubscriptionExpirationDate'] = date("d M Y", strtotime("+ " . $pmpro_level->expiration_number . " " . $pmpro_level->expiration_period, current_time("timestamp")));
                $level3_data['IsExpireSubscription']         = true;
            }
            $already = false;
            $current_user_ID = get_current_user_id();
            if (!empty($current_user_ID) && !empty($pmpro_level->id)) {

                // Check the current user's meta.
                $alreadyUsedLeveByUser = get_user_meta(get_current_user_id(), 'pmpro_trial_level_used', true);
                $already = $alreadyUsedLeveByUser == $pmpro_level->id;
            }

            if (pmpro_isLevelTrial($pmpro_level)) {
                $level3_data['RecuringDueDate'] = date("d M Y H:i", strtotime("+ " . $pmpro_level->cycle_number . " " . $pmpro_level->cycle_period, current_time("timestamp")));
            }

            if (function_exists('pmprosd_getDelay')) {
                // my_function is defined
                if (!empty($order->discount_code)) {
                    global $wpdb;
                    $code_id            = $wpdb->get_var("SELECT id FROM $wpdb->pmpro_discount_codes WHERE code = '" . esc_sql($discount_code) . "' LIMIT 1");
                    $subscription_delay = pmprosd_getDelay($level_id, $code_id);
                } else {
                    $subscription_delay = pmprosd_getDelay($level_id);
                }
            }
            if (!empty($subscription_delay) && !$already) {
                $level3_data['RecuringDueDate'] =  date("d M Y H:i", strtotime("+ " . $subscription_delay . ' days', current_time("timestamp")));
                if ($is_sub_date_same_as_charge && $pmpro_level->cycle_period == "Month") {
                    $level3_data['Interval'] =  date("j", strtotime("+ " . $subscription_delay . ' days', current_time("timestamp")));
                }
            } else {
                if ($delay_to_next_period_after_initial_payment && $pmpro_level->initial_payment > 0) {
                    $level3_data['RecuringDueDate'] = date("d M Y", strtotime("+ " . $pmpro_level->cycle_number . " " . $pmpro_level->cycle_period, current_time("timestamp")));
                }
            }
        } else {
            $regex = '/( [\x00-\x7F] | [\xC0-\xDF][\x80-\xBF] | [\xE0-\xEF][\x80-\xBF]{2} | [\xF0-\xF7][\x80-\xBF]{3} ) | ./x';
            // $description = substr(strip_tags(preg_replace($regex, '$1', $order->membership_level->description)), 0, 200);
            $product_name = substr(strip_tags(preg_replace($regex, '$1', $pmpro_level->name)), 0, 200);
            $takbull_line_items = array(
                array(
                    'SKU' => (string)$level_id,
                    'Description' => $product_name,
                    'ProductName' => $product_name,
                    'Price' => $pmpro_level->initial_payment,
                    'Quantity' => 1,
                )
            );
            $level3_data['OrderTotalSum']  = $pmpro_level->initial_payment;
        }
        $level3_data['Products'] = $takbull_line_items;

        $level3_data = apply_filters('pmpro_takbull_data_to_send', $level3_data, $pmpro_level);


        Takbull_API::set_api_secret(pmpro_getOption("apisecret"));
        Takbull_API::set_api_key(pmpro_getOption("apikey"));

        $response = Takbull_API::request(json_encode($level3_data), "api/ExtranalAPI/GetTakbullPaymentPageRedirectUrl");
        if (!empty($response->error)) {
            throw new Exception(print_r($response, true), $response
                ->error
                ->message);
        }
        $body = wp_remote_retrieve_body($response);
        wp_send_json_success($body);
    }

    /**
     * Filtering orders at checkout.
     *
     * @since 1.8
     */
    static function pmpro_checkout_order($morder)
    {
        return $morder;
    }

    /**
     * Make sure this gateway is in the gateways list
     *
     * @since 1.8
     */
    static function pmpro_gateways($gateways)
    {
        if (empty($gateways['takbull'])) $gateways['takbull'] = __('Takbull', 'paid-memberships-pro');

        return $gateways;
    }

    /**
     * Add Takbull to the list of allowed gateways.
     *
     * @return array
     */
    static function pmpro_gateways_with_pending_status($gateways)
    {
        $gateways[] = 'takbull';
        return $gateways;
    }

    /**
     * Get a list of payment options that the this gateway needs/supports.
     *
     * @since 1.8
     */
    static function getGatewayOptions()
    {
        $options = array(
            // 'sslseal',
            // 'nuclear_HTTPS',
            'apikey',
            'apisecret',
            'create_invoice',
            'is_taxtable',
            'is_sub_date_same_as_charge',
            'delay_to_next_period_after_initial_payment',
            'display_type',
            'currency',
            'is_to_cancel_sub_on_faile',
            'cancel_sub_on_failed_charge', // האם לבטל מנוי בחיוב כושל
            'takbull_logging'
        );

        return $options;
    }

    /**
     * Set payment options for payment settings page.
     *
     * @since 1.8
     */
    static function pmpro_payment_options($options)
    {
        //get options
        $takbull_options = PMProGateway_takbull::getGatewayOptions();

        //merge with others.
        $options = array_merge($takbull_options, $options);

        return $options;
    }

    /**
     * Display fields for this gateway's options.
     *
     * @since 1.8
     */
    static function pmpro_payment_option_fields($values, $gateway)
    {
?>
        <tr class="pmpro_settings_divider gateway  gateway_takbull " <?php if ($gateway != "takbull") {                                                                        ?>style="display: none;" <?php                                                                                                }                                                                                                    ?>>
            <td colspan="2">
                <hr />
                <h2 class="title"><?php esc_html_e('Takbull Settings', 'paid-memberships-pro'); ?></h2>
            </td>
        </tr>
        <tr class="gateway  gateway_takbull" <?php if ($gateway != "takbull") { ?>style="display: none;" <?php                                                                        }                                                                            ?>>
            <th scope="row" valign="top">
                <label for="apikey"><?php _e('API Key', 'paid-memberships-pro'); ?>:</label>
            </th>
            <td>
                <input type="text" id="apikey" name="apikey" value="<?php echo esc_attr($values['apikey']); ?>" class="regular-text code" />
            </td>
        </tr>
        <tr class="gateway  gateway_takbull" <?php if ($gateway != "takbull") { ?>style="display: none;" <?php } ?>>
            <th scope="row" valign="top">
                <label for="apisecret"><?php _e('API Secret', 'paid-memberships-pro'); ?>:</label>
            </th>
            <td>
                <input type="text" id="apisecret" name="apisecret" value="<?php echo esc_attr($values['apisecret']);                                                                            ?>" class="regular-text code" />
            </td>
        </tr>
        <tr class="gateway gateway_takbull" <?php if ($gateway != "takbull") { ?>style="display: none;" <?php } ?>>
            <th scope="row" valign="top">
                <label for="is_sub_date_same_as_charge"><?php _e('הגדר תאריך הוראה לאותו יום של רכישה', 'paid-memberships-pro');                                                        ?>
                    :</label>
            </th>
            <td>
                <select id="is_sub_date_same_as_charge" name="is_sub_date_same_as_charge">
                    <option value="0" <?php if (empty($values['is_sub_date_same_as_charge'])) { ?>selected="selected" <?php } ?>><?php _e('No', 'paid-memberships-pro'); ?></option>
                    <option value="1" <?php if (!empty($values['is_sub_date_same_as_charge'])) { ?>selected="selected" <?php } ?>><?php _e('Yes', 'paid-memberships-pro'); ?></option>
                </select>
            </td>
        </tr>
        <tr class="gateway gateway_takbull" <?php if ($gateway != "takbull") { ?>style="display: none;" <?php } ?>>
            <th scope="row" valign="top">
                <label for="delay_to_next_period_after_initial_payment"><?php _e('דחה את התשלום למחזור הבא אם מוגדר תשלום ראשוני', 'paid-memberships-pro'); ?>
                    :</label>
            </th>
            <td>
                <select id="delay_to_next_period_after_initial_payment" name="delay_to_next_period_after_initial_payment">
                    <option value="0" <?php if (empty($values['delay_to_next_period_after_initial_payment'])) { ?>selected="selected" <?php } ?>><?php _e('No', 'paid-memberships-pro'); ?></option>
                    <option value="1" <?php if (!empty($values['delay_to_next_period_after_initial_payment'])) { ?>selected="selected" <?php } ?>><?php _e('Yes', 'paid-memberships-pro'); ?></option>
                </select>
            </td>
        </tr>

        <tr class="gateway gateway_takbull" <?php
                                            if ($gateway != "takbull") {
                                            ?>style="display: none;" <?php
                                                                    }
                                                                        ?>>
            <th scope="row" valign="top">
                <label for="display_type"><?php
                                            _e('אופן הצגת שדות לתשלום', 'paid-memberships-pro');
                                            ?>
                    :</label>
            </th>
            <td>
                <select id="display_type" name="display_type">
                    <option value=1 <?php if ($values['display_type'] == 1) { ?>selected="selected" <?php } ?>><?php _e('Popup', 'paid-memberships-pro'); ?></option>
                    <option value=2 <?php if ($values['display_type'] == 2) { ?>selected="selected" <?php } ?>><?php _e('Redirect', 'paid-memberships-pro'); ?></option>
                </select>
            </td>
        </tr>
        <tr class="gateway gateway_takbull" <?php if ($gateway != "takbull") { ?>style="display: none;" <?php }                                                                        ?>>
            <th scope="row" valign="top">
                <label for="create_invoice"><?php _e('Create invoice', 'paid-memberships-pro'); ?>:</label>
            </th>
            <td>
                <select id="create_invoice" name="create_invoice">
                    <option value="0" <?php if (empty($values['create_invoice'])) { ?>selected="selected" <?php } ?>><?php _e('No', 'paid-memberships-pro');                                                                                                                                                                                                                                                                                                                                ?></option>
                    <option value="1" <?php if (!empty($values['create_invoice'])) { ?>selected="selected" <?php } ?>><?php _e('Yes', 'paid-memberships-pro');                                                                    ?></option>
                </select>
            </td>
        </tr>
        <tr class="gateway gateway_takbull" <?php if ($gateway != "takbull") { ?>style="display: none;" <?php } ?>>
            <th scope="row" valign="top">
                <label for="is_taxtable"><?php _e('Order include TAX', 'paid-memberships-pro'); ?> :</label>
            </th>
            <td>
                <select id="is_taxtable" name="is_taxtable">
                    <option value=0 <?php if (empty($values['is_taxtable'])) { ?> selected="selected" <?php } ?>><?php _e('No', 'paid-memberships-pro'); ?></option>
                    <option value=1 <?php if (!empty($values['is_taxtable'])) { ?>selected="selected" <?php } ?>><?php _e('Yes', 'paid-memberships-pro'); ?></option>
                </select>
            </td>
        </tr>
        <tr class="gateway gateway_takbull" <?php if ($gateway != "takbull") { ?>style="display: none;" <?php } ?>>
            <th scope="row" valign="top">
                <label for="is_to_cancel_sub_on_faile"><?php _e('In case of fail to charge, cancel subscription', 'paid-memberships-pro'); ?> :</label>
            </th>
            <td>
                <select id="is_to_cancel_sub_on_faile" name="is_to_cancel_sub_on_faile">
                    <option value=0 <?php if (empty($values['is_to_cancel_sub_on_faile'])) { ?> selected="selected" <?php } ?>><?php _e('No', 'paid-memberships-pro'); ?></option>
                    <option value=1 <?php if (!empty($values['is_to_cancel_sub_on_faile'])) { ?>selected="selected" <?php } ?>><?php _e('Yes', 'paid-memberships-pro'); ?></option>
                </select>
            </td>
        </tr>
        <tr class="gateway gateway_takbull" <?php if ($gateway != "takbull") { ?>style="display: none;" <?php } ?>>
            <th scope="row" valign="top">
                <label for="cancel_sub_on_failed_charge"><?php _e('האם לבטל מנוי בחיוב כושל', 'paid-memberships-pro'); ?> :</label>
            </th>
            <td>
                <select id="cancel_sub_on_failed_charge" name="cancel_sub_on_failed_charge">
                    <option value=0 <?php if (empty($values['cancel_sub_on_failed_charge'])) { ?> selected="selected" <?php } ?>><?php _e('לא', 'paid-memberships-pro'); ?></option>
                    <option value=1 <?php if (!empty($values['cancel_sub_on_failed_charge'])) { ?>selected="selected" <?php } ?>><?php _e('כן', 'paid-memberships-pro'); ?></option>
                </select>
            </td>
        </tr>
        <tr class="gateway  gateway_takbull" <?php if ($gateway != "takbull") { ?>style="display: none;" <?php } ?>>
            <th scope="row" valign="top">
                <label for="takbull_logging"><?php _e('Enable debuge log', 'paid-memberships-pro'); ?>:</label>
            </th>
            <td>
                <select id="takbull_logging" name="takbull_logging">
                    <option value=0 <?php if (empty($values['takbull_logging'])) { ?> selected="selected" <?php } ?>><?php _e('No', 'paid-memberships-pro'); ?></option>
                    <option value=1 <?php if (!empty($values['takbull_logging'])) { ?> selected="selected" <?php } ?>><?php _e('Yes', 'paid-memberships-pro'); ?></option>
                </select>
            </td>
        </tr>
        <script>
            //trigger the payment gateway dropdown to make sure fields show up correctly
            jQuery(document).ready(function() {
                pmpro_changeGateway(jQuery('#gateway').val());
            });
        </script>
<?php
    }

    static function pmpro_include_billing_address_fields($include)
    {
        return $include;
    }

    static function pmpro_required_billing_fields($fields)
    {
        unset($fields['bstate']);
        unset($fields['bcountry']);
        unset($fields['baddress1']);
        unset($fields['bemail']);
        unset($fields['CardType']);
        unset($fields['AccountNumber']);
        unset($fields['ExpirationMonth']);
        unset($fields['ExpirationYear']);
        unset($fields['CVV']);
        return $fields;
    }

    static function pmpro_checkout_confirmed($pmpro_confirmed)
    {
        Pmpro_Takbull_Logger::log("Takbull: pmpro_checkout_confirmed");
    }

    static function pmpro_checkout_before_change_membership_level($user_id, $morder)
    {
        global $wpdb, $discount_code_id;
        //if no order, no need to pay
        Pmpro_Takbull_Logger::log('Request pmpro_checkout_before_change_membership_level order:' . json_encode($morder));
        if (empty($morder)) return;
        // bail if the current gateway is not set to Takbull.
        if ('takbull' != $morder->gateway) {
            return;
        }
        $morder->user_id = $user_id;
        $morder->saveOrder();
        do_action("pmpro_before_send_to_takbull", $user_id, $morder);
        $display_type = pmpro_getOption("display_type");
        if ($display_type == 1) {
            return;
        }
        $endpoint =  $morder
            ->Gateway
            ->sendToTakbull($morder);
        wp_redirect($endpoint);
        exit;
    }

    function getDataToSend($order)
    {
        global $pmpro_currency;
        global $user;
        $this->create_document = pmpro_getOption("create_invoice") == 1;
        $this->is_taxtable = pmpro_getOption("is_taxtable");
        $is_sub_date_same_as_charge = pmpro_getOption("is_sub_date_same_as_charge") == 1;
        $delay_to_next_period_after_initial_payment = pmpro_getOption("delay_to_next_period_after_initial_payment") == 1;
        $display_type = pmpro_getOption("display_type");

        if (empty($order->code)) $order->code = $order->getRandomCode();

        $order->status = 'pending';
        $order->payment_transaction_id = $order->code;
        $order->subscription_transaction_id = $order->code;
        $order->saveOrder();

        $first_name    = pmpro_getParam('bfirstname', 'REQUEST');
        $last_name    = pmpro_getParam('blastname', 'REQUEST');
        $baddress1    = pmpro_getParam('baddress1', 'REQUEST');
        $baddress2    = pmpro_getParam('baddress2',  'REQUEST');
        $bcity        = pmpro_getParam('bcity', 'REQUEST');
        $bstate        = pmpro_getParam('bstate', 'REQUEST');
        $bzipcode        = pmpro_getParam('bzipcode', 'REQUEST');
        $bcountry        = pmpro_getParam('bcountry', 'REQUEST');
        $bphone        = pmpro_getParam('bphone', 'REQUEST');
        $bemail        = pmpro_getParam('bemail', 'REQUEST');
        $name = trim(($first_name ?? '') . ' ' . ($last_name ?? ''));

        if (!empty($order->Email)) {
            $email = $order->Email;
        } else {
            $email = "";
        }
        if (empty($email) && !empty($user->ID) && !empty($user->user_email)) {
            $email = $user->user_email;
        } elseif (empty($email)) {
            // $email = "No Email";
            if (isset($_REQUEST['bemail']))
                $email = sanitize_email($_REQUEST['bemail']);
            else
                $email = "";
        }
        $amount = $order->PaymentAmount;
        $amount_tax = $order->getTaxForPrice($amount);
        $amount = pmpro_round_price((float)$amount + (float)$amount_tax);
        $customer = array(
            'CustomerFullName' => $name,
            'FirstName' => $order->FirstName,
            'LastName' => $order->LastName,
            'Email' => $email,
            'PhoneNumber' => $order->billing->phone,
            'Address' => array(
                'Address1' => $baddress1,
                'Address2' => $baddress2,
                'City' => $bcity,
                'Country' => $bcountry,
                'Zip' => $bzipcode,
            )
        );

        $level3_data = array(
            'order_reference' => $order->code,
            'IPNAddress' => esc_url_raw(add_query_arg('action', 'pmpro_takbull_ipn_handler', admin_url('admin-ajax.php'))),
            'RedirectAddress' => esc_url_raw(add_query_arg('level', $order->membership_level->id, pmpro_url("confirmation"))),
            'CancelReturnAddress' => esc_url_raw(pmpro_url("levels")),
            'Currency' => $pmpro_currency,
            'CustomerFullName' => $name,
            'Customer' => $customer,
            'OrderTotalSum' => $amount,
            'TaxAmount' => $amount_tax,
            'Language' => get_locale(),
            'DealType' => 1,
            'Comments' => $order->membership_level->name
        );

        $level3_data['InitialAmount'] = $order->InitialPayment;
        $level3_data['CreateDocument'] = $this->create_document;
        $level3_data['Taxtable'] = $this->is_taxtable == "1";
        $level3_data['DisplayType'] =  $display_type == 1 ? 'iframe' : 'redirect';
        $level3_data['PostProcessMethod'] =  $display_type == 1 ? 1 : 0;
        $level3_data['NumberOfPayments'] = $order->membership_level->billing_limit;


        $level3_data['Interval']         = $order->BillingFrequency;
        switch ($order->BillingPeriod) {
            case 'Day':
                $frequency = 1;
                $recurring_date = date('d M Y', strtotime('+' . $level3_data['Interval'] . ' day'));
                break;
            case 'Week':
                $frequency = 2;
                $recurring_date = date('d M Y', strtotime('+7 day'));
                break;
            case 'Month':
                if ($is_sub_date_same_as_charge) {
                    $level3_data['Interval']         = date('j');
                }
                $recurring_date = date('d M Y H:i', mktime(null, null, null, date('m'), $level3_data['Interval'], date('Y')));
                $frequency = 3;
                break;
            case 'Year':
                $frequency = 4;
                $recurring_date = date('d M Y', strtotime('+1 year'));
                break;
        }

        if (!empty($frequency)) {
            $regex = '/( [\x00-\x7F] | [\xC0-\xDF][\x80-\xBF] | [\xE0-\xEF][\x80-\xBF]{2} | [\xF0-\xF7][\x80-\xBF]{3} ) | ./x';
            $product_name = substr(strip_tags(preg_replace($regex, '$1', $order->membership_level->name)), 0, 200);
            $takbull_line_items = array(
                array(
                    'SKU' => (string)$order->code,
                    'Description' => $product_name,
                    'ProductName' => $product_name,
                    'Price' => $amount,
                    'Quantity' => 1,
                    'ProductType' => 1
                )
            );

            $level3_data['OrderTotalSum']  = $amount;
            $level3_data['DealType']         = 4;
            $level3_data['RecuringInterval']         = $frequency;

            while (strtotime($recurring_date) < strtotime(date("d M Y"))) {
                $recurring_date = date('d M Y H:i', strtotime($recurring_date . "+ 1 " . $order->BillingPeriod));
            }

            $level3_data['RecuringDueDate']  = $recurring_date;
            if (!empty($order->membership_level->expiration_number) && !empty($order->membership_level->expiration_period)) {
                $level3_data['SubscriptionExpirationDate'] = date("d M Y", strtotime("+ " . $order->membership_level->expiration_number . " " . $order->membership_level->expiration_period, current_time("timestamp")));
                $level3_data['IsExpireSubscription']         = true;
            }
            $already = false;
            $current_user_ID = get_current_user_id();
            if (!empty($current_user_ID) && !empty($order->membership_level->id)) {

                // Check the current user's meta.
                $alreadyUsedLeveByUser = get_user_meta(get_current_user_id(), 'pmpro_trial_level_used', true);
                $already = $alreadyUsedLeveByUser == $order->membership_level->id;
            }

            if (pmpro_isLevelTrial($order->membership_level)) {
                $level3_data['RecuringDueDate'] = date("d M Y H:i", strtotime("+ " . $order->BillingFrequency . " " . $order->BillingPeriod, current_time("timestamp")));
            }

            if (function_exists('pmprosd_getDelay')) {
                // my_function is defined
                if (!empty($order->discount_code)) {
                    global $wpdb;
                    $code_id            = $wpdb->get_var("SELECT id FROM $wpdb->pmpro_discount_codes WHERE code = '" . esc_sql($order->discount_code) . "' LIMIT 1");
                    $subscription_delay = pmprosd_getDelay($order->membership_id, $code_id);
                } else {
                    $subscription_delay = pmprosd_getDelay($order->membership_id);
                }
            }
            if (!empty($subscription_delay) && !$already) {
                $level3_data['RecuringDueDate'] =  date("d M Y H:i", strtotime("+ " . $subscription_delay . ' days', current_time("timestamp")));
                if ($is_sub_date_same_as_charge && $order->BillingPeriod == "Month") {
                    $level3_data['Interval'] =  date("j", strtotime("+ " . $subscription_delay . ' days', current_time("timestamp")));
                }
            } else {
                if ($delay_to_next_period_after_initial_payment && $order->InitialPayment > 0) {
                    $level3_data['RecuringDueDate'] = date("d M Y", strtotime("+ " . $order->BillingFrequency . " " . $order->BillingPeriod, current_time("timestamp")));
                }
            }
        } else {
            $regex = '/( [\x00-\x7F] | [\xC0-\xDF][\x80-\xBF] | [\xE0-\xEF][\x80-\xBF]{2} | [\xF0-\xF7][\x80-\xBF]{3} ) | ./x';
            // $description = substr(strip_tags(preg_replace($regex, '$1', $order->membership_level->description)), 0, 200);
            $product_name = substr(strip_tags(preg_replace($regex, '$1', $order->membership_level->name)), 0, 200);
            $takbull_line_items = array(
                array(
                    'SKU' => (string)$order->code,
                    'Description' => $product_name,
                    'ProductName' => $product_name,
                    'Price' => $order->InitialPayment,
                    'Quantity' => 1,
                )
            );
            $level3_data['OrderTotalSum']  = $order->InitialPayment;
        }
        $level3_data['Products'] = $takbull_line_items;
        return $level3_data;
    }
    function sendToTakbull(&$order)
    {
        try {
            Takbull_API::set_api_secret(pmpro_getOption("apisecret"));
            Takbull_API::set_api_key(pmpro_getOption("apikey"));
            $level3_data = $this->getDataToSend($order);
            $level3_data = apply_filters('pmpro_takbull_data_to_send', $level3_data, $order);
            $response = Takbull_API::request(json_encode($level3_data), "api/ExtranalAPI/GetTakbullPaymentPageRedirectUrl");
            if (!empty($response->error)) {
                throw new Exception(print_r($response, true), $response
                    ->error
                    ->message);
            }
            $body = wp_remote_retrieve_body($response);
            if ($body->responseCode != 0) {
                throw new Exception(print_r($response, true), __($body->description, 'PMPRO-gateway-takbull'));
            }
            $endpoint = Takbull_API::get_redirecr_order_api() . "?orderUniqId=" . $body->uniqId;
            return $endpoint;
            // wp_redirect($endpoint);
            // exit;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    static function pmpro_checkout_preheader()
    {
        global $gateway, $pmpro_level, $current_user, $pmpro_requirebilling, $pmpro_pages, $pmpro_currency;
        $default_gateway = pmpro_getOption("gateway");
        $display_type = pmpro_getOption("display_type");
        if ($display_type != 1) {
            return;
        }
        if ($gateway == "takbull" || $default_gateway == "takbull") {
            wp_register_script('takbull_bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js');
            wp_enqueue_script('takbull_bootstrap');

            // CSS
            wp_register_style('takbull_bootstrap', plugin_dir_url(__DIR__) . '/css/modal.css');
            wp_enqueue_style('takbull_bootstrap');
            if (!function_exists('pmpro_takbull_javascript')) {

                $localize_vars = array(
                    'data' => array(
                        'url' => admin_url('admin-ajax.php'),
                        'nonce' => wp_create_nonce('ajax-nonce' . pmpro_getOption("gateway")),
                        'action' => 'pmpro_takbull_get_redirect',
                        'pmpro_require_billing' => $pmpro_requirebilling,
                        'redirect_url' => Takbull_API::get_redirecr_order_api() . "?orderUniqId=",
                        'order_reference' => wp_create_nonce('takbull_order_ref' . $pmpro_level->id)
                    )
                );

                wp_register_script(
                    'pmpro_takbull',
                    plugin_dir_url(__DIR__) . '/js/pmpro-takbull.js',
                    array('jquery'),
                    PMPRO_VERSION
                );
                wp_localize_script('pmpro_takbull', 'pmproTakbullVars', $localize_vars);
                wp_enqueue_script('pmpro_takbull');
            }
        }
    }

    function process(&$order)
    {
        // Pmpro_Takbull_Logger::log("process Order takbull hit: " . print_r($order, true));
        Pmpro_Takbull_Logger::log("process Order _REQUEST hit: " . print_r($_REQUEST, true));

        try {
            if (empty($order->code)) $order->code = $order->getRandomCode();
            $order->payment_type = "Takbull";
            $order->status = "pending";

            if (!empty($_REQUEST['subscription_id'])) {
                $uniqId = $_REQUEST['subscription_id'];
            }

            if (!empty($_REQUEST['InternalCode'])) {
                $InternalCode = $_REQUEST['InternalCode'];
            }
            $order->saveOrder();


            $display_type = pmpro_getOption("display_type");
            if ($display_type == 1) {
                if ($InternalCode != 0) {
                    return false;
                } else {
                    $order->payment_transaction_id      = $uniqId;
                    $order->subscription_transaction_id = $uniqId;
                    $order->status = 'success'; // We have confirmed that and thats the reason we are here.
                    $order->saveOrder();
                    return true;
                }
            }
            // error_log("process Order takbull hit: " . print_r($order, true));
            return true;
        } catch (\Throwable $th) {
            Pmpro_Takbull_Logger::log('Request ex : ' . print_r($th, true));
            throw $th;
        }
    }

    static function pmpro_checkout_after_form()
    {
        $display_type = pmpro_getOption("display_type");
        if ($display_type != 1) {
            return;
        }
        echo '
        <div class="modal fade" id="takbull_payment_popup" tabindex="-1" role="dialog"  data-backdrop="false">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">                  
                    <div class="modal-body">
                    <iframe id="wc_takbull_iframe" name="wc_takbull_iframe" width="100%" height="620px" style="border: 0;"></iframe>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">' . __('Close', 'takbull-gateway') . '</button>                                    
                    </div>
                </div>
             </div>
        </div>
     ';
    }


    function cancel(&$order)
    {
        global $wpdb, $gateway_environment, $logstr;
        try {
            Pmpro_Takbull_Logger::log("cancel Order takbull hit");
            if (!empty($order->payment_transaction_id)) {
                $uniqId = $wpdb->get_var("SELECT payment_transaction_id FROM $wpdb->pmpro_membership_orders WHERE payment_transaction_id = '" . $order->payment_transaction_id . "' ORDER BY id DESC LIMIT 1");
                do_action("hook_before_subscription_cancel_takbull", $order);
                Pmpro_Takbull_Logger::log("cancel Order takbull process: " . $uniqId);
                if (!empty($_POST['payment_status']) && $_POST['payment_status'] == 'CANCELLED') {
                    Pmpro_Takbull_Logger::log("cancel Order takbull already canceled: ");
                    return true;
                }
                Takbull_API::set_api_secret(pmpro_getOption("apisecret"));
                Takbull_API::set_api_key(pmpro_getOption("apikey"));
                $response = Takbull_API::get_request("api/ExtranalAPI/CancelSubscription?uniqId=" . $uniqId);
                Pmpro_Takbull_Logger::log("cancel Order takbull process: " . json_encode($response));

                if (!empty($response->error)) {
                    throw new WC_Takbull_Exception(print_r($response, true), $response->error->message);
                }
                $body = wp_remote_retrieve_body($response);
                if ($body->internalCode != 0) {
                    Pmpro_Takbull_Logger::log('Response' . print_r($response, true) . ' Description:: ' . __($body->internalDescription, 'takbull-gateway'));
                    return false;
                } else {
                    $order->updateStatus('cancelled');
                    return true;
                }
            } else {
                Pmpro_Takbull_Logger::log("cancel Order takbull no tra: ");
            }
            return false;
        } catch (Exception $e) {
            error_log($e->getMessage());
            Pmpro_Takbull_Logger::log("cancel Order Exception:" . $e->getMessage());
        }
    }

    function cancel_subscription($subscription)
    {
        global $wpdb;
        Pmpro_Takbull_Logger::log("cancel_subscription takbull hit " . print_r($subscription, true));
        $uniqId = $wpdb->get_var(
            "SELECT payment_transaction_id FROM $wpdb->pmpro_membership_orders WHERE subscription_transaction_id = '" .
                $subscription->get_subscription_transaction_id() . "' ORDER BY id DESC LIMIT 1"
        );
        do_action("hook_before_subscription_cancel_takbull", $subscription);
        Pmpro_Takbull_Logger::log("cancel Order takbull process: " . $uniqId);
        Takbull_API::set_api_secret(pmpro_getOption("apisecret"));
        Takbull_API::set_api_key(pmpro_getOption("apikey"));
        $response = Takbull_API::get_request("api/ExtranalAPI/CancelSubscription?uniqId=" . $uniqId);
        Pmpro_Takbull_Logger::log("cancel Order takbull process: " . json_encode($response));

        if (!empty($response->error)) {
            throw new WC_Takbull_Exception(print_r($response, true), $response->error->message);
        }
        $body = wp_remote_retrieve_body($response);
        if ($body->internalCode != 0) {
            Pmpro_Takbull_Logger::log('Response' . print_r($response, true) . ' Description:: ' . __($body->internalDescription, 'takbull-gateway'));
            return false;
        } else {
            return true;
        }
        return false;
    }

    function getSubscriptionStatus(&$order)
    {
        Pmpro_Takbull_Logger::log("Takbull: getSubscriptionStatus");
    }






    static function pmpro_getLevelAtTakbull($level_id = null, $discount_code = null)
    {
        global $pmpro_level, $wpdb, $post;

        // Reset $pmpro_level global.
        $pmpro_level = null;

        // Default to level passed in via URL.
        if (empty($level_id) && ! empty($_REQUEST['pmpro_level'])) {
            $level_id = intval($_REQUEST['pmpro_level']);
        }

        // If we don't have a level, check the legacy 'level' request parameter.
        if (empty($level_id) && ! empty($_REQUEST['level'])) {
            // TODO: We may want to show a message here that the level parameter is deprecated.
            $level_id = intval($_REQUEST['level']);
        }

        // If we still don't have a level yet, check for a default level in the custom fields for this post.
        if (empty($level_id) && ! empty($post)) {
            $level_id = intval(get_post_meta($post->ID, 'pmpro_default_level', true));
        }

        // If we still don't have a level, use the default level.
        if (empty($level_id)) {
            $all_levels = pmpro_getAllLevels(false, false);

            if (! empty($all_levels)) {
                // Get lowest level ID.
                $default_level =  min(array_keys($all_levels));
            } else {
                $default_level = null;
            }

            $level_id = apply_filters('pmpro_default_level', intval($default_level));
        }

        // If we still don't have a level, bail.
        if (empty($level_id) || intval($level_id) < 1) {
            return;
        }

        // default to discount code passed in via URL.
        if (empty($discount_code) && ! empty($_REQUEST['pmpro_discount_code'])) {
            $discount_code = preg_replace('/[^A-Za-z0-9\-]/', '', sanitize_text_field($_REQUEST['pmpro_discount_code']));
        }

        // If we still don't have a discount code, check the legacy 'discount_code' request parameter.
        if (empty($discount_code) && ! empty($_REQUEST['discount_code'])) {
            $discount_code = preg_replace('/[^A-Za-z0-9\-]/', '', sanitize_text_field($_REQUEST['discount_code']));
        }

        // If we still don't have a discount code, add a filter to let other plugins add one.
        if (empty($discount_code)) {
            $discount_code = apply_filters('pmpro_default_discount_code', null, $level_id);
        }

        // If we are using a discount code, check it and get the level.
        // error_log("Checking discount code: " . $discount_code);
        if (! empty($level_id) && ! empty($discount_code)) {
            // error_log("Checking discount code: " . $discount_code);
            $discount_code_id = $wpdb->get_var("SELECT id FROM $wpdb->pmpro_discount_codes WHERE code = '" . esc_sql($discount_code) . "' LIMIT 1");

            // check code
            $code_check = pmpro_checkDiscountCode($discount_code, $level_id, true);
            if ($code_check[0] != false) {
                $sqlQuery    = "SELECT l.id, cl.*, l.name, l.description, l.allow_signups, l.confirmation FROM $wpdb->pmpro_discount_codes_levels cl LEFT JOIN $wpdb->pmpro_membership_levels l ON cl.level_id = l.id LEFT JOIN $wpdb->pmpro_discount_codes dc ON dc.id = cl.code_id WHERE dc.code = '" . esc_sql($discount_code) . "' AND cl.level_id = '" . esc_sql($level_id) . "' LIMIT 1";
                $pmpro_level = $wpdb->get_row($sqlQuery);

                // if the discount code doesn't adjust the level, let's just get the straight level
                if (empty($pmpro_level)) {
                    $pmpro_level = $wpdb->get_row("SELECT * FROM $wpdb->pmpro_membership_levels WHERE id = '" . esc_sql($level_id) . "' LIMIT 1");
                }

                // filter adjustments to the level
                $pmpro_level->code_id       = $discount_code_id;
                $pmpro_level->discount_code = $discount_code;
                $pmpro_level                = apply_filters('pmpro_discount_code_level', $pmpro_level, $discount_code_id);
            } else {
                // error with discount code, we want to halt checkout
                pmpro_setMessage($code_check[1], 'pmpro_error');
            }
        }

        // If we don't have a level object yet, pull it from the database.
        if (empty($pmpro_level) && ! empty($level_id)) {
            $pmpro_level = $wpdb->get_row("SELECT * FROM $wpdb->pmpro_membership_levels WHERE id = '" . esc_sql($level_id) . "' AND allow_signups = 1 LIMIT 1");
        }

        // Filter the level (for upgrades, etc).
        $pmpro_level = apply_filters('pmpro_checkout_level', $pmpro_level);

        return $pmpro_level;
    }
}
