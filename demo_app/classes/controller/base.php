<?php
class Controller_Base
{
	public $response;
	public $request;

	public function __construct()
	{
		$this->response = Witty::instance('Response');
		$this->request = Witty::instance('Request');
	}
}
