// JavaScript Document
$(document).ready(function()
   {
     var index = 0;
     var jdlis = $('.jiaodiandiv').find('span'); 
     var timer;
     var liWidth = $('#ad').width();
     var len = $("#ad ul li").length;
	 var liw=(100/len)+'%'
	 $("#ad").find('li').css('width',liw);
     $("#ad ul").css("width",liWidth * (len));
	 
	 var jiaow=$('.jiaodiandiv').width();
	 var jiao=(liWidth-jiaow)/2-5;
	 //alert(jiao)
	 $('.jiaodiandiv').css('left',jiao)
     //上一张按钮
     $("#SlidePrev").click(function() {
     clearInterval(timer);
     index -= 1;
     if(index == -1) {index = len - 1;}
     showPic(index);
     });

    //下一张按钮
     $("#SlideNext").click(function() {
     clearInterval(timer);
    index += 1;
     if(index == len) {index = 0;}
     showPic(index);
     });
     //轮播
     $('#ad').hover(
     function()
     {
       clearInterval(timer); /*停止动画*/
      $('.slideshortcut a').show().css('opacity','0.4');
     },
     function()
     {
         $('.slideshortcut a').hide();
         timer=setInterval(function() {
         showPic(index);
         index++;
         if(index == len) {index = 0;}
       },3000);
     }).trigger("mouseleave");
     /*显示index图片*/
     function showPic(index){
      var nowLeft = -index*liWidth;
      jdlis.eq(index).css('backgroundColor','#c4374a');
      jdlis.not(jdlis.eq(index)).css('backgroundColor','');
	  $('#selectli').attr('id','');
      $("#ad ul").stop(true,false).animate({"left":nowLeft},300);
      /*$('#loginimg').hide().fadeIn(1000);*/
     }
     $('.slideshortcut a').mouseover(function()
     {
       $('.slideshortcut a').show();
    });
     $('.prev').mouseover(
     function()
     {
       $(this).css({opacity:'0.95',cursor:'pointer'});
     });
    $('.next').mouseover(
    function()
     {
       $(this).css({opacity:'0.95',cursor:'pointer'});
     });

    jdlis.click(
     function(){
       clearInterval(timer);
      index = jdlis.index(this);
       showPic(index);
    });
});