<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\StatusCode;

// Routes
$app->get('/', function (Request $req, Response $res) {
  return $res->withRedirect('/about', 307);
});

$app->get('/about', function (Request $req, Response $res) {

  return $this->view->render($res, 'about.html.twig', [
    "title" => "Me concernant"
  ]);
});

$app->get('/E6', function (Request $req, Response $res) {

  return $this->view->render($res, 'E6.html.twig', [
    "title" => "Épreuve E6"
  ]);
});

$app->get('/projects', function (Request $req, Response $res) {

  return $this->view->render($res, 'projects.html.twig', [
    "title" => "Mes projets"
  ]);
});

$app->get('/contact', function (Request $req, Response $res) {

  return $this->view->render($res, 'contact.html.twig', [
    "title" => "Contact"
  ]);
});

$app->get('/project/{id}', function (Request $req, Response $res, array $args) {

  return $this->view->render($res, 'project.html.twig', [
    'id' => $args['id'],
    "title" => $args['id']
  ]);
});

$app->get('/veilletechnologique', function (Request $req, Response $res) {

  return $this->view->render($res, 'veilletechnologique.html.twig', [
    'title' => "Veille technologique"
  ]);
});

//Error handlers
if (getenv('APP_ENV') != 'dev') {
  $c = $app->getContainer();
  $c['errorHandler'] = function ($c) {
    return function (Request $req, Response $res, $e) use ($c) {

      $res = $res->withStatus(StatusCode::HTTP_INTERNAL_SERVER_ERROR)->withHeader('Content-Type', 'text/html');

      return $c['view']->render($res, 'error.html.twig', [
        'title' => "Erreur 500",
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
        'title' => "Erreur 404",
        'code' => 404,
        'msg' => "Cette page n'existe pas :("
      ]);
    };
  };
}
