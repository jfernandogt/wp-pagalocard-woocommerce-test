<?php 
/**
* @since 1.0.0
* @package wp-pagalocard-woocommerce
* @author xicoofficial
* 
* Plugin Name: Pagalo Card - WooCommerce Payment Gateway
* Plugin URI: https://www.xicoofficial.com/producto/wp-pagalocard-woocommerce/
* Description: WooCommerce custom payment gateway integration with PagaloCard.
* Version: 1.2.2
* Author: XicoOfficial
* Author URI: https://www.XicoOfficial.com
* Licence: GPL-3.0+
* Text Domain: wp-pagalocard-woocommerce
* Domain Path: /languages/
* WC requires at least: 3.0.0 
* WC tested up to: 3.4.1 
*/
 

/**
 * Tell WordPress to load a translation file if it exists for the user's language
 */
function wowp_pcwpg_load_plugin_textdomain() {
    load_plugin_textdomain( 'wp-pagalocard-woocommerce', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

add_action( 'plugins_loaded', 'wowp_pcwpg_load_plugin_textdomain' );


function wowp_pcwpg_pagalocard_init() {
    //if condition use to do nothin while WooCommerce is not installed
	if ( ! class_exists( 'WC_Payment_Gateway_CC' ) ) return;
	include_once( 'includes/wp-pagalocard-woocommerce-admin.php' );
	include_once( 'includes/wp-pagalocard-woocommerce-api.php' );
	// class add it too WooCommerce
	add_filter( 'woocommerce_payment_gateways', 'wowp_pcwpg_add_pagalocard_gateway' );
	function wowp_pcwpg_add_pagalocard_gateway( $methods ) {
		$methods[] = 'wowp_pcwpg_pagalocard';
		return $methods;
	}
}

add_action( 'plugins_loaded', 'wowp_pcwpg_pagalocard_init', 0 );


/**
* Add custom action links
*/
function wowp_pcwpg_pagalocard_action_links( $links ) {
	$plugin_links = array(
		'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=checkout' ) . '">' . __( 'Settings', 'wp-pagalocard-woocommerce' ) . '</a>',
	);
	return array_merge( $plugin_links, $links );
}

add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'wowp_pcwpg_pagalocard_action_links' );


/**
* Customize credict card form
*/
function wowp_pcwpg_pagalocard_custom_credit_card_fields ($cc_fields , $payment_id){
	$new_fields = array(
	 'card-name-field' => '<p class="form-row form-row-wide"><label for="' . esc_attr( $payment_id ) . '-card-name">'
	 		. __( 'Cardholder Name', 'wp-pagalocard-woocommerce' ) . ' <span class="required">*</span>
	 	</label>
	 	<input id="' . esc_attr( $payment_id ) . '-card-name" class="input-text wc-credit-card-form-card-name" type="text" maxlength="30" autocomplete="off" placeholder="' . __('CARDHOLDER NAME', 'wp-pagalocard-woocommerce') . '" name="' . esc_attr( $payment_id ) . '-card-name' . '" />
	 </p>',
	 'card-number-field' => '<p class="form-row form-row-wide"><label for="' . esc_attr( $payment_id ) . '-card-number">'
	 		. __( 'Card Number', 'wp-pagalocard-woocommerce' ) . ' <span class="required">*</span>
	 	</label>
	 	<input id="' . esc_attr( $payment_id ) . '-card-number" class="input-text wc-credit-card-form-card-number" inputmode="numeric" autocomplete="cc-number" autocorrect="no" autocapitalize="no" spellcheck="no" type="tel" placeholder="•••• •••• •••• ••••" name="' . esc_attr( $payment_id ) . '-card-number' . '" />
	 </p>',
	 'card-expiry-field' => '<p class="form-row form-row-first"><label for="' . esc_attr( $payment_id ) . '-card-expiry">'
	 		. __( 'Expiry (MM/YY)', 'wp-pagalocard-woocommerce' ) . ' <span class="required">*</span>
	 	</label>
	 	<input id="' . esc_attr( $payment_id ) . '-card-expiry" class="input-text wc-credit-card-form-card-expiry" inputmode="numeric" autocomplete="cc-exp" autocorrect="no" autocapitalize="no" spellcheck="no" type="tel" placeholder="' . __('MM / AA', 'wp-pagalocard-woocommerce') . '" name="' . esc_attr( $payment_id ) . '-card-expiry' . '" />
	 </p>',
	 'card-cvc-field' => '<p class="form-row form-row-last"><label for="' . esc_attr( $payment_id ) . '-card-cvc">'
	 		. __( 'Card Code', 'wp-pagalocard-woocommerce' ) . ' <span class="required">*</span>
	 	</label>
	 	<input id="' . esc_attr( $payment_id ) . '-card-cvc" class="input-text wc-credit-card-form-card-cvc"inputmode="numeric" autocomplete="off" autocorrect="no" autocapitalize="no" spellcheck="no" type="tel" maxlength="4" placeholder="CVV" style="width:100px" name="' . esc_attr( $payment_id ) . '-card-cvc' . '" />
	 </p>'
	);

	return $new_fields;
}

add_filter( 'woocommerce_credit_card_form_fields' , 'wowp_pcwpg_pagalocard_custom_credit_card_fields' , 10, 2 );
