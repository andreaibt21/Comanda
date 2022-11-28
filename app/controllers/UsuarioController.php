<?php
require_once './models/Usuario.php';
require_once './interfaces/IApiUsable.php';

class UsuarioController extends Usuario implements IApiUsable
{
  public function CargarUno($request, $response, $args) //listo
  {
    $parametros = $request->getParsedBody();

    $dni = $parametros['dni'];
    $clave = $parametros['clave'];
    $tipo = $parametros['tipo'];
    $activo = $parametros['activo'];

    $usr = new Usuario();
    $usr->dni = $dni;
    $usr->clave = $clave;
    $usr->tipo = $tipo;
    $usr->activo = $activo;
    $usr->crearUsuario();

    $payload = json_encode(array("mensaje" => "Usuario creado con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerUno($request, $response, $args) //listo
  {
    $parametros = $request->getParsedBody();
    $id = $parametros['id'];
    $usuario = Usuario::obtenerUsuario($id);
    $payload = json_encode($usuario);

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerTodos($request, $response, $args) //listo
  {
    $lista = Usuario::obtenerTodos();
    $payload = json_encode(array("listaUsuario" => $lista));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function ModificarUno($request, $response, $args) //listo
  {


    try
    {
        $params = $request->getParsedBody();
        $usuario = new Usuario();
        $usuario->id = $params["id"];
        $usuario->tipo = $params["tipo"];
        $usuario->dni = $params["dni"];
        $usuario->clave = $params["clave"];
        $modificacion = Usuario::Modificar($usuario);

        switch($modificacion)
        {
            case 1:
                $respuesta = "Usuario modificado con éxito.";
                break;
            case 2:
                $respuesta = "El DNI ya existe en la base de datos.";
                break;
            case 3:
                $respuesta = "Este ID no corresponde a ningún usuario";
                break;
            default:
                $respuesta = "Nunca llega a la modificacion";
        }    
        $payload = json_encode($respuesta);
        $response->getBody()->write($payload);
        $newResponse = $response->withHeader('Content-Type', 'application/json');
    }
    catch(Throwable $mensaje)
    {
        printf("Error al modificar: <br> $mensaje .<br>");
    }
    finally
    {
     // $newResponse = $response->withHeader('Content-Type', 'application/json');

        return $newResponse;
    }











    // $datos = json_decode(file_get_contents("php://input"), true);
    // //$parametros = $request->getParsedBody();

    // $usuarioAModificar = new Usuario();
    // $usuarioAModificar->id=$datos["id"]; 
    // $usuarioAModificar->dni=$datos["dni"]; 
    // $usuarioAModificar->clave=$datos["clave"]; 
    // if(array_key_exists("tipo",$datos))
    // {
    //   $usuarioAModificar->clave=$datos["tipo"]; 
    // }
    // if(array_key_exists("fechaBaja",$datos))
    // {
    //   $usuarioAModificar->fechaBaja=$datos["fechaBaja"]; 
    // }
    // if(array_key_exists("perfil",$datos))
    // {
    //   $usuarioAModificar->perfil=$datos["perfil"]; 
    // }
    // Usuario::modificarUsuario($usuarioAModificar);

    // $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));

    // $response->getBody()->write($payload);
    // return $response
    //   ->withHeader('Content-Type', 'application/json');
  }

  public function BorrarUno($request, $response, $args) //listo
  {
    $parametros = $request->getParsedBody();

    $usuarioId = $parametros['id'];
    Usuario::borrarUsuario($usuarioId);

    $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
}
