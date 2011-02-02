<?php
/**
 * App Class
 *
 * @dependency profiler,request,route,response
 * @author lzyy http://blog.leezhong.com
 * @homepage https://github.com/witty/app
 * @version 0.1.0
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

		$prefix = 'Controller_';

		$route = Witty::instance('Route');
		$cntl_actn = $route->parse();
		$request = Witty::instance('Request');

		if (Profiler::$enabled)
		{
			$benchmark = Profiler::start('Requests', $request->uri);
		}
		
		$class = new ReflectionClass($prefix.$cntl_actn['controller']);

		if ($class->isAbstract())
		{
			throw new Noah_Exception('can\'t init abstract controller: {controller}', array('{controller}' => $prefix.$cntl_actn['controller']));
		}

		$controller = $class->newInstance();
		if ($class->hasMethod('before'))
			$class->getMethod('before')->invoke($controller);

		if ($class->hasMethod('action_'.$cntl_actn['action']))
			$class->getMethod('action_'.$cntl_actn['action'])->invoke($controller);

		if ($class->hasMethod('after'))
			$class->getMethod('after')->invoke($controller);
		
		if (isset($benchmark))
		{
			Profiler::stop($benchmark);
		}

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
