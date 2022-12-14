<?php
require_once './models/Sector.php';
require_once './interfaces/IApiUsable.php';

class SectorController extends Sector 
{
    public function CargarUno($request, $response, $args) //listo
    {
        try
        {
            $params = $request->getParsedBody();
            $sector = new Sector();
            $sector->nombre = $params["nombre"];
            $retorno = Sector::crearSector($sector);
            $respuesta;
            switch ($retorno) {
                case '0':
                    $respuesta = "Problemas creando el sector.";
                    break;
                case '1':
                    $respuesta = "Sector creado con exito.";
                    break;
                case '2':
                    $respuesta = "El sector ya existe.";
                    break;
            }
            $payload = json_encode($respuesta);
            $response->getBody()->write($payload);
            $newResponse = $response->withHeader('Content-Type', 'application/json');
        } catch (Throwable $mensaje) {
            printf("Error al dar de alta: <br> $mensaje .<br>");
        } finally {
            return $newResponse;
        }
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Sector::obtenerTodos();
        $payload = json_encode(array("lista Sectores" => $lista));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args) //listo
    {
        try
        {
            $params = $request->getParsedBody();
            $sector = new Sector();
            $sector->id = $params["id"];
            $sector->nombre = $params["nuevoNombre"];
            $retorno = Sector::modificarSector($sector);
            switch ($retorno) {
                case 1:
                    $respuesta = "Nombre de sector cambiado con exito";
                    break;
                case 2:
                    $respuesta = "El nombre ya existe en la base de datos.";
                    break;
                case 3:
                    $respuesta = "Este ID no corresponde a ning??n sector";
                    break;
                default:
                    $respuesta = "Error";
            }
            $payload = json_encode($respuesta);
            $response->getBody()->write($payload);
            $newResponse = $response->withHeader('Content-Type', 'application/json');
        } catch (Throwable $mensaje) {
            printf("Error al modifcar: <br> $mensaje .<br>");
        } finally {
            return $newResponse;
        }

    }

    public function BorrarUno($request, $response, $args) //listo
    {
        try
        {
            $id = $args["id"];
            $retorno = Sector::borrarSector($id);
            switch ($retorno) {
                case 0:
                    $respuesta = "No existe este sector.";
                    break;
                case 1:
                    $respuesta = "sector borrado con exito.";
                    break;
                case 2:
                    $respuesta = "No se puede borrar, hay productos relacionados con este sector.<br>
                  Intente con otro tipo";
                    break;
                default:
                    $respuesta = "error";
            }
            $payload = json_encode($respuesta);
            $response->getBody()->write($payload);
            $newResponse = $response->withHeader('Content-Type', 'application/json');
        } catch (Throwable $mensaje) {
            printf("Error al dar de baja: <br> $mensaje .<br>");
        } finally {
            return $newResponse;
        }
    }
}
