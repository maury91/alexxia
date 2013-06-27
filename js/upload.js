(function($){
	var aleupload = function() {
		var f_nothing = function() {
			return true;
		},
		defaults = {
			over : function() {$(this).css({'border' : '3px dashed rgba(127,127,127,0.5)'}); return false;},
			leave : function() {$(this).css({'border' : ''}); return false;},
			success : f_nothing,
			error : f_nothing,
			progress : f_nothing,
			uid : -1,
			multiple : false,
			url : __http_base+'zone_media_man.html',
			data : {act : 'upl', uid : -1, d : ''},
			button : null
		},
		processFiles = function(files,params) {
			console.log(files,params);
			if(files && typeof FileReader !== "undefined") {
				if (params.multiple)
					for(var i=0; i<files.length; i++)
						readFile(files[i],params);
				else
					readFile(files[0],params);
			}
			else {
				//some message or fallback
			}
		},
		readFile = function(file,params) {			
			if (file) {
				console.log(file);
				var fileSize = 0,
					sizeA = file.size,
					sizeB = 0,
					sizes=['B','KB','MB','GB','TB'];
				while (sizeA > 1024) {
					sizeA /= 1024;
					sizeB++;
				}
				var fileSize=(Math.round(sizeA*100)/100)+sizes[sizeB],
					fileInfo = {sizeB : file.size, size : fileSize, name : file.name},
					data = params.data;
				data.myfile = file.name;
				/*var options = {
					url : params.url,
					data : data,
					type : 'post',
					dataType : 'json',
					success : params.success,
					error : params.error
				};*/
				/*urldata = $.param(data);
				options['xhr'] = function() {
					var xhr = new window.XMLHttpRequest();
					//Upload progress
					//xhr.file = file;
					xhr.upload.addEventListener("progress", params.progress, false);
					return xhr;
				}
				$.ajax(options);*/

				var xhr = new XMLHttpRequest();
				xhr.file = file; // not necessary if you create scopes like this
				xhr.upload.onprogress = params.progress;
				xhr.onreadystatechange = function(e) {
					if ( 4 == this.readyState ) {
						response = eval("(" + xhr.responseText + ")");
						params.success(response);
					} else
						params.error(e);
				};
				url = params.url+'?'+$.param(data);
				xhr.open("POST", url, true);
				xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
				xhr.setRequestHeader("X-File-Name", encodeURIComponent(file.name));
				xhr.setRequestHeader("Content-Type", "application/octet-stream");
				xhr.send(file);	
			}
		};
		return {
			init : function(opt) {
				opt = $.extend({}, defaults, opt||{});
				if (opt.uid != -1)
					opt.data.uid = opt.uid;
				return this.each(function() {
					$(this).data('aleuploadparams',opt);
					$(this).on('dragover', opt.over);
					$(this).on('dragleave', opt.leave);
					$(this).on('drop', function(e) {
						//prevent browser from open the file when drop off
						e.stopPropagation();
						e.preventDefault();
						$(this).trigger('dragleave');
						var params = $(this).data('aleuploadparams');
						var files = e.originalEvent.dataTransfer.files;
						processFiles(files,params);
						return false;
					});
					if (opt.button!=null)
						opt.button
							.append($('<input/>').hide().data('aleuploadparams',opt).attr({type:'file',multiple:opt.multiple}))
							.click(function(e) {
								$(this).find('input[type="file"]').click();
							});
					opt.button.find('input[type="file"]').on('change', function(e) {
						console.log(e);
						e.stopPropagation();
						e.preventDefault();
						var files = $(this)[0].files;
						var params = $(this).data('aleuploadparams');
						processFiles(files,params);
						return false;
					}).click(function(e){
						e.stopPropagation();
					});
				})
			}
		}
	}();
	$.fn.extend({
		aleUpload : aleupload.init
	});
})(jQuery);