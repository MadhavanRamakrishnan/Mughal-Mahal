jQuery(document).ready(function(){
	var coockie  =getCookie();
    var locality =coockie.locality_id;
	  showDish(locality);
    showCart();
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
          containerSelector: '.container',
          innerWrapperSelector: '.tabs'
        });
    } 
    else{
      var stickySidebar3 = new StickySidebar('.tabs_wrapper', {
          topSpacing: 0,
          bottomSpacing: 20,
          containerSelector: '.container',
          innerWrapperSelector: '.tabs'
        });
    }

   /* smooth scroll tab*/
   
    jQuery('ul.tabs a[href^="#"]').click(function(e) {
      jQuery('html,body').animate({ scrollTop: (jQuery(this.hash).offset().top-100)}, 1000);   
      return false;
      e.preventDefault();
    });

    jQuery('ul.tabs a').click(function(e) {
       e.preventDefault();
        $('li').removeClass('activeLi');
        $(this).find('li').addClass('activeLi');
     });

    $('.menuButton').click(function(event) {
        $('ul.nav-menu').slideToggle();
        $('.menuButton').toggleClass('menuButtonActive');
    });     

    jQuery(".orderPopup").colorbox({inline:true, width:"90%" ,height:"80%" });

    jQuery(".orderPopupOnlineorder").colorbox({ inline:true  , className: 'my-class'  }); 


    jQuery(".topOrderButton").colorbox({ inline:true  , className: 'my-class'  }); 

    jQuery(".cartLink").click( function(event){
          event.stopPropagation();
          jQuery("div.cartPopup").toggle();
    });
    jQuery(document).click( function(){
        jQuery('div.cartPopup').hide();
    });

    wow = new WOW(
    {
        animateClass: 'animated',
        offset:       100,
        callback:     function(box) {
        }
      }
    );
    wow.init();
}); 
  
    
/*increment js*/
function stapper(){


    $(".numbers-row").prepend('<div class="dec button stapper">-</div>');
    $(".numbers-row").append('<div class="inc button stapper">+</div>');

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
          newVal = 0;
        }
      }
      $button.parent().find("input").val(newVal);

    });
}


