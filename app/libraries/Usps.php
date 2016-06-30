<?php
namespace App\Libraries;

use Session;use DB;
use Cart;

class Usps  {

//usps prority mail pdf label file generation

function USPSLabel($parameters_array){

		$xml_data="<DeliveryConfirmationV4.0Request USERID='".env('USERID')."'>".

		"<Revision>2</Revision>".
		"<ImageParameters />".
		"<FromName>".env('FROMNAME')."</FromName>".
		"<FromFirm>".env('FROMFIRM')."</FromFirm>".
		"<FromAddress1>".env('FROMADDRESS1')."</FromAddress1>".
		"<FromAddress2>".env('FROMADDRESS2')."</FromAddress2>".
		"<FromCity>".env('FROMCITY')."</FromCity>".
		"<FromState>".env('FROMSTATE')."</FromState>".
		"<FromZip5>".env('FROMZIP5')."</FromZip5>".
		"<FromZip4/>".
		"<ToName>".$parameters_array['ToName']."</ToName>".
		"<ToFirm>".$parameters_array['ToFirm']."</ToFirm>".
		"<ToAddress1>".$parameters_array['ToAddress2']."</ToAddress1>".
		"<ToAddress2>".$parameters_array['ToAddress2']."</ToAddress2>".
		"<ToCity>".$parameters_array['ToCity']."</ToCity>".
		"<ToState>".$parameters_array['ToState']."</ToState>".
		"<ToZip5>".$parameters_array['ToZip5']."</ToZip5>".
		"<ToZip4 />".
		"<ToPOBoxFlag></ToPOBoxFlag>".
		"<WeightInOunces>10</WeightInOunces>".
		"<ServiceType>PRIORITY</ServiceType>".

		"<SeparateReceiptPage>False</SeparateReceiptPage>".
		
		"<ImageType>PDF</ImageType>".
		"<AddressServiceRequested>False</AddressServiceRequested>".
		"<HoldForManifest>N</HoldForManifest>".
		//"<Container>NONRECTANGULAR</Container>".
	//	"<Size>".$parameters_array['Size']."</Size>".
	//	"<Width>".$parameters_array['Width']."</Width>".
	//	"<Length>".$parameters_array['Length']."</Length>".
	//	"<Height>".$parameters_array['Height']."</Height>".
	//	"<Girth>".$parameters_array['Girth']."</Girth>".
		"<ReturnCommitments>true</ReturnCommitments>".
		"</DeliveryConfirmationV4.0Request>";



		$url = "https://secure.shippingapis.com/ShippingAPI.dll?API=DeliveryConfirmationV4";

		$output=$this->callCurl($url,$xml_data);

		

		$array_data = json_decode(json_encode(simplexml_load_string($output)), true);
		//print_r($array_data);exit;
		
		$ret_array = $this->generateLabel($array_data,$parameters_array['order_id']);
		return $ret_array;
	
	}

//track a shipment

public function trackrequest($parameters_array){


		$user = $parameters_array['user'];
	
		$url = "http://production.shippingapis.com/ShippingAPI.dll?API=TrackV2";
	
		$xml_data ='<TrackFieldRequest USERID='.env('USERID').'>'.
		'<Revision>1</Revision>'.
		'<ClientIp>'.$parameters_array['FromIP'].'</ClientIp>'.
		'<SourceId>'.$parameters_array['Name'].'</SourceId>'.
		'<TrackID ID="'.$parameters_array['TrackID'].'">'.
		'<DestinationZipCode>'.$parameters_array['Zipcode'].'</DestinationZipCode>'.
		'<MailingDate>'.$parameters_array['MailDate'].'</MailingDate>'.
		'</TrackID>'.
		'</TrackFieldRequest>';
		
		$output=$this->callCurl($url,$xml_data);

		

		$array_data = json_decode(json_encode(simplexml_load_string($output)), true);
		return $array_data;
}


//common curl call

private function callCurl($url,$data){
	
		//setting the curl parameters.
		    $ch = curl_init();
		    curl_setopt($ch, CURLOPT_URL, $url);
		    // Following line is compulsary to add as it is:
		    curl_setopt($ch, CURLOPT_POSTFIELDS,'XML=' . $data);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
		    $output = curl_exec($ch);
		    curl_error($ch);
		    curl_close($ch);
		    return $output;
	}	

	
	
	
	
	
//generate shipping label file for priority mail

private function generateLabel($hasdata,$order_id){
	try
    {
		if(!isset($hasdata['DeliveryConfirmationLabel'])){
			return array("filename"=>'',"tracking_no"=>'');
		}
		$filecontent=base64_decode($hasdata['DeliveryConfirmationLabel']);

		$path = 'uploads/pdf/';
		$usps_filename = $order_id.'_label_'.rand(9999,99999).'.pdf';
		$label_title = $path.$usps_filename;

		
		$file=fopen($label_title,"w");
		
		fwrite($file,$filecontent);
		fclose($file);

		
		return array("filename"=>$usps_filename,"tracking_no"=>substr($hasdata['DeliveryConfirmationNumber'], 8));
    }
    catch(\App\Http\Controllers\Exception $e)
	{
		
	 return Redirect::to('/admin/orders')->withErrors( $e->getErrors() ) ->withInput();
	}
		 
}

	
	
//validate address for proper shipping label

public function varifyaddress($parameters_array){
	
	$url = "https://secure.shippingapis.com/ShippingAPI.dll?API=Verify";
	
	$xml_data=urlencode('<AddressValidateRequest USERID="'.env('USERID').'">
	<IncludeOptionalElements>true</IncludeOptionalElements>
      	<ReturnCarrierRoute>true</ReturnCarrierRoute>
	<Address ID="0">  
	  <FirmName />   
	  <Address1>'.$parameters_array['Address1'].'<Address1/>   
	  <Address2>'.$parameters_array['Address2'].'</Address2>   
	  <City>'.$parameters_array['City'].'</City>   
	  <State>'.$parameters_array['State'].'</State>   
	  <Zip5></Zip5>   
	  <Zip4>'.$parameters_array['Zip4'].'</Zip4> 
	</Address>      
      
      </AddressValidateRequest>');
	
	$output=$this->callCurl($url,$xml_data);

		$array_data = json_decode(json_encode(simplexml_load_string($output)), true);
		return $array_data;
}




//get shipping rates at checkout time

public function getshippingRates($parameters_array){
	
	$url='http://production.shippingapis.com/ShippingAPI.dll?API=RateV4';
	$xml_data=urlencode('<RateV4Request USERID="'.env('USERID').'">
	<Revision>2</Revision>
	<Package ID="1ST">
	<Service>FIRST CLASS</Service>
	<FirstClassMailType>LETTER</FirstClassMailType>
	<ZipOrigination>21209</ZipOrigination>
	<ZipDestination>'.$parameters_array['ZipDestination'].'</ZipDestination>
	<Pounds>0</Pounds>
	<Ounces>10</Ounces>
	<Container/>
	<Size>REGULAR</Size>
	<Machinable>true</Machinable>
	</Package>
	
	</RateV4Request>');

	$output=$this->callCurl($url,$xml_data);

		

		$array_data = json_decode(json_encode(simplexml_load_string($output)), true);
		return $array_data;
}



//print multiple pdf

public function printPdf($files){
	$filecontents='';
	foreach($files as $file){
	$filecontents .= file_get_contents($file);
	}

	$handle = printer_open();
	printer_write($handle,$filecontents);
	printer_close($handle);
}

public function new_printPdf($files){

	$filecontents='';
	foreach($files as $file){
	 	$filecontents = $filecontents.' '.$file;
	 }
	// print_r($filecontents);exit;
	 //shell_exec('lpr label2.pdf label22.pdf');

	 $output = shell_exec('lpr '.$filecontents);
	//echo "<pre>$output</pre>";


	
}

}
?>