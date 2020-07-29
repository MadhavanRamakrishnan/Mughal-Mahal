<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
	|--------------------------------------------------------------------------
	| Base Site URL
	|--------------------------------------------------------------------------
	|
	| URL to your CodeIgniter root. Typically this will be your base URL,
	| WITH a trailing slash:
	|
	|	http://example.com/
	|
	| WARNING: You MUST set this value!
	|
	| If it is not set, then CodeIgniter will try guess the protocol and path
	| your installation, but due to security concerns the hostname will be set
	| to $_SERVER['SERVER_ADDR'] if available, or localhost otherwise.
	| The auto-detection mechanism exists only for convenience during
	| development and MUST NOT be used in production!
	|
	| If you need to allow multiple domains, remember that this file is still
	| a PHP script and you can easily do that on your own.
*/
// $config['base_url'] = 'http://'.$_SERVER['HTTP_HOST'].'/MughalMahal';
$config['base_url'] = 'http://'.$_SERVER['HTTP_HOST'].'/devesh/MughalMahal';
/*
|--------------------------------------------------------------------------
| Index File
|--------------------------------------------------------------------------
|
| Typically this will be your index.php file, unless you've renamed it to
| something else. If you are using mod_rewrite to remove the page set this
| variable so that it is blank.
|
*/
//$config['index_page'] = 'index.php';
$config['index_page'] = '';

/*
|--------------------------------------------------------------------------
| URI PROTOCOL
|--------------------------------------------------------------------------
|
| This item determines which server global should be used to retrieve the
| URI string.  The default setting of 'REQUEST_URI' works for most servers.
| If your links do not seem to work, try one of the other delicious flavors:
|
| 'REQUEST_URI'    Uses $_SERVER['REQUEST_URI']
| 'QUERY_STRING'   Uses $_SERVER['QUERY_STRING']
| 'PATH_INFO'      Uses $_SERVER['PATH_INFO']
|
| WARNING: If you set this to 'PATH_INFO', URIs will always be URL-decoded!
*/
$config['uri_protocol']	= 'REQUEST_URI';

/*
|--------------------------------------------------------------------------
| URL suffix
|--------------------------------------------------------------------------
|
| This option allows you to add a suffix to all URLs generated by CodeIgniter.
| For more information please see the user guide:
|
| https://codeigniter.com/user_guide/general/urls.html
*/
$config['url_suffix'] = '';

/*
|--------------------------------------------------------------------------
| Default Language
|--------------------------------------------------------------------------
|
| This determines which set of language files should be used. Make sure
| there is an available translation if you intend to use something other
| than english.
|
*/
$config['language']	= 'english';

/*
|--------------------------------------------------------------------------
| Default Character Set
|--------------------------------------------------------------------------
|
| This determines which character set is used by default in various methods
| that require a character set to be provided.
|
| See http://php.net/htmlspecialchars for a list of supported charsets.
|
*/
$config['charset'] = 'UTF-8';

/*
|--------------------------------------------------------------------------
| Enable/Disable System Hooks
|--------------------------------------------------------------------------
|
| If you would like to use the 'hooks' feature you must enable it by
| setting this variable to TRUE (boolean).  See the user guide for details.
|
*/
$config['enable_hooks'] = FALSE;

/*
|--------------------------------------------------------------------------
| Class Extension Prefix
|--------------------------------------------------------------------------
|
| This item allows you to set the filename/classname prefix when extending
| native libraries.  For more information please see the user guide:
|
| https://codeigniter.com/user_guide/general/core_classes.html
| https://codeigniter.com/user_guide/general/creating_libraries.html
|
*/
$config['subclass_prefix'] = 'MY_';

/*
|--------------------------------------------------------------------------
| Composer auto-loading
|--------------------------------------------------------------------------
|
| Enabling this setting will tell CodeIgniter to look for a Composer
| package auto-loader script in application/vendor/autoload.php.
|
|	$config['composer_autoload'] = TRUE;
|
| Or if you have your vendor/ directory located somewhere else, you
| can opt to set a specific path as well:
|
|	$config['composer_autoload'] = '/path/to/vendor/autoload.php';
|
| For more information about Composer, please visit http://getcomposer.org/
|
| Note: This will NOT disable or override the CodeIgniter-specific
|	autoloading (application/config/autoload.php)
*/
$config['composer_autoload'] = FALSE;

/*
|--------------------------------------------------------------------------
| Allowed URL Characters
|--------------------------------------------------------------------------
|
| This lets you specify which characters are permitted within your URLs.
| When someone tries to submit a URL with disallowed characters they will
| get a warning message.
|
| As a security measure you are STRONGLY encouraged to restrict URLs to
| as few characters as possible.  By default only these are allowed: a-z 0-9~%.:_-
|
| Leave blank to allow all characters -- but only if you are insane.
|
| The configured value is actually a regular expression character group
| and it will be executed as: ! preg_match('/^[<permitted_uri_chars>]+$/i
|
| DO NOT CHANGE THIS UNLESS YOU FULLY UNDERSTAND THE REPERCUSSIONS!!
|
*/
$config['permitted_uri_chars'] = 'a-z 0-9~%.:_\-';

/*
|--------------------------------------------------------------------------
| Enable Query Strings
|--------------------------------------------------------------------------
|
| By default CodeIgniter uses search-engine friendly segment based URLs:
| example.com/who/what/where/
|
| You can optionally enable standard query string based URLs:
| example.com?who=me&what=something&where=here
|
| Options are: TRUE or FALSE (boolean)
|
| The other items let you set the query string 'words' that will
| invoke your controllers and its functions:
| example.com/index.php?c=controller&m=function
|
| Please note that some of the helpers won't work as expected when
| this feature is enabled, since CodeIgniter is designed primarily to
| use segment based URLs.
|
*/
$config['enable_query_strings'] = FALSE;
$config['controller_trigger'] = 'c';
$config['function_trigger'] = 'm';
$config['directory_trigger'] = 'd';

/*
|--------------------------------------------------------------------------
| Allow $_GET array
|--------------------------------------------------------------------------
|
| By default CodeIgniter enables access to the $_GET array.  If for some
| reason you would like to disable it, set 'allow_get_array' to FALSE.
|
| WARNING: This feature is DEPRECATED and currently available only
|          for backwards compatibility purposes!
|
*/
$config['allow_get_array'] = TRUE;

/*
|--------------------------------------------------------------------------
| Error Logging Threshold
|--------------------------------------------------------------------------
|
| You can enable error logging by setting a threshold over zero. The
| threshold determines what gets logged. Threshold options are:
|
|	0 = Disables logging, Error logging TURNED OFF
|	1 = Error Messages (including PHP errors)
|	2 = Debug Messages
|	3 = Informational Messages
|	4 = All Messages
|
| You can also pass an array with threshold levels to show individual error types
|
| 	array(2) = Debug Messages, without Error Messages
|
| For a live site you'll usually only enable Errors (1) to be logged otherwise
| your log files will fill up very fast.
|
*/
$config['log_threshold'] = 1;

/*
|--------------------------------------------------------------------------
| Error Logging Directory Path
|--------------------------------------------------------------------------
|
| Leave this BLANK unless you would like to set something other than the default
| application/logs/ directory. Use a full server path with trailing slash.
|
*/
$config['log_path'] = '';

/*
|--------------------------------------------------------------------------
| Log File Extension
|--------------------------------------------------------------------------
|
| The default filename extension for log files. The default 'php' allows for
| protecting the log files via basic scripting, when they are to be stored
| under a publicly accessible directory.
|
| Note: Leaving it blank will default to 'php'.
|
*/
$config['log_file_extension'] = '';

/*
|--------------------------------------------------------------------------
| Log File Permissions
|--------------------------------------------------------------------------
|
| The file system permissions to be applied on newly created log files.
|
| IMPORTANT: This MUST be an integer (no quotes) and you MUST use octal
|            integer notation (i.e. 0700, 0644, etc.)
*/
$config['log_file_permissions'] = 0644;

/*
|--------------------------------------------------------------------------
| Date Format for Logs
|--------------------------------------------------------------------------
|
| Each item that is logged has an associated date. You can use PHP date
| codes to set your own date formatting
|
*/
$config['log_date_format'] = 'Y-m-d H:i:s';

/*
|--------------------------------------------------------------------------
| Error Views Directory Path
|--------------------------------------------------------------------------
|
| Leave this BLANK unless you would like to set something other than the default
| application/views/errors/ directory.  Use a full server path with trailing slash.
|
*/
$config['error_views_path'] = '';

/*
|--------------------------------------------------------------------------
| Cache Directory Path
|--------------------------------------------------------------------------
|
| Leave this BLANK unless you would like to set something other than the default
| application/cache/ directory.  Use a full server path with trailing slash.
|
*/
$config['cache_path'] = '';

/*
|--------------------------------------------------------------------------
| Cache Include Query String
|--------------------------------------------------------------------------
|
| Whether to take the URL query string into consideration when generating
| output cache files. Valid options are:
|
|	FALSE      = Disabled
|	TRUE       = Enabled, take all query parameters into account.
|	             Please be aware that this may result in numerous cache
|	             files generated for the same page over and over again.
|	array('q') = Enabled, but only take into account the specified list
|	             of query parameters.
|
*/
$config['cache_query_string'] = FALSE;

/*
|--------------------------------------------------------------------------
| Encryption Key
|--------------------------------------------------------------------------
|
| If you use the Encryption class, you must set an encryption key.
| See the user guide for more info.
|
| https://codeigniter.com/user_guide/libraries/encryption.html
|
*/
$config['encryption_key'] = '';

/*
|--------------------------------------------------------------------------
| Session Variables
|--------------------------------------------------------------------------
|
| 'sess_driver'
|
|	The storage driver to use: files, database, redis, memcached
|
| 'sess_cookie_name'
|
|	The session cookie name, must contain only [0-9a-z_-] characters
|
| 'sess_expiration'
|
|	The number of SECONDS you want the session to last.
|	Setting to 0 (zero) means expire when the browser is closed.
|
| 'sess_save_path'
|
|	The location to save sessions to, driver dependent.
|
|	For the 'files' driver, it's a path to a writable directory.
|	WARNING: Only absolute paths are supported!
|
|	For the 'database' driver, it's a table name.
|	Please read up the manual for the format with other session drivers.
|
|	IMPORTANT: You are REQUIRED to set a valid save path!
|
| 'sess_match_ip'
|
|	Whether to match the user's IP address when reading the session data.
|
|	WARNING: If you're using the database driver, don't forget to update
|	         your session table's PRIMARY KEY when changing this setting.
|
| 'sess_time_to_update'
|
|	How many seconds between CI regenerating the session ID.
|
| 'sess_regenerate_destroy'
|
|	Whether to destroy session data associated with the old session ID
|	when auto-regenerating the session ID. When set to FALSE, the data
|	will be later deleted by the garbage collector.
|
| Other session cookie settings are shared with the rest of the application,
| except for 'cookie_prefix' and 'cookie_httponly', which are ignored here.
|
*/
$config['sess_driver'] = 'files';
$config['sess_cookie_name'] = 'ci_session';
$config['sess_expiration'] = 7200;
$config['sess_save_path'] = NULL;
$config['sess_match_ip'] = FALSE;
$config['sess_time_to_update'] = 300;
$config['sess_regenerate_destroy'] = FALSE;

/*
|--------------------------------------------------------------------------
| Cookie Related Variables
|--------------------------------------------------------------------------
|
| 'cookie_prefix'   = Set a cookie name prefix if you need to avoid collisions
| 'cookie_domain'   = Set to .your-domain.com for site-wide cookies
| 'cookie_path'     = Typically will be a forward slash
| 'cookie_secure'   = Cookie will only be set if a secure HTTPS connection exists.
| 'cookie_httponly' = Cookie will only be accessible via HTTP(S) (no javascript)
|
| Note: These settings (with the exception of 'cookie_prefix' and
|       'cookie_httponly') will also affect sessions.
|
*/
$config['cookie_prefix']	= '';
$config['cookie_domain']	= '';
$config['cookie_path']		= '/';
$config['cookie_secure']	= FALSE;
$config['cookie_httponly'] 	= FALSE;

/*
|--------------------------------------------------------------------------
| Standardize newlines
|--------------------------------------------------------------------------
|
| Determines whether to standardize newline characters in input data,
| meaning to replace \r\n, \r, \n occurrences with the PHP_EOL value.
|
| WARNING: This feature is DEPRECATED and currently available only
|          for backwards compatibility purposes!
|
*/
$config['standardize_newlines'] = FALSE;

/*
|--------------------------------------------------------------------------
| Global XSS Filtering
|--------------------------------------------------------------------------
|
| Determines whether the XSS filter is always active when GET, POST or
| COOKIE data is encountered
|
| WARNING: This feature is DEPRECATED and currently available only
|          for backwards compatibility purposes!
|
*/
$config['global_xss_filtering'] = FALSE;

/*
|--------------------------------------------------------------------------
| Cross Site Request Forgery
|--------------------------------------------------------------------------
| Enables a CSRF cookie token to be set. When set to TRUE, token will be
| checked on a submitted form. If you are accepting user data, it is strongly
| recommended CSRF protection be enabled.
|
| 'csrf_token_name' = The token name
| 'csrf_cookie_name' = The cookie name
| 'csrf_expire' = The number in seconds the token should expire.
| 'csrf_regenerate' = Regenerate token on every submission
| 'csrf_exclude_uris' = Array of URIs which ignore CSRF checks
*/
$config['csrf_protection'] = FALSE;
$config['csrf_token_name'] = 'csrf_test_name';
$config['csrf_cookie_name'] = 'csrf_cookie_name';
$config['csrf_expire'] = 7200;
$config['csrf_regenerate'] = TRUE;
$config['csrf_exclude_uris'] = array();

/*
|--------------------------------------------------------------------------
| Output Compression
|--------------------------------------------------------------------------
|
| Enables Gzip output compression for faster page loads.  When enabled,
| the output class will test whether your server supports Gzip.
| Even if it does, however, not all browsers support compression
| so enable only if you are reasonably sure your visitors can handle it.
|
| Only used if zlib.output_compression is turned off in your php.ini.
| Please do not use it together with httpd-level output compression.
|
| VERY IMPORTANT:  If you are getting a blank page when compression is enabled it
| means you are prematurely outputting something to your browser. It could
| even be a line of whitespace at the end of one of your scripts.  For
| compression to work, nothing can be sent before the output buffer is called
| by the output class.  Do not 'echo' any values with compression enabled.
|
*/
$config['compress_output'] = FALSE;

/*
|--------------------------------------------------------------------------
| Master Time Reference
|--------------------------------------------------------------------------
|
| Options are 'local' or any PHP supported timezone. This preference tells
| the system whether to use your server's local time as the master 'now'
| reference, or convert it to the configured one timezone. See the 'date
| helper' page of the user guide for information regarding date handling.
|
*/
$config['time_reference'] = 'local';

/*
|--------------------------------------------------------------------------
| Rewrite PHP Short Tags
|--------------------------------------------------------------------------
|
| If your PHP installation does not have short tag support enabled CI
| can rewrite the tags on-the-fly, enabling you to utilize that syntax
| in your view files.  Options are TRUE or FALSE (boolean)
|
| Note: You need to have eval() enabled for this to work.
|
*/
$config['rewrite_short_tags'] = FALSE;

/*
|--------------------------------------------------------------------------
| Reverse Proxy IPs
|--------------------------------------------------------------------------
|
| If your server is behind a reverse proxy, you must whitelist the proxy
| IP addresses from which CodeIgniter should trust headers such as
| HTTP_X_FORWARDED_FOR and HTTP_CLIENT_IP in order to properly identify
| the visitor's IP address.
|
| You can use both an array or a comma-separated list of proxy addresses,
| as well as specifying whole subnets. Here are a few examples:
|
| Comma-separated:	'10.0.1.200,192.168.5.0/24'
| Array:		array('10.0.1.200', '192.168.5.0/24')
*/
$config['proxy_ips'] = '';

$config['token_expire_time'] 	= '86400';
$config['site_name'] 			= 'http://mughalmahal.com/';
$config['company_name'] 		= 'Mughal Mahal';
$config['company_logo'] 		= 'http://mughalmahal.com/images/mughal-mahal.png';
$config['twitter_link'] 		= 'https://twitter.com/';
$config['facebook_link'] 		= 'https://facebook.com/';
$config['address_line1'] 		= 'Khalifa Tower / Warba Bank, Block - 5,';
$config['address_line2'] 		= 'Khalid IBN Waleed Street, Floor - 5, Kuwait City, Sharq.';
$config['company_state'] 		= '2386, Safat 13024';
$config['company_country'] 		= 'Kuwait';
$config['company_email'] 		= 'info@mughalmahal.com';
$config['company_phone'] 		= ' +965 22425131 / 2 ';
$config['newsletter_link'] 		= 'http://itoneclick.com/';
$config['project_name'] 		= 'Mughal Mahal';

// Config for sending an email using smtp/phpmailer()
/*$config['protocol']   		= 'smtp';
$config['smtp_host']    	= 'ssl://a2plcpnl0438.prod.iad2.secureserver.net';
$config['smtp_port']    	= '465';
$config['smtp_user']    	= 'developer@itoneclick.com';
$config['smtp_pass']    	= 'Oneclick1@';
$config['charset']    		= 'utf-8';
$config['newline']    		= '"\r\n"';
$config['mailtype'] 		= 'html'; // or html
$config['validation'] 		= TRUE;
$config['wordwrap'] 		= TRUE;*/

$config['protocol']   		= 'smtp';
$config['smtp_host']    	= 'ssl://smtp.googlemail.com';
$config['smtp_port']    	= '465';
$config['smtp_user']    	= 'customercare@mughalmahal.com';
$config['smtp_pass']    	= 'Kuwait2019$';
// $config['charset']    		= 'iso-8859-1';
$config['newline']    		= '"\r\n"';
$config['mailtype'] 		= 'html'; // or html
$config['validation'] 		= TRUE;
$config['wordwrap'] 		= TRUE;

$config['super_admin_role']			= '1';
$config['restaurant_owner_role'] 	= '2';
$config['restaurant_manager_role'] 	= '3';
$config['driver_role'] 				= '4';
$config['customer_role'] 			= '5';
$config['sales_role']    = '6';

$config['supported_languages'] 		= array("EN","AR");
$config['default_language'] 		= "EN";

$config['currency'] 				= 'KWD';
$config['gateway_code'] 			= 'credit-card'; /*test-knet*/
$config['disclosure_url'] 			= "https://pay.mughalmahal.com/pos/crt/";
$config['redirect_url'] 			= "http://oneclickitmarketing.co.in/subdomain/demo/knpay/success.php";

$config['OrderStatus'] 				= array("0"=>"Pending Order","1"=>"Confirm Order", "2"=>"Order Confirmed","3"=>"Cooking", "4"=>"Driver Collected The Order", "5"=>"Driver On The Way", "6"=>"Driver Near To You", "7"=>"Delivered","8"=>"Disputed","9"=>"Refund","10"=>"Refunded","11"=>"Replace","12"=>"Replaced","13"=>"Discarded By Customer","14"=>"Discarded By Admin");


$config['panelColor'] 				= array("0"=>"panel-default","1"=>"panel-primary", "2"=>"panel-info","3"=>"panel-warning", "4"=>"panel-collect", "5"=>"panel-ontheway", "6"=>"panel-nearby", "7"=>"panel-success","8"=>"panel-danger","9"=>"panel-success","10"=>"panel-success","11"=>"panel-info","12"=>"panel-collect","13"=>"panel-default","14"=>"panel-default");

$config['labelColor'] 				= array("0"=>"label-default","1"=>"label-primary", "2"=>"label-info","3"=>"label-warning", "4"=>"label-collect", "5"=>"label-ontheway", "6"=>"label-nearby", "7"=>"label-success","8"=>"label-danger","9"=>"label-success","10"=>"label-success","11"=>"label-info","12"=>"label-collect","13"=>"label-default", "14"=>"label-default");

$config['orderType'] 				= array("1"=>"Online", "2"=>"Store Pickup","3"=>"Cash On Delivery", "4"=>"Dine In");

//Opening Days for restaurant
$config['days'] 				= array("1"=>"Monday", "2"=>"Tuesday","3"=>"Wednesday", "4"=>"Thursday",'5'=>'Friday','6'=>'Saturday','7'=>'Sunday');

$config['day'] 				= array("Mon"=>"1", "Tue"=>"2","Wed"=>"3", "Thu"=>"4",'Fri'=>'5','Sat'=>'6','Sun'=>'7');    
//Arabic Order Status 
$config['OrderStatus_ar'] 			= array("0"=>"طلب معلق","1"=>"تم الطلب", "2"=>"تم تاكيد الطلب","3"=>"طبخ", "4"=>"سائق جمع الترتيب", "5"=>"سائق على الطريق", "6"=>"سائق بالقرب من أنت", "7"=>"تم التوصيل","8"=>"المتنازع عليها","9"=>"إعادة مال","10"=>"ردها","11"=>"يحل محل","12"=>"استبدال","13"=>"تجاهل حسب العرف","14"=>"تجاهل من قبل المشرف");

//types of report
$config['report_type'] 				= array("1" =>"Best Selling Area","2" =>"Most Selling Dishes","3" =>"Cancelled Orders","4" =>"Order per Hour","5" =>"Order per Day","6" =>"Order per Month","7" =>"Sales Report","8"=>"Summary","9"=>"Sales By Category");

//types of paymnet
$config['payment_type'] 			= array('1'=>'Credit Card','2'=>'KNET Debit Card','3'=>'COD','4'=>'Talabat Credit','5'=>'Loyalty Redemption','6'=>'Self cash','7'=>'Self KNET','8'=>'Self CC');

//orderId range
$config['order_range'] 				= 6;

//Customer Type
$config['customer_type']			= array("1" =>  "Talabat", "2" => "Takeaway", "3" => "Dine In", "4" => "Home Delivery");
