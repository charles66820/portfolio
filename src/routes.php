<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes
$app->get('/{name}', function (Request $request, Response $response, array $args) {

  return $this->view->render($response, 'index.html.twig', [
          'name' => $args['name']
      ]);
});
