<?php

 include_once "models/Cliente.php";
 include_once "models/Pedido.php";
 include_once "models/Mesa.php";

class PedidoController
{
    public function CargarUno($request, $response, $args) //listo
    {
        try
        {
            $params = $request->getParsedBody();
            //var_dump($params);
            $cliente = new Cliente();
            $cliente->nombre = $params["cliente"];
            $pedido = new Pedido();
            $pedido->id_mesa= $params["mesa"];
            $pedido->id_cliente =  Cliente::crearCliente($cliente);
            $pedido->id_usuario= $params["id_usuario"];
            $pedido->fecha_prevista = $params["estara_en"];
            $retorno = Pedido::crearPedido($pedido);
            switch($retorno)
            {
                case '1':
                    $respuesta = 'Pedido generado.';
                    break;
                case '0':
                    $respuesta = 'No se generó el pedido pues la mesa está ocupada';
                    break;   
                case '2':
                    $respuesta = 'Usuario inválido.';
                    break;  
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
            $pedido = new Pedido();
            $pedido->id = $params["idPedido"];
            $pedido->id_mesa = $params["nuevaMesa"];
            $pedido->id_usuario = $params["nuevoMozo"];
            $modificacion = Pedido::Modificar($pedido);
            switch($modificacion)
            {
                case 0:
                    $respuesta = "Este ID no corresponde a ningún pedido.";
                    break;
                case 1:
                    $respuesta = "Mesa no disponible.";
                    break;
                case 2:
                    $respuesta = "Pedido modificado con éxito.";
                    break;
                case 3:
                    $respuesta = "No existe el empleado asignado.";
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
            printf("Error al modifcar: <br> $mensaje .<br>");
        }
        finally
        {
            return $newResponse;
        }
    }

    public function BorrarUno($request, $response, $args)//listo
    {
        try
        {
            //var_dump($args);
            $idDelPedido = $args["id"];
            $modificacion = Pedido::borrarPedido($idDelPedido);
            switch($modificacion)
            {
                case 0:
                    $respuesta = "No existe este pedido.";
                    break;
                case 1:
                    $respuesta = "Pedido borrado con exito.";
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
            printf("Error al dar de baja: <br> $mensaje .<br>");
        }
        finally
        {
            return $newResponse;
        }
    }
  
    public function TraerTodos($request, $response, $args)//listo
    {
      $lista = Pedido::obtenerTodos();
      $payload = json_encode(array("lista de Pedidos" => $lista));
  
      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function SubirFoto($request, $response, $args)
    {
        try
        {
            $params = $request->getParsedBody();
            $pedido = new Pedido();
            $pedido->id = $params["id"];
            $archivo = ($_FILES["archivo"]);
            $pedido->foto = ($archivo["tmp_name"]);
            $pedido->GuardarImagen();
            //var_dump($archivo);
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

    public function PasarEstadoAComiendo($request, $response, $args)
    {
        try
        {           
            $params = $request->getParsedBody();
            $pedido = $params["pedido"];
            Pedido::CambiarEstado($pedido, 2);
            $payload = json_encode("El cliente esta comiendo.");
            $response->getBody()->write($payload);
            $newResponse = $response->withHeader('Content-Type', 'application/json');

        }
        catch(Throwable $mensaje)
        {
            printf("Error al cambia el estado: <br> $mensaje .<br>");
        }
        finally
        {
            return $newResponse;
        }  
    }

    public function PasarEstadoAPagando($request, $response, $args)
    {
        try
        {           
            $params = $request->getParsedBody();
            $pedido = $params["pedido"];
            $respuesta = Pedido::CambiarEstado($pedido, '3');
            $payload = json_encode("El cliente esta Pagando. La cuenta es: ".$respuesta);
            $response->getBody()->write($payload);
            $newResponse = $response->withHeader('Content-Type', 'application/json');

        }
        catch(Throwable $mensaje)
        {
            printf("Error al cambia el estado: <br> $mensaje .<br>");
        }
        finally
        {
            return $newResponse;
        }  
    }

    public function PasarEstadoACerrado($request, $response, $args)
    {
        try
        {           
            $params = $request->getParsedBody();
            $pedido = $params["pedido"];
            Pedido::CambiarEstado($pedido, '4');
            $payload = json_encode("Mesa cerrada.");
            $response->getBody()->write($payload);
            $newResponse = $response->withHeader('Content-Type', 'application/json');

        }
        catch(Throwable $mensaje)
        {
            printf("Error al cambia el estado: <br> $mensaje .<br>");
        }
        finally
        {
            return $newResponse;
        }  
    }

   
   
}

?>