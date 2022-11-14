<?php
require_once './herramientas/herramientas.php';
class PedidoXProducto
{
    public $id;
    public $id_pedido;
    public $id_producto;
    public $cantidad;
    public $fecha_prevista;
    public $fecha_fin;
    public $actualizado;
    public $creado;
    public $activo;
    
    public $estado; 
    /*
    0 - Pendiente 
    1 - En preparación 
    2 - Listo
     */
    
    public function crearPedidoXProducto()
    {
       
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "INSERT 
                INTO pedido_producto (id_pedido, id_producto, id_usuario, cantidad, estado, creado, actualizado, activo)
                VALUES (:id_pedido, :id_producto,:id_usuario, :cantidad, :estado, :creado, :actualizado,:activo)"
        );
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':creado', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->bindValue(':actualizado', date_format($fecha, 'Y-m-d H:i:s'));

        $consulta->bindValue(':id_pedido', $this->id_pedido, PDO::PARAM_STR);
        $consulta->bindValue(':id_producto', $this->id_producto, PDO::PARAM_STR);
        $consulta->bindValue(':id_usuario', $this->id_usuario, PDO::PARAM_STR);
        $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_STR);
        $consulta->bindValue(':estado',  $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':activo',  $this->activo, PDO::PARAM_STR);
     
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT * 
                FROM pedido_producto"
        );
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'PedidoXProducto');
    }

    public static function obtenerCriptomoneda($nombre)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT * 
                FROM pedido 
                WHERE nombre = :nombre"
        );
        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('PedidoXProducto');
    }

    public function modificarCriptomoneda($pedido)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta(
            "UPDATE pedido 
                SET nombre = :nombre,
                    precio=:precio , 
                    activo= :activo, 
                    creado= :creado 
           WHERE id = :id"
        );
        $consulta->bindValue(':nombre', $pedido->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $pedido->precio, PDO::PARAM_STR);
        $consulta->bindValue(':activo', $pedido->activo, PDO::PARAM_STR);
        $consulta->bindValue(':creado', $pedido->creado, PDO::PARAM_STR);
        $consulta->bindValue(':id', $pedido->id, PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function borrarCriptomoneda($nombre)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta(
            "UPDATE pedido 
                SET actualizado = :actualizado 
              WHERE nombre = :nombre"
        );
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_INT);
        $consulta->bindValue(':actualizado', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();
    }

    
    public static function obtenerCriptomonedaPorId($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT * 
            FROM pedido 
            WHERE id = :id"
        );
        $consulta->bindValue(':id', $id, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('PedidoXProducto');
    }
    
    public static function obtenerPedidoXProductoPorSector($id_pedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT * 
               FROM pedido 
              WHERE id_pedido = :id_pedido"
        );
        $consulta->bindValue(':id_pedido', $id_pedido, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS,'PedidoXProducto');
    }
}
