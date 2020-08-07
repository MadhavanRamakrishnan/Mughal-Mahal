jQuery(document).ready(function(){
	var coockie  =getCookie();
    var locality =coockie.locality_id;
    document.cookie = "locality_id="+locality+"; expires=0; path=/";
    if (jQuery(window).width() > 767) {
      var stickySidebar1 = new StickySidebar('.rightMain', {
          topSpacing: 0,
          bottomSpacing: 20,
          containerSelector: '.container',
          innerWrapperSelector: '.rightOut'
        });
        var stickySidebar2 = new StickySidebar('.scroller_anchor', {
          topSpacing: 0,
          bottomSpacing: 20,
          containerSelector: '.container',
          innerWrapperSelector: '.searchForm'
        });   
    }

    if (jQuery(window).width() > 800) {
      var stickySidebar3 = new StickySidebar('.tabs_wrapper', {
          topSpacing: 100,
          bottomSpacing: 20,
          containerSelector: '.contentSection',
          innerWrapperSelector: '.tabs'
        });
    } 
    else{
      var stickySidebar3 = new StickySidebar('.tabs_wrapper', {
          topSpacing: 0,
          bottomSpacing: 20,
          containerSelector: '.contentSection',
          innerWrapperSelector: '.tabs'
        });
    }

   /* smooth scroll tab*/
   
    jQuery('ul.tabs a[href^="#"]').click(function(e) {
      jQuery('html,body').animate({ scrollTop: (jQuery(this.hash).offset().top-100)}, 1000);   
      return false;
      //e.preventDefault();
    });

    jQuery('ul.tabs a').click(function(e) {
       e.preventDefault();
        $('li').removeClass('activeLi');
        $(this).find('li').addClass('activeLi');
     });

    jQuery(".orderPopup").colorbox({inline:true, width:"90%" ,height:"80%" });

    jQuery(".orderPopupOnlineorder").colorbox({ inline:true  , className: 'my-class'  }); 


    jQuery(".topOrderButton").colorbox({ inline:true  , className: 'my-class'  }); 

    /*jQuery(".cartLink").click( function(event){
          event.stopPropagation();
          jQuery("div.cartPopup").toggle();
    });
    jQuery(document).click( function(){
        jQuery('div.cartPopup').hide();
    });*/

    $("body").click(function() {
        $(".cartPopup").hide();
    });

    $(".cartLink").click(function(e) {
        e.stopPropagation();
        $(".cartPopup").toggle();
    });

    $(".cartPopup").click(function(e){
        e.stopPropagation();
    });

}); 
  
    
/*increment js*/
function stapper(className){


    $(className).prepend('<div class="dec button stapper">-</div>');
    $(className).append('<div class="inc button stapper">+</div>');

    $(".button").on("click", function() {

      var $button = $(this);
      var oldValue = $button.parent().find("input").val();
      if ($button.text() == "+") {
        var newVal = parseFloat(oldValue) + 1;
      } 
      else{
        // Don't allow decrementing below zero
        if (oldValue > 0) {
          var newVal = parseFloat(oldValue) - 1;
          } else {
          newVal = 1;
        }
      }
      $button.parent().find("input").val(newVal);

    });
}


