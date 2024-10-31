<?php

/**
 * Fired during plugin activation
 *
 * @link       https://ae.pricena.com
 * @since      1.0.0
 *
 * @package    Pricena
 * @subpackage Pricena/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Pricena
 * @subpackage Pricena/includes
 * @author     Pricena Development Team
 */
class Pricena_Activator {

	/**
	 * Register plugin options and send setup request to Pricena
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		$options = [
			'storesetup_is_done' => 'no',
		];

		foreach ($options as $option_name => $option_value) {
			add_option( $option_name, $option_value, '', false );
		}
	}

}
