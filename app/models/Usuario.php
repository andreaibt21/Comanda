<?php
include_once "db/AccesoDatos.php";

class Usuario
{
    public $id;
    public $dni;
    public $tipo;
    public $clave;

    public $activo;
    public $creado;
    public $actualizado;

    public static function crearUsuario($usuario) //listo
    {

        $retorno = -1; //valor -- campo -- tabla -- clase
        $tipoAux = AccesoDatos::retornarObjetoActivoPorCampo($usuario->tipo, "id", "tipo_usuario", "TipoUsuario");
        $usuarioAux = AccesoDatos::retornarObjetoPorCampo($usuario->dni, "dni", "usuario", "Usuario");
        if ($tipoAux == null) {
            $retorno = 0;
        } else {
            if ($usuarioAux != null) {
                $usuarioAux[0]->tipo = $tipoAux[0]->id;
                Usuario::Modificar($usuarioAux[0]);
                $retorno = 1;
            } else {
                $usuario->tipo = $tipoAux[0]->id;
                //var_dump($usuario->tipo);
                $objAccesoDatos = AccesoDatos::obtenerInstancia();
                $consulta = $objAccesoDatos->prepararConsulta(
                    "INSERT 
                        INTO usuario (dni, clave, tipo, activo, creado, actualizado)
                          VALUES (:dni, :clave, :tipo, :activo, :creado, :actualizado) ");
                $claveHash = password_hash($usuario->clave, PASSWORD_DEFAULT);
                $consulta->bindValue(':dni', $usuario->dni, PDO::PARAM_STR);
                $consulta->bindValue(':clave', $claveHash);
                $consulta->bindValue(':tipo', $usuario->tipo, PDO::PARAM_STR);
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

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT *
            FROM usuario"
        );
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Usuario');
    }

    public static function obtenerUsuarioPorTipo($tipo)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT * FROM usuario
            WHERE tipo = :tipo"
        );
        $consulta->bindValue(':tipo', $tipo, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Usuario');
    }

    public static function obtenerUsuario($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT * FROM usuario
            WHERE id = :id"
        );
        $consulta->bindValue(':id', $id, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Usuario');
    }

    public static function borrarUsuario($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta(
            "UPDATE usuario
                SET fechaBaja = :fechaBaja
                WHERE id = :id"
        );
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();
    }

    public static function Modificar($usuario)
    {
        $retorno = 3;

        $usuarioAux = AccesoDatos::retornarObjetoActivo($usuario->id, 'usuario', 'Usuario');
        if ($usuarioAux != null) {
            //busco si ya existe uno con ese DNI
            $usuarioAuxDNI = AccesoDatos::retornarObjetoPorCampo($usuario->dni, 'dni', 'usuario', 'Usuario');
            $retorno = 2;
            if ($usuarioAuxDNI == null || $usuarioAuxDNI[0]->id == $usuario->id) {
                $usuario->activo = 1;
                $objAccesoDato = AccesoDatos::obtenerInstancia();
                $consulta = $objAccesoDato->prepararConsulta(
                    "UPDATE usuario
                        SET
                            dni = :dni,
                            clave = :clave,
                            tipo = :tipo,
                            activo = :activo,
                            actualizado = :actualizado
                        WHERE id = :id"
                );

                $consulta->bindValue(':id', $usuario->id, PDO::PARAM_STR);
                $consulta->bindValue(':dni', $usuario->dni, PDO::PARAM_STR);
                $claveHash = password_hash($usuario->clave, PASSWORD_DEFAULT);
                $consulta->bindValue(':clave', $claveHash, PDO::PARAM_STR);
                $consulta->bindValue(':tipo', $usuario->tipo, PDO::PARAM_STR);
                $consulta->bindValue(':activo', $usuario->activo, PDO::PARAM_STR);
                $fecha = new DateTime(date("d-m-Y H:i:s"));
                $consulta->bindValue(':actualizado', date_format($fecha, 'Y-m-d H:i:s'));
                $consulta->execute();
                //cambio el nombre
                $retorno = 1;
            }
        }
        return $retorno;
    }

}
