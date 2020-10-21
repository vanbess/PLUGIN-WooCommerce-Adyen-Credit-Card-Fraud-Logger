<?php

/* Handles backend functionality */

class sbadaf_admin
{

    /** traits */
    use sbadaf_admin_cols, sbadaf_fraud_log_metaboxes;

    /** init */
    public static function init()
    {
        // init admin col data and metabox data
        sbadaf_admin_cols::init();
        sbadaf_fraud_log_metaboxes::init();

        // enqueue css
        add_action('admin_head', [__CLASS__, 'sbadaf_admin_scripts']);

        // register pll strings
        pll_register_string('sbadaf_1', 'User IP Address:');
        pll_register_string('sbadaf_2', 'Order ID:');
        pll_register_string('sbadaf_3', 'Order number:');
        pll_register_string('sbadaf_4', 'Checkout attempts:');
        pll_register_string('sbadaf_5', 'User agent:');
        pll_register_string('sbadaf_6', 'PSP Reference:');
        pll_register_string('sbadaf_7', 'Adyen\'s reason for declining transaction:');
        pll_register_string('sbadaf_8', 'Payment method:');
        pll_register_string('sbadaf_9', 'Fraud log notes:');
        pll_register_string('sbadaf_10', 'If you would like to add notes about this log you can do so below.');
    }

    /** css */
    public static function sbadaf_admin_scripts()
    {
        wp_enqueue_style('sbadaf-admin', SBADAF_URL . 'includes/assets/css_back.css');
    }
}
sbadaf_admin::init();
