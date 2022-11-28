<?php
include_once "db/AccesoDatos.php";

class TipoUsuario
{
    public $id;
    public $nombre;
    public $activo;
    public $creado;
    public $actualizado;

    public static function CrearTipoUsuario($tipoUsuario) //listo
    {
        $retorno = 0;
        $tipoAux = AccesoDatos::retornarObjetoPorCampo($tipoUsuario->nombre, "nombre", "tipo_usuario", "TipoUsuario");
        if ($tipoAux == null) {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta(
                "INSERT INTO tipo_usuario (nombre, activo, actualizado, creado)
                        VALUES (:nombre, :activo, :actualizado, :creado)");

            $consulta->bindValue(':nombre', $tipoUsuario->nombre, PDO::PARAM_STR);
            $consulta->bindValue(':activo', '1', PDO::PARAM_STR);
            $fecha = new DateTime(date("d-m-Y H:i:s"));
            $consulta->bindValue(':actualizado', date_format($fecha, 'Y-m-d H:i:s'));
            $consulta->bindValue(':creado', date_format($fecha, 'Y-m-d H:i:s'));
            $consulta->execute();
            $retorno = 1;
        } else {
            $tipoAux[0]->activo = 1;
            TipoUsuario::modificarTipoUsuario($tipoAux[0]);
            $retorno = 2;
        }
        return $retorno;
    }

    public static function BorrarUno($id) //listo
    {
        $retorno = 0;
        $tipoAux = AccesoDatos::retornarObjetoActivo($id, 'tipo_usuario', 'TipoUsuario');

        if ($tipoAux != null) {
            $usuarioAux = AccesoDatos::retornarObjetoActivoPorCampo($id, 'tipo', 'usuario', 'Usuario');
            $retorno = 2;
            if (sizeof($usuarioAux) == 0) {

                $conexion = AccesoDatos::obtenerInstancia();
                $consulta = $conexion->prepararConsulta(
                    "UPDATE tipo_usuario
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

    public static function Modificar($tipoUsuario) //listo
    {
        $retorno = 3;
        $tipoAux = AccesoDatos::retornarObjetoActivo($tipoUsuario->id, 'tipo_usuario', 'TipoUsuario');

        if ($tipoAux != null) {
            //busco si ya existe uno con ese nombre
            $sectorAuxNombre = AccesoDatos::retornarObjetoPorCampo($tipoUsuario->nombre, 'nombre', 'tipo_usuario', 'TipoUsuario');
            $retorno = 2; 
            if ($sectorAuxNombre == null) {
                $tipoUsuario->activo = 1;
                TipoUsuario::modificarTipoUsuario($tipoUsuario);
                $retorno = 1; //se cambia el nombre
            }
        }
        return $retorno;
    }

    public static function ModificarTipoUsuario($item) //listo
    {
        try
        {
            $objAccesoDato = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDato->prepararConsulta("UPDATE tipo_usuario
                                                          SET nombre = :nombre,
                                                              activo = :activo,
                                                              actualizado = :actualizado
                                                          WHERE id = :id");
            $consulta->bindValue(':id', $item->id, PDO::PARAM_STR);
            $consulta->bindValue(':nombre', $item->nombre, PDO::PARAM_STR);
            $consulta->bindValue(':activo', $item->activo, PDO::PARAM_STR);
            $fecha = new DateTime(date("d-m-Y H:i:s"));
            $consulta->bindValue(':actualizado', date_format($fecha, 'Y-m-d H:i:s'));
            $consulta->execute();
        } catch (Throwable $mensaje) {
            printf("Error al conectar en la base de datos: <br> $mensaje .<br>");
        }
    }

    
    public static function ObtenerTodos() //listo
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT *
            FROM tipo_usuario"
        );
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'TipoUsuario');
    }


}
