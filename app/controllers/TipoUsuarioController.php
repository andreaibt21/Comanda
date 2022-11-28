<?php
include_once "models/TipoUsuario.php" ;
require_once './interfaces/IApiUsable.php';

class TipoUsuarioController 
{

    public function CargarUno($request, $response, $args) //listo
    {
        try
        {
            $params = $request->getParsedBody();
            $tipoUsuario = new TipoUsuario();
            $tipoUsuario->nombre = $params["nombre"];
            //$tipoUsuario->id = $params["id"];
            $alta = TipoUsuario::CrearTipoUsuario($tipoUsuario);
            if($alta == 1)
            { 
                $respuesta =  "Tipo de usuario creado con exito.";
            }
            else if($alta == 2)
            { 
                $respuesta = "EL tipo de usuario ya existía, fue actualizado con exito";
            }
            else
            {
                $respuesta = "Problemas creando el tipo de usuario.";
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
            $newResponse = $response->withHeader('Content-Type', 'application/json');
            return $newResponse;
        }
    }

    public function BorrarUno($request, $response, $args)//listo
    {
        try
        {
            //var_dump($args);
            $id = $args["id"];
            $retorno = TipoUsuario::BorrarUno($id);
            switch($retorno)
            {
                case 0:
                    $respuesta = "No existe este tipo de usuario.";
                    break;
                case 1:
                    $respuesta = "Tipo de usuario borrado con exito.";
                    break;
                case 2:
                    $respuesta = "No se puede borrar, hay usuario relacionados con este sector.<br> 
                    Intente con otro tipo" ;
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
            printf("Error al dar de baja: <br> $mensaje .<br>");
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
            $tipoUsuario = new TipoUsuario();
            $params = $request->getParsedBody();
            $tipoUsuario->nombre = $params["nuevoNombre"];
            $tipoUsuario->id = $params["id"];
            $retorno = TipoUsuario::Modificar($tipoUsuario);
            switch($retorno)
            {
                case 1:
                    $respuesta = "Nombre de tipo de usuario cambiado con exito";
                    break;
                case 2:
                    $respuesta = "El nombre de tipo de usuario ya existe en la base de datos.";
                    break;
                case 3:
                    $respuesta = "Este ID no corresponde a ningún tipo de usuario de la base de datos.";
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
            return $newResponse;
        }
    }

    public function TraerTodos($request, $response, $args) //listo
    {
      $lista = TipoUsuario::ObtenerTodos();
      $payload = json_encode(array("lista de Tipo Usuario" => $lista));
  
      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

   
}

?>