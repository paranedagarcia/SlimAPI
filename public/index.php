<?php
/* actualizado 9-9-2022

*/
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/db.php';

$app = AppFactory::create();
$app->setBasePath('/api');

$app->get('/', function (Request $request, Response $response, array $args) {
    $response->getBody()->write("Hello API funca ");
    return $response;
});


$app->get('/entradas', 'getPosts');

$app->group('/v1', function ($app) {
  $app->get('/salud', 'getSalud');
	$app->get('/salud/{id}', 'getPost');
	$app->get('/nutricion', 'getNutricion');
	$app->get('/nutricion/{id}', 'getPost');
});

  function getSalud(Request $request, Response $response)
  {
    $sql = "SELECT ID, post_date, post_title 
FROM `vrl8kHT_term_relationships` 
LEFT JOIN vrl8kHT_posts ON ID = object_id
WHERE term_taxonomy_id = 6 and post_type = 'post' AND post_status='publish'";
    try {
      $db = new DB();
      $conn = $db->connect();

      $stmt = $conn->query($sql);
      $resultados = $stmt->fetchAll(PDO::FETCH_OBJ);

      $response->getBody()->write(json_encode($resultados));
      $db = null;
      return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('content-type', 'application/json')
        ->withStatus(200); 
    } catch (PDOException $e) {
      $datos = array('status' => 'error', 'data' => $e->getMessage());
      $response->getBody()->write(json_encode($datos));

      return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('content-type', 'application/json')
        ->withStatus(500);
    }
  }

function getNutricion(Request $request, Response $response)
  {
    $sql = "SELECT ID, post_date, post_title 
FROM `vrl8kHT_term_relationships` 
LEFT JOIN vrl8kHT_posts ON ID = object_id
WHERE term_taxonomy_id = 7 and post_type = 'post' AND post_status='publish'";
    try {
      $db = new DB();
      $conn = $db->connect();
      $stmt = $conn->query($sql);
      $resultados = $stmt->fetchAll(PDO::FETCH_OBJ);
      $db = null;
      $response->getBody()->write(json_encode($resultados));
      return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('content-type', 'application/json')
        ->withStatus(200);
    } catch (PDOException $e) {
      $datos = array('status' => 'error', 'data' => $e->getMessage());
      $response->getBody()->write(json_encode($datos));
      return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(404);
    }
  }

  function getPost(Request $request, Response $response)
{
  try {
    $db = new DB();
    $id = $request->getAttribute('id');
    $conn = $db->connect();
    $sql = "SELECT ID, post_date AS date, post_title AS titulo, post_content AS contenido
    FROM vrl8kHT_posts
    WHERE ID=$id";
    $stmt = $conn->query($sql);
    $db = null;
    if ($stmt->rowCount() != 0) {
      $resultados = $stmt->fetch(PDO::FETCH_ASSOC);
      $response->getBody()->write(json_encode($resultados));
      return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('content-type', 'application/json')
        ->withStatus(200);
    } else {
      $datos = array('status' => 'error', 'data' => "No se ha encontrado el usuario con ID: $id.");
      $response->getBody()->write(json_encode($datos));
      return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(404);
    }
  } catch (PDOException $e) {
    $datos = array('status' => 'error', 'data' => $e->getMessage());
    $response->getBody()->write(json_encode($datos));
    return $response
      ->withHeader('content-type', 'application/json')
      ->withStatus(404);
  }
};

  $app->run();

?>