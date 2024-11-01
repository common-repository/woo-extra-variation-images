<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post, $product;
$columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
$post_thumbnail_id = get_post_thumbnail_id( $post->ID );
$full_size_image   = wp_get_attachment_image_src( $post_thumbnail_id, 'full' );
$image_title       = get_post_field( 'post_excerpt', $post_thumbnail_id );
$placeholder       = has_post_thumbnail() ? 'with-images' : 'without-images';
$wrapper_classes   = apply_filters( 'woocommerce_single_product_image_gallery_classes', array(
	'woocommerce-product-gallery',
	'extra_variation_sub_img woocommerce-product-gallery--' . $placeholder,
	'woocommerce-product-gallery--columns-' . absint( $columns ),
	'images',
) );

	

   


?>
<div class="wc_extra_variation_container">
	<aside class="wc_extra_variation_img_wrapper">
		<ul class="wc_extra_variation_image_list">
		<?php
		$attributes = array(
			'title'                   => $image_title,
			'data-src'                => $full_size_image[0],
			'data-large_image'        => $full_size_image[0],
			'data-large_image_width'  => $full_size_image[1],
			'data-large_image_height' => $full_size_image[2],
		);

		if ( has_post_thumbnail() ) {
			$html  = '<li data-thumb="' . get_the_post_thumbnail_url( $post->ID, 'shop_thumbnail' ) . '" class="wc_extra_variation_main_img"><a href="' . esc_url( $full_size_image[0] ) . '">';
			$html .= get_the_post_thumbnail( $post->ID, 'shop_single', $attributes );
			$html .= '</a></li>';
			
		} else {
			$html  = '<li class="wc_extra_variation_main_img">';
			$html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src() ), esc_html__( 'Awaiting product image', 'woocommerce' ) );
			$html .= '</li>';
			
		}

		echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, get_post_thumbnail_id( $post->ID ) );
		$product_gal = new WC_product($post->ID);
				 $attachment_ids = $product_gal->get_gallery_attachment_ids();
					if(count($attachment_ids) > 0){	
						$html= "";
						foreach( $attachment_ids as $attachment_id ) 
						{
							$html .= '<li data-thumb="'.wp_get_attachment_url($attachment_id).'" class="wc_product_gallery_image"><a href="'.wp_get_attachment_url($attachment_id).'"><img src="'.wp_get_attachment_url($attachment_id).'" class="attachment-shop_single size-shop_single" alt="doggee_turquoise" title="" sizes="(max-width: 600px) 100vw, 600px" width="600" height="600"></a></li>';
						}
						echo $html;
					}	
		//do_action( 'woocommerce_product_thumbnails' );
		?>
	 </ul>	
	</aside>
</div>
