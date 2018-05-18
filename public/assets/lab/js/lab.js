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
						
						console.log(callback);
						eval(callback+";");
					}

					if (data.redirect)
						window.location.replace(data.redirect);

					if (data.success)
						simpleSuccess (frm, data.success);

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

	});

	$(document).on('click', '.callback', function(e){
		var callback = $(this).data('callback');
		eval(callback+";");
	});

	$(document).on('click', ".close-upload-meta", function(){
		$('#upload-meta').html('');				
		$('#upload-plugin').show(0);				
	});

	$(document).on('click', ".change-flag", function(){
		var btn = $(this);
		var route = $(this).data('route');
		var html = btn.html();
		$(btn).removeClass('colorRed');
		$(btn).removeClass('colorGreen');
		btn.html('<i class="fa fa-spinner fa-spin" aria-hidden="true"></i>'); // spinner

		$.get(route, function(data){
			if (data.success) {

				var result = JSON.parse(data.result);
				if (parseInt(result['flag']) == 1) $(btn).addClass('colorGreen');
				else $(btn).addClass('colorRed');

				btn.html(html);

				// delete tootip
				$('div.tooltip').remove();
				$('.tip').tooltip();
			}
		}, 'json');	
	});

	$(document).on('keyup','.google-snippet', function(){
		var lang = $(this).data('v');
		var snippet = $('#snippet-' + lang);
		var title = $('#mtitle-' + lang).val();
		var description = $('#mdescription-' + lang).val();
		var murl = 'www.domain.com/' + slugify($('#murl-' + lang).val());

		$(snippet).serpSnippet({
			title: title,
			url: murl,
		    description: description,
		    search: ""
		});		
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
    jQuery.alerts.dialogClass = 'alert-danger';
	jAlert(message, 'Error', function(){
                   jQuery.alerts.dialogClass = null; // reset to default
                });    
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
	jQuery.jGrowl(message);    
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

function openCl (result) {
	var route = result.route;
	getHtml(route);
}	

function openContact (result) {
	var route = result.route;
	getHtml(route);
}	

function openQi (id) {
	(function($) {
		var el = $(id);
		if (el.length)
			el.trigger('click');
	})(jQuery);	
}

function refreshCalendar() {
	bootbox.hideAll();
	(function($) {
	$('#event-calendar').fullCalendar('refetchEvents');
	})(jQuery);
}

function slugify(text)
{
  return text.toString().toLowerCase()
    .replace(/\s+/g, '-')           // Replace spaces with -
    .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
    .replace(/\-\-+/g, '-')         // Replace multiple - with single -
    .replace(/^-+/, '')             // Trim - from start of text
    .replace(/-+$/, '');            // Trim - from end of text
}

function demoUpload() {    
    (function($) {

        var $uploadCrop;

        function readFile(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                
                reader.onload = function (e) {
                    $uploadCrop.croppie('bind', {
                        url: e.target.result
                    });
                    $('.upload-crop').show(0);
                }
                
                reader.readAsDataURL(input.files[0]);
            }
            else {
                alert("Sorry - you're browser doesn't support the FileReader API");
            }
        }

        $uploadCrop = $('#crop-img').croppie({
            viewport: {
                width: 300,
                height: 300
            },
            boundary: {
                width: 340,
                height: 340
            },
            exif: true
        });

        $('#upload').on('change', function () { readFile(this); });
        $('.upload-crop').on('click', function (ev) {
            var btn = $(this); // for submitting form
            $uploadCrop.croppie('result', {
                type: 'canvas',
                size: 'viewport'
            }).then(function (resp) {
                $('#base64Pic').val(resp);
                var frm = btn.closest('form.ns');
                frm.trigger('submit');
                // btn.addClass('submit');
                // setTimeout(function(){
                //     btn.removeClass('submit');
                // }, 2000);            
            });
        });
    })(jQuery);
}

function changeAvatarPic (pic) {
    (function($) {
        $('.avatar').attr('src', pic);
    })(jQuery);
}

function initUI() {
	(function($) {
		$('.tabbedwidget').tabs();

        // Select with Search
        jQuery(".chzn-select").chosen();

	    // crop	    
	    if ($('#crop-img').length) {        
	        demoUpload();
	    }		
		
		// ckeditor
		$('.wysiwyg_editor').ckeditor();	

		$('.datepicker').each(function(i, el){
			var format = $(el).data('format');
			$(el).datepicker({ dateFormat: format });			
		});

		// tooltip
		$('.tip').tooltip();

		// tag input
		$("textarea[name='mkeys']").each(function(i, el){
			var attr = $(el).attr('id');
			if (typeof attr === 'undefined' || attr === false) {
				$(el).tagsInput();
			}			
		});

		$('.preview').anarchytip();

		// Google Serp snippet 
		$("input.google-snippet[name='mtitle']").trigger('keyup');

		// color picker
		if(jQuery('#colorpicker').length > 0) {
			var color = $('#colorpicker').val();			
			jQuery('#colorSelector').ColorPicker({
				color: color,
				onShow: function (colpkr) {
					jQuery(colpkr).fadeIn(500);
					return false;
				},
				onHide: function (colpkr) {
					jQuery(colpkr).fadeOut(500);
					return false;
				},
				onChange: function (hsb, hex, rgb) {
					jQuery('#colorSelector span').css('backgroundColor', '#' + hex);
					jQuery('#colorpicker').val('#'+hex);
				}
			});
			jQuery('#colorSelector span').css('backgroundColor', color);
		}		

		// sortable
		$(".sortable").sortable({ 
			handle: '.handle',
			helper: fixWidthHelper,
			placeholder: "sortable-highlight",
		    update: function(event, ui) {
		        var order = $(this).sortable("serialize");
		        var route = $(this).data('route');
		        var token = $(this).data('token');

				$.ajax({
				    url: route,
				    type: 'POST',
				    data: order,
				    dataType: 'json',
					headers: {
							"x-csrf-token" : token
					},
				    success: function(data) {
						if (data.success)
							popUpSuccess (data.success);
						
						if (data.error)
							popUpError (data.error);
				    }
				});	
		    }
		});		

		function fixWidthHelper(e, ui) {
		    ui.children().each(function() {
		        $(this).width($(this).width());
		    });
		    return ui;
		}

		// plupload
		if ($('#filelist').length > 0) {

			var empty = $('#filelist').html();			
			var route = $('#filelist').data('route');
			var token = $('#filelist').data('token');
			var uploader = new plupload.Uploader({
								browse_button: 'browse', // this can be an id of a DOM element or the DOM element itself
  								url: route,
        						max_file_size : '2mb',
        						chunk_size: '1mb',
        						headers: {
        							"x-csrf-token" : token
    							}
							});
			uploader.init();

			uploader.bind('FilesAdded', function(up, files) {
				
				$('#filelist').html('');

			  	plupload.each(files, function(file) {
			  		tr = $('<tr>').attr('id', file.id);
			    	td_delete = $('<td><button class="btn btn-xs btn-primary remove-upload" data-v="'+file.id+'"><i class="fa fa-trash-o" aria-hidden="true"></i></button></td>');
			  		td_name = $('<td>').html(file.name);
			  		td_size = $('<td>').html(plupload.formatSize(file.size));
					td_progress = $('<td><div class="progress"><div id="p_'+file.id+'" class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div></td>');

			    	td_delete.appendTo(tr);
			    	td_name.appendTo(tr);
			    	td_size.appendTo(tr);
			    	td_progress.appendTo(tr);

			    	tr.appendTo($('#filelist'));
			  	});
			});
			 
			uploader.bind('UploadProgress', function(up, file) {
				$('#p_'+file.id).attr('aria-valuenow', file.percent).css('width', file.percent+'%');
			});

			uploader.bind('UploadComplete', function(up, file) {
				setTimeout(function(){
					$('#refresh-tabs-attachments').trigger('click');
				}, 2000);
			});
			 
			uploader.bind('Error', function(up, err) {
				popUpSuccess ("Error #" + err.code + ": " + err.message)
			});
			 
			document.getElementById('start-upload').onclick = function() {
			  	uploader.start();
			};

			$(document).on('click', '.remove-upload', function(){
				var id = $(this).data('v');
        		var tr = $(this).closest('tr');
				$.each(uploader.files, function (i, file) {
				    if (file && file.id == id) {
				        uploader.removeFile(file);
				        tr.remove();
				    }
				});				
			});
		}

	})(jQuery);
}