<?php
require_once './models/Mesa.php';

class MesaController extends Mesa
{
    public function CargarUno($request, $response, $args) 
    {
        try
        {
            $params = $request->getParsedBody();
            $mesa = new Mesa();
            $mesa->nombre = $params["nombre"];
            $retorno = Mesa::crearMesa($mesa);
            switch($retorno)
            {
                case 1:
                    $respuesta = "Mesa creada con exito;";
                    break;
                case 2:
                    $respuesta = "Ya existe una mesa con ese nombre";
                    break;
                case 3:
                    $respuesta = "Mesa dada de alta de nuevo.";
                    break;
                default:
                    $respuesta = "Error";
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

    public function TraerTodos($request, $response, $args)
    {
        $lista = PedidoProducto::obtenerTodos();
        $payload = json_encode(array("listaPedidosXProducto" => $lista));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args)
    {
        try
        {
            $params = $request->getParsedBody();
            $mesa = new Mesa();
            $mesa->id = $params["id"];
            $mesa->nombre = $params["nuevoNombre"];
            $retorno = Mesa::ModificarMesa($mesa);

            switch($retorno)
            {
                case 1:
                    $respuesta = "Nombre de mesa cambiada";
                    break;
                case 2:
                    $respuesta = "Ya existe en la base de datos";
                    break;
                case 3:
                    $respuesta = "Este ID no corresponde a ninguna mesa";
                    break;
                default:
                    $respuesta = "Error";
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

    public function BorrarUno($request, $response, $args) //listo
    {
        try
        {
            $idDeLaMesa = $args["id"];
            $retorno = Mesa::BorrarMesa($idDeLaMesa);
            switch ($retorno) {
                case 0:
                    $respuesta = "No existe este pedido.";
                    break;
                case 1:
                    $respuesta = "Pedido borrado con éxito :D";
                    break;
                default:
                    $respuesta = "Error";
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
