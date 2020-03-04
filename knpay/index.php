<?php

//echo "<pre>"; print_r($_REQUEST); exit;
$fields = array(
	"amount" 			=> "15",
	"currency_code"		=> "KWD",
	"gateway_code" 		=> "test-knet",
	"order_no" 			=> "CUSTOMER".mt_rand(10000,99999),
	"customer_email" 	=> "umang@itoneclick.com",
	"disclosure_url" 	=> "http://oneclickitmarketing.co.in/subdomain/demo/knpay/disclosure.php",
	"redirect_url" 		=> "http://oneclickitmarketing.co.in/subdomain/demo/knpay/success.php"
);


//url-ify the data for the POST
 foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
rtrim($fields_string, '&');

//open connection
$ch = curl_init();

curl_setopt($ch,CURLOPT_URL, "https://pay.mughalmahal.com/pos/crt/");
curl_setopt($ch,CURLOPT_POST, count($fields));
curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//execute post
$result = curl_exec($ch);
$array = json_decode($result);
curl_close($ch);
header('Location:'.$array->url);
//echo "<pre>"; print_r($result); exit;
//close connection