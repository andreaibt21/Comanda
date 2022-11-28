<?php
require_once './models/Producto.php';
require_once './interfaces/IApiUsable.php';
include_once "csv/GuardarLeer.php";


class ProductoController extends Producto //implements IApiUsable
{
  public function CargarUno($request, $response, $args) //listo
  {
    try
        {
            $params = $request->getParsedBody();
            //var_dump($params);
            $producto = new Producto();
            $producto->id_sector = $params["id_sector"];
            $producto->nombre = $params["nombre"];
            $producto->precio = $params["precio"];
           // var_dump($producto);
            $retorno = Producto::crearProducto($producto);
            switch($retorno)
            {
                case -1:
                    $respuesta = "Problema generando el alta.";
                    break;
                case 0:
                    $respuesta = "ERROR. No existe este sector.";
                    break;
                case 1:
                    $respuesta = "El producto ya existía en la BD. Se ha pasado activo si no lo estaba y se ha actualizado la información.";
                    break;
                case 2:
                    $respuesta = "Producto creado con éxito.";
                    break;
                default:
                    $respuesta = "Nunca llega al alta";
            }
    
            $payload = json_encode($respuesta);
            $response->getBody()->write($payload);
            $newResponse = $response->withHeader('Content-Type', 'application/json');

        }
        catch(Throwable $mensaje)
        {
            printf("Error al dar de alta: <br> $mensaje .<br>");
        }
        finally
        {
            return $newResponse;
        }
  }

  public function ModificarUno($request, $response, $args) //listo
  {
      try
      {
          $params = $request->getParsedBody();
          $producto = new Producto();
          $producto->id = $params["idDelProducto"];
          $producto->id_sector = $params["id_sector"];
          $producto->nombre = $params["nuevoNombre"];
          $producto->precio = $params["nuevoPrecio"];
 
          $retorno = Producto::ModificarProducto($producto);
          switch($retorno)
          {
              case 3:
                  $respuesta = "El id introducido no existe.";
                  break;
              case 2:
                  $respuesta = "El nombre ya existe.";
                  break;
              case 1:
                  $respuesta = "Producto modificado con éxito.";
                  break;
              default:
                  $respuesta = "Erorr";
          }    
          $payload = json_encode($respuesta);
          $response->getBody()->write($payload);
          $newResponse = $response->withHeader('Content-Type', 'application/json');
      }
      catch(Throwable $mensaje)
      {
          printf("Error al modifcar: <br> $mensaje .<br>");
      }
      finally
      {
          return $newResponse;
      }
  }


  public function BorrarUno($request, $response, $args) //listo
  {
    try
    {
        //var_dump($args);
        $idDelProducto = $args["id"];
        $modificacion = Producto::borrarProducto($idDelProducto);
        switch($modificacion)
        {
            case 0:
                $respuesta = "No existe este producto.";
                break;
            case 1:
                $respuesta = "Producto borrado con éxito.";
                break;
            default:
                $respuesta = "Nunca llega a la baja";
        }    
        $payload = json_encode($respuesta);
        $response->getBody()->write($payload);
        $newResponse = $response->withHeader('Content-Type', 'application/json');
    }
    catch(Throwable $mensaje)
    {
        printf("Error al dar de baja: <br> $mensaje .<br>");
    }
    finally
    {
        return $newResponse;
    }
  }



  public function TraerTodos($request, $response, $args)//listo
  {
    $lista = Producto::obtenerTodos();
    $payload = json_encode(array("lista de Productos" => $lista));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }


  public function ExportarTabla($request, $response, $args)//listo
  {
      try
      {
          CSV::ExportarTabla('producto', 'Producto', 'productos.csv');
          $payload = json_encode("Tabla exportada con éxito");
          $response->getBody()->write($payload);
          $newResponse = $response->withHeader('Content-Type', 'application/json');
      }
      catch(Throwable $mensaje)
      {
          printf("Error al listar: <br> $mensaje .<br>");
      }
      finally
      {
          return $newResponse;
      }    
  }

  public function ImportarTabla($request, $response, $args)//listo
  {   
      try
      {
          $archivo = ($_FILES["archivo"]);
          //var_dump($archivo);
          //var_dump($archivo["tmp_name"]);
          Producto::CargarCSV($archivo["tmp_name"]);
          $payload = json_encode("Carga exitosa.");
          $response->getBody()->write($payload);
          $newResponse = $response->withHeader('Content-Type', 'application/json');
          
      }
      catch(Throwable $mensaje)
      {
          printf("Error al listar: <br> $mensaje .<br>");
      }
      finally
      {
          return $newResponse;
      }    
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
