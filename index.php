<?php

use Libs\Routing\Router;
use Libs\Routing\RouterACLProxy;
use Libs\Storage\Session;

spl_autoload_register(function($classWithNamespace) {
	$tokens = explode('\\', $classWithNamespace);
	$class = array_pop($tokens);
	$tokens = array_map('strtolower', $tokens);
	$path = implode(DIRECTORY_SEPARATOR, $tokens).DIRECTORY_SEPARATOR.$class.'.php';

	if (is_readable($path)) {
		require_once $path;
	}
});


Session::start();

$router = new Router();
$router->loadRoutes('./config/routes.json');

$aclProxy = new RouterACLProxy($router);
$aclProxy->loadAclFile('./config/acl.json');
$aclProxy->route();