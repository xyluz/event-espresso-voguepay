<?php if ( ! defined( 'EVENT_ESPRESSO_VERSION' )) { exit(); }

// define the plugin directory path and URL
define( 'EE_VOGUEPAY_BASENAME', plugin_basename( EE_VOGUEPAY_PLUGIN_FILE ));
define( 'EE_VOGUEPAY_PATH', plugin_dir_path( __FILE__ ));
define( 'EE_VOGUEPAY_URL', plugin_dir_url( __FILE__ ));

/**
 * ------------------------------------------------------------------------
 *
 * Class  EE_New_Payment_Method
 *
 * @package			Event Espresso
 * @subpackage		espresso voguepay
 * @author			    Seyi Onifade
 * @ version		 	$VID:$
 *
 * ------------------------------------------------------------------------
 */
// Class  EE_New_Payment_Method extends EE_Addon {
Class  EE_Voguepay extends EE_Addon {

	/**
	 * class constructor
	 */
	public function __construct() {
	}

	public static function register_addon() {
		// register addon via Plugin API
		EE_Register_Addon::register(
			// 'New_Payment_Method',
			'Voguepay',
			array(
				'version' 					=> 1,
				'min_core_version' => '4.6.0.dev.000',
				'main_file_path' 				=> EE_VOGUEPAY_PLUGIN_FILE,
				'admin_callback' => 'additional_admin_hooks',
				// if plugin update engine is being used for auto-updates. not needed if PUE is not being used.
				'pue_options'			=> array(
					'pue_plugin_slug' => 'espresso_voguepay',
					'plugin_basename' => EE_VOGUEPAY_BASENAME,
					'checkPeriod' => '24',
					'use_wp_update' => FALSE,
					),
				'payment_method_paths' => array(
					//EE_INTERSWITCH_PATH . 'payment_methods' . DS . 'Interswitch_Onsite',
					EE_VOGUEPAY_PATH . 'payment_methods' . DS . 'Voguepay_Offsite',
					),
		));
	}



	/**
	 * 	additional_admin_hooks
	 *
	 *  @access 	public
	 *  @return 	void
	 */
	public function additional_admin_hooks() {
		// is admin and not in M-Mode ?
		if ( is_admin() && ! EE_Maintenance_Mode::instance()->level() ) {
			add_filter( 'plugin_action_links', array( $this, 'plugin_actions' ), 10, 2 );
		}
	}



	/**
	 * plugin_actions
	 *
	 * Add a settings link to the Plugins page, so people can go straight from the plugin page to the settings page.
	 * @param $links
	 * @param $file
	 * @return array
	 */
	public function plugin_actions( $links, $file ) {
		if ( $file == EE_VOGUEPAY_BASENAME ) {
			// before other links
			array_unshift( $links, '<a href="admin.php?page=espresso_payment_settings">' . __('Settings') . '</a>' );
		}
		return $links;
	}






}
// End of file EE_New_Payment_Method.class.php
// Location: wp-content/plugins/espresso-new-payment-method/EE_New_Payment_Method.class.php
