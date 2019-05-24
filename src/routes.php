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

$app->get('/project/OTako', function (Request $req, Response $res, array $args) {

  return $this->view->render($res, 'project.html.twig', [
    'id' => $args['id'],
    "title" => "Projet Ô' Tako",
    "projet" => [
      "name" => "Ô' Tako",
      "description" => "Ô' Tako est un site de vente de goodis.
      Sur celui-ci il est possible de consulter les produits, de créer un compte, d’ajouter des produits au panier et de commander.",
      "imgs" => [
        [
          "src" => "/img/projects/OTako/ppe3ScreenFull.png"
        ],
        [
          "src" => "/img/projects/OTako/screen1.png"
        ]
      ],
      "docU" => [
        "url" => "/dl/DocumentationUtilisateurOTako.pdf",
        "name" => "Documentation utilisateur Ô' Tako"
      ],
      "docT" => [
        "url" => "/dl/DocumentationTechniqueOTako.pdf",
        "name" => "Documentation technique Ô' Tako"
      ],
      "code" => [
        "url" => "/dl/OTactileSourceCode.zip",
        "name" => "Ô' Tako code source"
      ],
      "build" => [
        "lbl" => "Accéder au site",
        "url" => "https://ppe3.magicorp.fr/",
        "name" => "Ô' Tako"
      ],
      "dossierDrive" => [
        "url" => "https://drive.google.com/drive/folders/1s12xr3EbPmE5juf-7NMtzh2-qtiMuGwe?usp=sharing",
        "name" => "Dossier situation 1"
      ]
    ]
  ]);
});

$app->get('/project/OTactile', function (Request $req, Response $res, array $args) {

  return $this->view->render($res, 'project.html.twig', [
    'id' => $args['id'],
    "title" => "Projet Ô' Tactile",
    "projet" => [
      "name" => "Ô' Tactile",
      "description" => "Ô' Tactile est une application de vente de goodis.
      Elle permet de consulter les produits, consulter son profile et de gérer le stock d’un produit.",
      "imgs" => [
        [
          "src" => "/img/projects/OTactile/screen1.png"
        ],
        [
          "src" => "/img/projects/OTactile/screen2.png"
        ]
      ],
      "docU" => [
        "url" => "/dl/DocumentationUtilisateurOTactile.pdf",
        "name" => "Documentation utilisateur Ô' Tactile"
      ],
      "docT" => [
        "url" => "/dl/DocumentationTechniqueOTactile.pdf",
        "name" => "Documentation technique Ô' Tactile"
      ],
      "code" => [
        "url" => "/dl/OTactileSourceCode.zip",
        "name" => "Ô' Tactile code source"
      ],
      "build" => [
        "lbl" => "Lien de téléchargement de l'apk",
        "url" => "/dl/OTactile.apk",
        "name" => "apk Ô' Tactile"
      ],
      "dossierDrive" => [
        "url" => "https://drive.google.com/drive/folders/181gcj8rbZrXH3fJbC5N8eyK_P3IN8pZ5?usp=sharing",
        "name" => "Dossier situation 2"
      ]
    ]
  ]);
});

$app->get('/project/{id}', function (Request $req, Response $res, array $args) {

  return $this->view->render($res, 'error.html.twig', [
    'title' => "Erreur 404",
    'code' => 404,
    'msg' => "le projet ".$args['id']." n'existe pas ou n'a pas encore été docummanté."
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
