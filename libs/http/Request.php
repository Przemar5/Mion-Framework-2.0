<?php

namespace Libs\Http;

use Libs\Routing\Router;

class Request
{
	public $data;
	private $address;
	private $errorCode;
	private $statusCode;
	private $userAgent;
	private $headers;
	private $httpMethod;
	

	public function __construct()
	{
		$this->headers = getallheaders();
		$this->statusCode = http_response_code();
		$this->httpMethod = $_SERVER['REQUEST_METHOD'];
		$this->data = $_REQUEST;
	}
}