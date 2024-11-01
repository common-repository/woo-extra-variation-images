jQuery(document).ready(function(){
	var ajaxURL =  wc_add_to_cart_params.ajax_url;
	var ua = navigator.userAgent,pickclick = (ua.match(/iPad/i) || ua.match(/iPhone/)) ? "touchstart" : "click";
	/**********************************Change Images From List*************************************************/
	jQuery(document).on(pickclick,"aside.wc_extra_variation_img_wrapper .wc_product_gallery_image",function(e){
		e.preventDefault();
		var default_img  = jQuery("aside.wc_extra_variation_img_wrapper .wc_extra_variation_main_img").html();
		jQuery("aside.wc_extra_variation_img_wrapper .wc_extra_variation_main_img").html(jQuery(this).html());
		jQuery(this).html(default_img);
	});
	
	/*****************************Change Variation Images Gallery**************************************/
	jQuery(document).on("change",".variations select",function(){
		var sel_value=jQuery(this).val();
		var product_id=jQuery("form.variations_form").data("product_id");
		jQuery.ajax({
			url:ajaxURL,
			type:'POST',
			dataType:'json',
			data:{ action:'extra_variation_ajax',sel_value:sel_value,product_id:product_id},
			success:function(data,textStatus,statusCode){
				if(data.status=="1"){
					jQuery("aside.wc_extra_variation_img_wrapper").html(data.data);
				}
			},
			error:function(data,textStatus,statusCode){
		
			}
		});
	});
});