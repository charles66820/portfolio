<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\StatusCode;

// Routes
$app->get('/', function (Request $req, Response $res) {

  return $this->view->render($res, 'about.html.twig');
});

$app->get('/about', function (Request $req, Response $res) {

  return $this->view->render($res, 'about.html.twig');
});

$app->get('/contact', function (Request $req, Response $res) {

  return $this->view->render($res, 'contact.html.twig');
});

$app->get('/projects', function (Request $req, Response $res) {

  return $this->view->render($res, 'projects.html.twig');
});

$app->get('/project/{id}', function (Request $req, Response $res, array $args) {

  return $this->view->render($res, 'project.html.twig', [
    'id' => $args['id']
  ]);
});

$app->get('/test', function (Request $req, Response $res) {

  return $this->view->render($res, 'test.html.twig', [
    'name' => "test"
  ]);
});


//Error handlers
$c = $app->getContainer();
$c['errorHandler'] = function ($c) {
  return function (Request $req, Response $res, $e) use ($c) {

    $res = $res->withStatus(StatusCode::HTTP_INTERNAL_SERVER_ERROR)->withHeader('Content-Type', 'text/html');

    return $c['view']->render($res, 'error.html.twig', [
    'code' => 500,
    'msg' => "une erreur c'est produite :( , veuillez réessayer us trader.",
    'details' => json_encode($e)
    ]);
  };
};

$c['notFoundHandler'] = function ($c) {
  return function (Request $req, Response $res) use ($c) {

    $res = $res->withStatus(StatusCode::HTTP_NOT_FOUND);

    return $c['view']->render($res, 'error.html.twig', [
    'code' => 404,
    'msg' => "Cette page n'existe pas :("
    ]);
  };
};
