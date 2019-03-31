<?php
use Symfony\Component\Dotenv\Dotenv;
// DIC configuration

$container = $app->getContainer();

/*// view renderer
$container['renderer'] = function ($c) {
  $settings = $c->get('settings')['renderer'];
  return new Slim\Views\PhpRenderer($settings['template_path']);
};*/

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/../.env');

// twig view renderer
$container['view'] = function ($container) {
  $settings = $container->get('settings')['renderer'];
  $view = new \Slim\Views\Twig($settings['template_path'], [
    'cache' => __DIR__ . '/../tmp',
    'auto_reload' => (getenv('APP_ENV') == "dev")
  ]);

  // Instantiate and add Slim specific extension
  $router = $container->get('router');
  $uri = \Slim\Http\Uri::createFromEnvironment(new \Slim\Http\Environment($_SERVER));
  $view->addExtension(new Slim\Views\TwigExtension($router, $uri));

  return $view;
};

// monolog
$container['logger'] = function ($c) {
  $settings = $c->get('settings')['logger'];
  $logger = new Monolog\Logger($settings['name']);
  $logger->pushProcessor(new Monolog\Processor\UidProcessor());
  $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
  return $logger;
};
