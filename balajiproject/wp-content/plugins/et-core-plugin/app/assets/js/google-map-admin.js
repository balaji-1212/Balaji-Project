function ethemeMapFindAddress(ob){
	var address=jQuery(ob).parent().find('input').val();
	if(address!=''){
		geocoder=new google.maps.Geocoder();
		geocoder.geocode({'address':address},function(results,status){
			if(status==google.maps.GeocoderStatus.OK){
				var output=jQuery(ob).parent().find('.etheme-output-result');
				var latiude=results[0].geometry.location.lat();
				var longitude=results[0].geometry.location.lng();
				jQuery(output).html("Latitude: "+latiude+"<br>Longitude: "+longitude+"<br>(Copy and Paste your Latitude & Longitude value below)");
				jQuery(ob).parents('.elementor-control-map_notice').nextAll('.elementor-control-map_lat').find("input").val(latiude).trigger("input");
				jQuery(ob).parents('.elementor-control-map_notice').nextAll('.elementor-control-map_lng').find("input").val(longitude).trigger("input")
			}else{
				alert("Geocode was not successful for the following reason: "+status)
			}
		})
	}
}

function ethemeMapFindPinAddress(ob){
	var address=jQuery(ob).parent().find('input').val();
	if(address!=''){
		geocoder=new google.maps.Geocoder();
		geocoder.geocode({'address':address},function(results,status){
			if(status==google.maps.GeocoderStatus.OK){
				var output=jQuery(ob).parent().find('.etheme-output-result');
				var latiude=results[0].geometry.location.lat();
				var longitude=results[0].geometry.location.lng();jQuery(output).html("Latitude: "+latiude+"<br>Longitude: "+longitude+"<br>(Copy and Paste your Latitude & Longitude value below)");
				jQuery(ob).parents('.elementor-control-pin_notice').nextAll('.elementor-control-pin_lat').find("input").val(latiude).trigger("input");
				jQuery(ob).parents('.elementor-control-pin_notice').nextAll('.elementor-control-pin_lng').find("input").val(longitude).trigger("input")
			}else{
				alert("Geocode was not successful for the following reason: "+status)
			}
		})
	}
}
(function(jQuery){jQuery('.repeater-fields').each(function(){jQuery(this).click(function(){jQuery('.etheme-output-result').empty()})})})