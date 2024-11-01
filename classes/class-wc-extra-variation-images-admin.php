<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WC_Extra_Variation_Images_Admin {
	private static $_this;

	/**
	 * init
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function __construct() {
		self::$_this = $this;
		add_action( 'admin_enqueue_scripts', array( $this, 'wc_load_admin_media_scripts' ) );
		add_action( 'woocommerce_product_after_variable_attributes', array( $this,'wc_extra_variation_images_settings_fields'), 10, 3 );
		//add_action( 'woocommerce_save_product_variation', array( $this, 'wc_extra_variation_image_save' ), 10, 2 );
		add_action( 'save_post', array( $this, 'wc_extra_variation_image_save' ));
    	return true;
	}

	/**
	 * public function to get instance
	 *
	 * @since 1.1.1
	 * @return instance object
	 */
	public function get_instance() {
		return self::$_this;
	}
		
	/**
	 * load admin scripts
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function wc_load_admin_media_scripts() {
			wp_enqueue_media();
			wp_enqueue_script( 'media_ipload', plugins_url(). '/woocommerce-extra-variation-images/assets/js/admin.js', array( 'jquery' ), '', true );
			wp_enqueue_style( 'media_css', plugins_url(). '/woocommerce-extra-variation-images/assets/css/admin.css', array(), '' );
			return true;
	}

	/**
	 * load variation images field
	 *
	 * @since 1.0.0
	 * @return json
	 */
	public function wc_extra_variation_images_settings_fields($loop, $variation_data, $variation) {
		
		$metavalue=get_post_meta( $variation->post_parent, 'extra_variation_img_ids', true );
		$htmlData="";
		if(strlen($metavalue[$variation->ID]) > 0){
			$attachment_ids=explode(",",$metavalue[$variation->ID]);
			foreach($attachment_ids as $id){
				if($id!=""){
				$img=wp_get_attachment_image_src($id,"thumbnail");
				$htmlData .="<span class='img'><img src='".$img[0]."' data-id='".$id."' title='' width='80' height='80'/><a href='javascript:void 0' class='remove_icon'>X</a></span>";
				}
			}
		}
		echo "<div class='variations_images_list'><div class='img_list'>".$htmlData."</div>";	/* Hidden field*/
		woocommerce_wp_hidden_input( 
			array( 
				'id'          => 'extra_variation_img_ids[' . $variation->ID . ']', 
				'desc_tip'    => 'true',
				'value'       =>$metavalue[$variation->ID],
				'class' =>'hidden_img',
			)
		);
		
		echo "<a href='javascript:void 0;' class='button button-primary button-large upload_varitn_img'>Add Variation Images</a>";
		echo "</div>";
		
		// Text Field
		
	}

	
	/**
	 * hooks into save post
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function wc_extra_variation_image_save($post_id) {
		$text_field = $_POST['extra_variation_img_ids'];
		if(isset($_POST['extra_variation_img_ids'])) {
			update_post_meta( $post_id, 'extra_variation_img_ids',$text_field );
		}
	}
}

new WC_Extra_Variation_Images_Admin();