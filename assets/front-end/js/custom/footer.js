var curr = new Date;
var lastday = new Date(curr.getFullYear(), curr.getMonth() + 6, 0);


$(document).ready(function () {
    $('.menuButton').click(function (event) {
        $('ul.nav-menu').slideToggle();
        $('.menuButton').toggleClass('menuButtonActive');
    });

    document.cookie = "locality_id=''";
    expires = 0;
    path = "/";
    $("#locality_div").text('');
    $("#localityId").val(1);
    
});

//  $(window).on('load', function () {
//    $(".orderPopupOnlineorder").trigger('click');
//    $("#cboxClose").hide();
//  });

//it will return cookie
function getCookie() {
    var pairs = document.cookie.split(";");
    var cookies = {};
    for (var i = 0; i < pairs.length; i++) {
        var pair = pairs[i].split("=");
        cookies[(pair[0] + '').trim()] = unescape(pair[1]);
    }
    return cookies;
}

//it will change language
$(document).on('click', ".lang", function () {
    var lang = $(this).find('span').text();
    document.cookie = "lang=" + lang + "; expires=0; path=/";

    window.location.reload();
})

//select Locality and start online order

$(document).on("click", "#start_my_order", function () {
    var locality = $("#localityId").val();
    var start = $("#start_my_order").val();
    document.cookie = "locality_id=" + locality + "; expires=0; path=/";

    var getNewLocality = getlocalites + '/' + locality;
    if (locality != '') {
        $.ajax({
            type: 'post',
            url: getNewLocality,
            /*beforeSend: function()
            {
                $("#start_my_order").attr("disabled", true);
                $("#start_my_order").html('<i class="fa fa-spinner fa-spin"></i>'); 
            },*/
            success: function (data) {
                /*$("#start_my_order").attr("disabled", false);
                $("#start_my_order").html(start); */

                var obj = $.parseJSON(data);
                if (obj.success == '1') {
                    $("#locality_div").text(obj.message.name);
                    $("#locality_time_div").text(obj.message.delivered_time + " " + minutes);
                }
            }
        });

    }

    var path = window.location.pathname;
    if (path.search("orderSummary") !== -1) {
        window.location.href = site_url;
        $("#myModal").hide();
    }
    showDish(locality);
});

//it will delete cookie
var delete_cookie = function (name) {
    document.cookie = name + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT; path=/';
};

/**
 * [close conformation popup on cancel]
 * Description:
 * @author: Manisha Kanazariya
 * @CreatedDate:2019-02-02T18:41:59+0530
 */
$(document).on("click", "#cancel_delete_dish_btn", function () {
    /*location.reload();*/
    $.fn.colorbox.close();
})

$(document).on("click", ".change_preferences", function () {
    $("#myModal").show();
})
