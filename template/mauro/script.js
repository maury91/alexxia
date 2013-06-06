/*function log10 (arg) {
  // http://kevin.vanzonneveld.net
  // +   original by: Philip Peterson
  // +   improved by: Onno Marsman
  // +   improved by: Tod Gentille
  // +   improved by: Brett Zamir (http://brett-zamir.me)
  // *     example 1: log10(10);
  // *     returns 1: 1
  // *     example 2: log10(1);
  // *     returns 2: 0
  return Math.log(arg) / 2.302585092994046; // Math.LN10
}*/

$(function(){
	$('body').mousemove(function(e) {
		var off=$('.title_text').offset();
		var xx = e.clientX-(off.left+234);
		var yy = e.clientY-(off.top+52);
		/*if (xx>1)
			xx=-log10(xx);
		else if (xx<-1)
			xx=log10(-xx);
		else
			xx=0;
		if (yy>1)
			yy=-log10(yy);
		else if (yy<-1)
			yy=log10(-yy);
		else
			yy=0;*/
		xx/=-50;
		yy/=-50;
		//$('.title_text').css('text-shadow',xx*2+'px '+yy*2+'px 2px #777');
		$('.title_text .shadow').css({left:xx,top:yy});
	});
});