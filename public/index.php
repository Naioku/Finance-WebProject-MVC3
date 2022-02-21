<?php

/**
 * Front controller
 *
 * PHP version 7.4.12
 */
 
/*
 * Packages and autoloader
 */
require_once dirname(__DIR__) . '/vendor/autoload.php';

/*
 * Error and exception handling
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

/**
 * Sessions
 */
session_start();

/**
 * Routing
 */
$router = new Core\Router();

//Add the routes
$router->add("", ["controller" => "Home", "action" => "index"]);
$router->add('{controller}/{action}'); // <<=============================== on NETMARK add /; on XSAMPP remove / from the begening

$router->add('signup', ["controller" => "Signup", "action" => "new"]);
$router->add('login', ["controller" => "Login", "action" => "new"]);
$router->add('logout', ["controller" => "Logout", "action" => "destroy"]);
$router->add('main-menu', ["controller" => "MainMenu", "action" => "show"]);
$router->add('add-income', ["controller" => "AddIncome", "action" => "new"]);
$router->add('add-expense', ["controller" => "AddExpense", "action" => "new"]);
$router->add('view-balance', ["controller" => "ViewBalance", "action" => "show"]);
$router->add('settings', ["controller" => "Settings", "action" => "show"]);

$router->add('{controller}/{action}/{token:[\da-f]+}'); // <<=============================== on NETMARK add /; on XSAMPP remove / from the begening

$router->dispatch($_SERVER['QUERY_STRING']);

?>