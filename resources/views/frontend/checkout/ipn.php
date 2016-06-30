<?php
$base_url = "http://www.phppowerhousedemo.com/webroot/team13/payment_gateway/paypal/standard/";
$req = 'cmd=_notify-validate';
foreach ($_POST as $key => $value) {
	$value = urlencode(stripslashes($value));
	$value = preg_replace('/(.*[^%^0^D])(%0A)(.*)/i','${1}%0D%0A${3}',$value);// IPN fix
	$req .= "&$key=$value";
}
// assign posted variables to local variables
//$data['item_name']		= $_POST['item_name'];
//$data['item_number'] 		= $_POST['item_number'];
$data['payment_status'] 	= $_POST['payment_status'];
$data['payment_amount'] 	= $_POST['mc_gross'];
$data['discount'] 		= $_POST['discount'];
$data['mc_shipping'] 		= $_POST['mc_shipping'];
$data['mc_handling'] 		= $_POST['mc_handling'];
$data['payment_currency']	= $_POST['mc_currency'];
$data['txn_id']			= $_POST['txn_id'];
$data['receiver_email'] 	= $_POST['receiver_email'];
$data['payer_email'] 		= $_POST['payer_email'];
$cnt		 		= $_POST['custom'];

//list($data['user_id'],$data['plan_id'])= explode("-",$_POST['custom']);

$pay_date=date('l jS \of F Y h:i:s A');

// post back to PayPal system to validate
$header = "POST /cgi-bin/webscr HTTP/1.0\r\n";

$header .= "Host: www.sandbox.paypal.com\r\n";
//$header .= "Host: www.paypal.com:443\r\n";

$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";

$fp = fsockopen ('ssl://www.sandbox.paypal.com', 443, $errno, $errstr, 30);	
//$fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);
if (!$fp) 
{
	// HTTP ERROR
} 
else 
{
	fputs ($fp, $header . $req);
	while (!feof($fp)) {
		$res = fgets ($fp, 1024);
		if (strcmp($res, "VERIFIED") == 0) 
		{
			//mail("amit.unified@gmail.com", "PAYPAL DEBUGGING", "Verified Response<br />data = <pre>".print_r($_POST, true)."</pre>");exit;
			
			// Add POST to DB
			
			
			
			// Send Message
			$item_strng = '';
			for($k=1;$k<=$cnt;$k++){
				$item_strng .= "<tr>
							<td width='50%' align='right' valign='middle' class='td'>Name:&nbsp;</td>
							<td width='50%' align='left' valign='middle'>".$_POST['item_name'.$k]."</td>
						</tr>
						<tr>
							<td width='50%' align='right' valign='middle' class='td'>Quantity:&nbsp;</td>
							<td width='50%' align='left' valign='middle'>".$_POST['quantity'.$k]."</td>
						</tr>
						<tr>
							<td width='50%' align='right' valign='middle' class='td'>Price:&nbsp;</td>
							<td width='50%' align='left' valign='middle'>".$_POST['mc_gross_'.$k]."</td>
						</tr>";
			}
			
			$message="<table width='100%' border='0' cellspacing='0' cellpadding='0'>
				<tr>
				<td align='left' valign='top'>
				  <fieldset style=' margin-left:8px; margin-right:8px;padding-left:10px; padding-right:10px;'>
					  <LEGEND  ACCESSKEY=L><span class='heading_txt'>Billing / Shipping </span></LEGEND>
					  <table width='100%' border='0' cellspacing='0' cellpadding='0'>
				<tr>
				      <td width='50%' align='left' valign='middle'><table width='90%' border='1' align='left' cellpadding='0' cellspacing='0' bordercolor='#CCCCCC' style=' border-collapse: collapse;'>".$item_strng.
				      
					"<tr>
					      <td width='50%' align='right' valign='middle' class='td'> Payer Email:&nbsp;</td>
					      <td width='50%' align='left' valign='middle'>".$data['payer_email']."</td>
					</tr>
					<tr>
					      <td width='50%' align='right' valign='middle' class='td'> Shipping Price:&nbsp;</td>
					      <td width='50%' align='left' valign='middle'>".$data['mc_handling']."</td>
					</tr>
					<tr>
					      <td width='50%' align='right' valign='middle' class='td'> Discusount:&nbsp;</td>
					      <td width='50%' align='left' valign='middle'>".$data['discount']."</td>
					</tr>
					<tr>
					      <td width='50%' align='right' valign='middle' class='td'>&nbsp;</td>
					      <td width='50%' align='left' valign='middle'>&nbsp;</td>
					</tr>
				      </table></td>
				      <td width='50%' align='right' valign='top'><table width='90%' border='1' align='right' cellpadding='0' cellspacing='0' bordercolor='#CCCCCC' style=' border-collapse: collapse;'>
					<tr>
					      <td width='50%' align='right' valign='middle' class='td'>Order Date </td>
					      <td width='50%' class='td'>".$pay_date."</td>
					</tr>
			      
				      </table></td>
				</tr>
				
				
			      <tr>
				      <td width='50%' align='left' valign='middle' >&nbsp;</td>
				      <td width='50%'>&nbsp;</td>
				</tr>
				<tr>
				      <td width='50%' align='right' valign='middle'>&nbsp;</td>
				      <td width='50%'>&nbsp;</td>
				</tr>
				<tr>
				      <td align='right' valign='middle'>&nbsp;</td>
				      <td><table width='90%' border='0' align='right' cellpadding='0' cellspacing='0'>
					<tr>
					      <td width='45%' align='right' valign='middle' class='td'>Total</td>
					      <td width='45%' align='left' valign='middle' class='td'> $".$data['payment_amount']."</td>
					</tr>
				      </table></td>
				</tr>
				<tr>
				      <td align='right' valign='middle'>&nbsp;</td>
				      <td>&nbsp;</td>
				</tr>
			      </table>
			      
					</fieldset>
				      </td>
				</tr>
				<tr>
				      <td align='left' valign='top'>&nbsp;</td>
				</tr>
				<tr>
				      <td align='left' valign='top'>&nbsp;</td>
				</tr>
				<tr>
				      <td align='left' valign='top'>&nbsp;</td>
				</tr>
			      </table>";
				
				$subject="Payment Confirmation";
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'From: info@abcd.com' . "\r\n";
				$headers .= "\r\nReturn-Path: \r\n";  // Return path for errors 
				@mail('amit.unified@gmail.com', $subject, $message, $headers);
				@mail('sumitra.unified@gmail.com', $subject, $message, $headers);
			
						
		}
		if (strcmp ($res, "INVALID") == 0) {
		// PAYMENT INVALID & INVESTIGATE MANUALY! 
		// E-mail admin or alert user
		$message = '
			Dear Administrator,
			A payment has been made but is flagged as INVALID.
			Please verify the payment manualy and contact the buyer.
			Buyer Email: '.$data['payer_email'].'
			';
		// Used for debugging
		@mail("amit.unified@gmail.com", "PAYPAL DEBUGGING".$message, "Invalid Response<br />data = <pre>".print_r($post, true)."</pre>");
	}
}	
fclose ($fp);
}


?>

<!--
Verified Response<br />data = <pre>Array
(
    [mc_gross] => 22.00
    [protection_eligibility] => Ineligible
    [address_status] => confirmed
    [item_number1] =>
    [tax] => 0.00
    [item_number2] =>
    [payer_id] => AKHA8W2NAF8ES
    [address_street] => 1 Main St
    [payment_date] => 01:34:32 Feb 06, 2015 PST
    [payment_status] => Pending
    [charset] => windows-1252
    [address_zip] => 95131
    [mc_shipping] => 2.00
    [mc_handling] => 0.00
    [first_name] => Buyer
    [mc_fee] => 0.94
    [address_country_code] => US
    [address_name] => Buyer Buyer's Test Store
    [notify_version] => 3.8
    [custom] =>
    [payer_status] => verified
    [business] => amit_use@gmail.com
    [address_country] => United States
    [num_cart_items] => 2
    [mc_handling1] => 0.00
    [mc_handling2] => 0.00
    [address_city] => San Jose
    [verify_sign] => AwD4sJJmdrzDKNGw7KMAMuZSx1AHANf.je6FyCCj3a9nr48deZAGaXn0
    [payer_email] => amit.unified_buyer@gmail.com
    [mc_shipping1] => 2.00
    [mc_shipping2] => 0.00
    [tax1] => 0.00
    [tax2] => 0.00
    [txn_id] => 2KU20838UP189245K
    [payment_type] => instant
    [payer_business_name] => Buyer Buyer's Test Store
    [last_name] => Buyer
    [address_state] => CA
    [item_name1] => Test1
    [receiver_email] => amit_use@gmail.com
    [item_name2] => Test2
    [payment_fee] => 0.94
    [shipping_discount] => 0.00
    [quantity1] => 2
    [insurance_amount] => 0.00
    [quantity2] => 1
    [receiver_id] => NCWYQ9SKJWYX2
    [pending_reason] => paymentreview
    [txn_type] => cart
    [discount] => 5.00
    [mc_gross_1] => 12.00
    [mc_currency] => USD
    [mc_gross_2] => 15.00
    [residence_country] => US
    [test_ipn] => 1
    [shipping_method] => Default
    [transaction_subject] =>
    [payment_gross] => 22.00
    [ipn_track_id] => 942d551b52640
)-->
