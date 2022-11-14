<?php
require_once './models/Usuario.php';
require_once './interfaces/IApiUsable.php';

class UsuarioController extends Usuario implements IApiUsable
{
  public function CargarUno($request, $response, $args)
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

  public function TraerUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();
    $mail = $parametros['mail'];
    $usuario = Usuario::obtenerUsuario($mail);
    $payload = json_encode($usuario);

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerTodos($request, $response, $args)
  {
    $lista = Usuario::obtenerTodos();
    $payload = json_encode(array("listaUsuario" => $lista));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function ModificarUno($request, $response, $args)
  {
    $datos = json_decode(file_get_contents("php://input"), true);
    //$parametros = $request->getParsedBody();

    $usuarioAModificar = new Usuario();
    $usuarioAModificar->id=$datos["id"]; 
    $usuarioAModificar->mail=$datos["mail"]; 
    $usuarioAModificar->clave=$datos["clave"]; 
  
    if(array_key_exists("fechaBaja",$datos))
    {
      $usuarioAModificar->fechaBaja=$datos["fechaBaja"]; 
    }
    if(array_key_exists("perfil",$datos))
    {
      $usuarioAModificar->perfil=$datos["perfil"]; 
    }
    Usuario::modificarUsuario($usuarioAModificar);

    $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function BorrarUno($request, $response, $args)
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
