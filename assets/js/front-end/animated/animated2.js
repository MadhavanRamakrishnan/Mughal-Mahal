!function(){function o(){s=window.innerWidth,h=window.innerHeight,p={x:0,y:h},l=document.getElementById("main-header"),m=document.getElementById("demo-canvas"),m.width=s,m.height=h,m.style.opacity=.6,u=m.getContext("2d"),f=[];for(var o=0;480>o;o++)n(10*o)}function n(o){setTimeout(function(){var o=new c;f.push(o),a(o)},o)}function t(){d()}function a(o){var n=Math.random()*(2*Math.PI),t=(200+100*Math.random())*Math.cos(n)+.5*s,i=(200+100*Math.random())*Math.sin(n)+.5*h-20,e=4+3*Math.random();TweenLite.to(o.pos,e,{x:t,y:i,ease:Circ.easeOut,onComplete:function(){o.init(),a(o)}})}function i(){window.addEventListener("scroll",e),window.addEventListener("resize",r)}function e(){w=document.body.scrollTop>h?!1:!0}function r(){s=window.innerWidth,h=window.innerHeight,m.width=s,m.height=h}function d(){if(w){u.clearRect(0,0,s,h);for(var o in f)f[o].draw()}requestAnimationFrame(d)}function c(){function o(){n.pos.x=.5*s,n.pos.y=.5*h-20,n.coords[0].x=-10+40*Math.random(),n.coords[0].y=-10+40*Math.random(),n.coords[1].x=-10+40*Math.random(),n.coords[1].y=-10+40*Math.random(),n.coords[2].x=-10+40*Math.random(),n.coords[2].y=-10+40*Math.random(),n.scale=.1+.3*Math.random(),n.color=y[Math.floor(Math.random()*y.length)],setTimeout(function(){n.alpha=.8},10)}var n=this;!function(){n.coords=[{},{},{}],n.pos={},o()}(),this.draw=function(){n.alpha>=.005?n.alpha-=.005:n.alpha=0,u.beginPath(),u.moveTo(n.coords[0].x+n.pos.x,n.coords[0].y+n.pos.y),u.lineTo(n.coords[1].x+n.pos.x,n.coords[1].y+n.pos.y),u.lineTo(n.coords[2].x+n.pos.x,n.coords[2].y+n.pos.y),u.closePath(),u.fillStyle="rgba("+n.color+","+n.alpha+")",u.fill()},this.init=o}var s,h,l,m,u,f,p,w=!0,y=["72,35,68","43,81,102","66,152,103","250,178,67","224,33,48"];o(),i(),t()}();