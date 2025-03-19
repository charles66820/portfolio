<?php
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);

$domain = "{$_SERVER['SERVER_NAME']}";

$langs = [ 'fr', 'en' ];
$local = [
  "en" => json_decode(file_get_contents(__DIR__ . '/data_en.json')),
  "fr" => json_decode(file_get_contents(__DIR__ . '/data_fr.json'))
];

// Language selection
$app->add(function ($req, $res, $next) use ($domain, $local, $langs) {

  // If lang cookie is set
  if (isset($_COOKIE["lang"])) $lang = $_COOKIE["lang"];
  else {
    // default if lang cookie not set
    $lang = $req->getHeader('accept-language')[0];
    $lang = explode(',', $lang)[0];
    $lang = explode('-', $lang)[0];
  }

  // If bad lang set to en
  if (!in_array($lang, $langs)) $lang = 'en';

  $req = $req->withAttribute('accept-language', $lang);
  $req->local["data"] = $local[$lang];

  $req->local["domain"] = $domain;

  return $next($req, $res);
});
