<?php
/*
  Plugin Name: Event Espresso - VoguePay (EE 4.x+)
  Plugin URI: http://www.xyluzstore.biz/
  Description: Add Voguepay support to Event Espresso
  methods: new offsite
  Version: 0.0.1.dev.002
  Author: Seyi Onifade
  Author URI: http://www.xyluz.com
  Copyright 2017 xyLuz Developers (email : hello@xyluz.com)

 */
define( 'EE_VOGUEPAY_VERSION', '0.0.1.dev.002' );
define( 'EE_VOGUEPAY_PLUGIN_FILE',  __FILE__ );
function load_espresso_voguepay() {
if ( class_exists( 'EE_Addon' )) {
	// new_payment_method version
	require_once ( plugin_dir_path( __FILE__ ) . 'EE_Voguepay.php' );
	EE_Voguepay::register_addon();
}
}
add_action( 'AHEE__EE_System__load_espresso_addons', 'load_espresso_voguepay' );

// End of file espresso_new_payment_method.php
// Location: wp-content/plugins/espresso-new-payment-method/espresso_new_payment_method.php