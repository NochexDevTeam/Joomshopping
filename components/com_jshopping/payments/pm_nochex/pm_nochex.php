<?php
defined('_JEXEC') or die('Restricted access');

class pm_nochex extends PaymentRoot{
    
    function showPaymentForm($params){
        include(dirname(__FILE__)."/paymentform.php");
    }

	//function call in admin
	function showAdminFormParams($params){
	  $array_params = array('testmode', 'email_received', 'transaction_end_status', 'transaction_pending_status', 'transaction_failed_status', 'hidemode', 'xmlCollection', 'debug', 'postage');
	  foreach ($array_params as $key){
	  	if (!isset($params[$key])) $params[$key] = '';
	  } 
	  $orders = JSFactory::getModel('orders', 'JshoppingModel'); //admin model
      include(dirname(__FILE__)."/adminparamsform.php");	  
	}

	function checkTransaction($params, $order, $act){

        $jshopConfig = &JSFactory::getConfig();

		$postvars = http_build_query($_POST);

		$url = "https://secure.nochex.com/apc/apc.aspx";

		// Curl code to post variables back
		$ch = curl_init(); // Initialise the curl tranfer
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_VERBOSE, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars); // Set POST fields
		curl_setopt($ch, CURLOPT_HTTPHEADER, "Host: www.nochex.com");
		curl_setopt($ch, CURLOPT_POSTFIELDSIZE, 0); 
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60); // set connection time out variable - 60 seconds	
		//curl_setopt ($ch, CURLOPT_SSLVERSION, 6); // set openSSL version variable to CURL_SSLVERSION_TLSv1
		$output = curl_exec($ch); // Post back
		curl_close($ch);

		// Put the variables in a printable format for the email
		$debug = "IP -> " . $_SERVER['REMOTE_ADDR'] ."\r\n\r\nPOST DATA:\r\n"; 
		foreach($_POST as $Index => $Value) 
		$debug .= "$Index -> $Value\r\n"; 
		$debug .= "\r\nRESPONSE:\r\n$output";

		//If statement
		if (!strstr($output, "AUTHORISED")) {  // searches response to see if AUTHORISED is present if it isn’t a failure message is displayed
			return array(3, 'Status Failed. Order ID '.$order->order_id,$output,$_POST);
			saveToLog("payment.log", "Nochex failed: ".$order->order_id);
		} 
		else { 
			return array(1, 'Status Payment Authorised'.$order->order_id,$output,$_POST);
			saveToLog("payment.log", "Nochex Authorised: ".$order->order_id);
		}


        if ($jshopConfig->savelog && $jshopConfig->savelogpaymentdata){

            saveToLog("paymentdata.log", $debug);

        }

	}

	function showEndForm($params, $order){
        
        $jshopConfig = &JSFactory::getConfig();        
	    $item_name = sprintf(_JSHOP_PAYMENT_PRODUCT_IN_SITE, $jshopConfig->store_name);
        
		$cart = JSFactory::getModel('cart', 'jshop');

        $cart->load();
		
        if ($params['testmode'] == 1){
		
			$test_code = "100";
			$success_url = "test_success_url";

        } else{
            $test_code = "0";
			$success_url = "success_url";
        } 

		if($params['hidemode'] == 1){
		
			$hide_mode = "true";
		
		}
		
		if($params['postage'] == 1){
		
		$TotalAmount = $order->order_total - $order->order_shipping;
		$shipping = $order->order_shipping;
		
		}else{
		
		$TotalAmount = $order->order_total;
		$shipping = '';
		
		}
       
        $email = $params['email_received'];
        $notify_url = JURI::root() . "index.php?option=com_jshopping&controller=checkout&task=step7&act=notify&js_paymentclass=pm_nochex&no_lang=1";
        $return = JURI::root(). "index.php?option=com_jshopping&controller=checkout&task=step7&act=return&js_paymentclass=pm_nochex";
        $cancel_return = JURI::root() . "index.php?option=com_jshopping&controller=checkout&task=step7&act=cancel&js_paymentclass=pm_nochex";

		
		$fullName = $order->f_name . ", " . $order->l_name;
		$billing_address =  $order->street;
		
		$deliveryfullname = $order->d_f_name . ", " . $order->d_l_name;
		$delivery_address = $order->d_street;
		
			
		
		if($params['xmlCollection'] == 1){
		
		$description = $item_name;
		
		$xmlTags = "<items>";
		
		foreach ($cart->products as $shwproducts){
		
			$xmlTags .= '<item><id>' . $shwproducts["product_id"] . '</id><name>' . $shwproducts["product_name"] . '</name><description>' . $shwproducts["product_name"] . '</description><quantity>' . $shwproducts["quantity"] . '</quantity><price>' . $shwproducts["price"] . '</price></item>';
		
		}
		$xmlTags .= "</items>";
		
		}else{
		
		$description = '';
		
		foreach ($cart->products as $shwproducts){
		
			$description .= 'Product ID: ' . $shwproducts["product_id"] . ', Product Name: ' . $shwproducts["product_name"] . ', Price:' . $shwproducts["price"] . ', Quantity: ' . $shwproducts["quantity"] . ', Details:' ;
		
		}
		
		}
		//print_r($params);
	
		
        ?>
        <html>
        <body>
        <head>
            <meta http-equiv="content-type" content="text/html; charset=utf-8" />            
        </head><!---->
        <form id="paymentform" action="https://secure.nochex.com" name="paymentform" method="post">
        <input type='hidden' name='merchant_id' value='<?php print $email?>'>      
		<input type='hidden' name='amount' value='<?php print $TotalAmount ?>'>  
		<input type='hidden' name='postage' value='<?php print $shipping ?>'>  
		<input type='hidden' name='description' value='<?php print $description?>'>
		
		<input type='hidden' name='xml_item_collection' value='<?php print $xmlTags ?>'>
		<input type='hidden' name='hide_billing_details' value='<?php print $hide_mode?>'>
		<input type='hidden' name='billing_fullname' value='<?php print $deliveryfullname?>'>
		<input type='hidden' name='billing_address' value='<?php print $billing_address?>'>
		<input type='hidden' name='billing_city' value='<?php print $order->city?>'>
		<input type='hidden' name='billing_postcode' value='<?php print $order->zip?>'>
		
		<input type='hidden' name='delivery_fullname' value='<?php print $deliveryfullname?>'>
		<input type='hidden' name='delivery_address' value='<?php print $delivery_address?>'>
		<input type='hidden' name='delivery_city' value='<?php print $order->d_city?>'>
		<input type='hidden' name='delivery_postcode' value='<?php print $order->d_zip?>'>
		
		<input type='hidden' name='customer_phone_number' value='<?php print $order->phone?>'>
		<input type='hidden' name='email_address' value='<?php print $order->email?>'>
		
        <input type='hidden' name='callback_url' value='<?php print $notify_url?>'>
        <input type='hidden' name='<? print $success_url ?>' value='<?php print $return?>'>
		<input type='hidden' name='test_transaction' value='<?php print $test_code?>'>   
        <input type='hidden' name='cancel_url' value='<?php print $cancel_return?>'>    
		
		<input type='hidden' name='order_id' value='<?php print $order->order_id?>'>
		
        </form>
        <?php print _JSHOP_REDIRECT_TO_PAYMENT_PAGE ?>
        <br>
        <script type="text/javascript">document.getElementById('paymentform').submit();</script>
        </body>
        </html>
        <?php
        die();
	}
    
    function getUrlParams($ps_params){                        
        $params = array(); 
        $params['order_id'] = JRequest::getInt("order_id");
        $params['hash'] = "";
        $params['checkHash'] = 0;
        $params['checkReturnParamss'] = $ps_params['checkdatareturns'];
    return $params;
    }
    
}

?>
