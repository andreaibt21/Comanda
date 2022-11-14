<?php
require_once './models/PedidoXProducto.php';
require_once './interfaces/IApiUsable.php';

class PedidoXProductoController extends PedidoXProducto
{
  public function CargarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

   
    $id_usuario = $parametros["id_usuario"];
    $id_pedido = $parametros["id_pedido"];
    $id_producto = $parametros["id_producto"];
    $cantidad = $parametros["cantidad"];
    $estado = $parametros["estado"];
    $activo = $parametros["activo"];

    $pedido = new PedidoXProducto();
    $pedido->id_usuario = $id_usuario;
    $pedido->id_pedido = $id_pedido;
    $pedido->id_producto = $id_producto;
    $pedido->estado = $estado;     
    $pedido->cantidad = $cantidad;     
    $pedido->activo = $activo;
    $pedido->crearPedidoXProducto();

    $payload = json_encode(
      array("mensaje" => "PedidoxProducto creado con exito")
    );
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }

 

  public function TraerTodos($request, $response, $args)
  {
    $lista = PedidoXProducto::obtenerTodos();
    $payload = json_encode(array("listaPedidosXProducto" => $lista));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerUnoPorSector($request, $response, $args)
  {
    $parametros = $request->getParsedBody();
    $id_sector = $parametros['id_sector'];
    $lista = Pedido::obtenerPedidoPorSector($id_sector);
    $payload = json_encode(array("listaPorSector" => $lista));
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerUnoPorId($request, $response, $args)
  {
    $parametros = $request->getParsedBody();
    $id = $parametros['id'];
    $cripto = Pedido::obtenerCriptomonedaPorId($id);
    $payload = json_encode($cripto);

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

 private function moverImagenACarpeta()
  {
    $carpetaFotos = "." . DIRECTORY_SEPARATOR . "fotosCripto" . DIRECTORY_SEPARATOR;
    if (!file_exists($carpetaFotos)) {
      mkdir($carpetaFotos, 0777, true);
    }
    $nuevoNombre = $carpetaFotos . $_FILES["foto"]["name"];
    rename($_FILES["foto"]["tmp_name"], $nuevoNombre);

    return $nuevoNombre;
  }

  // public function ModificarUno($request, $response, $args)
  // {
  //   $datos = json_decode(file_get_contents("php://input"), true);
  //   //$parametros = $request->getParsedBody();

  //   $usuarioAModificar = new Pedido();
  //   $usuarioAModificar->id = $datos["id"];
  //   $usuarioAModificar->mail = $datos["mail"];
  //   $usuarioAModificar->clave = $datos["clave"];

  //   if (array_key_exists("fechaBaja", $datos)) {
  //     $usuarioAModificar->fechaBaja = $datos["fechaBaja"];
  //   }
  //   if (array_key_exists("perfil", $datos)) {
  //     $usuarioAModificar->perfil = $datos["perfil"];
  //   }
  //   Pedido::modificarUsuario($usuarioAModificar);

  //   $payload = json_encode(array("mensaje" => "Pedido modificado con exito"));

  //   $response->getBody()->write($payload);
  //   return $response
  //     ->withHeader('Content-Type', 'application/json');
  // }

  // public function BorrarUno($request, $response, $args)
  // {
  //   $parametros = $request->getParsedBody();

  //   $usuarioId = $parametros['id'];
  //   Pedido::borrarUsuario($usuarioId);

  //   $payload = json_encode(array("mensaje" => "Pedido borrado con exito"));

  //   $response->getBody()->write($payload);
  //   return $response
  //     ->withHeader('Content-Type', 'application/json');
  // }
}
