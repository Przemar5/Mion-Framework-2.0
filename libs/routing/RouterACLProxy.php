<?php

namespace Libs\Routing;

use Libs\Routing\AbstractRouter;
use Libs\JsonParser;

class RouterACLProxy extends AbstractRouter
{
	public AbstractRouter $router;
	public array $acl;


	public function __construct(AbstractRouter $router)
	{
		$this->router = $router;
	}

	public function loadAclFile(string $path): void
	{
		$this->acl = JsonParser::parseFile($path);
	}

	public function route()
	{
		if ($this->hasAccess()) {
			$this->router->route();
		}
		$this->router->route();
	}

	protected function hasAccess()
	{
		// echo $this->currentUri();
		// $route = $this->getAclMatchingRoute();
		// var_dump($this->acl);die;
	}

	protected function getAclMatchingRoute()
	{
		foreach ($this->routes as $route) {
			if ($this->currentUriMatchesRoute($route)) {
				return $route;
			}
		}
	}

	protected function currentUriMatchesRoute(string $route): bool
	{
		$currentUri = $this->currentUri();
		$uriTokens = explode('/', $currentUri);
		$routeTokens = explode('/', $route);

		if (count($uriTokens) !== count($routeTokens)) {
			return false;
		}

		for ($i = 0; $i < count($routeTokens); $i++) {
			if ($uriTokens[$i] !== $routeTokens[$i] && 
				!preg_match('/^(?:\{)(.*)(?:\})$/', $routeTokens[$i])) {
				
				return false;
			}
		}

		return true;
	}
}