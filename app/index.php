<?php
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';

require_once './db/AccesoDatos.php';
// require_once './middlewares/Logger.php';
 require_once './middlewares/CheckTokenMW.php';

require_once './controllers/UsuarioController.php';
require_once './controllers/SectorController.php';
require_once './controllers/ProductoController.php';
require_once './controllers/PedidoXProductoController.php';
require_once './controllers/AutenticadorController.php';

// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();

// set base path
$app->setBasePath("/clasesPHP/LaComanda/app");
// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();

// Routes
$app->group('/usuarios', function (RouteCollectorProxy $group) {
  $group->post('/altaUsuario',   \UsuarioController::class . ':CargarUno');// listo
  $group->get('[/]',             \UsuarioController::class . ':TraerTodos'); //listo
  $group->post('/altaSector',   \SectorController::class . ':CargarUno');// listo

  $group->post('/altaProducto', \ProductoController::class . ':CargarUno'); // listo 
  $group->post('/altaPedidoXProducto',   \PedidoXProductoController::class . ':CargarUno'); // listo
  
  $group->get('/usuario',        \UsuarioController::class . ':TraerUno');
  $group->put('/usuario',        \UsuarioController::class . ':ModificarUno');
  $group->delete('/usuario',     \UsuarioController::class . ':BorrarUno');
});//->add(new CheckTokenMW());
//crear token

$app->get('/token', \AutentificadorController::class . ':CrearTokenLogin');
$app->get('/productos', \ProductoController::class . ':TraerTodos');//listo
$app->get('/pedidoxproductos', \PedidoXProductoController::class . ':TraerTodos');//listo
$app->get('/producto/sector', \ProductoController::class . ':TraerUnoPorSector');//listo 
$app->get('/criptos/cripto', \ProductoController::class . ':TraerUnoPorId');


$app->get('[/]', function (Request $request, Response $response) {
  $payload = json_encode(array("mensaje" => "Slim Framework 4 PHP andrea COMANDA"));

  $response->getBody()->write($payload);
  return $response->withHeader('Content-Type', 'application/json');
});

$app->run();
