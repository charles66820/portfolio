<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\StatusCode;

// Routes
$app->get('/', function (Request $req, Response $res) {
  return $this->view->render($res, 'home.html.twig', [
    "title" => $req->local["data"]->titles->home,
    "data" => $req->local["data"]
  ]);
});

$app->get('/about', function (Request $req, Response $res) {
  return $this->view->render($res, 'about.html.twig', [
    "title" => $req->local["data"]->titles->about,
    "data" => $req->local["data"]
  ]);
});

$app->get('/projects', function (Request $req, Response $res) {
  return $this->view->render($res, 'projects.html.twig', [
    "title" => $req->local["data"]->titles->projects,
    "data" => $req->local["data"]
  ]);
});

$app->get('/contact', function (Request $req, Response $res) {
  return $this->view->render($res, 'contact.html.twig', [
    "title" => $req->local["data"]->titles->contact,
    "data" => $req->local["data"]
  ]);
});

$app->get('/project/{name}', function (Request $req, Response $res, array $args) {
  $index = array_search($args["name"], array_column($req->local["data"]->projects, "name"));
  if ($index === false)
    return $this->view->render($res, 'error.html.twig', [
      'title' => $req->local["data"]->errors->error . "404",
      'code' => 404,
      'msg' => $req->local["data"]->errors->projectNotFound,
      'name' => $args['name'],
      "data" => $req->local["data"]
    ]);

  $project = $req->local["data"]->projects[$index];
  return $this->view->render($res, 'project.html.twig', [
    "title" => sprintf($req->local["data"]->titles->project, $project->title),
    "projet" => $project,
    "data" => $req->local["data"]
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
        'title' => $req->local["data"]->errors->error . "500",
        'code' => 500,
        'msg' => $req->local["data"]->errors->serverError,
        'details' => json_encode($e)
      ]);
    };
  };

  $c['notFoundHandler'] = function ($c) {
    return function (Request $req, Response $res) use ($c) {

      $res = $res->withStatus(StatusCode::HTTP_NOT_FOUND);

      return $c['view']->render($res, 'error.html.twig', [
        'title' =>  $req->local["data"]->errors->error . "404",
        'code' => 404,
        'msg' => $req->local["data"]->errors->pageNotFound
      ]);
    };
  };
}
