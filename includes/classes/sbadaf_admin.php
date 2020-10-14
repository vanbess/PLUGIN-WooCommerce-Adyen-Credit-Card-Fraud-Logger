<?php

/* Handles backend functionality */

class sbadaf_admin
{
    /** init */
    public static function init()
    {
        // add settings page
        add_submenu_page('edit.php?post_type=fraud_log', "Fraud Log Settings", "Settings", "manage_options", "sbadaf_settings", [__CLASS__, 'sbadaf_settings']);

        // save settings
        add_action('wp_ajax_nopriv_save_fraud_log_settings', [__CLASS__, 'save_fraud_log_settings']);
        add_action('wp_ajax_save_fraud_log_settings', [__CLASS__, 'save_fraud_log_settings']);

        // css and js
        add_action('admin_head', [__CLASS__, 'sbadaf_admin_scripts']);
    }

    /** css and js */
    public static function sbadaf_admin_scripts()
    {
        wp_enqueue_style('sbadaf-admin', SBADAF_URL . 'includes/assets/css_back.css');
        wp_enqueue_script('sbadaf-admin', SBADAF_URL . 'includes/assets/js_back.js', ['jquery']);
    }

    /** settings */
    public static function sbadaf_settings()
    { ?>
        <div id="sbadaf-settings-cont">

            <h2><?php pll_e('Fraud Log Settings'); ?></h2>
            <hr>

            <!-- checkout attempts before logging -->
            <div class="sbadaf-settings-section">
                <label for="sbadaf-checkout-attempts"><?php pll_e('Start logging failed checkout attempts from how many checkout attempts onwards?'); ?></label>
                <input type="number" id="sbadaf-checkout-attempts" placeholder="eg. 5" value="<?php echo get_option('sbadaf_co_attempts'); ?>">
            </div>

            <!-- block user -->
            <div class="sbadaf-settings-section">
                <label for="sbadaf-block-user"><?php pll_e('Block user after how many failed checkout attempts?'); ?></label>
                <select id="sbadaf-block-user" setting="<?php echo get_option('sbadaf_blocking'); ?>">
                    <option value="no_blocking"><?php pll_e('Do not block'); ?></option>
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="15">15</option>
                    <option value="20">20</option>
                    <option value="25">25</option>
                    <option value="30">30</option>
                </select>
            </div>

            <!-- block user for how long -->
            <div class="sbadaf-settings-section">
                <label for="sbadaf-block-user-period"><?php pll_e('Block user for how long?'); ?></label>
                <select id="sbadaf-block-user-period" setting="<?php echo get_option('sbadaf_block_length'); ?>">
                    <option value=""><?php pll_e('Please select...'); ?></option>
                    <option value="300"><?php pll_e('5 minutes'); ?></option>
                    <option value="900"><?php pll_e('15 minutes'); ?></option>
                    <option value="3600"><?php pll_e('60 minutes'); ?></option>
                    <option value="86400"><?php pll_e('24 hours'); ?></option>
                    <option value="permanent"><?php pll_e('Permanently'); ?></option>
                </select>
                <span class="sbadaf-help"><?php pll_e('NOTE: This setting will only work if user blocking is active.'); ?></span>
            </div>

            <!-- send admin email -->
            <div class="sbadaf-settings-section">
                <label for="sbadaf-mail-admin"><?php pll_e('If you would like to send fraud log alerts to a specific email address please enter said address below:'); ?></label>
                <input type="email" id="sbadaf-mail-admin" value="<?php echo get_option('sbadaf_emails'); ?>">
                <span class="sbadaf-help"><?php pll_e('Multiple email addresses can be specified by separating them with commas.'); ?></span>
            </div>

            <!-- delete all logs -->
            <div class="sbadaf-settings-section">
                <label for="sbadaf-delete-logs"><?php pll_e('If you would like to purge all logs from the database you can do so by clicking the button below:'); ?></label>
                <a id="sbadaf-delete-all-logs" href="javascript:void(0)"><?php pll_e('Delete all logs'); ?></a>
                <span class="sbadaf-help"><?php pll_e('WARNING: This action cannot be reversed!'); ?></span>

                <!-- deletion confirmation dialogue -->
                <div id="sbadaf-delete-logs-overlay" style="display: none;"></div>
                <div id="sbadaf-delet-logs-modal" style="display: none;">
                    <a href="javascript:void(0)" title="<?php pll_e('Cancel'); ?>">x</a>
                    <p><?php pll_e('Are you sure you want to delete all logs? This cannot be undone!'); ?></p>
                    <a id="sbadaf_del_logs_confirm" href="javascript:void(0)"><?php pll_e('Yes, delete all logs'); ?></a>
                    <a id="sbadaf_del_logs_cancel" href="javascript:void(0)"><?php pll_e('No, cancel deletion'); ?></a>
                </div>
            </div>

            <hr>
            <!-- save settings -->
            <div class="sbadaf-settings-section">
                <a id="sbadaf-save-settings" href="javascript:void(0)"><?php pll_e('Save Settings'); ?></a>
            </div>
        </div>
<?php
    }

    /** save settings */
    public static function save_fraud_log_settings()
    {

        // save settings
        if (isset($_POST['sbadaf_save_settings'])) :

            // get data
            $checkout_attempts = $_POST['checkout_attempts'];
            $blocking = $_POST['blocking'];
            $block_length = $_POST['block_length'];
            $email_addresses = $_POST['email_addresses'];

            // update options
            $checkout_attempts_updated = update_option('sbadaf_co_attempts', $checkout_attempts);
            $blocking_updated = update_option('sbadaf_blocking', $blocking);
            $block_length_updated = update_option('sbadaf_block_length', $block_length);
            $email_addresses_updated = update_option('sbadaf_emails', $email_addresses);

            if ($checkout_attempts_updated || $blocking_updated || $block_length_updated || $email_addresses_updated) :
                pll_e('Settings have been updated successfully.');
            else :
                pll_e('Settings could not be updated. Please make sure you enter the correct data before attempting to save.');
            endif;

        endif;

        // delete logs
        if (isset($_POST['sbadaf_del_logs_confirm'])) :

            // query logs
            $logs = new WP_Query([
                'post_type' => 'fraud_log',
                'posts_per_page' => -1,
                'post_status' => 'any'
            ]);

            if ($logs->have_posts()) :
                while ($logs->have_posts()) :
                    $logs->the_post();
                    $log_id_arr[] = get_the_ID();
                endwhile;
                wp_reset_postdata();
            endif;

            // if log ids present delete all, else display error
            if (!empty($log_id_arr) && is_array($log_id_arr)) :
                foreach ($log_id_arr as $log_id) :
                    $logs_deleted[] = wp_delete_post($log_id . true);
                endforeach;
            endif;

            // if logs deleted, display success message
            if ($logs_deleted && !empty($logs_deleted)) :
                pll_e('All logs have been successfully deleted');
            else :
                pll_e('There are no logs to delete.');
            endif;

        endif;
        wp_die();
    }

    /** custom post type meta data inputs */
    public static function fraud_log_meta_inputs()
    {
    }

    /** save custom post type meta data */
    public static function save_fraud_log_meta()
    {
    }
}
sbadaf_admin::init();
