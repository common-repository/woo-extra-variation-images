<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; 
}

class WC_Extra_Variation_Images_Frontend {
	private static $_this;

	/**
	 * init
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function __construct() {
	
		self::$_this = $this;
		if ( ! is_admin() ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'wc_extra_load_scripts' ) );
		}
		add_action('wp_ajax_extra_variation_ajax',array($this,'wc_extra_vrt_single_product_image_ajax'));
		add_action('wp_ajax_nopriv_extra_variation_ajax',array($this,'wc_extra_vrt_single_product_image_ajax'));
		add_filter( 'woocommerce_locate_template', array($this,'wc_extra_woo_plugin_template'), 10, 3 );
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
	 * load frontend scripts lib
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function wc_extra_load_scripts() {
		//wp_localize_script( 'ajax-param', 'ajax_params', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
		wp_enqueue_style( 'wc_extra_variation_images_css', plugins_url( 'assets/css/frontend.css' , dirname( __FILE__ ) ));
		wp_enqueue_script( 'wc_extra_variation_images_script', plugins_url( 'assets/js/frontend.js' , dirname( __FILE__ ) ), array( 'jquery' ) );
		return true;
	}

	/**
	 * load etxra variation images frontend ajax
	 *
	 * @since 1.0.0
	 * @return html
	 */
	function wc_extra_vrt_single_product_image_ajax() { 
		$images_array=array("status"=>0);
		$product_id	=$_POST['product_id'];
		$sel_value=$_POST['sel_value'];
		$product_variation = wc_get_product( $product_id );
		$variations = $product_variation->get_available_variations();
		$product_varition_ids=array();
		foreach($variations as $vartn){ $vrtn_name = explode("-",$vartn['slug']); $product_varition_ids[$vrtn_name[count($vrtn_name)-1]] = $vartn['id']; }
		$extra_variation_img_ids=get_post_meta($product_id,"extra_variation_img_ids",true);
		$img_array_ids=$product_varition_ids[$sel_value].",".$extra_variation_img_ids[$product_varition_ids[$sel_value]];
		$images_data="<ul class='wc_extra_variation_image_list'>";
		
		$img_array_ids_list=explode(",",$img_array_ids);
		if(count($img_array_ids_list) > 0 && has_post_thumbnail($product_varition_ids[$sel_value])){
			foreach($img_array_ids_list as $id){
				if($id!=""){
				$post_thumbnail_id = get_post_thumbnail_id( $id );
				$html="";
				if ( has_post_thumbnail($id) ) {
				$full_size_image   = wp_get_attachment_image_src( $post_thumbnail_id, 'full' );
				$image_title       = get_post_field( 'post_excerpt', $post_thumbnail_id );
				$placeholder       = has_post_thumbnail() ? 'with-images' : 'without-images';
				$wrapper_classes   = apply_filters( 'woocommerce_single_product_image_gallery_classes', array(
					'woocommerce-product-gallery',
					'extra_variation_sub_img woocommerce-product-gallery--' . $placeholder,
					'woocommerce-product-gallery--columns-' . absint( $columns ),
					'images',
				) );
				$attributes = array(
				'title'                   => $image_title,
				'data-src'                => $full_size_image[0],
				'data-large_image'        => $full_size_image[0],
				'data-large_image_width'  => $full_size_image[1],
				'data-large_image_height' => $full_size_image[2],
				);
				
				$html  = '<li data-thumb="' . get_the_post_thumbnail_url( $id, 'shop_thumbnail' ) . '" class="wc_extra_variation_main_img"><a href="' . esc_url( $full_size_image[0] ) . '">';
				$html .= get_the_post_thumbnail( $id, 'shop_single', $attributes );
				$html .= '</a></li>';
				}else{
					$html = '<li data-thumb="'.wp_get_attachment_url($id).'" class="wc_product_gallery_image"><a href="'.wp_get_attachment_url($id).'"><img src="'.wp_get_attachment_url($id).'" class="attachment-shop_single size-shop_single" alt="doggee_turquoise" title="" sizes="(max-width: 600px) 100vw, 600px" width="600" height="600"></a></li>';
					
				}
				$images_data .= $html;
				$images_array['status']=1;
				}
			}
		}
		if(strlen($images_data) <= 0){
				if ( has_post_thumbnail($product_id) ) {
					$html  = '<li data-thumb="' . get_the_post_thumbnail_url( $product_id, 'shop_thumbnail' ) . '" class="wc_extra_variation_main_img"><a href="' . esc_url( get_the_post_thumbnail_url( $product_id, 'original' ) ) . '">';
					$html .= get_the_post_thumbnail( $product_id, 'shop_single', $attributes );
					$html .= '</a></li>';
				} else{
					$html  = '<li class="wc_extra_variation_main_img">';
					$html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src() ), esc_html__( 'Awaiting product image', 'woocommerce' ) );
					$html .= '</li>';
				}
				$product = new WC_product($product_id);
				 $attachment_ids = $product->get_gallery_attachment_ids();
					if(count($attachment_ids) > 0){	
						foreach( $attachment_ids as $attachment_id ) 
						{
							$html .= '<li data-thumb="'.wp_get_attachment_url($attachment_id).'" class="wc_product_gallery_image"><a href="'.wp_get_attachment_url($attachment_id).'"><img src="'.wp_get_attachment_url($attachment_id).'" class="attachment-shop_single size-shop_single" alt="doggee_turquoise" title="" sizes="(max-width: 600px) 100vw, 600px" width="600" height="600"></a></li>';
						}
					}	
				$images_array['status']=1;	
				$images_data .=$html;
			}
		$images_array['data']=$images_data."</ul>";
		print_r(json_encode($images_array));
		die();
	}
	
	 function wc_extra_woo_plugin_template( $template, $template_name, $template_path ) {
		 global $woocommerce;
		 $_template = $template;
		 if ( ! $template_path ) 
			$template_path = $woocommerce->template_url;
		 
			 $plugin_path  = untrailingslashit( plugin_dir_path( __FILE__ ) )  . '/woocommerce/';
			// Look within passed path within the theme - this is priority
			$template = locate_template(
			array(
			  $template_path . $template_name,
			  $template_name
			)
		   );
		 
		   if( ! $template && file_exists( $plugin_path . $template_name ) )
			$template = $plugin_path . $template_name;
		 
		   if ( ! $template )
			$template = $_template;

		   return $template;
	}	
         
	

}

new WC_Extra_Variation_Images_Frontend();