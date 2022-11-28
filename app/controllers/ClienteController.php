<?php
require_once './models/Cliente.php';
require_once './interfaces/IApiUsable.php';

class ClienteController extends Cliente 
{
    public function CargarUno($request, $response, $args) 
    {
        try
        {
            $params = $request->getParsedBody();
            $cliente = new Cliente();
             $cliente->nombre = $params["nombre"];
            $retorno = Cliente::crearCliente($cliente);
            $respuesta = "";
            switch ($retorno) {
                case '0':
                    $respuesta = "Problemas creando el cliente.";
                    break;
                case '1':
                    $respuesta = "Cliente creado con exito.";
                    break;
                case '2':
                    $respuesta = "El cliente ya existe.";
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
        $lista = Cliente::obtenerTodos();
        $payload = json_encode(array("lista Clientees" => $lista));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args) 
    {
        try
        {
            $params = $request->getParsedBody();
            $cliente = new Cliente();
            $cliente->id = $params["id"];
            $cliente->nombre = $params["nuevoNombre"];
            $retorno = Cliente::modificarCliente($cliente);
            switch ($retorno) {
                case 1:
                    $respuesta = "Nombre de cliente cambiado con exito";
                    break;
                case 2:
                    $respuesta = "El nombre ya existe en la base de datos.";
                    break;
                case 3:
                    $respuesta = "Este ID no corresponde a ningún cliente";
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

    public function BorrarUno($request, $response, $args) 
    {
        try
        {
            $id = $args["id"];
            $retorno = Cliente::borrarCliente($id);
            switch ($retorno) {
                case 0:
                    $respuesta = "No existe este cliente.";
                    break;
                case 1:
                    $respuesta = "cliente borrado con exito.";
                    break;
                case 2:
                    $respuesta = "No se puede borrar, hay productos relacionados con este cliente.<br>
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
