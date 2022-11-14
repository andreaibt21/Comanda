<?php
require_once './herramientas/herramientas.php';
class Producto
{
    public $id;
    public $id_sector;
    public $nombre;
    public $precio;
    public $activo;
    public $creado;
    public $actualizado;

    public function crearProducto()
    {
       
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "INSERT 
                INTO producto (id_sector,nombre, precio, activo, creado, actualizado) 
                VALUES (:id_sector, :nombre, :precio, :activo, :creado, :actualizado)"
        );
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':creado', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->bindValue(':actualizado', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->bindValue(':id_sector', $this->id_sector, PDO::PARAM_STR);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':precio',  $this->precio, PDO::PARAM_STR);
        $consulta->bindValue(':activo',  $this->activo, PDO::PARAM_STR);
     
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT * 
                FROM producto"
        );
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }

    public static function obtenerCriptomoneda($nombre)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT * 
                FROM producto 
                WHERE nombre = :nombre"
        );
        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Producto');
    }

    public function modificarCriptomoneda($criptomoneda)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta(
            "UPDATE producto 
                SET nombre = :nombre,
                    precio=:precio , 
                    activo= :activo, 
                    creado= :creado 
           WHERE id = :id"
        );
        $consulta->bindValue(':nombre', $criptomoneda->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $criptomoneda->precio, PDO::PARAM_STR);
        $consulta->bindValue(':activo', $criptomoneda->activo, PDO::PARAM_STR);
        $consulta->bindValue(':creado', $criptomoneda->creado, PDO::PARAM_STR);
        $consulta->bindValue(':id', $criptomoneda->id, PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function borrarCriptomoneda($nombre)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta(
            "UPDATE producto 
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
            FROM producto 
            WHERE id = :id"
        );
        $consulta->bindValue(':id', $id, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Producto');
    }
    
    public static function obtenerProductoPorSector($id_sector)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT * 
               FROM producto 
              WHERE id_sector = :id_sector"
        );
        $consulta->bindValue(':id_sector', $id_sector, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS,'Producto');
    }
}
