<?php

class Sector
{
    public $id;
    public $nombre;
    public $activo;
    public $creado;
    public $actualizado;

    public static function crearSector($sector) //listo
    {
        $retorno = 0;                                    //($valor, $campo, $tabla, $clase)
        $sectorAux = AccesoDatos::retornarObjetoPorCampo($sector->nombre, "nombre", "sector", "Sector");

        if ($sectorAux == null) {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta(
                "INSERT
                INTO sector (nombre, activo, creado, actualizado)
                VALUES (:nombre, :activo, :creado, :actualizado)"
            );

            $fecha = new DateTime(date("d-m-Y H:i:s"));
            $consulta->bindValue(':creado', date_format($fecha, 'Y-m-d H:i:s'));
            $consulta->bindValue(':actualizado', date_format($fecha, 'Y-m-d H:i:s'));
            $consulta->bindValue(':nombre', $sector->nombre, PDO::PARAM_STR);
            $consulta->bindValue(':activo', '1', PDO::PARAM_STR);
            $consulta->execute();

            $retorno = 1;

        } else {
            $sectorAux[0]->activo = 1;
             Sector::modificarSector($sectorAux[0]);
            $retorno = 2;
        }
        return $retorno;

    }

    public static function modificarSector($sector)//listo
    {
        $retorno = 3;
        $sectorAux = AccesoDatos::retornarObjetoActivo($sector->id, 'sector', 'Sector');

        if ($sectorAux != null) {
            $sectorAuxNombre = AccesoDatos::retornarObjetoPorCampo($sector->nombre, 'nombre', 'sector', 'Sector');
            $retorno = 2; //es el mismo nombre
            if ($sectorAuxNombre == null) {
                $sector->activo = 1;
                // Sector::modificarRegistro($sector);
                $objAccesoDato = AccesoDatos::obtenerInstancia();
                $consulta = $objAccesoDato->prepararConsulta("UPDATE sector
                                                              SET nombre = :nombre, 
                                                                  activo = :activo,
                                                                  actualizado = :actualizado
                                                              WHERE id = :id");
                $consulta->bindValue(':id', $sector->id, PDO::PARAM_STR);
                $consulta->bindValue(':nombre', $sector->nombre, PDO::PARAM_STR);
                $consulta->bindValue(':activo', $sector->activo, PDO::PARAM_STR);
                $fecha = new DateTime(date("d-m-Y H:i:s"));
                $consulta->bindValue(':actualizado', date_format($fecha, 'Y-m-d H:i:s'));
                $consulta->execute();
                $retorno = 1; //se cambia el nombre
            }
        }
        return $retorno;

    }

    public static function obtenerTodos()//listo
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT *
            FROM sector"
        );
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Sector');
    }

    public static function borrarSector($id)//listo
    {
        $retorno = 0;
        $sectorAux = AccesoDatos::retornarObjetoActivo($id, 'sector', 'Sector');

        if ($sectorAux != null) {    
                                            //($valor, $campo, $tabla, $clase)
            $productoAux = AccesoDatos::retornarObjetoActivoPorCampo($id, 'id_sector', 'producto', 'Producto');
            $retorno = 2;
            if (sizeof($productoAux) == 0) {

                $conexion = AccesoDatos::obtenerInstancia();
                $consulta = $conexion->prepararConsulta(
                    "UPDATE sector
                            SET activo = :activo , actualizado = :actualizado
                            WHERE id = $id");
                $fecha = new DateTime(date("d-m-Y"));
                $consulta->bindValue(':actualizado', date_format($fecha, 'Y-m-d H:i:s'));
                $consulta->bindValue(':activo', '0', PDO::PARAM_STR);
                $consulta->execute();

                return 1;

            }
        }
        return $retorno;
    }
}
