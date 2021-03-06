<?php

namespace Slash;

use Slash\Event\Dispatcher\EventDispatcherInterface;
use Slash\Event\Events;
use Slash\Event\ResponseEvent;
use Slash\Http\Request;
use Slash\Http\Response;

class Router {

	private $routes;

	private $matchesRoutes;

	/** @var $currentRoute Route */
	private $currentRoute;

	public function __construct() {
		$this->routes = [];
	}

	public function lock(Controller $controller) {
		$this->routes[] = $controller->getRoute();

		return $this;
	}

	public function getCurrentRoute() {
		return $this->currentRoute;
	}

	public function resolve(Request $request) {
		$requestURI = $request->getUri();
		$method = $request->getMethod();

		$this->matchesRoutes = [];

		/** @var $route Route */
		foreach($this->routes as $route) {
			if($route->matches($requestURI, $method)) {
				$this->matchesRoutes[] = $route;
			}
		}

		if($this->matchesRoutes != null) {
			$this->currentRoute = $this->matchesRoutes[0];
		}

		if($this->currentRoute == null) {
            throw new \Exception("Page not found!", Response::NOT_FOUND);
		}

		return function() {
			return call_user_func_array($this->currentRoute->getAction(), $this->currentRoute->getRequirements());
		};
	}
} 