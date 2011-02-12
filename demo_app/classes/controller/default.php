<?php
class Controller_Default extends Controller_Base
{
	public function action_index()
	{
		$this->response->body = 'hello default';
	}
}
