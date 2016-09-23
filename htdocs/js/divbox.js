/*
	DivBox v1.2
	@copyright: http://wwww.phpbasic.com
	@Download: http://code.google.com/p/divbox
	@Contact: w2ajax@gmail.com for lastest version.
*/
(function($){
	$.fn.divbox = function(opt){
		var _cfg = {
			width: null, 
			height: null,
			speed: 500,
			left: null,
			top: null,
			type: null,
			src: 'href',
			scrollbar: 'auto',
			btn_closed: '#divbox_frame .closed',
			btn_prev: '#divbox_frame .prev',
			btn_next: '#divbox_frame .next',
			btn_number: '#divbox_frame .number',
			path: 'players/',
			full_drag_handle: false,
			resize_large_image: true,
			click_full_image: true,
			overlay: true,
			caption: true,
			caption_control: true,
			caption_number: false,
			event: 'click',
			container: document.body,
			download_able:['pdf','zip','gz','rar','doc','docx','xls','xslx','ppt','pptx','csv'],
			languages: {
				btn_close: 'Close',
				btn_next: 'Next',
				btn_prev: 'Prev',
				click_full_image: 'Click on here to view full image',
				error_not_youtube: 'This is not a youtube link',
				error_cannot_load: "We can't load this page\nError: "
			},
			api:{
				start: null,
				beginLoad: null,
				afterLoad: null,
				closed: null
			}
			
		}
		if(opt) $.extend(_cfg,opt);
		
		var oMatch = this;
		var objArr = [];
		$(oMatch).each(function(i,o){
			objArr[i] = o;
		});
		function _run(index,init){
			var fn = {}
			fn.closed = function(){
				$('#divbox_frame').animate({
					'top': _click.top,
					'left': _click.left,
					width: '0px',
					height: '0px'
				},_cfg.speed,function(){
					$(this).remove();
					$('#divbox').remove();
					fn.toggleObj('body','show');
					if(typeof(_cfg.api.closed)=='function') _cfg.api.closed(this);
				});
			}
			
			fn.toggleObj = function(o,act){
				if(ie6){
					if(act=='show') $(o).find('embed,object,select').show();
					else $(o).find('embed,object,select').hide();
				}
			}
			
			fn.init = function(){
				if(typeof(_cfg.api.start)=='function') _cfg.api.start(obj);
				var requires  = '#divbox,#divbox_frame,#divbox_content,#divbox_ajax';
				$(requires).remove();
				$(_cfg.container).prepend('<div id="divbox"></div><div id="divbox_ajax"></div><div id="divbox_frame"><div class="closed" title="'+_cfg.languages.btn_close+'"></div><div id="divbox_data"><div id="divbox_content"></div><div class="prev" title="'+_cfg.languages.btn_prev+'"></div><div class="caption"></div><div class="number">10/10</div><div class="next" title="'+_cfg.languages.btn_next+'"></div></div></div>');
				$(_cfg.btn_closed+','+_cfg.btn_next+','+_cfg.btn_prev+','+_cfg.btn_number).hide();
				if(_cfg.overlay){
					$('#divbox').css({
						'width': sizesystem[0]+'px',
						'height': sizesystem[1]+'px',
						'position':'absolute',
						'zIndex':'10001',
						'left':'0',
						'top':'0'
					}).click(function(){
						fn.closed();
					});
				}
				$('#divbox_frame').css({
					'position':'absolute',
					'top': _click.top,
					'left': _click.left,
					'zIndex':'10002',
					'width': 0,
					'height': 0
				}).animate({
					width: 50, 
					height: 50,
					top: _cfg.top?sizesystem[3] +  _cfg.top: sizesystem[3] + Math.round(sizesystem[5]/2),
					left: _cfg.left?sizesystem[2] +_cfg.left:Math.round(sizesystem[4]/2) 
				});
			}
			fn.resizeWindow = function(resizeW){
				var sizesystem = pageSize(ie);
				$('#divbox').css({
					'width': sizesystem[0]+'px',
					'height': sizesystem[1]+'px'
				});
				var w = $(oFrame).outerWidth();
				var h = $(oFrame).outerHeight();
				$(oFrame).css({
					top: _cfg.top?sizesystem[3] +  _cfg.top: sizesystem[3] + Math.round((sizesystem[5] - h)/2),
					left:  _cfg.left?sizesystem[2] +_cfg.left:Math.round((sizesystem[4] - w)/2) 
				});
			}
			fn.animate = function(t,l,w,h,fncallback,fnclosed, caption){
				$('#divbox').unbind('click');
				$(document).unbind('keydown');
				$(_cfg.btn_closed+','+_cfg.btn_next+','+_cfg.btn_prev+','+_cfg.btn_number).hide();
				var border = 0;//ie?parseInt($(oFrame).css('border-left-width'))+parseInt($(oFrame).css('border-right-width')):0;
				$(oFrame).removeClass('white').animate({
					left:_cfg.left?_cfg.left:l,
					width:_cfg.width?_cfg.width+border:w+border
				},_cfg.speed).animate({
					top: _cfg.top?sizesystem[3] + _cfg.top:t, 
					height: _cfg.height?_cfg.height:h
				},_cfg.speed,function(){		
					fn.toggleObj('body');
					var oContent = $('#divbox_content');
					if(typeof(_cfg.api.beginLoad)=='function') _cfg.api.beginLoad(oContent);
					if(typeof(fncallback) == 'function') fncallback(oContent);
					if(typeof(_cfg.api.afterLoad) == 'function') _cfg.api.afterLoad(oContent);
				
					$(_cfg.btn_closed).show().click(function(){
						if(typeof(fnclosed) == 'function') fnclosed(oContent);
						fn.closed();
					});
					$('#divbox').bind('click',function(){
						if(typeof(fnclosed) == 'function') fnclosed(oContent);
						fn.closed();
					});
					
					$(this).addClass('white');
					
					var c = $(oFrame).find('.caption');
						
					if(_cfg.caption != false && (caption != '' || _cfg.caption_control == true)){
						var cH = c.outerHeight(true);
						$(oFrame).animate({height: h + cH}).find('.caption').show();
						
						if(_cfg.caption_control == true){// caption control
							var btn_top = h + parseInt(c.css('padding-top')); // 12 = 1/2 height of button prev/next icon
							$(_cfg.btn_prev+','+_cfg.btn_next+','+_cfg.btn_number).css({top: btn_top}).show();
							if(index*1>0){
								$(_cfg.btn_prev).removeClass('prevDisabled').bind('click',function(){
									fn.prevItem(index);
								});
							}else{
								$(_cfg.btn_prev).addClass('prevDisabled').unbind('click');		
							}
							if(_cfg.caption_number){
								$(_cfg.btn_number).html((index*1+1)+'/'+total)
							}else{
								$(_cfg.btn_number).remove();
							}
							if(index*1<total - 1){
								$(_cfg.btn_next).removeClass('nextDisabled').bind('click',function(){
									fn.nextItem(index);
								});
							}else{
								$(_cfg.btn_next).addClass('nextDisabled').unbind('click');	
							}
						}else{ // have no caption control
							$(c).css({'padding-left': '5px', 'padding-right': '5px'});		
						}
						
					}
						
					
					
					$(document).bind('keydown',function(e) {
						var k = e?e.keyCode:event.keyCode;
						if(k == 27){
							if(typeof(fnclosed) == 'function') fnclosed($('#divbox_content'));
							fn.closed();
						}
						if(_cfg.caption != '' && _cfg.caption_control==true){
							if(k == 38 || k==39){ fn.nextItem(index); return false; }
							if(k == 37 || k==40){ fn.prevItem(index); return false; }
						}
					});
					
					try {
						var drag_handle = _cfg.caption == false?'#divbox_frame':'#divbox_frame .caption';
						$("#divbox_frame").draggable({ handle: $(drag_handle) }).css({ cursor: 'move' });
						if(!_cfg.full_drag_handle) $('#divbox_content').css({ cursor: 'pointer' });
					} catch(e) { /* requires jQuery UI draggables */ }
					
					$(window).bind('resize scroll',function(){
						fn.resizeWindow();
					});
					
					
				});
			}
			
			
			fn.prevItem = function(index){
				if(index*1>0) _run(index*1 - 1);
			}
			
			fn.nextItem = function(index){
				if(index*1<total - 1) _run(index*1 + 1);
			}
			
			fn.parseType = function(src){
				if(_cfg.type) return _cfg.type;
				if (src.match(/youtube\.com\/watch/i)){
					return 'youtube';
				}
				var aExt = src.split('.');
				var ext = aExt[aExt.length-1];
				return ext.toLowerCase();
			}
			fn.viewImage = function(src,caption){
				$('#divbox_content').html('<img src="'+src+'" />').find('img').hide();
				var Img = new Image();
				Img.onload = function(){
					$('#divbox_content img').attr('src',src);
					var imgW = Img.width;
					var imgH = Img.height;
					var zoom = 0;
					if(_cfg.resize_large_image){
						if(imgW >= sizesystem[4] - 100 || imgH >= sizesystem[5] - 100){
							if(imgW >= sizesystem[4] - 100){
								imgW = sizesystem[4] - 100;
								imgH = Math.round(imgW*Img.height/Img.width);
							}
							if(imgH >= sizesystem[5] - 100){
								tH = sizesystem[5] - 100;
								imgW = Math.round(imgW*tH/imgH);
								imgH = tH;
							}
							if(_cfg.click_full_image) zoom = 1;
						}
					}
					var top = sizesystem[3] + Math.round((sizesystem[5] - imgH)/2);
					var left = Math.round((sizesystem[4] - imgW)/2);
					fn.animate(top,left,imgW,imgH,function(o){
						if(ie6 && ext == 'png'){
							$(o).find('img').wrap('<span style="display:inline-block;width: '+imgW+'px;height: '+imgH+'px;filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='+src+');"></span>');
						}else{
							$(o).find('img').css({'width': imgW,'height': imgH,'display':'block'}).fadeIn();
						}
						if(zoom) $(o).find('img').addClass('zoom').attr({'title': _cfg.languages.click_full_image}).click(function(){
							window.open(src,'wDivBox');
						});
					},false,caption)
					//IE 
					Img.onload=function(){};
				}
				Img.src = src;
				
			}
			fn.flashEmbedString = function(file,w,h,type){ // default type is FLV
				var flashvar = '&provider=video';
				var swf_file = type=='swf'? file : _cfg.path+'jwplayer.swf';
				if(type=='mp3') flashvar = '&provider=sound';
				var str = '<object id="player" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" name="player" width="'+w+'" height="'+h+'">';
				str += '<param name="movie" value="'+swf_file+'" />';
				str += '<param name="allowfullscreen" value="true" />';
				if(type!='swf') str += '<param name="flashvars" value="file='+file+'&autostart=true'+flashvar+'" />';
				str += '<embed  pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" quality="high"';
					str += ' type="application/x-shockwave-flash"';
					str += ' id="player2"';
					str += ' name="player2"';
					str += ' src="'+swf_file+'" ';
					str += ' width="'+w+'" ';
					str += ' height="'+h+'"';
					str += ' allowfullscreen="true"';
					if(type!='swf') str += ' flashvars="file='+file+'&start=true&autostart=true'+flashvar+'" ';
				str += ' />';
				str += '</object>';
				return str;
				
			}
			fn.viewFLV = function(src,caption){
				var winW = 400;
				var winH = 300;
				var top = sizesystem[3] + Math.round((sizesystem[5] - winH)/2);
				var left = Math.round((sizesystem[4] - winW)/2);
				var str = fn.flashEmbedString(obj.href,winW,winH,'flv');
				fn.animate(top,left,winW,winH,function(o){
					$(o).html(str);
				},false,caption);
			}
			fn.viewMP4 = function(src,caption){
				var winW = 400;
				var winH = 300;
				var top = sizesystem[3] + Math.round((sizesystem[5] - winH)/2);
				var left = Math.round((sizesystem[4] - winW)/2);
				var str = fn.flashEmbedString(obj.href,winW,winH,'mp4');
				fn.animate(top,left,winW,winH,function(o){
					$(o).html(str);
				},false,caption);
			}
			fn.viewMP3 = function(src,caption){
				var winW = 320;
				var winH = 80;
				var top = sizesystem[3] + Math.round((sizesystem[5] - winH)/2);
				var left = Math.round((sizesystem[4] - winW)/2);
				var str = fn.flashEmbedString(src,winW,winH,'mp3');
				fn.animate(top,left,winW,winH,function(o){
					$(o).html(str);
				},false,caption);
			}
			fn.viewWMV = function(src,caption){
				var winW = 400;
				var winH = 300;
				var top = sizesystem[3] + Math.round((sizesystem[5] - winH)/2);
				var left = Math.round((sizesystem[4] - winW)/2);
				var str = '<object  type="application/x-oleobject" classid="CLSID:22D6f312-B0F6-11D0-94AB-0080C74C7E95" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,4,7,1112"';
				str += ' width="'+winW+'" height="'+winH+'">';
				str += '<param name="filename" value="'+src+'" />';
				str += '<param name="Showcontrols" value="true" />';
				str += '<param name="autoStart" value="true" />';
				str += '<embed type="application/x-mplayer2" src="'+src+'" Showcontrols="true" autoStart="true" width="'+winW+'" height="'+winH+'"></embed>';
				str += '<object/>';
				fn.animate(top,left,winW,winH,function(o){
					$(o).html(str);
				},false,caption);
			}
			fn.viewSWF = function(src,caption){
				var winW = 400;
				var winH = 300;
				var top = sizesystem[3] + Math.round((sizesystem[5] - winH)/2);
				var left = Math.round((sizesystem[4] - winW)/2);
				var str = fn.flashEmbedString(src,winW,winH,'swf');
				fn.animate(top,left,winW,winH,function(o){
					$(o).html(str);
				},false,caption);
			}
			fn.viewElement = function(caption){
				var e = '#'+$(obj).attr('rel');
				var winW = $(e).outerWidth();
				var winH = $(e).outerHeight();
				var top = sizesystem[3] + Math.round((sizesystem[5] - winH)/2);
				var left = Math.round((sizesystem[4] - winW)/2);
				fn.animate(top,left,winW,winH,function(o){
					$(o).html($(e).html());
					fn.toggleObj(o,'show');
					$(e).html('');
				},function(o){
					$(e).html($(o).html());
				},caption);
			}
			fn.viewAjax = function(src,caption){
				$.ajax({
					url: src,
					success:function(data){
						if(_cfg.width) $('#divbox_ajax').css({'width': _cfg.width});
						if(_cfg.height) $('#divbox_ajax').css({'height': _cfg.height});
						$('#divbox_ajax').html(data);
						var winW = $('#divbox_ajax').outerWidth();
						var winH = $('#divbox_ajax').outerHeight();
						var top = sizesystem[3] + Math.round((sizesystem[5] - winH)/2);
						var left = Math.round((sizesystem[4] - winW)/2);
						fn.animate(top,left,winW,winH,function(o){
							$(o).html(data);
							
							$('#divbox_ajax').remove();
						},false,caption);
					} ,
					error: function(x,e){
						alert(_cfg.languages.error_cannot_load+x.responseText);
					}
				});
			}
			fn.viewDefault = function(src,caption){
				var winW = sizesystem[4]-100;
				var winH = sizesystem[5]-100;
				var top = sizesystem[3] + Math.round((sizesystem[5] - winH)/2);
				var left = sizesystem[2] + Math.round((sizesystem[4] - winW)/2);
				fn.animate(top,left,winW,winH,function(o){
					$(o).html('<iframe src="'+src+'" width="'+(winW-12)+'" frameborder="0" scrolling="'+_cfg.scrollbar+'" height="'+winH+'"></iframe>');	
				},false,caption);
			}
			
			
			fn.viewYouTube = function(src,caption){
				if (!src.match(/youtube\.com\/watch/i)){
					alert(_cfg.languages.error_not_youtube);
					return false;
				}
				var vidId = src.split('v=')[1].split('&')[0];
				var vidSrc = "http://www.youtube.com/v/" + vidId + "&hl=en&fs=1&autoplay=1&rel=0";
				var winW = 640;
				var winH = 385;
				var top = sizesystem[3] + Math.round((sizesystem[5] - winH)/2);
				var left = Math.round((sizesystem[4] - winW)/2);
				var str = '<object width="'+winW+'" height="'+winH+'"><param name="movie" value="'+vidSrc+'"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="'+vidSrc+'" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="'+winW+'" height="'+winH+'"></embed></object>';
				//var str = fn.flashEmbedString(src,winW,winH,'youtube');
				fn.animate(top,left,winW,winH,function(o){
					$(o).html(str);
				},false,caption);
			}
			var obj = objArr[index];
			var ie = $.browser.msie;
			var ie6 = (ie && parseInt($.browser.version) == 6)?true:false;
			var sizesystem = pageSize(ie);
			var total = $(oMatch).length;
			var _click = $(obj).offset();
			var src = $(obj).attr(_cfg.src).toString();
			var ext = fn.parseType(src);
			
			// Download able
			for(var i in _cfg.download_able) if(ext==_cfg.download_able[i]){
				return window.open(src);
			}
			
			//
			var caption = '';
			if(typeof(_cfg.caption)=='function'){
				caption = _cfg.caption(obj);
			}else if(_cfg.caption === true){
				caption = $(obj).attr('title');	
			}
			
			if(init) fn.init();
			$(_cfg.btn_prev).unbind('click');
			$(_cfg.btn_next).unbind('click');
			var oFrame = $('#divbox_frame');
			$(oFrame).find('.caption').hide().html(caption);
			
			switch(ext){
				case 'jpg':
				case 'jpeg':
				case 'gif':
				case 'png': fn.viewImage(src,caption);break;
				case 'flv':fn.viewFLV(src,caption);break;
				case 'wmv': fn.viewWMV(src,caption);break;
				case 'mp3': fn.viewMP3(src,caption);break;
				case 'mp4': fn.viewMP4(src,caption);break;
				case 'swf': fn.viewSWF(src,caption);break;
				case 'element': fn.viewElement(caption); break;
				case 'ajax': fn.viewAjax(src,caption); break;
				case 'youtube': fn.viewYouTube(src,caption); break;
				default: fn.viewDefault(src,caption);break;
			}	
			
			return false;
		}
		
		$(oMatch).bind(_cfg.event,function(){
			var index = 0;
			for(var i in objArr) if(objArr[i] === this) index = i;
			_run(index,true);
			return false;
		});
	}
	
	function pageSize(ie){
		var de = document.documentElement;
		var winW = window.innerWidth || self.innerWidth || (de&&de.clientWidth) || document.body.clientWidth;
		winW -= 18;
		var winH = window.innerHeight || self.innerHeight || (de&&de.clientHeight) || document.body.clientHeight;
		var x = window.pageXOffset || self.pageXOffset || (de&&de.scrollLeft) || document.body.scrollLeft;
		var y = window.pageYOffset || self.pageYOffset || (de&&de.scrollTop) || document.body.scrollTop;
		var pW = window.innerWidth || document.body.scrollWidth || document.body.offsetWidth;
		var pH = window.innerHeight+window.scrollMaxY || document.body.scrollHeight || document.body.offsetHeight;
		var w = pW<winW?winW:pW; 
		w -= 18;
		var h = pH<winH?winH:pH;
		arrayPageSize = [w,h,x,y,winW,winH];
		return arrayPageSize;
	}
})(jQuery)