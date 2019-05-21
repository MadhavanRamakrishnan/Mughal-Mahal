/*window.fbAsyncInit=function(){FB.init({appId:'562756694091929',cookie:true,xfbml:true,version:'v2.8'});};(function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(d.getElementById(id))return;js=d.createElement(s);js.id=id;js.src="//connect.facebook.net/en_US/sdk.js";fjs.parentNode.insertBefore(js,fjs);}(document,'script','facebook-jssdk'));function fbLogin(){FB.login(function(response){if(response.authResponse){getFbUserData();}else{$('#error_message').text(canclledFBlogin);$('#error_notification').fadeIn("slow");setTimeout(function(){$('#error_notification').fadeOut("slow");},4000);}},{scope:'email'});}function fbLogout(){FB.logout(function(){});}function getFbUserData(){FB.api('/me',{locale:'en_US',fields:'id,first_name,last_name,email,link,gender,locale,picture'},function(response){$.post(fbdata,{oauth_provider:'facebook',userData:JSON.stringify(response)},function(data){var obj=JSON.parse(data);if(obj.response=='true'){document.cookie="access_token="+obj.data.access_token+"; expires="+lastday+"; path=/";document.cookie="user_id="+obj.data.user_id+"; expires="+lastday+"; path=/";var x=document.getElementById("snackbar");$('#snackbar').text("User Login Successfully");x.className="show";setTimeout(function(){x.className=x.className.replace("show","");location.reload();},3000);}else{$('#error_message').text(obj.message);$('#error_notification').fadeIn("slow");setTimeout(function(){$('#error_notification').fadeOut("slow");},3000);}});});}*/

window.fbAsyncInit = function() {
	FB.init({
		appId: '562756694091929',
		cookie: true,
		xfbml: true,
		version: 'v2.8'
	});
};
(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s);
	js.id = id;
	js.src = "//connect.facebook.net/en_US/sdk.js";
	fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

function fbLogin() {
	FB.login(function(response) {
		if (response.authResponse) {
			getFbUserData();
		} else {
			$('#error_message').text(canclledFBlogin);
			$('#error_notification').fadeIn("slow");
			setTimeout(function() {
				$('#error_notification').fadeOut("slow");
			}, 4000);
		}
	}, {
		scope: 'email'
	});
}

function fbLogout() {
	FB.logout(function() {});
}

function getFbUserData() {
	FB.api('/me', {
		locale: 'en_US',
		fields: 'id,first_name,last_name,email,link,gender,locale,picture'
	}, function(response) {
		$.post(fbdata, {
			oauth_provider: 'facebook',
			userData: JSON.stringify(response)
		}, function(data) {
			var obj = JSON.parse(data);
			if (obj.response == 'true') {
				document.cookie = "access_token=" + obj.data.access_token + "; expires=" + lastday + "; path=/";
				document.cookie = "user_id=" + obj.data.user_id + "; expires=" + lastday + "; path=/";
				var x = document.getElementById("snackbar");
				$('#snackbar').text("User Login Successfully");
				x.className = "show";
				setTimeout(function() {
					x.className = x.className.replace("show", "");
					if($(".checkOut-Click").val() == "1"){
						window.location.href = baseUrl+"Home/orderSummary";
						return false;
					}
					location.reload();
				}, 3000);
			} else {
				$('#error_message').text(obj.message);
				$('#error_notification').fadeIn("slow");
				setTimeout(function() {
					$('#error_notification').fadeOut("slow");
				}, 3000);
			}
		});
	});
}