<?php
/**
* @package wp-pagalocard-woocommerce
* @author XicoOfficial
* @since 1.1.0
 */

class wowp_pcwpg_pagalocard extends WC_Payment_Gateway_CC {

	function __construct() {

		// global ID
		$this->id = "wowp_pcwpg_pagalocard";

		// Show Title
		$this->method_title = __( "Pagalo Card", 'wp-pagalocard-woocommerce' );

		// Show Description
		$this->method_description = __( "Pagalo Card Payment Gateway Plug-in for WooCommerce", 'wp-pagalocard-woocommerce' );

		// vertical tab title
		$this->title = __( "Pagalo Card", 'wp-pagalocard-woocommerce' );


		$this->icon = null;

		$this->has_fields = true;

		// support default form with credit card
		$this->supports = array( 'default_credit_card_form' );

		// setting defines
		$this->init_form_fields();

		// load time variable setting
		$this->init_settings();
		
		// Turn these settings into variables we can use
		foreach ( $this->settings as $setting_key => $value ) {
			$this->$setting_key = $value;
		}
		
		// further check of SSL if you want
		add_action( 'admin_notices', array( $this,	'do_ssl_check' ) );

		// Check if the keys have been configured
		if( !is_admin() ) {
				wc_add_notice( __("This website is on test mode, so orders are not going to be processed. Please contact the store owner for more information or alternative ways to pay.", "wp-pagalocard-woocommerce") );
		}

		// Save settings
		if ( is_admin() ) {
			add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
		}		
	} // Here is the  End __construct()

	// administration fields for specific Gateway
	public function init_form_fields() {
		$this->form_fields = array(
			'enabled' => array(
				'title'			=> __( 'Enable / Disable', 'wp-pagalocard-woocommerce' ),
				'label'			=> __( 'Enable this payment gateway', 'wp-pagalocard-woocommerce' ),
				'type'			=> 'checkbox',
				'default'		=> 'no',
			),
			'pc_idenEmpresa' => array(
				'title'			=> __( 'IdemEmpresa', 'wp-pagalocard-woocommerce' ),
				'type'			=> 'text',
				'desc_tip'	=> __( 'This is the IdemEmpresa provided by PagaloCard when you signed up for an account.', 'wp-pagalocard-woocommerce' ),
			),
			'pc_token' => array(
				'title'			=> __( 'Token', 'wp-pagalocard-woocommerce' ),
				'type'			=> 'text',
				'desc_tip'	=> __( 'This is the Token provided by PagaloCard when you signed up for an account.', 'wp-pagalocard-woocommerce' ),
			),
			'pc_key_public' => array(
				'title'			=> __( 'API Public Key', 'wp-pagalocard-woocommerce' ),
				'type'			=> 'text',
				'desc_tip'	=> __( 'This is the Public Key provided by PagaloCard when you signed up for an account.', 'wp-pagalocard-woocommerce' ),
			),
			'pc_key_secret' => array(
				'title'			=> __( 'API Secret Key', 'wp-pagalocard-woocommerce' ),
				'type'			=> 'text',
				'desc_tip'	=> __( 'This is the API Secret Key provided by PagaloCard when you signed up for an account.', 'wp-pagalocard-woocommerce' ),
			)
		);		
	}
	
	// Response handled for payment gateway
	public function process_payment( $order_id ) {
		global $woocommerce;

		$customer_order = new WC_Order( $order_id );

		$products = $customer_order->get_items();

		$pagalo_card = new WOWP_PCWPG_Pagalocard_API();

		$str_empresa = $pagalo_card->get_empresa_data( $this );
		$str_cliente = $pagalo_card->get_client_data_on_checkout( $customer_order );
		$str_detalle = $pagalo_card->get_detalle_data( $products );
		$str_tarjeta = $pagalo_card->get_credit_card_data();

		$data = array('empresa' => $str_empresa, 'cliente' => $str_cliente, 'detalle' => $str_detalle, 'tarjetaPagalo' => $str_tarjeta);

		// Decide which URL to post to
		$environment_url = 'https://sandbox.pagalocard.com/api/v1/integracionIn/' . $this->pc_token;
 
    $result = wp_remote_post( $environment_url, array( 
          'method'    => 'POST', 
          'body'      => json_encode( $data ), 
          'timeout'   => 90, 
          'sslverify' => true, 
          'headers' => array( 'Content-Type' => 'application/json' ) 
        ) ); 

		if ( is_wp_error( $result ) ) {
					throw new Exception( __( 'There is issue for connectin payment gateway. Sorry for the inconvenience.', 'wp-pagalocard-woocommerce' ) );
				if ( empty( $result['body'] ) ) {
					throw new Exception( __( 'PagaloCard\'s Response was not get any data.', 'wp-pagalocard-woocommerce' ) );	
				}
		}

		// get body response while get not error
		$response_body = $pagalo_card->get_response_body($result);

		// 100 o 200 means the transaction was a success
		if ( ( $response_body['reasonCode'] == 200 ) || $response_body['reasonCode'] == 100) {

			// Payment successful
			$customer_order->add_order_note( __( 'PagaloCard complete payment.', 'wp-pagalocard-woocommerce' ) );
												 
			// paid order marked
			$customer_order->payment_complete();
			// this is important part for empty cart
			$woocommerce->cart->empty_cart();

			// Redirect to thank you page
			return array( 'result'   => 'success', 'redirect' => $this->get_return_url( $customer_order ) );
		} else {
			//transiction fail
			if( !current_user_can('edit_plugins') ) {
				wc_add_notice( $response_body['decision'] . ' - ' . __('Payment failed. Please contact the store owner for more information or alternative ways to pay. ', 'wp-pagalocard-woocommerce'), 'error');
			} else {
				wc_add_notice( __('Payment failed. ') . $response_body['decision'] . ' - ' . $response_body['descripcion'] . '.', 'error' );
			}

			wc_add_notice( 'Full response: ' . json_encode($response_body) );

		}

	}
	
	// Validate fields
	public function validate_fields() {
		return true;
	}

	public function do_ssl_check() {
		if( $this->enabled == "yes") {
			echo "<div class=\"error\"><p>". sprintf( __( "<strong>%s</strong> is enabled but remember that this plugin is for testing proposes only. Feel free to hack into the code and make it work for your store or better yet, purchase an already tested, fully functional and with more features to ensure a better experience for your clients. The plugin can be purchased <a href=\"%s\">here</a>.", 'wp-pagalocard-woocommerce' ), $this->method_title, 'https://www.xicoofficial.com/producto/wp-pagalocard-woocommerce/' ) ."</p></div>";
    }     
  } 

}