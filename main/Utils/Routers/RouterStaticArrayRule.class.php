<?php
	/**
	 * @author Andrey Ryaguzov <a.ryaguzov@co.wapstart.ru>
	 * @copyright Copyright (c) 2013, Wapstart
	 */
	final class RouterStaticArrayRule extends RouterBaseRule
	{
		protected $routes;

		public static function create(array $routes = array())
		{
			return new self($routes);
		}

		public function __construct(array $routes)
		{
			$this->routes = $routes;
		}

		public function match(HttpRequest $request)
		{
			$result = false;
			$path = rtrim($this->processPath($request)->toString(), '/');

			if (array_key_exists($path, $this->routes)) {
				$result = $this->routes[$path];
			}

			return array('controller' => $result);
		}

		public function assembly(
			array $data = array(),
			$reset = false,
			$encode = false
		)
		{
			return null;
		}
	}
?>