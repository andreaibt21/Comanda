<?php
require_once './models/PedidoProducto.php';

class PedidoProductoController extends PedidoProducto
{
    public function CargarUno($request, $response, $args) //listo
    {
        try
        {
            $params = $request->getParsedBody();
            //var_dump($params);
            $pedidoProducto = new PedidoProducto();
            $pedidoProducto->id_pedido = $params["id_pedido"];
            $pedidoProducto->id_producto = $params["id_producto"];
            $pedidoProducto->cantidad = $params["cantidad"];
            $alta = PedidoProducto::crearPedidoProducto($pedidoProducto);

            switch ($alta) {
                case 0:
                    $respuesta = "No se ha grabado el pedido.";
                    break;
                case 1:
                    $respuesta = "Pedido grabado con éxito :).";
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
        $lista = PedidoProducto::obtenerTodos();
        $payload = json_encode(array("listaPedidosXProducto" => $lista));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public  function ModificarUno($request, $response, $args)
    {
        try
        {
            $params = $request->getParsedBody();
            $pedidoProducto = new PedidoProducto();
            $pedidoProducto->id = $params["id"];
            $pedidoProducto->id_producto = $params["idProducto"];
            $pedidoProducto->id_cantidad = $params["cantidad"];
            $modificacion = PedidoProducto::ModificarPedidoProducto($pedidoProducto);
            switch($modificacion)
            {
                case 0:
                    $respuesta = "Error generando el pedido del producto.";
                    break;
                case 1:
                    $respuesta = "Pedido de producto modificado.";
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
            $modificacion = PedidoProducto::BorrarPedidoProducto($idDelPedido);
            switch($modificacion)
            {
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


    public function PedidoEnPreparacion($request, $response, $args)
    {
        try
        {
            $params = $request->getParsedBody();
            $idPedidoProducto = $params["id"];
            $idUsuario = $params["usuario"];
            $tardanzaEnMinutos = $params["tiempo"];

            PedidoProducto::CambiarEstado('1', $idPedidoProducto, $idUsuario, $tardanzaEnMinutos);
            $payload = json_encode("Pedido en preparación.");
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


    public function PedidoListo($request, $response, $args)
    {
        try
        {
            $params = $request->getParsedBody();
            $idPedidoProducto = $params["id"];
            PedidoProducto::CambiarEstado('2', $idPedidoProducto);
            $payload = json_encode("Pedido listo para servir.");
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

    public function ListarPedidosBarra($request, $response, $args)
    {
        $newResponse = "";
        try
        {
            $id = AccesoDatos::retornarObjetoActivoPorCampo('barra', 'nombre', 'sector', 'Sector');
            $lista = AccesoDatos::ObtenerPedidosPorSector($id[0]->id);
            $payload = json_encode(array("Pedidos Activos de Barra" => $lista));
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

    public function ListarPedidosChoperas($request, $response, $args)
    {
        try
        {
            $id = AccesoDatos::retornarObjetoActivoPorCampo('choperas', 'nombre', 'sector', 'Sector');
            $lista = AccesoDatos::ObtenerPedidosPorSector($id[0]->id);
            $payload = json_encode(array("Pedidos Activos de Choperas" => $lista));
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

    public function ListarPedidosCocina($request, $response, $args)
    {
        try
        {   $clase = null;
            $id = AccesoDatos::retornarObjetoActivoPorCampo('cocina', 'nombre', 'sector', 'Sector');
            $lista = AccesoDatos::ObtenerPedidosPorSector($id[0]->id);
            $payload = json_encode(array("Pedidos Activos de Cocina" => $lista));
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

    public function ListarPedidosCandybar($request, $response, $args)
    {
        try
        {
            $id = AccesoDatos::retornarObjetoActivoPorCampo('candybar', 'nombre', 'sector', 'Sector');
            $lista = AccesoDatos::ObtenerPedidosPorSector($id[0]->id);
            $payload = json_encode(array("Pedidos Activos de Candybar" => $lista));
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

   }
