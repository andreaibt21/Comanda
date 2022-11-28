<?php
require_once './herramientas/herramientas.php';
include_once "db/AccesoDatos.php";
include_once "csv/GuardarLeer.php";

class Producto
{
    public $id;
    public $id_sector;
    public $nombre;
    public $precio;
    public $activo;
    public $creado;
    public $actualizado;

    public static function crearProducto($producto) //listo

    {
        $retorno = -1;
        $sectorAux = AccesoDatos::retornarObjetoActivoPorCampo($producto->id_sector, "id", "sector", "Sector");
        var_dump($producto->id_sector);
        if ($sectorAux == null) {
            $retorno = 0;
        } else {
            $productoAux = AccesoDatos::retornarObjetoPorCampo($producto->nombre, "nombre", "producto", "Producto");

            if ($productoAux != null) {
                $productoAux[0]->id_sector = $sectorAux[0]->id;
                $productoAux[0]->precio = $producto->precio;
                Producto::ModificarProducto($productoAux[0]);
                $retorno = 1;
            } else {
                $producto->id_sector = $sectorAux[0]->id;
                $objAccesoDatos = AccesoDatos::obtenerInstancia();
                $consulta = $objAccesoDatos->prepararConsulta(
                    "INSERT
                       INTO producto (nombre, precio, id_sector, activo, creado, actualizado)
                        VALUES (:nombre, :precio, :id_sector, :activo, :creado, :actualizado)");
                $consulta->bindValue(':nombre', $producto->nombre, PDO::PARAM_STR);
                $consulta->bindValue(':precio', $producto->precio, PDO::PARAM_STR);
                $consulta->bindValue(':id_sector', $producto->id_sector, PDO::PARAM_STR);
                $consulta->bindValue(':activo', '1', PDO::PARAM_STR);
                $fecha = new DateTime(date("d-m-Y H:i:s"));
                $consulta->bindValue(':creado', date_format($fecha, 'Y-m-d H:i:s'));
                $consulta->bindValue(':actualizado', date_format($fecha, 'Y-m-d H:i:s'));
                $consulta->execute();
                $retorno = 2;
            }
        }
        return $retorno;

    }

    public static function ModificarProducto($producto) //listo

    {
        $retorno = 3;
        $productoAux = AccesoDatos::retornarObjetoActivo($producto->id, 'producto', 'Producto');

        if ($productoAux != null) {
            $productoAuxNombre = AccesoDatos::retornarObjetoPorCampo($producto->nombre, 'nombre', 'producto', 'Producto');
            $retorno = 2; //es el mismo nombre
            if ($productoAuxNombre == null || $productoAuxNombre[0]->id == $producto->id) {
                $producto->activo = 1;

                $objAccesoDato = AccesoDatos::obtenerInstancia();
                $consulta = $objAccesoDato->prepararConsulta(
                    "UPDATE producto
                            SET nombre = :nombre,
                                precio = :precio,
                                id_sector = :id_sector,
                                actualizado = :actualizado
                            WHERE id = :id");
                $consulta->bindValue(':id', $producto->id);
                $consulta->bindValue(':nombre', $producto->nombre);
                $consulta->bindValue(':precio', $producto->precio);
                $consulta->bindValue(':id_sector', $producto->id_sector);
                $fecha = new DateTime(date("d-m-Y H:i:s"));
                $consulta->bindValue(':actualizado', date_format($fecha, 'Y-m-d H:i:s'));
                $consulta->execute();
                $retorno = 1; //se cambia el nombre
            }
        }
        return $retorno;
    }

    public static function borrarProducto($id) //listo

    {
        $retorno = 0; //($id, $tabla, $clase)
        $productoAux = AccesoDatos::retornarObjetoActivo($id, 'producto', 'Producto');

        if ($productoAux !== null) {

            $conexion = AccesoDatos::obtenerInstancia();
            $consulta = $conexion->prepararConsulta(
                "UPDATE producto
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

    public static function obtenerTodos() //listo

    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT *
                FROM producto"
        );
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
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

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }

    public static function CargarCSV($archivo) //listo

    {
        $array = CSV::LeerCsv($archivo);
        for ($i = 0; $i < sizeof($array); $i++) {
            $campos = explode(",", $array[$i]);
            $producto = new Producto();
            $producto->id = $campos[0];
            $producto->id_sector = $campos[1];
            $producto->nombre = $campos[2];
            $producto->precio = $campos[3];
            $producto->activo = $campos[4];
            $producto->creado = $campos[5];
            $producto->actualizado = $campos[6];
            //var_dump($producto);
            Producto::crearProducto($producto);
        }
    }

}
