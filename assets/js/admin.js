jQuery(document).ready(function(){
var media_uploader = null;
var ua = navigator.userAgent,pickclick = (ua.match(/iPad/i) || ua.match(/iPhone/)) ? "touchstart" : "click";
/**********************************Upload Images From List*************************************************/
jQuery(document).on(pickclick,".upload_varitn_img",function(){
	var curr_obj=jQuery(this);
	if(!curr_obj.parents(".woocommerce_variation.wc-metabox").find("a.upload_image_button").hasClass("remove")){
		alert("Please select varition default image");
		return false;
	}
	media_uploader = wp.media({
        frame:    "post", 
        state:    "insert", 
        multiple: true 
    });

    media_uploader.on("insert", function(){

        var length = media_uploader.state().get("selection").length;
        var images = media_uploader.state().get("selection").models
		var image_ids="";
		var htmlData="";
        for(var iii = 0; iii < length; iii++)
        {
            var image_title = images[iii].changed.title;
			htmlData += "<span class='img'><img src='"+images[iii].changed.url+"' data-id='"+images[iii].id+"' title='"+image_title+"' width='80' height='80'/><a href='javascript:void 0' class='remove_icon'>X</a></span>";
			image_ids  += images[iii].id+",";
	   }
		curr_obj.parent(".variations_images_list").find(".hidden_img").val(image_ids);
		curr_obj.parent(".variations_images_list").find(".img_list").html(htmlData);
    });

    media_uploader.open();
});
/**********************************Remove Images From List*************************************************/
jQuery(document).on(pickclick,".variations_images_list .remove_icon",function(){
		var image_ids="";
		var curr_obj=jQuery(this);
		var curr_id=curr_obj.prev("img").data("id");
		var curr_list=curr_obj.parents(".img_list").find("span.img");
		curr_list.each(function(){
				if(curr_id!=jQuery(this).find("img").data("id"))
					image_ids += jQuery(this).find("img").data("id")+",";
		});
		curr_obj.parent(".variations_images_list").find(".hidden_img").val(image_ids);
		curr_obj.parent("span.img").remove();
});
   	
});