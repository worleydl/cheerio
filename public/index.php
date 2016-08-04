<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

spl_autoload_register(function ($classname) {
    require ("../classes/" . $classname . ".php");
});

$config['displayErrorDetails'] = true;
$config['db']['host']   = "localhost";
$config['db']['user']   = "root";
$config['db']['pass']   = "";
$config['db']['dbname'] = "cheerios";


$app = new \Slim\App(['settings' => $config]);
$container = $app->getContainer();

// Setup view templates
$container['view'] = new \Slim\Views\PhpRenderer("../templates/");

// Setup database
$container['db'] = function($c) {
    $db = $c['settings']['db'];
    $pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'],
        $db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};


$app->get('/', function(Request $request, Response $response) {
  $mapper = new CheerioMapper($this->db);
  $cheerios = $mapper->getCheerios();

  $response = $this->view->render($response, 'cheerios.phtml', ['cheerios' => $cheerios, 'router' => $this->router]);

  return $response;
});

$app->post('/newCheerio', function(Request $request, Response $response) {
  $data = $request->getParsedBody();
  $cheerio_data = [];

  $cheerio_data['message'] = filter_var($data['message'], FILTER_SANITIZE_STRING);

  $cheerio = new CheerioEntity($cheerio_data);
  $mapper = new CheerioMapper($this->db);
  $mapper->save($cheerio);

  $response = $response->withRedirect('/');
  return $response;
});


$app->run();
