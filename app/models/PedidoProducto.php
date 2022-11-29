<?php
include_once "db/AccesoDatos.php";

class PedidoProducto
{
    public $id;
    public $id_pedido;
    public $id_producto;
    public $cantidad;
    public $precio;
    public $fecha_prevista;
    public $fecha_fin;
    public $actualizado;
    public $creado;
    public $activo;
    public $estado;
    // 0 - Pendiente
    // 1 - En preparación
    // 2 - Listo

    public static function crearPedidoProducto($pedidoProducto) //listo

    {
        $retorno = 0;

        $pedidoAux = AccesoDatos::retornarObjetoActivo($pedidoProducto->id_pedido, 'pedido', 'Pedido');
        $productoAux = AccesoDatos::retornarObjetoActivo($pedidoProducto->id_producto, 'producto', 'Producto');
        //var_dump($productoAux[0]->precio);
        if (sizeof($pedidoAux) > 0 && sizeof($productoAux) > 0) {
            $pedidoProducto->precio = $productoAux[0]->precio * $pedidoProducto->cantidad;
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta(
                "INSERT
                    INTO  pedido_producto (id_pedido, id_producto, id_usuario, cantidad, precio, estado, creado, actualizado, activo)
                             VALUES (:id_pedido, :id_producto, :id_usuario, :cantidad, :precio, :estado, :creado, :actualizado, :activo)");
            $consulta->bindValue(':id_pedido', $pedidoProducto->id_pedido, PDO::PARAM_STR);
            $consulta->bindValue(':id_producto', $pedidoProducto->id_producto, PDO::PARAM_STR);
            $consulta->bindValue(':id_usuario', '-1', PDO::PARAM_STR);
            $consulta->bindValue(':cantidad', $pedidoProducto->cantidad, PDO::PARAM_STR);
            $consulta->bindValue(':precio', $pedidoProducto->precio, PDO::PARAM_STR);
            $consulta->bindValue(':estado', '0', PDO::PARAM_STR); // 0 - Pendiente
            $consulta->bindValue(':activo', '1', PDO::PARAM_STR);
            $fecha = new DateTime(date("d-m-Y H:i:s"));
            $consulta->bindValue(':creado', date_format($fecha, 'Y-m-d H:i:s'));
            $consulta->bindValue(':actualizado', date_format($fecha, 'Y-m-d H:i:s'));
            $consulta->execute();
            $retorno = 1;
        }
        return $retorno;
    }

    public static function ObtenerTodos() //listo

    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT *
            FROM pedido_producto"
        );
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function BorrarPedidoProducto($id) //listo

    {
        $retorno = 0;
        $pedidoProductoAux = AccesoDatos::retornarObjetoActivo($id, 'pedido_producto', 'PedidoProducto');
        var_dump($pedidoProductoAux);
        if ($pedidoProductoAux != null) {
            $conexion = AccesoDatos::obtenerInstancia();
            $consulta = $conexion->prepararConsulta(
                "UPDATE pedido_producto
                            SET activo = :activo , actualizado = :actualizado
                            WHERE id = $id");
            $fecha = new DateTime(date("d-m-Y"));
            $consulta->bindValue(':actualizado', date_format($fecha, 'Y-m-d H:i:s'));
            $consulta->bindValue(':activo', '0', PDO::PARAM_STR);
            $consulta->execute();

            return 1;
        }
        return $retorno;
    }

    public static function ModificarPedidoProducto($pedidoProducto)
    {

        $retorno = 0;
        $pedidoAux = AccesoDatos::retornarObjetoActivo($pedidoProducto->id, 'pedido_producto', 'Pedido');
        $productoAux = AccesoDatos::retornarObjetoActivo($pedidoProducto->id_producto, 'producto', 'Producto');
        if (sizeof($pedidoAux) > 0 && sizeof($productoAux) > 0) {
            $pedidoAux[0]->cantidad = $pedidoProducto->id_cantidad;
            $pedidoAux[0]->id_producto = $pedidoProducto->id_producto;
            $pedidoAux[0]->precio = $pedidoProducto->id_cantidad * $productoAux[0]->precio;

            $objAccesoDato = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDato->prepararConsulta(
                "UPDATE pedido_producto
                            SET id_pedido = :id_pedido,
                                id_producto = :id_producto,
                                id_usuario = :id_usuario,
                                cantidad = :cantidad,
                                precio = :precio,
                                estado = :estado,
                                fecha_prevista = :fecha_prevista,
                                fecha_fin = :fecha_fin,
                                activo = :activo,
                                actualizado = :actualizado
                            WHERE id = :id");
            $consulta->bindValue(':id', $pedidoAux[0]->id, PDO::PARAM_STR);
            $consulta->bindValue(':id_pedido', $pedidoAux[0]->id_pedido, PDO::PARAM_STR);
            $consulta->bindValue(':id_usuario', $pedidoAux[0]->id_usuario, PDO::PARAM_STR);
            $consulta->bindValue(':id_producto', $pedidoAux[0]->id_producto, PDO::PARAM_STR);
            $consulta->bindValue(':cantidad', $pedidoAux[0]->cantidad, PDO::PARAM_STR);
            $consulta->bindValue(':precio', $pedidoAux[0]->precio, PDO::PARAM_STR);
            $consulta->bindValue(':estado', $pedidoAux[0]->estado, PDO::PARAM_STR);
            $consulta->bindValue(':fecha_prevista', $pedidoAux[0]->fecha_prevista, PDO::PARAM_STR);
            $consulta->bindValue(':fecha_fin', $pedidoAux[0]->fecha_fin);
            $consulta->bindValue(':activo', $pedidoAux[0]->activo, PDO::PARAM_STR);
            $fecha = new DateTime(date("d-m-Y H:i:s"));
            $consulta->bindValue(':actualizado', date_format($fecha, 'Y-m-d H:i:s'));
            $consulta->execute();
            $retorno = 1;
        }
        return $retorno;
    }

    public static function CambiarEstado($idEstado, $idPedidoProducto, $idUsuario = null, $tardanzaEnMinutos = null)
    {
        $pedidoProducto = new PedidoProducto();
        $pedidoProducto = AccesoDatos::retornarObjetoActivoPorCampo($idPedidoProducto, 'id', 'pedido_producto', 'PedidoProducto');
        // var_dump($pedidoProducto);
        switch ($idEstado) {
            case 1:
                //Cambiar a en preparacion
                $pedidoProducto[0]->id_usuario = $idUsuario;
                $pedidoProducto[0]->estado = 1;
                $fecha = new DateTime(date("d-m-Y H:i:s"));
                $pedidoProducto[0]->fecha_prevista = $fecha->modify('+' . $tardanzaEnMinutos . ' minutes')->format("Y-m-d H:i:s");

                break;
            case 2:
                //Cambiar a para servir
                $pedidoProducto[0]->estado = 2;
                $fecha = new DateTime(date("d-m-Y H:i:s"));
                $pedidoProducto[0]->fecha_fin = $fecha->format("Y-m-d H:i:s");

                break;
        }
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta(
            "UPDATE pedido_producto
                    SET
                        estado = :estado,
                        fecha_fin = :fecha_fin,
                        fecha_prevista = :fecha_prevista,
                        actualizado = :actualizado
                    WHERE id = :id");

        $consulta->bindValue(':id', $pedidoProducto[0]->id, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $pedidoProducto[0]->estado, PDO::PARAM_STR);
        $consulta->bindValue(':fecha_prevista', $pedidoProducto[0]->fecha_prevista);
        $consulta->bindValue(':fecha_fin', $pedidoProducto[0]->fecha_fin);
        $fecha = new DateTime(date("d-m-Y H:i:s"));
        $consulta->bindValue(':actualizado', date_format($fecha, 'Y-m-d H:i:s'));
         $consulta->execute();
    }

    public static function ObtenerTodosSector($sector)
    { //listo

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT *
            FROM pedido_producto"
        );
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

}
