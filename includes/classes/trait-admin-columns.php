<?php

/** Construct and populate admin columns for fraud post type */
trait sbadaf_admin_cols
{

    /** init */
    public static function init()
    {
        // add custom cols
        add_filter('manage_fraud_log_posts_columns', [__CLASS__, 'sbadaf_custom_cols']);

        // add custom col data
        add_action('manage_fraud_log_posts_custom_column', [__CLASS__, 'sbadaf_custom_cols_content']);
    }

    // add custom post columns
    public static function sbadaf_custom_cols($columns)
    {

        // remove date and title col
        unset($columns['title']);

        // add custom cols
        $columns['fraud_attempts'] = pll__('Checkout Attempts');
        $columns['fraud_order_id'] = pll__('Order ID');
        $columns['fraud_order_no'] = pll__('Order No');
        $columns['fraud_reason'] = pll__('Adyen Reason');
        $columns['fraud_psp_ref'] = pll__('PSP Ref');

        return $columns;
    }

    // add column data
    public static function sbadaf_custom_cols_content($column)
    {
        switch ($column) {

            case 'fraud_attempts':
                echo get_post_meta(get_the_ID(), 'sbadaf_fraud_attempts', true);
                break;
            case 'fraud_order_id':
                echo get_post_meta(get_the_ID(), 'sbadaf_fraud_order_id', true);
                break;
            case 'fraud_order_no':
                echo get_post_meta(get_the_ID(), 'sbadaf_fraud_order_no', true);
                break;
            case 'fraud_reason':
                echo get_post_meta(get_the_ID(), 'sbadaf_fraud_reason', true);
                break;
            case 'fraud_psp_ref':
                pll_e(get_post_meta(get_the_ID(), 'sbadaf_fraud_psp_ref', true));
                break;
        }
    }
}
