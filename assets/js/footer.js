$(document).ready(function () {
    
    /*var idleState = false;
    var idleTimer = null;
    $('body').bind('mousemove click mouseup mousedown keydown keypress keyup submit change mouseenter scroll resize dblclick', function () {
        clearTimeout(idleTimer);
        if (idleState == true) { 

        }else{
            idleTimer = setTimeout(function () { 
                $("#logOutConformation").modal('show');
                    setTimeout(function () {
                        $.ajax({
                            type:"POST",
                            url:logoutLink,
                            success:function(){
                            $(location).attr('href',logoutLink);
                            }
                        })
                    },300000);
                    idleState = true; },300000);
        }

    });*/
});
    
$("body").trigger("mousemove");

// wow = new WOW({
//     animateClass: 'animated',
//     offset:       100,
//     callback:     function(box) {
//       console.log("WOW: animating <" + box.tagName.toLowerCase() + ">")
//     }
// });
// wow.init();

