<?php

/**
 * Plugin Name: Silverback Adyen Fraud Log
 * Author: Werner C. Bessinger
 * Description: Logs fraudulent credit card checkout attempts made via Silverback Adyen Payment Gateway
 * Version: 1.0.0
 * Text Domain: sbadaf
 */

//  block direct access
if (!defined('ABSPATH')) :
    exit();
endif;

// define constants
define('SBADAF_PATH', plugin_dir_path(__FILE__));
define('SBADAF_URL', plugin_dir_url(__FILE__));
define('SBADAF_AJURL', admin_url('admin-ajax.php'));

// load plugin files
function sbadaf_init()
{
    // cpt
    require_once SBADAF_PATH . 'includes/cpt.php';

    // traits
    require_once SBADAF_PATH . 'includes/classes/trait-admin-columns.php';
    require_once SBADAF_PATH . 'includes/classes/trait-fraud-log-metaboxes.php';

    // classes
    require_once SBADAF_PATH . 'includes/classes/sbadaf_admin.php';
    require_once SBADAF_PATH . 'includes/classes/sbadaf_front.php';
}

add_action('plugins_loaded', 'sbadaf_init');
