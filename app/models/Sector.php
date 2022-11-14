<?php

class Sector
{
    public $id;
    public $nombre;
    public $activo;
    public $creado;
    public $actualizado;


    public function crearSector()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "INSERT 
                INTO sector (nombre, activo, creado, actualizado) 
                VALUES (:nombre, :activo, :creado, actualizado)"
        );

        $fecha = new DateTime( date("d-m-Y") );
        $consulta->bindValue(':creado'     , date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->bindValue(':actualizado', date_format($fecha, 'Y-m-d H:i:s'));

        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':activo', $this->activo, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT * 
            FROM sector"
        );
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Sector');
    }
   /*
    public static function obtenerSectorPorTipo($tipo)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT * FROM usuario 
            WHERE tipo = :tipo"
        );
        $consulta->bindValue(':tipo', $tipo, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS,'Sector');
    }
    public static function obtenerSector($mail)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT * FROM usuario 
            WHERE mail = :mail"
        );
        $consulta->bindValue(':mail', $mail, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Sector');
    }

    public function modificarSector($usuario)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta(
            "UPDATE usuario 
                SET tipo = :tipo,
                    clave = :clave,
                    fechaBaja = :fechaBaja
                WHERE mail = :mail"
        );
        if ($usuario->tipo != null) {
            $consulta->bindValue(':tipo', $usuario->tipo, PDO::PARAM_STR);
        } else {
            $consulta->bindValue(':tipo', null, PDO::PARAM_STR);
        }
        if ($usuario->fechaBaja != null) {
            $consulta->bindValue(':fechaBaja', $usuario->fechaBaja, PDO::PARAM_INT);
        } else {
            $consulta->bindValue(':fechaBaja', null, PDO::PARAM_INT);
        }
        $claveHash = password_hash($usuario->clave, PASSWORD_DEFAULT);
        $consulta->bindValue(':mail', $usuario->mail, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $claveHash, PDO::PARAM_STR);

        $consulta->execute();
    }

    public static function borrarSector($id)
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
    }*/
}
