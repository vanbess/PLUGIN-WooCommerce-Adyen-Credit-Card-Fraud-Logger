<?php

/* Handles front-end functionality */
class sbadaf_front
{

    /**
     * Init
     */
    public static function init()
    {
        // enqueue js
        add_action('wp_footer', [__CLASS__, 'sbadaf_frontend_js']);

        // ajax
        add_action('wp_ajax_nopriv_sbadaf_ajax_front', [__CLASS__, 'sbadaf_ajax_front']);
        add_action('wp_ajax_sbadaf_ajax_front', [__CLASS__, 'sbadaf_ajax_front']);

        file_put_contents(SBADAF_PATH.'server_data.txt', print_r($_SERVER, true));
    }

    /**
     * Ajax for handling data
     */
    public static function sbadaf_ajax_front()
    {

        //1. GET CHECKOUT PAYMENT METHOD
        if (isset($_POST['co_data'])) :
            // parse checkout data
            $co_data = $_POST['co_data'];
            parse_str($co_data, $parsed_data);
            // send back payment method
            print $parsed_data['payment_method'];
        endif;

        // 2.LOG USER DETAILS FOR INVALID/FAILED TRANSACTION IF PAYMENT METHOD == sb-adyen-cc

        wp_die();
    }

    /**
     * Frontend js to listen for and test transactions
     */
    public static function sbadaf_frontend_js()
    { ?>
        <script id="sbadaf-front" type="text/javascript">
            jQuery(document).ready(function($) {
                $(document).ajaxComplete(function(event, request, settings) {

                    // get current lang, set valid url and get current payment method
                    var currLang = '<?php echo pll_current_language(); ?>';
                    var validUrl = '/' + currLang + '/?wc-ajax=checkout';

                    // get required variables
                    var requestUrl = settings.url;

                    // if request url valid, get associated data
                    if (requestUrl == validUrl) {
                        var requestData = settings.data;
                        var transResult = request.responseJSON.result;

                        console.log(transResult);
                        

                        var ajaxurl = '<?php echo admin_url('admin-ajax.php') ?>';
                        var data = {
                            'action': 'sbadaf_ajax_front',
                            'co_data': requestData
                        };
                        $.post(ajaxurl, data, function(response) {
                            var pmtMethod = response;
                        });

                        // if payment method == sb-adyen-cc and transResult == failure, start keeping tabs
                        if (pmtMethod == 'sb-adyen-cc' && transResult == 'failure') {
                            
// setup data vars for logging
var user_ip = '<?php $_SERVER[''] ?>';

                        }
                    }




                    // console.log(requestUrl);
                    // console.log(requestData);
                    // console.log(transResult);
                    // console.log(request.responseJSON.result);


                });
            });
        </script>
<?php }
}

sbadaf_front::init();
