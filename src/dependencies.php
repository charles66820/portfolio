<?php
use \Monolog\Logger;
use \Monolog\Handler\StreamHandler;
use \Monolog\Processor\UidProcessor;
use \Slim\Http\Environment;
use \Slim\Views\Twig;
use Slim\Views\TwigExtension;
use \Slim\Http\Uri;

// DIC configuration

$container = $app->getContainer();

/*// view renderer
$container['renderer'] = function ($c) {
  $settings = $c->get('settings')['renderer'];
  return new Slim\Views\PhpRenderer($settings['template_path']);
};*/

// twig view renderer
$container['view'] = function ($container) {
  $settings = $container->get('settings')['renderer'];
  $view = new Twig($settings['template_path'], [
    'cache' => __DIR__ . '/../tmp',
    'auto_reload' => (getenv('APP_ENV') == "dev")
  ]);

  // Instantiate and add Slim specific extension
  $router = $container->get('router');
  $uri = Uri::createFromEnvironment(new Environment($_SERVER));
  $view->addExtension(new TwigExtension($router, $uri));

  return $view;
};

// monolog
$container['logger'] = function ($c) {
  $settings = $c->get('settings')['logger'];
  $logger = new Logger($settings['name']);
  $logger->pushProcessor(new UidProcessor());
  $logger->pushHandler(new StreamHandler($settings['path'], $settings['level']));
  return $logger;
};
