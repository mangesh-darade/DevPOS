<?php defined('BASEPATH') OR exit('No direct script access allowed');

/** 
 * Sandbox / Test Mode
 * -------------------------
 * TRUE means you'll be hitting PayPal's sandbox /Stripe test mode. FALSE means you'll be hitting the live servers.
 */
$config['TestMode'] = FALSE;

/* ***************** Paypal Settings ***************** */
/* 
 * PayPal API Version
 * ------------------
 * The library is currently using PayPal API version 98.0.  
 * You may adjust this value here and then pass it into the PayPal object when you create it within your scripts to override if necessary.
 */
$config['APIVersion'] = '98.0';

/*
 * PayPal Gateway API Credentials
 * ------------------------------
 * These are your PayPal API credentials for working with the PayPal gateway directly.
 * These are used any time you're using the parent PayPal class within the library.
 * 
 * We're using shorthand if/else statements here to set both Sandbox and Production values.
 * Your sandbox values go on the left and your live values go on the right.
 * 
 * You may obtain these credentials by logging into the following with your PayPal account: https://www.paypal.com/us/cgi-bin/webscr?cmd=_login-api-run
 */
$config['APIUsername'] = $config['TestMode'] ? '' : '';
$config['APIPassword'] = $config['TestMode'] ? '' : '';
$config['APISignature'] = $config['TestMode'] ? '' : '';


/* ***************** Stripe Keys ***************** */
/* 
 * Stripe API Keys
 * ------------------ 
 * You may obtain these by visiting account settings link and then API keys at https://dashboard.stripe.com/login
 */
$config['stripe_secret_key']			= $config['TestMode'] ? '' : ''; 
$config['stripe_publishable_key']		= $config['TestMode'] ? '' : ''; 

/* ***************** Authorize.net ***************** */
/* 
 * Authorize.net API Keys
 * ---------------------- 
 * You may obtain these by visiting account settings link and then API keys at https://authorize.net/
 */
$config['authorize'] = array(
    'api_login_id' => ($config['TestMode'] ? '' : ''),
    'api_transaction_key' => ($config['TestMode'] ? '' : ''),
    'api_url' => ($config['TestMode'] ? 'https://test.authorize.net/gateway/transact.dll' : 'https://secure.authorize.net/gateway/transact.dll')
    );

/* ***************** instamojo ***************** */
/* 
 * instamojo Keys
 * ----------------------  
 */

$config['instamojo'] = array(
    'API_KEY' => ($config['TestMode'] ? '' : ''),
    'AUTH_TOKEN' => ($config['TestMode'] ? '' : ''),
    'API_URL' => ($config['TestMode'] ? 'https://test.instamojo.com/api/1.1/' : 'https://www.instamojo.com/api/1.1/')
);

/* ***************** CCAvenue ***************** */
/* 
 * CCAvenue Keys
 * ----------------------  
 */

$config['ccavenue'] = array(
    'MERCHANT_ID' => ($config['TestMode'] ? '' : ''),
    'ACCESS_CODE' => ($config['TestMode'] ? '' : ''),
    'API_KEY'     => ($config['TestMode'] ? '' : ''),
    'API_URL'     => ($config['TestMode'] ? 'https://secure.ccavenue.com/transaction/transaction.do?command=initiateTransaction' : 'https://secure.ccavenue.com/transaction/transaction.do?command=initiateTransaction')
);

/* ***************** PayTM ***************** */
/* 
 * PayTM Keys
 * ----------------------  
 */

$config['paytm'] = array(
    'PAYTM_ENVIRONMENT' => ($config['TestMode'] ? '' : ''),
    'PAYTM_MERCHANT_KEY' => ($config['TestMode'] ? '' : ''),
    'PAYTM_MERCHANT_MID'     => ($config['TestMode'] ? '' : ''),
    'PAYTM_MERCHANT_WEBSITE'     => ($config['TestMode'] ? '' : '')
);

$PAYTM_DOMAIN = "pguat.paytm.com";
if ($config['paytm']['PAYTM_ENVIRONMENT']== 'PROD') {
	$PAYTM_DOMAIN = 'secure.paytm.in';
}
$config['paytm']['PAYTM_REFUND_URL']= 'https://'.$PAYTM_DOMAIN.'/oltp/HANDLER_INTERNAL/REFUND';
$config['paytm']['PAYTM_STATUS_QUERY_URL']= 'https://'.$PAYTM_DOMAIN.'/oltp/HANDLER_INTERNAL/TXNSTATUS';
$config['paytm']['PAYTM_TXN_URL']= 'https://'.$PAYTM_DOMAIN.'/oltp-web/processTransaction';



 
/* ***************** Paynear ***************** */
/* 
 * Paynear Keys
 * ----------------------  
 */
$config['paynear'] = array(
    'PAYNEAR_APP_SECRET_KEY' => ($config['TestMode'] ? '' : ''),
    'PAYNEAR_SECRET_KEY' => ($config['TestMode'] ? '' : ''),
    'PAYNEAR_MERCHANT_ID' => ($config['TestMode'] ? '' : ''),
    'PAYNEAR_APP_MERCHANT_ID' => ($config['TestMode'] ? '' : ''),
    'PAYNEAR_SANDBOX'     => ($config['TestMode'] ? '' : ''), 
);




 
/* ***************** Payumoney ******************/
/* 
 * payumoney Keys
 * ----------------------  
 */
$config['payumoney'] = array(
    'PAYUMONEY_MID' => ($config['TestMode'] ? '' : ''),
    'PAYUMONEY_KEY' => ($config['TestMode'] ? '' : ''),
    'PAYUMONEY_SALT' => ($config['TestMode'] ? '' : ''),
    'PAYUMONEY_AUTH_HEADER' => ($config['TestMode'] ? '' : ''), 
);


 
/* End of file payment_gateways.php */
/* Location: ./sma/config/payment_gateways.php */