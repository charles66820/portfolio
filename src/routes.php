<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\StatusCode;

// Routes
$app->get("/", function (Request $req, Response $res) {
  return $this->view->render($res, "home.html.twig", [
    "title" => $req->local["data"]->titles->home,
    "data" => $req->local["data"]
  ]);
});

$app->get("/about", function (Request $req, Response $res) {
  return $this->view->render($res, "about.html.twig", [
    "title" => $req->local["data"]->titles->about,
    "data" => $req->local["data"]
  ]);
});

$app->get("/projects", function (Request $req, Response $res) {
  return $this->view->render($res, "projects.html.twig", [
    "title" => $req->local["data"]->titles->projects,
    "data" => $req->local["data"]
  ]);
});

$app->get("/contact", function (Request $req, Response $res) {
  return $this->view->render($res, "contact.html.twig", [
    "title" => $req->local["data"]->titles->contact,
    "data" => $req->local["data"]
  ]);
});

$app->post("/contact/email", function (Request $req, Response $res) {
  if ($req->isXhr()) {
    // Do something
    return;
  }

  $status = true;

  // Setup mailer
  $transport = (new Swift_SmtpTransport(getenv("EMAIL_HOST"), getenv("EMAIL_PORT"), "tls"))
    ->setUsername(getenv("EMAIL_USERNAME"))
    ->setPassword(getenv("EMAIL_PASSWORD"));
  $mailer = new Swift_Mailer($transport);

  // Test if mailer work
  if (!$mailer) $status = false;

  // Validation
  if ((!isset($_POST["email"]) || !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) ||
  (!isset($_POST["name"]) || $_POST["name"] === "") ||
  (!isset($_POST["message"]) || $_POST["message"] === "") ||
  (!isset($_POST["subject"]) || $_POST["subject"] === "")) $status = false;

  // Send email
  if ($status) {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $subject = $_POST["subject"];
    $message = $_POST["message"];

    $message = (new Swift_Message("⁡"))
      ->setFrom([$email => $name])
      ->setSubject($subject)
      ->setTo(["charles@magicorp.fr" => "charles form cgoedefroit.com"])
      ->setBody($message);

    // Send the message
    $status = ($mailer->send($message) == 1);
  }

  return $this->view->render($res, "contact.html.twig", [
    "title" => $req->local["data"]->titles->contact,
    "data" => $req->local["data"],
    "status" => $status? "yes" : "no" // tmp
  ]);
});

$app->get("/project/{name}", function (Request $req, Response $res, array $args) {
  $index = array_search($args["name"], array_column($req->local["data"]->projects, "name"));
  if ($index === false)
    return $this->view->render($res, "error.html.twig", [
      "title" => $req->local["data"]->errors->error . "404",
      "code" => 404,
      "msg" => $req->local["data"]->errors->projectNotFound,
      "name" => $args["name"],
      "data" => $req->local["data"]
    ]);

  $project = $req->local["data"]->projects[$index];
  return $this->view->render($res, "project.html.twig", [
    "title" => sprintf($req->local["data"]->titles->project, $project->title),
    "projet" => $project,
    "data" => $req->local["data"]
  ]);
});

$app->get("/veilletechnologique", function (Request $req, Response $res) {
  return $this->view->render($res, "veilletechnologique.html.twig", [
    "title" => "Veille technologique"
  ]);
});

//Error handlers
if (getenv("APP_ENV") != "dev") {
  $c = $app->getContainer();
  $c["errorHandler"] = function ($c) {
    return function (Request $req, Response $res, $e) use ($c) {

      $res = $res->withStatus(StatusCode::HTTP_INTERNAL_SERVER_ERROR)->withHeader("Content-Type", "text/html");

      return $c["view"]->render($res, "error.html.twig", [
        "title" => $req->local["data"]->errors->error . "500",
        "code" => 500,
        "msg" => $req->local["data"]->errors->serverError,
        "details" => json_encode($e)
      ]);
    };
  };

  $c["notFoundHandler"] = function ($c) {
    return function (Request $req, Response $res) use ($c) {

      $res = $res->withStatus(StatusCode::HTTP_NOT_FOUND);

      return $c["view"]->render($res, "error.html.twig", [
        "title" =>  $req->local["data"]->errors->error . "404",
        "code" => 404,
        "msg" => $req->local["data"]->errors->pageNotFound
      ]);
    };
  };
}
