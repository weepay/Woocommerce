<?php

/*
 * Plugin Name:WooCommerce weepay Payment Gateway
 * Plugin URI: https://www.kahvedigital.com
 * Description: weepay Payment Gateway For Woocommerce
 * Version: 1.0.0
 * Author: weepay.co
 * Author URI: http://weepay.co/
 * Domain Path: /i18n/languages/
 */

if (!defined('ABSPATH')) {
    exit;
}

error_reporting(E_ALL ^ E_NOTICE);

global $weepay_db_version;
$weepay_db_version = '1.0';
register_deactivation_hook(__FILE__, 'weepay_deactivation');
register_activation_hook(__FILE__, 'weepay_activate');
add_action('plugins_loaded', 'weepay_update_db_check');

function weepay_update_db_check() {
    global $weepay_db_version;
    global $wpdb;
    $installed_ver = get_option("weepay_db_version");
    if ($installed_ver != $weepay_db_version) {
        weepay_update();
    }
}

function weepay_update() {
    global $weepay_db_version;
    update_option("weepay_db_version", $weepay_db_version);
}

function weepay_activate() {
    global $wpdb;
    global $weepay_db_version;
    $weepay_db_version = '1.0';

    $charset_collate = $wpdb->get_charset_collate();
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    add_option('weepay_db_version', $weepay_db_version);
}

function weepay_deactivation() {
    global $wpdb;
    global $weepay_db_version;

    delete_option('weepay_db_version');
    flush_rewrite_rules();
}

function weepay_install_data() {
    global $wpdb;
}

add_action('plugins_loaded', 'woocommerce_weepay_payment_init', 0);

function woocommerce_weepay_payment_init() {
    if (!class_exists('WC_Payment_Gateway'))
        return;

    class WC_Gateway_Weepay extends WC_Payment_Gateway {

        public function __construct() {
            $this->id = 'weepay';
            $this->method_title = __('weepay Checkout form', 'weepay-payment');
            $this->method_description = __('weepay Payment Module', 'weepay-payment');
            $this->icon = plugins_url('/weepay-payment/img/cards.png', dirname(__FILE__));
            $this->has_fields = false;
            $this->supports = array('products', 'refunds');
            $this->init_form_fields();
            $this->init_settings();
            $this->weepay_payment_bayi_id = $this->settings['weepay_payment_bayi_id'];
            $this->weepay_payment_bayi_api = $this->settings['weepay_payment_bayi_api'];
            $this->weepay_payment_bayi_secret = $this->settings['weepay_payment_bayi_secret'];
            $this->weepay_payment_bayi_form_type = $this->settings['weepay_payment_bayi_form_type'];
            $this->weepay_payment_bayi_installement = $this->settings['weepay_payment_bayi_installement'];
            $this->title = $this->settings['title'];
            $this->description = $this->settings['description'];
            $this->enabled = $this->settings['enabled'];
            $this->order_button_text = $this->settings['button_title'];
            add_action('init', array(&$this, 'check_weepay_response'));
            add_action('woocommerce_api_wc_gateway_weepay', array($this, 'check_weepay_response'));
            add_action('admin_notices', array($this, 'checksFields'));
            if (version_compare(WOOCOMMERCE_VERSION, '2.0.0', '>=')) {
                add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
            } else {
                add_action('woocommerce_update_options_payment_gateways', array($this, 'process_admin_options'));
            }
            add_action('woocommerce_receipt_weepay', array($this, 'receipt_page'));
        }

        function checksFields() {
            global $woocommerce;

            if ($this->enabled == 'no')
                return;
        }

        function init_form_fields() {
            $this->form_fields = array(
                'enabled' => array(
                    'title' => __('Enable/Disable', 'weepay-payment'),
                    'label' => __('Enable weepay Payment', 'weepay-payment'),
                    'type' => 'checkbox',
                    'default' => 'no',
                ),
                'title' => array(
                    'title' => __('Title', 'weepay-payment'),
                    'type' => 'text',
                    'description' => __('This message will show to the user during checkout.', 'weepay-payment'),
                    'default' => 'Kredi Kartı İle Öde'
                ),
                'description' => array(
                    'title' => __('Description.', 'weepay-payment'),
                    'type' => 'text',
                    'description' => __('This controls the description which the user sees during checkout.', 'weepay-payment'),
                    'default' => __('Pay with your credit card via weepay.', 'weepay-payment'),
                    'desc_tip' => true,
                ),
                'button_title' => array(
                    'title' => __('Checkout Button.', 'weepay-payment'),
                    'type' => 'text',
                    'description' => __('Checkout Button.', 'weepay-payment'),
                    'default' => __('Pay With Credit Card.', 'weepay-payment'),
                    'desc_tip' => true,
                ),
                'weepay_payment_bayi_id' => array(
                    'title' => __('weepay Dealer ID.', 'weepay-payment'),
                    'type' => 'text',
                    'desc_tip' => __('Dealer ID Given by weepay System.', 'weepay-payment'),
                ),
                'weepay_payment_bayi_api' => array(
                    'title' => __('weepay API Key.', 'weepay-payment'),
                    'type' => 'text',
                    'desc_tip' => __('API key Given by weepay System.', 'weepay-payment'),
                ),
                'weepay_payment_bayi_secret' => array(
                    'title' => __('weepay Secret Key.', 'weepay-payment'),
                    'type' => 'text',
                    'desc_tip' => __('Secret key Given by weepay System.', 'weepay-payment'),
                ),
               'weepay_payment_bayi_form_type' => array(
                    'title' => __('weepay Checkout Form Type.', 'weepay-payment'),
                    'type' => 'select',
                    'default' => 'popup',
                    'options' => array(
                        'popup' => __('Popup', 'weepay-payment'),
                        'responsive' => __('Responsive', 'weepay-payment'),
                    ),
                ),
                'weepay_payment_bayi_installement' => array(
                    'title' => __('Installments Options.', 'weepay-payment'),
                    'type' => 'select',
                    'default' => 'off',
                    'options' => array(
                        'off' => __('OFF', 'weepay-payment'),
                        'on' => __('ON', 'weepay-payment'),
                    ),
                )
            );
        }

        public function admin_options() {
  
  
            $weepay_url = plugins_url() . '/weepay-payment/';
            echo '<img src="' . $weepay_url . 'img/logo.png" width="150px"/>';
            echo '<h2>weepay Ödeme ayarları</h2><hr/>';
            echo '<table class="form-table">';
            $this->generate_settings_html();
            echo '</table>';

            echo '<input name="save" class="button-primary woocommerce-save-button" type="submit" value="Kaydet"><hr/>';
            include(dirname(__FILE__) . '/includes/weepay-help-about.php');
        }

       
       
       
       function CreateCheckOutFormweePay($order_id) {
            global $woocommerce;
            if (version_compare(get_bloginfo('version'), '4.5', '>='))
                wp_get_current_user();
            else
                get_currentuserinfo();
            $order = new WC_Order($order_id);

            $ip = $_SERVER['REMOTE_ADDR'];
            $siteLanguage = get_locale();
            $user_meta = get_user_meta(get_current_user_id());
            $siteLang = explode('_', get_locale());
            $locale = ($siteLang[0] == "tr") ? "tr" : "en";
            $billing_full_name = $order->get_billing_first_name() .' '. $order->get_billing_last_name();
            $billing_full_name = !empty($billing_full_name) ? $billing_full_name : "NOT PROVIDED";
            $phone = !empty($order->get_billing_phone()) ? $order->get_billing_phone() : 'NOT PROVIDED';
            $email = !empty($order->get_billing_email()) ? $order->get_billing_email() : 'NOT PROVIDED';
            $city_buyer=WC()->countries->states[$order->get_billing_country()][$order->get_billing_state()];
           
            $city = !empty($city_buyer) ? $city_buyer : 'NOT PROVIDED';
            $order_amount = $order->get_total();
            $currency = $order->get_currency();
			if($currency=='TRY'){$currency="TL";}
            $endPointUrl = "https://api.weepay.co/Payment/PaymentCheckoutFormCreate/";
            $weepayArray = array();
            $weepayArray['Aut'] = array(
                    'bayi-id' => $this->weepay_payment_bayi_id,
                    'api-key' => $this->weepay_payment_bayi_api,
                    'secret-key' => $this->weepay_payment_bayi_secret
                );
       
            $weepayArray['Data'] = array(
                    'CallBackUrl' =>$order->get_checkout_payment_url(true),
                    'Price' => round($order_amount, 2),
                    'Locale' => $locale,
                    'IpAddress' =>$_SERVER['REMOTE_ADDR'],
                    'CustomerNameSurname' => $billing_full_name,
                    'CustomerPhone' => $phone,
                    'CustomerEmail' => $email,
                    'OutSourceID' => $order_id,
                    'Description' => $city,
                    'Currency' => $currency,
                    'Channel' => 'Module'
                );
 
            $endPointUrl = "https://api.weepay.co/Payment/PaymentCheckoutFormCreate/";
             
             
                $response = json_decode($this->curlPostExt(json_encode($weepayArray), $endPointUrl, true));



        return  $response;

          
        }

        function receipt_page($orderid) {
            global $woocommerce;
            $error_message = false;
            $order = new WC_Order($orderid);
            $status = $order->get_status();
            $showtotal = $order->get_total();
			$currency=$order->get_currency();
           if ($_POST['isSuccessful'] == 'True') {
                 
                 
             
                    $Result = $this->GetOrderData($orderid);
             $installment = $Result->Data->PaymentDetail->InstallmentNumber;
             if ($installment > 1) {
                 
                        $installment_fee = $Result->Data->PaymentDetail->Amount - $showtotal;
                        $order_fee = new stdClass();
                        $order_fee->id = 'Installment Fee';
                        $order_fee->name = __('Installment Fee', 'weepay-payment');
                        $order_fee->amount = $installment_fee;
                        $order_fee->taxable = false;
                        $order_fee->tax = 0;
                        $order_fee->tax_data = array();
                        $order_fee->tax_class = '';
                        $fee_id = $order->add_fee($order_fee);
                        $order->calculate_totals(true);
                         update_post_meta($order_id, 'weepay_installment_number', esc_sql($installment));
                        update_post_meta($order_id, 'weepay_installment_fee', $installment_fee);
                 }
                $order->payment_complete();
                $order->add_order_note(__('Payment successful.', 'weepay-payment') . '<br/>' . __('Payment ID', 'weepay-payment') . ': ' . esc_sql($Result->Data->PaymentDetail->DealerPaymentId));
                $woocommerce->cart->empty_cart();
                wp_redirect($this->get_return_url());
          
            }else if(isset($_POST['resultMessage'])){
          
            $order->update_status('pending', $_POST['resultMessage'], 'woocommerce');
            $error_message =$_POST['resultMessage'];
            
            
            
            }

        
   

            $installments_mode = $this->installments_mode;
            $form_class = $this->weepay_payment_bayi_form_type;
            $installments_mode = $this->installments_mode;
            $text_credit_card =__('Credit Cart Form', 'weepay-payment');
            $checkOutFormData = $this->CreateCheckOutFormweePay($orderid);
            if($checkOutFormData->status=='failure'){
            
                $error_message=$checkOutFormData->message;
            }else{
                
            $CheckoutForm=$checkOutFormData->CheckoutFormData;
            
            }
      
            include(dirname(__FILE__) . '/weepay.php');
            
            
        }
        
        
          function GetOrderData($id_order) {
        $weepayArray = array();
        $weepayArray['Aut'] = array(
                    'bayi-id' => $this->weepay_payment_bayi_id,
                    'api-key' => $this->weepay_payment_bayi_api,
                    'secret-key' => $this->weepay_payment_bayi_secret
        );
        $weepayArray['Data'] = array(
            'OrderID' => $id_order
        );
        $weepayEndPoint = "https://api.weepay.co/Payment/GetPaymentDetail";
        return json_decode($this->curlPostExt(json_encode($weepayArray), $weepayEndPoint, true));
    } 
        
 function curlPostExt($data, $url, $json = false) {
        $ch = curl_init(); // initialize curl handle
        curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        if ($json)
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // times out after 4s
        curl_setopt($ch, CURLOPT_POST, 1); // set POST method
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data); // add POST fields
        if ($result = curl_exec($ch)) { // run the whole process
            curl_close($ch);
            return $result;
        }
    }
        function process_payment($order_id) {
             global $woocommerce;
             $order = new WC_Order( $order_id );
      
        
            if (version_compare(WOOCOMMERCE_VERSION, '2.1.0', '>=')) {
                /* 2.1.0 */
                $checkout_payment_url = $order->get_checkout_payment_url(true);
            } else {
                /* 2.0.0 */
                $checkout_payment_url = get_permalink(get_option('woocommerce_pay_page_id'));
            }

            return array(
                'result' => 'success',
                'redirect' => $checkout_payment_url,
            );
        }

    }

}

add_filter('woocommerce_payment_gateways', 'woocommerce_add_weepay_checkout_form_gateway');

function woocommerce_add_weepay_checkout_form_gateway($methods) {
    $methods[] = 'WC_Gateway_Weepay';
    return $methods;
}

function weepay_checkout_form_load_plugin_textdomain() {
    load_plugin_textdomain('weepay-payment', FALSE, plugin_basename(dirname(__FILE__)) . '/i18n/languages/');
}

add_action('plugins_loaded', 'weepay_checkout_form_load_plugin_textdomain');


