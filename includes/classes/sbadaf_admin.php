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
    }
}
sbadaf_admin::init();
