jQuery(document).ready(function ($) {

    // show delete logs modal
    $('a#sbadaf-delete-all-logs').click(function (e) {
        e.preventDefault();
        $('div#sbadaf-delete-logs-overlay, div#sbadaf-delet-logs-modal').show();
    });

    // delete logs confirm
    $('a#sbadaf_del_logs_confirm').click(function (e) {
        e.preventDefault();

        var data = {
            'action': 'save_fraud_log_settings',
            'sbadaf_del_logs_confirm': true
        };

        $.post(ajaxurl, data, function (response) {
            alert(response);
            location.reload();
        });

    });

    // delete logs cancel/close modal/overlay click
    $('a#sbadaf_del_logs_cancel, div#sbadaf-delet-logs-modal > a:first-child, div#sbadaf-delete-logs-overlay').click(function (e) {
        e.preventDefault();
        $('div#sbadaf-delete-logs-overlay, div#sbadaf-delet-logs-modal').hide();
    });

    // delete logs confirm
    $('a#sbadaf-save-settings').click(function (e) {
        e.preventDefault();

        var data = {
            'action': 'save_fraud_log_settings',
            'sbadaf_save_settings': true,
            'checkout_attempts': $('input#sbadaf-checkout-attempts').val(),
            'blocking': $('select#sbadaf-block-user').val(),
            'block_length': $('select#sbadaf-block-user-period').val(),
            'email_addresses': $('input#sbadaf-mail-admin').val()
        };

        $.post(ajaxurl, data, function (response) {
            alert(response);
            location.reload();
        });

    });

    // set db defined values for selects if appropriate
    var user_blocking = $('select#sbadaf-block-user').attr('setting');
    $('select#sbadaf-block-user').val(user_blocking);

    var blocking_length = $('select#sbadaf-block-user-period').attr('setting');
    $('select#sbadaf-block-user-period').val(blocking_length);

});