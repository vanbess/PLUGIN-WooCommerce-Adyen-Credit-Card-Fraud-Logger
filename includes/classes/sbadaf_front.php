<?php

/* Handles front-end functionality */
class sbadaf_front
{

    /**
     * Init
     */
    public static function init()
    {
        // register rest route - used to receive transaction comms from adyen
        add_action('rest_api_init', [__CLASS__, 'sbadaf_rest_route']);
    }

    /**
     * Rest route for comms from Adyen
     */
    public static function sbadaf_rest_route()
    {
        register_rest_route('sbadaf-notifications', 'check-adyencc-fraud', [
            'methods'  => 'POST',
            'callback' => [__CLASS__, 'sbadaf_adyen_log']
        ]);
    }

    /**
     * Function to be called when comms received via rest route
     */
    public static function sbadaf_adyen_log()
    {
        /* required to echo string [accepted] so that adyen knows the notifications endpoint is legit */
        print '[accepted]';

        /* since we're receiving a JSON object via HTTP Post we will need to get its contents using file_get_contents */
        $data = file_get_contents('php://input');

        /* now that the JSON object has been correctly parsed to a string we can decode it */
        $data_arr = json_decode($data, true);

        /** Test response from Adyen */
        // file_put_contents(SBADAF_PATH . 'sbadaf-adyen-comms-test.txt', print_r($data_arr, true));

        // get relevant user data for use in fraud log
        $order_no = $data_arr['notificationItems'][0]['NotificationRequestItem']['merchantReference'];
        $pmt_method = $data_arr['notificationItems'][0]['NotificationRequestItem']['paymentMethod'];
        $psp_ref = $data_arr['notificationItems'][0]['NotificationRequestItem']['pspReference'];
        $reason = $data_arr['notificationItems'][0]['NotificationRequestItem']['reason'];

        // get order id from order number
        $order_id = wc_seq_order_number_pro()->find_order_by_order_number($order_no);

        // get order data
        $order_data = new WC_Order($order_id);

        // get user ip and user agent
        if ($order_data && is_array($order_data) || is_object($order_data)) :
            $user_ip = $order_data->get_customer_ip_address();
            $user_agent = $order_data->get_customer_user_agent();
        endif;

        /**
         * Meta keys we will be inserting/checking:
         * sbadaf_fraud_ip
         * sbadaf_fraud_order_id
         * sbadaf_fraud_order_no
         * sbadaf_fraud_user_agent
         * sbadaf_fraud_psp_ref
         * sbadaf_fraud_reason
         * sbadaf_fraud_pmt_method
         * sbadaf_fraud_attempts
         */

        // check if fraud log already exists for detected ip
        $flogs = new WP_Query([
            'post_type' => 'fraud_log',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'meta_query' => [
                'relation' => 'OR',
                [
                    'key' => 'sbadaf_fraud_ip',
                    'value' => $user_ip,
                    'compare' => '='
                ],
                [
                    'key' => 'sbadaf_fraud_order_id',
                    'value' => $order_id,
                    'compare' => '='
                ]
            ]
        ]);

        // if log already exists, update it, else insert new
        if ($flogs->have_posts()) :
            while ($flogs->have_posts()) : $flogs->the_post();

                $attempts = get_post_meta(get_the_ID(), 'sbadaf_fraud_attempts', true);
                $attempts++;
                update_post_meta(get_the_ID(), 'sbadaf_fraud_attempts', $attempts);

            endwhile;
            wp_reset_postdata();

        // insert fraud log if none exists for given user IP/order ID
        else :
            wp_insert_post([
                'post_type' => 'fraud_log',
                'post_status' => 'publish',
                'meta_input' => [
                    'sbadaf_fraud_ip' => $user_ip,
                    'sbadaf_fraud_order_id' => $order_id,
                    'sbadaf_fraud_user_agent' => $user_agent,
                    'sbadaf_fraud_psp_ref' => $psp_ref,
                    'sbadaf_fraud_reason' => $reason,
                    'sbadaf_fraud_pmt_method' => $pmt_method,
                    'sbadaf_fraud_order_no' => $order_no,
                    'sbadaf_fraud_attempts' => 1
                ]
            ]);

        endif;
    }
}

sbadaf_front::init();
