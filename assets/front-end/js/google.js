/*function onLoadGoogleCallback(){gapi.load('auth2',function(){auth2=gapi.auth2.init({'client_id':'823542492889-n17k4kteoi45l7q9kv2fltigsjopa8ob.apps.googleusercontent.com','cookiepolicy':'single_host_origin','scope':'profile email','onsuccess':onSuccess,'onfailure':onFailure});auth2.attachClickHandler(element,{},function(googleUser){onSuccess(googleUser);},function(error){onFailure(error);});});element=document.getElementById('go');}function onSuccess(googleUser){var profile=googleUser.getBasicProfile();saveUserData(profile);}function onFailure(error){var x=document.getElementById("snackbar");$('#snackbar').text(GoogleloginError);x.className="errorshow";setTimeout(function(){x.className=x.className.replace("errorshow","");},3000);}function renderButton(){gapi.signin2.render('gSignIn',{'lang':'en','scope':'profile email','width':240,'height':50,'longtitle':true,'theme':'dark','onsuccess':onSuccess,'onfailure':onFailure});}function signOut(){var auth2=gapi.auth2.getAuthInstance();auth2.signOut().then(function(){$('.userContent').html('');$('#gSignIn').slideDown('slow');});}function saveUserData(data){$.ajax({type:"POST",url:saveGoogleData,data:{role_id:5,first_name:data.wea,last_name:data.ofa,email:data.U3,profile_photo:data.Paa,google_id:data.Eea},success:function(response){var obj=$.parseJSON(response);if(obj.success==1){document.cookie="access_token="+obj.data.access_token+"; expires="+lastday+"; path=/";document.cookie="user_id="+obj.data.user_id+"; expires="+lastday+"; path=/";var x=document.getElementById("snackbar");$('#snackbar').text(UserLoginSucc);x.className="show";setTimeout(function(){x.className=x.className.replace("show","");$("#loading-div-background").hide();location.reload();},3000);}else{$("#loading-div-background").hide();$('#error_message').text(obj.message);$('#error_notification').fadeIn("slow");setTimeout(function(){$('#error_notification').fadeOut("slow");},3000);}}})}*/
function onLoadGoogleCallback() {
	gapi.load('auth2', function() {
		auth2 = gapi.auth2.init({
			'client_id': '823542492889-n17k4kteoi45l7q9kv2fltigsjopa8ob.apps.googleusercontent.com',
			'cookiepolicy': 'single_host_origin',
			'scope': 'profile email',
			'onsuccess': onSuccess,
			'onfailure': onFailure
		});
		auth2.attachClickHandler(element, {}, function(googleUser) {
			onSuccess(googleUser);
		}, function(error) {
			onFailure(error);
		});
	});
	element = document.getElementById('go');
}

function onSuccess(googleUser) {
	var profile = googleUser.getBasicProfile();
	saveUserData(profile);
}

function onFailure(error) {
	var x = document.getElementById("snackbar");
	$('#snackbar').text(GoogleloginError);
	x.className = "errorshow";
	setTimeout(function() {
		x.className = x.className.replace("errorshow", "");
	}, 3000);
}

function renderButton() {
	gapi.signin2.render('gSignIn', {
		'lang': 'en',
		'scope': 'profile email',
		'width': 240,
		'height': 50,
		'longtitle': true,
		'theme': 'dark',
		'onsuccess': onSuccess,
		'onfailure': onFailure
	});
}

function signOut() {
	var auth2 = gapi.auth2.getAuthInstance();
	auth2.signOut().then(function() {
		$('.userContent').html('');
		$('#gSignIn').slideDown('slow');
	});
}

function saveUserData(data) {
	$.ajax({
		type: "POST",
		url: saveGoogleData,
		data: {
			role_id: 5,
			first_name: data.wea,
			last_name: data.ofa,
			email: data.U3,
			profile_photo: data.Paa,
			google_id: data.Eea
		},
		success: function(response) {
			var obj = $.parseJSON(response);
			if (obj.success == 1) {
				document.cookie = "access_token=" + obj.data.access_token + "; expires=" + lastday + "; path=/";
				document.cookie = "user_id=" + obj.data.user_id + "; expires=" + lastday + "; path=/";
				var x = document.getElementById("snackbar");
				$('#snackbar').text(UserLoginSucc);
				x.className = "show";
				setTimeout(function() {
					x.className = x.className.replace("show", "");
					$("#loading-div-background").hide();
					if($(".checkOut-Click").val() == "1"){
						window.location.href = baseUrl+"Home/orderSummary";
						return false;
					}
					location.reload();
				}, 3000);
			} else {
				$("#loading-div-background").hide();
				$('#error_message').text(obj.message);
				$('#error_notification').fadeIn("slow");
				setTimeout(function() {
					$('#error_notification').fadeOut("slow");
				}, 3000);
			}
		}
	})
}