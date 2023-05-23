<?php

include('Crypt/RSA.php');

// Creates the API Request from the context
class APIRequest {
	
	var $context;
	
	// Constructer context
	function __construct($context) {
		if ($context != null && get_class($context) != 'APIContext') {
			throw new Exception('Input must be an APIContext');
		}
		$this->context = $context;
	}
	
	// Does the HTTP Request
	function execute() {
		if ($this->context == null) {
			throw new Exception('Context cannot be null');
		} 
		$this->create_default_headers();
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		
		switch ($this->context->get_method_type()) {
			case APIMethodType::GET:
				return $this->__get($ch);
			case APIMethodType::POST:
				return $this->__post($ch);
			case APIMethodType::PUT:
				return $this->__put($ch);
			default:
				return null;
		}
	}
	
	// Creates the Authorisation bearer token using the RSA public key provided
	function create_bearer_token() {
		// Need to do these lines to create a 'valid' formatted RSA key for the openssl library
		$rsa = new Crypt_RSA();
		$rsa->loadKey($this->context->get_public_key());
		$rsa->setPublicKey($this->context->get_public_key());
		
		$publickey = $rsa->getPublicKey();
		$api_encrypted = '';
		$encrypted = '';
		
		if (openssl_public_encrypt($this->context->get_api_key(), $encrypted, $publickey)) {
			$api_encrypted = base64_encode($encrypted);
		}
		return $api_encrypted;
	}
	
	// Add the default headers
	function create_default_headers() {
		$this->context->add_header('Authorization', 'Bearer ' . $this->create_bearer_token());
		$this->context->add_header('Content-Type','application/json');
		$this->context->add_header('Host', $this->context->get_address());
	}
	
	// Do a GET request
	function __get($ch) {
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($ch, CURLOPT_URL, $this->context->get_url().'?'.http_build_query($this->context->get_parameters()));
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->context->get_headers());
		$response = curl_exec($ch);
		
		echo $response;
		echo '<br>';
		$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$headers = substr($response, 0, $header_size);
		$body = substr($response, $header_size);
		curl_close($ch);
		return new APIResponse($status_code, $headers, $body);
	}
	
	// Do a POST request
	function __post($ch) {
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_URL, $this->context->get_url());
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->context->get_headers());
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->context->get_parameters()));
		$response = curl_exec($ch);
		echo $response;
		echo '<br>';
		$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$headers = substr($response, 0, $header_size);
		$body = substr($response, $header_size);
		curl_close($ch);
		return new APIResponse($status_code, $headers, $body);
	}
	
	// Do a PUT request
	function __put($ch) {
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($ch, CURLOPT_URL, $this->context->get_url());
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->context->get_headers());
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->context->get_parameters()));
		$response = curl_exec($ch);
		echo $response;
		echo '<br>';
		$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$headers = substr($response, 0, $header_size);
		$body = substr($response, $header_size);
		curl_close($ch);
		return new APIResponse($status_code, $headers, $body);
		
	}
	
	function __unknown() {
		throw new Exception('Unknown method');
	}
}

// API Response 
class APIResponse {
	
	var $status_code;
	var $headers;
	var $body;
	
	// Constructer
	function __construct($status_code, $headers, $body) {
		$this->set_status_code($status_code);
		$this->set_headers($headers);
		$this->set_body($body);
	}
	

	function get_status_code() {
		return $this->status_code;
	}
	
	function set_status_code($status_code) {
		if (gettype($status_code) != 'integer') {
			throw new Exception('status_code must be a integer');
		} else {
			$this->status_code = $status_code;
		}
	}
	
	function get_headers() {
		return $this->headers;
	}
	
	function set_headers($headers) {
		if (gettype($headers) != 'string') {
			throw new Exception('headers must be a string');
		} else {
			$this->headers = $headers;
		}
	}
	
	function get_body() {
		return $this->body;
	}
	
	function set_body($body) {
		if (gettype($body) != 'string') {
			throw new Exception('body must be a string');
		} else {
			$this->body = $body;
		}
	}
}

// Api Method Type Constants
class APIMethodType {

	const GET = 0;
	const POST = 1;
	const PUT = 2;
	const DELETE = 4;
}

// API Context that contain info for the API endpoint
class APIContext {
	
	var $api_key = '';
	var $public_key = '';
	var $ssl = false;
	var $method_type = APIMethodType::GET;
	var $address = '';
	var $port = 80;
	var $path = '';
	var $headers = array();
	var $parameters = array();
	
	// Constructor with optional prepopulated variables
	function __construct($dictionary=null)
	{
		if ($dictionary != null && gettype($dictionary) != 'array') {
			throw new Exception('Input must be an array');
		}
		
		if ($dictionary != null) {
			foreach($dictionary as $i => $item) {
				switch (strtolower($i)) {
					case 'api_key':
						$this->set_api_key($dictionary[$i]);
						break;
					case 'public_key':
						$this->set_public_key($dictionary[$i]);
						break;
					case 'ssl':
						$this->set_ssl($dictionary[$i]);
						break;
					case 'method_type':
						$this->set_method_type($dictionary[$i]);
						break;
					case 'address':
						$this->set_address($dictionary[$i]);
						break;
					case 'port':
						$this->set_port($dictionary[$i]);
						break;
					case 'path':
						$this->set_path($dictionary[$i]);
						break;
					case 'headers':
						if (gettype($dictionary[$i]) != 'array') {
							throw new Exception('headers must be an array');
						}
						foreach($dictionary[$i] as $key => $value) {
							$this->add_header($key, $dictionary[$i][$key]);
						}
						break;
					case 'parameters':
					if (gettype($dictionary[$i]) != 'array') {
							throw new Exception('parameters must be an array');
						}
						foreach($dictionary[$i] as $key => $value) {
							$this->add_parameter($key, $dictionary[$i][$key]);
						}
						break;
					default:
						echo 'Unknown parameter type';
				}
			}
		}
	}
	
	// Build the URL from context parameters
	function get_url() {
		if ($this->ssl == true) {
			return 'https://' . $this->address . ':' . $this->port . $this->path;
		} else {
			return 'http://' . $this->address . ':' . $this->port . $this->path;
		}
	}
	
	// Add/update headers
	function add_header($header, $value) {
		$this->headers[$header] = $value;
	}
	
	// Get headers as an array
	function get_headers() {
		$headers = array();
		foreach($this->headers as $key => $value) {
			array_push($headers, $key . ": " . $value);
		}
		
		return $headers;
	}
	
	// Add parameter
	function add_parameter($key, $value) {
		$this->parameters[$key] = $value;
	}
	
	// Get parameters
	function get_parameters() {
		return $this->parameters;
	}
	
	function get_api_key() {
		return $this->api_key;
	}
	
	function set_api_key($api_key) {
		if (gettype($api_key) != 'string') {
			throw new Exception('api_key must be a string');
		} else {
			$this->api_key = $api_key;
		}
	}
	
	function get_public_key() {
		return $this->public_key;
	}
	
	function set_public_key($public_key) {
		if (gettype($public_key) != 'string') {
			throw new Exception('public_key must be a string');
		} else {
			$this->public_key = $public_key;
		}
	}
	
	function get_ssl() {
		return $this->ssl;
	}
	
	function set_ssl($ssl) {
		if (gettype($ssl) != 'boolean') {
			throw new Exception('ssl must be a boolean');
		} else {
			$this->ssl = $ssl;
		}
	}
	
	function get_method_type() {
		return $this->method_type;
	}
	
	function set_method_type($method_type) {
		if (gettype($method_type) != 'integer') {
			throw new Exception('method_type must be a integer');
		} else {
			$this->method_type = $method_type;
		}
	}
	
	function get_address() {
		return $this->address;
	}
	
	function set_address($address) {
		if (gettype($address) != 'string') {
			throw new Exception('address must be a string');
		} else {
			$this->address = $address;
		}
	}
	
	function get_port() {
		return $this->port;
	}
	
	function set_port($port) {
		if ($port != null && gettype($port) != 'integer') {
			throw new Exception('port must be a integer');
		} else {
			$this->port = $port;
		}
	}
	
	function get_path() {
		return $this->path;
	}
	
	function set_path($path) {
		if (gettype($path) != 'string') {
			throw new Exception('path must be a string');
		} else {
			$this->path = $path;
		}
	}
			
}

?>
