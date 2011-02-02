<?php
class Controller_Default 
{
	public function action_index()
	{
		$response = Witty::instance('Response');
		$response->body = 'hello default';
	}
}
