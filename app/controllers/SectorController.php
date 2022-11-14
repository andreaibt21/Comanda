<?php
require_once './models/Sector.php';
require_once './interfaces/IApiUsable.php';

class SectorController extends Sector implements IApiUsable
{
  public function CargarUno($request, $response, $args)
  {
    // try {
       $parametros = $request->getParsedBody();

    $nombre = $parametros["nombre"];
    $activo = $parametros["activo"];

    $sector = new Sector();
    $sector->nombre = $nombre;
    $sector->activo = $activo;
    $sector->crearSector();

    $payload = json_encode(array("mensaje" => "Sector creado con exito"));

    $response->getBody()->write($payload);
 
    // } catch (Throwable $error) {

      printf("Error al dar de alta: <br> $error .<br>");
    // }finally{
         return $response
      ->withHeader('Content-Type', 'application/json');
    // }
   
  }

  public function TraerUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();
    $mail = $parametros['mail'];
    $sector = Sector::obtenerSector($mail);
    $payload = json_encode($sector);

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerTodos($request, $response, $args)
  {
    $lista = Sector::obtenerTodos();
    $payload = json_encode(array("listaSector" => $lista));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function ModificarUno($request, $response, $args)
  {
    $datos = json_decode(file_get_contents("php://input"), true);
    //$parametros = $request->getParsedBody();

    $sectorAModificar = new Sector();
    $sectorAModificar->id=$datos["id"]; 
    $sectorAModificar->mail=$datos["mail"]; 
    $sectorAModificar->clave=$datos["clave"]; 
  
    if(array_key_exists("fechaBaja",$datos))
    {
      $sectorAModificar->fechaBaja=$datos["fechaBaja"]; 
    }
    if(array_key_exists("perfil",$datos))
    {
      $sectorAModificar->perfil=$datos["perfil"]; 
    }
    Sector::modificarSector($sectorAModificar);

    $payload = json_encode(array("mensaje" => "Sector modificado con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function BorrarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $sectorId = $parametros['id'];
    Sector::borrarSector($sectorId);

    $payload = json_encode(array("mensaje" => "Sector borrado con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
}
