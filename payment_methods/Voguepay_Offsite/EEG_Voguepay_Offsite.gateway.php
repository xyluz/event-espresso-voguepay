<?php

if (!defined('EVENT_ESPRESSO_VERSION')) {
	exit('No direct script access allowed');
}

/**
 *
 * EEG_Mock_Onsite
 *
 * Just approves payments where billing_info[ 'credit_card' ] == 1.
 * If $billing_info[ 'credit_card' ] == '2' then its pending.
 * All others get refused
 *
 * @package			Event Espresso
 * @subpackage
 * @author				Mike Nelson
 *
 */
class EEG_Voguepay_Offsite extends EE_Offsite_Gateway{

	/**
	 * This gateway supports all currencies by default. To limit it to
	 * only certain currencies, specify them here
	 * @var array
	 */
	protected $_currencies_supported = EE_Gateway::all_currencies_supported;
	
	/**
	 * Example of site's login ID
	 * @var string
	 */
	protected $_prod_id = null;
	
	protected $_pay_item_id = null;
	
	protected $_mac_key = null;
	
	
	
	/**
	 * @return EEG_New_Payment_Method_Offsite
	 */
	public function __construct() {
		//if the gateway you are integrating with sends a separate instant-payment-notification request
		//(instead of sending payment information along with the user)
		//set this to TRUE
		
		parent::__construct();
	}
	
	
	/**
	 *
	 * @param arrat $update_info {
	 *	@type string $gateway_txn_id
	 *	@type string status an EEMI_Payment status
	 * }
	 * @param EEI_Transaction $transaction
	 * @return EEI_Payment
	 */
	public function handle_payment_update($update_info, $transaction) {
		$payment = $transaction->last_payment();
			
		if(isset( $update_info[ 'status' ] ) ){
			if( $update_info[ 'status' ] == "Approved" ){
				$billing_info[ 'credit_card' ] = 1;	
				$payment->set_status( $this->_pay_model->approved_status() );
				$payment->set_gateway_response( __( 'Payment Approved', 'event_espresso' ));
			}
			else{
				$payment->set_status( $this->_pay_model->failed_status());
				$payment->set_gateway_response( __( 'Transaction failed: Something went wrong', 'event_espresso' ) );
			}
		}
		 
		return $payment;
	}
	/**
	 * Also sets the gateway url class variable based on whether debug mode is enabled or not
	 * @param array $settings_array
	 */
	public function set_settings($settings_array){
		parent::set_settings($settings_array);
		$this->_gateway_url = $this->_debug_mode
			? 'https://voguepay.com/pay/'
			: 'https://voguepay.com/pay/';
	}
	
	public function getHas($mac_key, $txn_ref, $prod_id, $pay_item_id, $amount, $return_url){
		$hash_string = $txn_ref . $prod_id . $pay_item_id . $amount . $return_url . $mac_key;
		$hash = hash('sha512', $hash_string); 
		return $hash;
	}

	/**
	 *
	 * @param EEI_Payment $payment
	 * @param type $billing_info
	 * @param type $return_url
	 * @param type $cancel_url
	 */
	public function set_redirection_info($payment, $billing_info = array(), $return_url = NULL, $notify_url = NULL, $cancel_url = NULL) {


			
		global $auto_made_thing_seed;
		
		if( empty( $auto_made_thing_seed ) ) {
			$auto_made_thing_seed = rand(1,1000000);
		}
			
		
		$payment->set_redirect_url('https://voguepay.com/pay/');

		$primary_attendee = $payment->get_primary_attendee();
		
			
		$payment->set_redirect_args( array(
			'total' => $payment->amount()*100,			
			'notify_url' => $return_url,
			'success_url' => $return_url,
			'fail_url' => $return_url,
			'v_merchant_id'=> 'qa331322179752',
			'merchant_ref' => '234-567-890',
			'developer_code' => '5963e64bb755c',
			'store_id' => 25,
			'memo' => "Registration",
			
		));
		
		return $payment;		
		
	}
}

// End of file EEG_Mock_Onsite.php