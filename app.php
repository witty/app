<?php
/**
 * App Class
 *
 * @dependency profiler,request,route,response
 * @author lzyy http://blog.leezhong.com
 * @homepage https://github.com/witty/app
 * @version 0.1.1
 */
class App extends Witty_Base
{
	protected $_config = array(
		'app_path' => 'app',
	);

	protected function _after_config()
	{
		$this->_config['app_path'] = rtrim($this->_config['app_path'], '/').'/';
	}

	public function start()
	{
		static $started = FALSE;

		if ($started)
			return;

		spl_autoload_register(array($this, 'autoload'));

		Witty::factory('Request')->execute();

		$started = TRUE;

		return $this;
	}

	public function render()
	{
		echo Witty::instance('Response');
	}

	public function autoload($classname)
	{
		$classpath = $this->_config['app_path'].'classes/';
		$classfile = $classpath.str_replace('_', '/', strtolower($classname)).'.php';
		if (file_exists($classfile))
		{
			require $classfile;
			return TRUE;
		}
		return FALSE;
	}
}
