<?php
namespace App\Libraries;

use Session;use DB;
use Cart;

class Postmaster 
{

	public static $apiKey;
	public static $apiBase = 'https://api.postmaster.io';
	const VERSION = '1.3.1';




	public static function apiUrl($url='')
  {
    $apiBase = Postmaster::$apiBase;
    return "$apiBase$url";
  }

  public static function utf8($value)
  {
    if (is_string($value))
      return utf8_encode($value);
    else
      return $value;
  }

  public function request($meth, $url, $params=null, $headers=null)
  {
    $absUrl = self::apiUrl($url);
    $apiKey = self::$apiKey;

    if (!$params)
      $params = array();

    $ua = array(
      'bindings_version' => Postmaster::VERSION,
      'lang' => 'php',
      'lang_version' => phpversion(),
      'publisher' => 'stripe',
      'uname' => php_uname()
    );
    $allHeaders = array(
        'X-Postmaster-Client-User-Agent: ' . json_encode($ua),
        'User-Agent: Postmaster/v1 PhpBindings/' . Postmaster::VERSION
    );
    if ($headers)
        $allHeaders = array_merge($allHeaders, $headers);

    list($rbody, $rcode) = $this->_curlRequest($meth, $absUrl, $allHeaders, $params, $apiKey);

    if ($rbody == 'OK') {
      $resp = $rbody;
    } else {
      try {
        $resp = json_decode($rbody, true);
      } catch (Exception $e) {
        throw new Postmaster_Error("Invalid response body from API: $rbody (HTTP response code was $rcode)", $rcode, $rbody);
      }
    }

    if ($rcode < 200 || $rcode >=300) {
      if (is_array($resp) && array_key_exists('message', $resp)) {
        $msg = $resp['message'];
      } else {
        $msg = "Unknown API error";
      }
      if ($rcode == 400) {
        return $resp;
      } else if ($rcode == 401) {
        return $resp;
      } else if ($rcode == 403) {
        return $resp;
      } 
      return $resp;
    }
    return $resp;
  }

  private function _curlRequest($meth, $absUrl, $headers, $params, $apiKey)
  {
    $curl = curl_init();
    $opts = array();
    if ($meth == 'get') {
      $opts[CURLOPT_HTTPGET] = 1;
      if (!is_string($params) && count($params) > 0) {
        $encoded = http_build_query($params);
        $absUrl = "$absUrl?$encoded";
      } elseif (is_string($params) && strlen($params) > 0) {
        $absUrl = "$absUrl?$params";
      }
    } else if ($meth == 'post') {
      $opts[CURLOPT_POST] = 1;
      if (!is_string($params)) {
        $opts[CURLOPT_POSTFIELDS] = http_build_query($params);
      } else {
        $opts[CURLOPT_POSTFIELDS] = $params;
      }

    } else {
      throw new Postmaster_Error("Unrecognized method $meth");
    }

    $absUrl = self::utf8($absUrl);
    $opts[CURLOPT_URL] = $absUrl;
    $opts[CURLOPT_RETURNTRANSFER] = true;
    $opts[CURLOPT_CONNECTTIMEOUT] = 30;
    $opts[CURLOPT_TIMEOUT] = 80;
    $opts[CURLOPT_RETURNTRANSFER] = true;
    $opts[CURLOPT_HTTPHEADER] = $headers;

    if ($apiKey)
      $opts[CURLOPT_USERPWD] = $apiKey . ":";

    curl_setopt_array($curl, $opts);
    $rbody = curl_exec($curl);
    if ($rbody === false) {
      $errno = curl_errno($curl);
      $message = curl_error($curl);
      curl_close($curl);
      $this->handleCurlError($errno, $message);
    }

    $rcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    return array($rbody, $rcode);
  }

 public function handleCurlError($errno, $message)
  {
    $apiBase = Postmaster::$apiBase;
    switch ($errno) {
      case CURLE_COULDNT_CONNECT:
      case CURLE_COULDNT_RESOLVE_HOST:
      case CURLE_OPERATION_TIMEOUTED:
        $msg = "Could not connect to Postmaster ($apiBase). Please check your internet connection and try again. If this problem persists, let us know at support@postmaster.io.";
        break;
      case CURLE_SSL_CACERT:
      case CURLE_SSL_PEER_CERTIFICATE:
        $msg = "Could not verify Postmaster's SSL certificate. If this problem persists, let us know at support@postmaster.io.";
        break;
      default:
        $msg = "Unexpected error communicating with Postmaster. If this problem persists, let us know at support@postmaster.io.";
    }

    $msg .= "\n\n(Network error [errno $errno]: $message)";
    throw new Network_Error($msg);
  }

	public function instanceUrl($base, $action=null)
		{
			$id = $this['id'];
			if(is_float($id))
				$id = sprintf("%.0f", $id);
				$class = get_class($this);
					if (!$id) {
						throw new Postmaster_Error("Could not determine which URL to request: $class instance has invalid ID: $id", null);
					}
					$id = Postmaster_ApiRequestor::utf8($id);
					$extn = urlencode($id);
					if ($action) {
						return "$base/$extn/$action";
					}
				return "$base/$extn";
		}

	protected static function _validateParams($params=null)
	{
		if ($params && !is_array($params))
			throw new Postmaster_Error("You must pass an array as the first argument to Postmaster API method calls.");
	}
	public static function normalizeAddress(&$array)
	{
		if(is_array($array) && array_key_exists('address', $array)){
			  $address = $array['address'];
			  unset($array['address']);
			  if(count($address) > 0)
			    $array['line1'] = $address[0];
			  if(count($address) > 1)
			    $array['line2'] = $address[1];
			  if(count($address) > 2)
			    $array['line3'] = $address[2];
		}
	}
	public static function setApiKey($apiKey=null)
	  {
	    self::$apiKey = $apiKey;
	  }

	private static $urlBase = '/v1/validate';

	public static function validate($params=null)
	  {
	    $class = get_class();
	    self::_validateParams($params);
	    self::normalizeAddress($params);
	    $requestor = new Postmaster();
	    $response = $requestor->request('post', self::$urlBase, $params);
	    return $response;
	  }

		/*
		* Ask for the time to transport a shipment between two zip codes.
		*/ 
		public static function get($params=null)
		{
		$class = get_class();
		self::_validateParams($params);
		$requestor = new Postmaster();
		$response = $requestor->request('post', self::$urlBase, $params);

		
		return $response;
		}




		/*
		* Shipment
		*/

		public static function create($params=null)
  {
    $class = get_class();
    self::normalizeAddress($params['to']);
    self::normalizeAddress($params['from_']);
    self::_validateParams($params);
    $requestor = new Postmaster();
    $response = $requestor->request('post', '/v1/shipments', $params);
    return $response;
  }

  public static function all($params=null)
  {
    $class = get_class();
    Postmaster_ApiResource::_validateParams($params);
    $requestor = new Postmaster_ApiRequestor();
    $response = $requestor->request('get', self::$urlBase, $params);

    $results = array();
    foreach($response['results'] as $data)
      array_push($results, Postmaster_Object::scopedConstructObject($class, $data));

    return $results;
  }

  public function refresh()
  {
    $requestor = new Postmaster_ApiRequestor();
    $url = $this->instanceUrl(self::$urlBase);
    $response = $requestor->request('get', $url);
    $this->setValues($response);
    return $this;
  }

  /*
   * Retrieve a package by ID.
   */
  public static function retrieve($id)
  {
    $instance = new Postmaster_Shipment($id);
    $instance->refresh();
    return $instance;
  }

  /*
   * Void a shipment (from an object).
   */
  public function void()
  {
    $requestor = new Postmaster_ApiRequestor();
    $url = $this->instanceUrl(self::$urlBase, 'void');
    $response = $requestor->request('post', $url);
    $this->setValues(array()); //clear
    return $response['message'] == 'OK';
  }

  /*
   * Track a shipment (from an object).
   */
  public function track()
  {
    $requestor = new Postmaster_ApiRequestor();
    $url = $this->instanceUrl(self::$urlBase, 'track');
    $response = $requestor->request('get', $url);

    $class = 'Postmaster_Tracking';
    $results = array();
    foreach($response['results'] as $data)
      array_push($results, Postmaster_Object::scopedConstructObject($class, $data));

    return $results;
  }




}
?>