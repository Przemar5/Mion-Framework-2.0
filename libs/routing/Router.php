<?php

namespace Libs\Routing;

use Libs\Routing\AbstractRouter;
use Libs\JsonParser;
use Libs\Http\Request;

class Router extends AbstractRouter
{
	protected const PAGE_NOT_FOUND_ROUTE = 'page_not_found';
	protected const REQUEST_METHODS = ['GET', 'POST'];
	protected string $currentRoute;
	protected ?array $routes;


	public function loadRoutes(string $path): void
	{
		$this->routes = JsonParser::parseFile($path);
	}

	public function redirectTo(string $routeName, ?array $args = []): void
	{
		$pattern = $this->uriPatternByNameIfExists($routeName);

		if (!empty($pattern)) {
			$route = $this->complementRoutePattern($pattern, $args);
			$route = $this->getFullRoute($route);
			$this->chooseWayOfRedirectionAndRedirect($route);
		}
	}

	protected function complementRoutePattern(string $pattern, ?array $args = []): string
	{
		$replace = array_keys($args);
		$replace = array_map(fn($r) => '{'.$r.'}', $replace);
		$new = array_values($args);

		return str_replace($replace, $new, $pattern);
	}

	protected function getFullRoute(string $route): string
	{
		return self::baseUri().$route;
	}

	protected function chooseWayOfRedirectionAndRedirect($uri): void
	{
		if (headers_sent()) {
			$this->renderRedirectionLink($uri);
		}
		else {
			$this->sendRedirectionHeader($uri);
		}
	} 

	protected function sendRedirectionHeader(string $uri): void
	{
		header("Location: $uri");
	}

	protected function renderRedirectionLink(string $uri): void
	{
		echo "<script>window.location.href = $uri</script>";
		echo "<meta http-equiv=\"Refresh\" content=\"0; url='$uri'\">";
	}

	public function uriPatternByNameIfExists(string $name)
	{
		foreach ($this->routes as $route => $routeData) {
			foreach ($routeData as $method => $data) {
				if ($name === $routeData[$method]['name']) {
					return $route;
				}
			}
		}
	}

	protected function getNameForRouteAndMethod(string $route, string $method): string
	{
		return $this->routes[$route][$method]['name'];
	}

	protected function getRouteByName(string $name): string
	{
		foreach ($this->routes as $route => $methods) {
			foreach ($methods as $method => $routeData) {
				if ($routeData['name'] === $name) {
					return $route;
				}
			}
		}
	}

	protected function getRequestMethod(): string
	{
		return $_SERVER['REQUEST_METHOD'];
	}

	public function route()
	{
		$route = $this->getMatchingRoute();
		$requestMethod = $this->getRequestMethod();

		if (empty($route)) {
			$route = self::PAGE_NOT_FOUND_ROUTE;
		}
		$this->currentRoute = $route;

		$response = $this->routes[$route][$requestMethod]['response'];
		[$controller, $method] = explode('::', $response);
		$controller = new $controller();
		$args = $this->getCurrentRouteArgs();
		array_unshift($args, new Request());

		return call_user_func_array([$controller, $method], $args);
	}

	protected function getMatchingRoute()
	{
		foreach (array_keys($this->routes) as $route) {
			if ($this->currentUriMatchesRoute($route)) {
				return $route;
			}
		}
	}

	protected function currentUriMatchesRoute(string $route): bool
	{
		$currentUri = self::shortenedCurrentUri();
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

	protected function getCurrentRouteArgs(): array
	{
		$currentUri = self::shortenedCurrentUri();
		$uriTokens = explode('/', $currentUri);
		$routeTokens = explode('/', $this->currentRoute);
		$args = [];

		for ($i = 0; $i < count($routeTokens); $i++) {
			if (preg_match('/^(?:\{)(.*)(?:\})$/', $routeTokens[$i], $match)) {
				$args[$match[1]] = $uriTokens[$i];
			} 
		}

		return $args;
	}
}