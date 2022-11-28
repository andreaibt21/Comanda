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


date_default_timezone_set('America/Buenos_Aires');

require __DIR__ . '/../vendor/autoload.php';

require_once './db/AccesoDatos.php';
// require_once './middlewares/Logger.php';
 require_once './middlewares/CheckTokenMW.php';
 require_once './middlewares/UsuarioMW.php';
 include_once './controllers/TipoUsuarioController.php';
require_once './controllers/UsuarioController.php';
require_once './controllers/SectorController.php';
require_once './controllers/ProductoController.php';
require_once './controllers/PedidoProductoController.php';
require_once './controllers/AutenticadorController.php';
include_once './controllers/PedidoController.php';
include_once './controllers/ClienteController.php';
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

$app->get('/token', \AutentificadorController::class . ':CrearTokenLogin');

// Routes
$app->group('/usuarios', function (RouteCollectorProxy $group)
{
  $group->post('/altaUsuario',   \UsuarioController::class . ':CargarUno'); // listo
  $group->get('[/]',             \UsuarioController::class . ':TraerTodos'); //listo
  $group->get('/usuario',        \UsuarioController::class . ':TraerUno'); //listo
  $group->put('/modificar',        \UsuarioController::class . ':ModificarUno'); //listo
  $group->delete('/usuario',     \UsuarioController::class . ':BorrarUno'); //listo ?
}) ;

$app->group('/pedidoProductos', function (RouteCollectorProxy $group)
{

  $group->post('/crear', \PedidoProductoController::class . ':CargarUno');
  $group->post('/modificar[/]', \PedidoProductoController::class . ':ModificarUno');
  $group->delete('/borrar/{id}', \PedidoProductoController::class . ':BorrarUno');
  $group->get('/listar', \PedidoProductoController::class . ':TraerTodos');//listo

  $group->get('/paraservir[/]', \ReportesController::class . ':PedidoProductoListoParaServir'); 
  $group->post('/comiendo[/]', \PedidoController::class . ':PasarAComiendo'); 
  $group->post('/pagando[/]', \PedidoController::class . ':PasarAPagando'); 

});
  
  
  
  
  $app->group('/pedido', function (RouteCollectorProxy $group)
  {
    
    $group->post('/crear', \PedidoController::class . ':CargarUno');
    $group->post('/modificar[/]', \PedidoController::class . ':ModificarUno');
    $group->post('/comiendo', \PedidoController::class . ':PasarEstadoAComiendo');
    $group->post('/pagando', \PedidoController::class . ':PasarEstadoAPagando');
    $group->post('/cerrar', \PedidoController::class . ':PasarEstadoACerrado');
    $group->delete('/borrar/{id}', \PedidoController::class . ':BorrarUno');
    $group->get('/listar[/]', \PedidoController::class . ':TraerTodos');

  })->add(new CheckTokenMW())->add(\UsuarioMW::class. ':ValidarMozo');

  $app->group('/pedido', function (RouteCollectorProxy $group)
  {
    $group->get('/listabarra[/]', \PedidoProductoController::class . ':ListarPedidosBarra')
    ->add(\UsuarioMW::class. ':ValidarBartender');
    $group->get('/listachoperas[/]', \PedidoProductoController::class . ':ListarPedidosChoperas')
    ->add(\UsuarioMW::class. ':ValidarCervecero');
    $group->get('/listacocina[/]', \PedidoProductoController::class . ':ListarPedidosCocina')
    ->add(\UsuarioMW::class. ':ValidarCocinero');  
    $group->get('/listacandybar[/]', \PedidoProductoController::class . ':ListarPedidosCandybar') 
    ->add(\UsuarioMW::class. ':ValidarRepostero');
  }) ->add(new CheckTokenMW());


$app->group('/producto', function (RouteCollectorProxy $group) //listo
{

  $group->post('/crear', \ProductoController::class . ':CargarUno');//listo
  $group->post('/modificar[/]', \ProductoController::class . ':ModificarUno');//listo
  $group->delete('/borrar/{id}', \ProductoController::class . ':BorrarUno'); //listo
  $group->get('/listar[/]', \ProductoController::class . ':TraerTodos'); //listo
   //CSV
   $group->get('/guardarCsv[/]', \ProductoController::class . ':ExportarTabla');  //listo
   $group->post('/leerCsv[/]', \ProductoController::class . ':ImportarTabla');  //listo

})->add(new CheckTokenMW()) ->add(\UsuarioMW::class. ':ValidarSocio');

$app->group('/tipousuario', function (RouteCollectorProxy $group) //listo
{
  //ABM
  //Tipo de usuario 1-socio 2-mozo 3-bartender 4-cervecero 5-cocinero 6-repostero
  $group->post('/crear', \TipoUsuarioController::class . ':CargarUno'); ;// listo
  $group->post('/modificar[/]', \TipoUsuarioController::class . ':ModificarUno');
  $group->delete('/borrar/{id}', \TipoUsuarioController::class . ':BorrarUno');
  $group->get('/listar[/]', \TipoUsuarioController::class . ':TraerTodos');
}) ->add(new CheckTokenMW()) ->add(\UsuarioMW::class. ':ValidarSocio');

$app->group('/sector', function (RouteCollectorProxy $group) //listo
{
  //ABM 1- cocina 2 - barra 3 - choperas 4 - candybar
  $group->post('/crear', \SectorController::class . ':CargarUno');//listo
  $group->put('/modificar[/]', \SectorController::class . ':ModificarUno');//listo
  $group->delete('/borrar/{id}', \SectorController::class . ':BorrarUno');//listo
  $group->get('/listar[/]', \SectorController::class . ':TraerTodos'); //listo
})->add(new CheckTokenMW()) ->add(\UsuarioMW::class. ':ValidarSocio');


$app->group('/cliente', function (RouteCollectorProxy $group) //listo
{
  //ABM
  $group->post('/crear', \ClienteController::class . ':CargarUno');
   $group->put('/modificar[/]', \ClienteController::class . ':ModificarUno');
  $group->delete('/borrar/{id}', \ClienteController::class . ':BorrarUno');
   $group->get('/listar[/]', \ClienteController::class . ':TraerTodos');
});



$app->get('[/]', function (Request $request, Response $response) {
  $payload = json_encode(array("mensaje" => " LA SUPER COMANDA DE ANDREA :D "));

  $response->getBody()->write($payload);
  return $response->withHeader('Content-Type', 'application/json');
});
$app->run();
