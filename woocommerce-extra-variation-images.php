<?php
/*
Plugin Name: WooCommerce Extra Variation Images 
Plugin URI: https://wordpress.org/plugins/woo-extra-variation-images/
Description: A WooCommerce Extra Variation images plugin/extension that adds ability for shop/store owners to add variation specific images in a group.
Version: 1.0.0
Author: D001928403
Contributors: D001928403
Author URI: 

Copyright: (R) 2017 Dev

License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Not Defined
}

/**
 * Required functions
 */

require_once( 'woo-includes/woo-functions.php' );




if ( ! class_exists( 'WC_Extra_Variation_Images' ) ) :

/**
 * main class.
 *
 * @package  WC_Extra_Variation_Images
 */
class WC_Extra_Variation_Images {

	/**
	 * init
	 *
	 * @access public
	 * @since 1.0.0
	 * @return bool
	 */
	function __construct() {

		if ( is_woocommerce_active() ) {

			if ( is_admin() ) {
				include_once( 'classes/class-wc-extra-variation-images-admin.php' );
			}

			include_once( 'classes/class-wc-extra-variation-images-frontend.php' );

		} else {

			add_action( 'admin_notices', array( $this, 'woocommerce_not_available_notice' ) );

		}

		return true;
	}

	/**
	 * WooCommerce  notice.
	 *
	 * @return string
	 */
	public function woocommerce_not_available_notice() {
		echo '<div class="error"><p>' . sprintf( __( 'WooCommerce Extra Variation Images Plugin requires WooCommerce to be installed and active. You can download %s here.', 'woocommerce-extra-variation-images' ), '<a href="https://wordpress.org/plugins/woocommerce/" target="_blank">WooCommerce</a>' ) . '</p></div>';
	}
}

add_action( 'plugins_loaded', 'woocommerce_extra_variation_images_init', 0 );

/**
 * init function
 *
 * @package  WC_Extra_Variation_Images
 * @since 1.0.0
 * @return bool
 */
function woocommerce_extra_variation_images_init() {
	new WC_Extra_Variation_Images();
	return true;
}

endif;