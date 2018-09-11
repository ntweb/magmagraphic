var maincontainer = '#rpc-container'; // container rprincipale per risultati RPC

(function($) {

	$(document).on('ready', function(){
	
		$(document).on('submit', 'form.ns', function(e){
			e.preventDefault();

			var frm = $(this);
			var btn = frm.find("*[type='submit']").first();
			var route = frm.data('route');
			if (typeof route === 'undefined' ) {alert ('error: no route detected.'); return false;}

			var check = frm.find("*[type='checkbox'][name='confirm']").first();
			if (check.length)
				if (!check.is(':checked')){
					popUpError('Please confirm.')
					return false;
				}

			var dataserialized = frm.serialize();
			var callback = frm.data('callback');
			if (typeof callback === 'undefined') callback = null;

			var method = frm.data('method');
			if (typeof method === 'undefined') method = 'POST';

			if (method == 'POST') {
				var dataserialized = new FormData(frm[0]); // per upload dei file
				var processData = false;
				var contentType = false;
			}
			else { // PUT
				var dataserialized = frm.serialize();				
				var processData = false;				
				var contentType = 'application/x-www-form-urlencoded,multipart/form-data,text/plain';
			}			

			var btnOldValue = btn.html();
			btn.attr('disabled', 'disabled');
			btn.html('Wait..');
			waitButton(btn, false);

			var dataType = 'json';

	        $.ajax({
	            method: method,
	            url: route,
				data: dataserialized,
				cache: false,
				processData: processData,
				contentType: contentType,				
	            dataType: dataType,
	            timeout: 60000, // timeout ad 1 minuto
	            error: function(jqXHR, textStatus, errorThrown){
	              if (textStatus == 'timeout') {
	              	popUpError ('Timeout error');                
	              } 
				  else {				  	
				  	popUpError (jqXHR.status+" "+errorThrown);
				  }
	            },
	            beforeSend: function() {
					hideErrorsSuccess();
	            },
	            success: function(data){
					if (data.error) {
						simpleError (frm, data.error);
					}
					if (data.errorfields) {
						fieldsError (frm, data.errorfields);
					}

					if (data.error || data.errorfields)
						return false;

					if (callback) {
						if (data.result)
							callback = callback.replace("param", data.result);
						
						eval(callback+";");
					}

					if (data.redirect)
						window.location.replace(data.redirect);

					if (data.success)
						simpleSuccess (frm, data.success);

					if (data.pop_up_success)
						alertify.success(data.pop_up_success);					

		  			var _ismodified = frm.find("input[name='_ismodified']").first();
		  			if (_ismodified.length) {
		  				_ismodified.val(0);
		  			}


	            },
	            complete: function(){
				  btn.html(btnOldValue);
				  btn.removeAttr('disabled');
				  waitButton(btn, false);
				  
				  if (frm.hasClass('ns-reset')) {				  	
				  	frm[0].reset();
				  }
	            }
	        });					

		});

		$(document).on( "submit", ".form-get-html", function(e) {
			e.preventDefault();
			var route = $(this).data('route');
			var container = $(this).data('container');
			var callback = $(this).data('callback');
			var confirm = $(this).data('confirm');
			
			var dataserialized = $(this).serialize();

			if (route.indexOf("?")<0)			
				route = route+'?'+dataserialized;
			else
				route = route+'&'+dataserialized;

			getHtml(route, container, callback, confirm);
		});

		$(document).on( "click", ".get-html", function(e) {
			e.preventDefault();
			var btn = $(this);
			var route = btn.data('route');
			var container = btn.data('container');
			var callback = $(this).data('callback');
			var confirm = $(this).data('confirm');			

			getHtml(route, container, callback, confirm);
		});

		$(document).on( "click", ".get-json", function(e) {
			e.preventDefault();
			var btn = $(this);
			var route = btn.data('route');
			var callback = btn.data('callback');
			if (typeof callback === 'undefined') callback = null;

			getJson(route, btn, callback);
		});

		$(document).on( "click", ".delete-json", function(e) {
			e.preventDefault();
			var btn = $(this);
			var route = btn.data('route');
			var callback = btn.data('callback');
			var token = btn.data('token');
			if (typeof callback === 'undefined') callback = null;

			if (typeof btn.data('confirm') !== 'undefined') {
				if (confirm(btn.data('confirm'))) 
					deleteJson(route, token, btn, callback);
			}
			else {
				deleteJson(route, token, btn, callback);	
			}
		});

	   $(document).on( "change", "._ismodified input, ._ismodified select, ._ismodified textarea", function(){
	   		var frm = $(this).closest('form');
	   		$(this).addClass('warning');
	   		if (frm.length) {
	  			var _ismodified = frm.find("input[name='_ismodified']").first();
	  			if (_ismodified.length) {
	  				// console.log(_ismodified);
	  				_ismodified.val(1);
	  			}
	   		}
	   }); 	

	   $(document).on('submit', 'form.ns-disable-submit', function(e){
	   		$("button", $(this)).attr('disabled', 'disabled');
	   });

	   // map
	   if ($("*[data-widget='map']").length) {
	   		if($("*[data-widget='infowindow']").length) {

	   			var widget = $("*[data-widget='infowindow']").first();
	   			var txt_infowindow = widget.html();
	   			var txt_lat = parseFloat(widget.data('lat'));
	   			var txt_lng = parseFloat(widget.data('lng'));

			    var zoom = 16;
				var myLatlng = new google.maps.LatLng(txt_lat, txt_lng);
				var mapOptions = {
					zoom: zoom,
					center: myLatlng
				}
				var infowindow = new google.maps.InfoWindow({
					content: txt_infowindow,
				});		

				var map = new google.maps.Map($("*[data-widget='map']").first()[0], mapOptions);
				var marker = new google.maps.Marker({position: myLatlng, map: map});

				map.setZoom(zoom);
				infowindow.open(map,marker);
	   		}
	   }

	});

	$(document).on('click', '.callback', function(e){
		var callback = $(this).data('callback');
		eval(callback+";");
	});

	$(document).on('click', ".close-upload-meta", function(){
		$('#upload-meta').html('');				
		$('#upload-plugin').show(0);				
	});


	// google geocomplete
    $("input[name='city']")
    .geocomplete({ types: ['(cities)'] })
    .bind("geocode:result", function(event, result){
        $(this).prevAll("input[name='place_id']").first().val(result.place_id);
        var political_short_name = $(this).prevAll("input[name='political_short_name']").first();
        var country_short_name = $(this).prevAll("input[name='country_short_name']").first();

        var geocoder = new google.maps.Geocoder;
        geocoder.geocode({'placeId': result.place_id}, function(results, status) {
            if (status === google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                    var address_components = results[0].address_components;
                    $(address_components).each(function(i, el){
                        if (el.types.indexOf('country') >= 0) {                                
                            country_short_name.val(el.short_name)
                        } else if (el.types.indexOf('political') >= 0) {
                            political_short_name.val(el.short_name)
                        }

                    });
                }
            } else {
              window.alert('Geocoder failed due to: ' + status);
            }
          });          
    });

    $(document).on('ready', function(){
        if ($("input[name='city']").length) {
            setTimeout(function() {
                $("input[name='city']").attr("autocomplete", "none");
            }, 1000);
        }
    });

})(jQuery);

function getHtml(route, container, callback, confirmmsg) {
	(function($) {
		if(typeof container == "undefined") 
			container = maincontainer;

		if(typeof confirmmsg != "undefined") {
			if (!confirm(confirmmsg)) return false;
		}

		spinner(container);

		$.get(route, function(data){
			$(container).html(data);

			if (typeof callback !== 'undefined')
				eval(callback+";");

			initUI();
		}, 'html');
	})(jQuery);
}

function getHtmlNoSpinner(route, container) {
	(function($) {
		if(typeof container == "undefined") 
			container = maincontainer;

		$.get(route, function(data){							
			$(container).html(data);
			initUI();
		}, 'html');
	})(jQuery);
}

function getJson(route, btn, callback) {
	(function($) {
		var txt = btn.html();
		btn.html('wait...');
		$.get(route, function(data){							

			btn.html(txt);

			if (data.error) {
				popUpError (data.error);
				return;
			}

			if (callback) {
				if (data.result)
					callback = callback.replace("param", data.result);
								
				eval(callback+";");
			}

			if (data.success)
				popUpSuccess (data.success);
			
		}, 'json');

	})(jQuery);
}

function deleteJson(route, token, btn, callback) {
	(function($) {
		var txt = btn.html();
		btn.html('wait...');
		$.ajax({
		    url: route,
		    type: 'DELETE',
		    dataType: 'json',
			headers: {
					"x-csrf-token" : token
			},
		    success: function(data) {
				btn.html(txt);

				if (data.error) {
					popUpError (data.error);
					return;
				}

				if (callback) {
					if (data.result)
						callback = callback.replace("param", data.result);
									
					eval(callback+";");
				}

				if (data.success)
					popUpSuccess (data.success);
		    }
		});		
	})(jQuery);
}

function spinner (container) {
	(function($) {
		data = '<div style="margin-left: 10px; margin-top: 5px;"><i class="fa fa-circle-o-notch fa-spin spinner" aria-hidden="true"></i> wait...</div>';
		$(container).html(data);
	})(jQuery);
}

function waitButton(btn, bool) {
	(function($) {
		if (bool) {
			btn.hide(0);
			var loader = $('<div style="margin-left: 10px; margin-top: 5px;"><i class="fa fa-circle-o-notch fa-spin spinner" aria-hidden="true"></i> wait...</div>');
			loader.insertBefore(btn);
		}
		else {
			$('.spinner').remove();
			btn.show(0);
		}
	})(jQuery);
}

function simpleError (frm, message) {
	var errorAlert = frm.find('.alert-danger').first();
	if (errorAlert.length > 0) {
		errorAlert.html(message);
		errorAlert.show(0);
		return true;
	}

	popUpError(message);		
}

function fieldsError (frm, errorFields) {
	(function($) {
		$.each(errorFields, function(i, el) {
			var element = frm.find("*[name='"+i+"']").first();
			element.closest('.form-group').addClass('has-error');
			$('<span>').addClass('label')
					.addClass('label-danger')
					.addClass('pull-left')
					.addClass('margin-top2px')
					.text(el)
					.insertAfter(element);
		});
	})(jQuery);
}

function popUpError (message) {		
	alert(message);    
}

function simpleSuccess (frm, message) {
	var successAlert = frm.find('.alert-success').first();
	if (successAlert.length > 0) {
		successAlert.html(message);
		successAlert.show(0);
		return true;
	}

	popUpSuccess(message);
}

function popUpSuccess (message) {
	// jQuery.jGrowl(message);    
}

function hideErrorsSuccess () {
	(function($) {
		$('.alert-danger').hide(0);
		$('.alert-sucess').hide(0);
		$('.label-danger').remove();
		$('*').removeClass('has-error');
		$('*').removeClass('warning');
	})(jQuery);
}	

function initUI() {
	(function($) {

		// all to initiaize

	})(jQuery);
}