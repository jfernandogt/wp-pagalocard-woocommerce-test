<?php

/**
* @package wp-pagalocard-woocommerce
* @author XicoOfficial
* @since 1.2.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * WC_Cardpay_Authnet_API
 */
 class WOWP_PCWPG_Pagalocard_API {

	public $wc_pre_30;

	public function __construct() {
		$this->wc_pre_30 = version_compare( WC_VERSION, '3.0.0', '<' );

		$date_array = $_POST['wowp_pcwpg_pagalocard-card-expiry'];
		$date_array = explode("/", str_replace(' ', '', $date_array));

		$this->credit_card_data = array(
				'nameCard'				=> mb_convert_encoding($_POST['wowp_pcwpg_pagalocard-card-name'], 'HTML-ENTITIES'),
				'accountNumber'		=> str_replace( array(' ', '-' ), '', $_POST['wowp_pcwpg_pagalocard-card-number'] ),
				'expirationMonth'	=> $date_array[0],
				'expirationYear'	=> $date_array[1], 
				'CVVCard'					=> ( isset( $_POST['wowp_pcwpg_pagalocard-card-cvc'] ) ) ? $_POST['wowp_pcwpg_pagalocard-card-cvc'] : 'no',
		);
	}


	/**
	 * get_empresa_data function
	 * 
	 * @return string
	 */
	public function get_empresa_data( $empresa ) {
		$empresa_data = array(
			'key_secret'=> $empresa->pc_key_secret,
			'key_public'=> $empresa->pc_key_public,
			'idenEmpresa'=> $empresa->pc_idenEmpresa,
		);

		$empresa_data = json_encode( $empresa_data );
		return $empresa_data;
	}

	/**
	 * get_existing_client_data function
	 * 
	 * @return string
	 */
	public function get_client_data_on_checkout( $customer_order ) {

		$cliente= array(
			'codigo'		=> 'c001',
			'firstName' => $customer_order->get_billing_first_name(),
			'lastName'  => $customer_order->get_billing_last_name(),
			'street1'		=> $customer_order->get_billing_address_1(),
			'phone'			=> $customer_order->get_billing_phone(),
			'country'		=> $customer_order->get_billing_country(),
			'city'			=> $customer_order->get_billing_city(),
			'state'			=> $customer_order->get_billing_state(),
			'postalCode'=> $customer_order->get_billing_postcode(),
			'email'			=> $customer_order->get_billing_email(),
			'ipAddress'	=> $customer_order->get_customer_ip_address(),
			'Total'			=> $customer_order->get_total(),
			'fecha_transaccion'=> $customer_order->get_date_created(),
			'currency'	=> $customer_order->get_currency(),
		  'deviceFingerprintID' => '',
		);

		$client_data = json_encode( $cliente );
		return $client_data;
	}


	/**
	 * get_detalle_data function
	 * 
	 * @return string
	 */
	public function get_detalle_data( $products ) {
		foreach ( $products as $product ) {
    	$detalle[] = array(
				'id_producto'	=> $product->get_product_id(),
				'cantidad'		=> $product->get_quantity(),
				'tipo'				=> $product->get_type(),
				'nombre'			=> $product->get_name(),
				'precio'			=> get_post_meta( $product->get_product_id(), '_regular_price', true),
				'Subtotal'		=> $product->get_total(),
			);
		}

		$detalle_data = json_encode( $detalle );
		return $detalle_data;
	}

	/**
	 * get_credit_card_data function
	 * 
	 * @return string
	 */
	public function get_credit_card_data( ) {

		$credit_card_data = json_encode( $this->credit_card_data );

		return $credit_card_data;
	}


	/**
	 * get_response_body function
	 * 
	 * @return string
	 */
	public function get_response_body( $response ) {

		// get body response while get not error
		$response_body = wp_remote_retrieve_body( $response );

		foreach ( preg_split( "/\r?\n/", $response_body ) as $line ) {
			$resp = explode( "|", $line );
		}

		// values get
		$r = json_decode( $resp[0], true );

		return $r;
	}

}
