<?php
require_once './models/Producto.php';
require_once './interfaces/IApiUsable.php';

class ProductoController extends Producto
{
  public function CargarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $id_sector = $parametros['id_sector'];
    $nombre = $parametros['nombre'];
    $precio = $parametros['precio'];
    $activo = $parametros['activo'];


    $cripto = new Producto();
    $cripto->id_sector = $id_sector;
    $cripto->nombre = $nombre;
    $cripto->precio = $precio;
    $cripto->activo = $activo;
    $cripto->crearProducto();

    $payload = json_encode(
      array("mensaje" => "Producto creado con exito")
    );
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }

 

  public function TraerTodos($request, $response, $args)
  {
    $lista = Producto::obtenerTodos();
    $payload = json_encode(array("listaProductos" => $lista));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerUnoPorSector($request, $response, $args)
  {
    $parametros = $request->getParsedBody();
    $id_sector = $parametros['id_sector'];
    $lista = Producto::obtenerProductoPorSector($id_sector);
    $payload = json_encode(array("listaPorSector" => $lista));
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerUnoPorId($request, $response, $args)
  {
    $parametros = $request->getParsedBody();
    $id = $parametros['id'];
    $cripto = Producto::obtenerCriptomonedaPorId($id);
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

  //   $usuarioAModificar = new Producto();
  //   $usuarioAModificar->id = $datos["id"];
  //   $usuarioAModificar->mail = $datos["mail"];
  //   $usuarioAModificar->clave = $datos["clave"];

  //   if (array_key_exists("fechaBaja", $datos)) {
  //     $usuarioAModificar->fechaBaja = $datos["fechaBaja"];
  //   }
  //   if (array_key_exists("perfil", $datos)) {
  //     $usuarioAModificar->perfil = $datos["perfil"];
  //   }
  //   Producto::modificarUsuario($usuarioAModificar);

  //   $payload = json_encode(array("mensaje" => "Producto modificado con exito"));

  //   $response->getBody()->write($payload);
  //   return $response
  //     ->withHeader('Content-Type', 'application/json');
  // }

  // public function BorrarUno($request, $response, $args)
  // {
  //   $parametros = $request->getParsedBody();

  //   $usuarioId = $parametros['id'];
  //   Producto::borrarUsuario($usuarioId);

  //   $payload = json_encode(array("mensaje" => "Producto borrado con exito"));

  //   $response->getBody()->write($payload);
  //   return $response
  //     ->withHeader('Content-Type', 'application/json');
  // }
}
