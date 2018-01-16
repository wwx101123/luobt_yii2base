var tooltip=function(){
	var id = 'tt';
	var top = 3;
	var left = 3;
	var maxw = 250;
	var speed = 10;
	var timer = 20;
	var endalpha = 100;
	var alpha = 0;
	var tt,t,c,b,h;
	var ie = document.all ? true : false;
	var posch=0
	return{
		show:function(v,w){
		id = 'tt';
		top = 3;
		left = 3;
		maxw = 250;
		speed = 10;
		timer = 20;
		endalpha = 100;
		alpha = 0;
		tt,t,c,b,h;
		ie = document.all ? true : false;
		posch=0
			if(tt == null){
				tt = document.createElement('div');
				tt.setAttribute('id',id);
				t = document.createElement('div');
				t.setAttribute('id',id + 'top');
				c = document.createElement('div');
				c.setAttribute('id',id + 'cont');
				b = document.createElement('div');
				b.setAttribute('id',id + 'bot');
				tt.appendChild(t);
				tt.appendChild(c);
				tt.appendChild(b);
				document.body.appendChild(tt);
				tt.style.opacity = 0;
				tt.style.filter = 'alpha(opacity=0)';
				
				document.onmousemove = this.pos;
			}
			tt.style.display = 'block';
			c.innerHTML = v;
			tt.style.width = w ? w + 'px' : 'auto';
			if(!w && ie){
				t.style.display = 'none';
				b.style.display = 'none';
				tt.style.width = tt.offsetWidth;
				t.style.display = 'block';
				b.style.display = 'block';
			}
			if(tt.offsetWidth > maxw){tt.style.width = maxw + 'px'}
			h = parseInt(tt.offsetHeight) + top;
			clearInterval(tt.timer);
			tt.timer = setInterval(function(){tooltip.fade(1)},timer);

		},
		pos:function(e){
			if (posch==0)
			{
			var u = ie ? event.clientY + document.documentElement.scrollTop : e.pageY;
			var l = ie ? event.clientX + document.documentElement.scrollLeft : e.pageX;
			tt.style.top = (u - h) + 'px';

			if (parseInt(document.documentElement.clientWidth )<(parseInt(l) + parseInt(left) + parseInt(tt.offsetWidth)))
				tt.style.left = ((l + left)-tt.offsetWidth) + 'px';			
			else
				tt.style.left = (l + left) + 'px';
			posch=0;
			}

		},
		fade:function(d){
			var a = alpha;
			if((a != endalpha && d == 1) || (a != 0 && d == -1)){
				var i = speed;
				if(endalpha - a < speed && d == 1){
					i = endalpha - a;
				}else if(alpha < speed && d == -1){
					i = a;
				}
				alpha = a + (i * d);
				tt.style.opacity = alpha * .01;
				tt.style.filter = 'alpha(opacity=' + alpha + ')';
			}else{
				clearInterval(tt.timer);
				if(d == -1){tt.style.display = 'none'}
			}

		},
		hide:function(){
			clearInterval(tt.timer);
			tt.timer = setInterval(function(){tooltip.fade(-1)},timer);
		}
	};
}();