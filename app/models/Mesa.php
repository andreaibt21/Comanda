<?php
require_once './herramientas/herramientas.php';
class Mesa
{  
    public $id;
    public $nombre;
    public $activo;
    public $creado;
    public $actualizado;
    
    public function crearMesa()
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

}
