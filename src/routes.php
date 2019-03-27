<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes
$app->get('/', function (Request $req, Response $res, array $args) {

  return $this->view->render($res, 'template.html.twig');
});


$app->get('/{name}', function (Request $req, Response $res, array $args) {

  return $this->view->render($res, 'test.html.twig', [
    'name' => $args['name']
  ]);
});
