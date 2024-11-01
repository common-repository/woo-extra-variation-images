<?php
/**
 * Functions used by plugins
 */
if ( ! class_exists( 'WC_Extra_Dependencies' ) )
	require_once 'class-wc-extra-dependencies.php';

/**
 * WC Detection Function
 */
if ( ! function_exists( 'is_woocommerce_active' ) ) {
	function is_woocommerce_active() {
		return WC_Extra_Dependencies::woocommerce_active_check_status();
	}
}

