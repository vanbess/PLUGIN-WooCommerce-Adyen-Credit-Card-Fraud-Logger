<?php

/** Renders fraud log metaboxes */

trait sbadaf_fraud_log_metaboxes
{

    /**
     * Init
     */
    public static function init()
    {
        add_action('admin_init', [__CLASS__, 'sbadaf_metabox']);
        add_action('save_post', [__CLASS__, 'sbadaf_save_meta'], 10, 2);
    }

    /**
     * Add custom metabox to fraud log cpt
     */
    public static function sbadaf_metabox()
    {
        add_meta_box('fraud_log_meta_box', 'Fraud Log Data', [__CLASS__, 'sbadaf_metabox_data'], 'fraud_log', 'normal', 'high');
    }

    /**
     * Display fraud log metadata
     */
    public static function sbadaf_metabox_data()
    { ?>
        <div id="sbadaf_log_metadata_cont">

            <div id="sbadaf_data_row">

                <!-- ip address -->
                <div class="sbadaf_meta_input_sbs">
                    <label for="sbadaf_fraud_ip"><?php pll_e('User IP Address:'); ?></label>
                    <input type="text" id="sbadaf_fraud_ip" readonly value="<?php echo get_post_meta(get_the_ID(), 'sbadaf_fraud_ip', true); ?>">
                </div>

                <!-- order id -->
                <div class="sbadaf_meta_input_sbs">
                    <label for="sbadaf_fraud_order_id"><?php pll_e('Order ID:'); ?></label>
                    <input type="text" id="sbadaf_fraud_order_id" readonly value="<?php echo get_post_meta(get_the_ID(), 'sbadaf_fraud_order_id', true); ?>">
                </div>

                <!-- order no -->
                <div class="sbadaf_meta_input_sbs">
                    <label for="sbadaf_fraud_order_no"><?php pll_e('Order number:'); ?></label>
                    <input type="text" id="sbadaf_fraud_order_no" readonly value="<?php echo get_post_meta(get_the_ID(), 'sbadaf_fraud_order_no', true); ?>">
                </div>

                <!-- fraud attempts -->
                <div class="sbadaf_meta_input_sbs">
                    <label for="sbadaf_fraud_attempts"><?php pll_e('Checkout attempts:'); ?></label>
                    <input type="text" id="sbadaf_fraud_attempts" readonly value="<?php echo get_post_meta(get_the_ID(), 'sbadaf_fraud_attempts', true); ?>">
                </div>

                <!-- user agent -->
                <div class="sbadaf_meta_input_sbs">
                    <label for="sbadaf_fraud_user_agent"><?php pll_e('User agent:'); ?></label>
                    <input type="text" id="sbadaf_fraud_user_agent" readonly value="<?php echo get_post_meta(get_the_ID(), 'sbadaf_fraud_user_agent', true); ?>">
                </div>

                <!-- psp ref -->
                <div class="sbadaf_meta_input_sbs">
                    <label for="sbadaf_fraud_psp_ref"><?php pll_e('PSP Reference:'); ?></label>
                    <input type="text" id="sbadaf_fraud_psp_ref" readonly value="<?php echo get_post_meta(get_the_ID(), 'sbadaf_fraud_psp_ref', true); ?>">
                </div>

                <!-- reason -->
                <div class="sbadaf_meta_input_sbs">
                    <label for="sbadaf_fraud_reason"><?php pll_e('Adyen\'s reason for declining transaction:'); ?></label>
                    <input type="text" id="sbadaf_fraud_reason" readonly value="<?php echo get_post_meta(get_the_ID(), 'sbadaf_fraud_reason', true); ?>">
                </div>

                <!-- payment method -->
                <div class="sbadaf_meta_input_sbs">
                    <label for="sbadaf_fraud_pmt_method"><?php pll_e('Payment method:'); ?></label>
                    <input type="text" id="sbadaf_fraud_pmt_method" readonly value="<?php echo get_post_meta(get_the_ID(), 'sbadaf_fraud_pmt_method', true); ?>">
                </div>
            </div>

            <!-- fraud log notes -->
            <div class="sbadaf_meta_input_notes">
                <hr style="margin-top: 30px;">
                <label for="sbadaf_fraud_notes"><?php pll_e('Fraud log notes:'); ?></label>
                <span class="sbadaf-help">If you would like to add notes about this log you can do so below.</span>
                <textarea name="sbadaf_fraud_notes" id="sbadaf_fraud_notes" cols="30" rows="10">
                <?php echo get_post_meta(get_the_ID(), 'sbadaf_fraud_notes', true); ?>
                </textarea>
            </div>
        </div>
<?php }

    /**
     * Save fraud log data as needed
     */
    public static function sbadaf_save_meta($post_id, $post)
    {
        if ($post->post_type == 'fraud_log') {
            // fraud notes
            if (isset($_POST['sbadaf_fraud_notes'])) {
                update_post_meta($post_id, 'sbadaf_fraud_notes', $_POST['sbadaf_fraud_notes']);
            }
        }
    }
}
?>