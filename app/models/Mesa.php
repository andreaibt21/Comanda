<?php
require_once './herramientas/herramientas.php';
class Mesa
{
    public $id;
    public $nombre;
    public $activo;
    public $creado;
    public $actualizado;

    public static function crearMesa($mesa)
    {
        $retorno = 0;
        $mesaAux = AccesoDatos::retornarObjetoPorCampo($mesa->nombre, "nombre", "mesa", "Mesa");
        if ($mesaAux == null) {

            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta(
                "INSERT INTO mesa (nombre, activo, creado, actualizado)
                           VALUES (:nombre, :activo, :creado, :actualizado)");
            $consulta->bindValue(':nombre', $mesa->nombre, PDO::PARAM_STR);
            $consulta->bindValue(':activo', '1', PDO::PARAM_STR);
            $fecha = new DateTime(date("d-m-Y H:i:s"));
            $consulta->bindValue(':creado', date_format($fecha, 'Y-m-d H:i:s'));
            $consulta->bindValue(':actualizado', date_format($fecha, 'Y-m-d H:i:s'));
            $consulta->execute();
            $retorno = 1;
        } else {
            $mesaAux[0]->activo = 1;
            Mesa::Modificar($mesaAux[0]);
            $retorno = 2;
        }
        return $retorno;

    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT *
                FROM mesa"
        );
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'PedidoXProducto');
    }

    public static function Modificar($mesa)
    {
        $retorno = 3;
        $mesaAux = AccesoDatos::retornarObjetoActivo($mesa->id, 'mesa', 'Mesa');

        if ($mesaAux != null) {
            $mesaAuxNombre = AccesoDatos::retornarObjetoPorCampo($mesa->nombre, 'nombre', 'mesa', 'Mesa');
            $retorno = 2; //es el mismo nombre
            if ($mesaAuxNombre == null) {
                $mesa->activo = 1;
                $objAccesoDato = AccesoDatos::obtenerInstancia();
                $consulta = $objAccesoDato->prepararConsulta(
                    "UPDATE mesa
                        SET nombre = :nombre,
                            activo = :activo,
                            updated_at = :updated_at
                        WHERE id = :id");
                $consulta->bindValue(':id', $item->id, PDO::PARAM_STR);
                $consulta->bindValue(':nombre', $item->nombre, PDO::PARAM_STR);
                $consulta->bindValue(':activo', $item->activo, PDO::PARAM_STR);
                $fecha = new DateTime(date("d-m-Y H:i:s"));
                $consulta->bindValue(':updated_at', date_format($fecha, 'Y-m-d H:i:s'));
                $consulta->execute();
                $retorno = 1; //se cambia el nombre
            }
        }
        return $retorno;
    }

    public static function EstaOcupada($mesa)
    {
        $retorno = 0;
        $sql = "SELECT *
                FROM pedido
               WHERE id_mesa = $mesa->id AND pedido.activo = '1' AND pedido.estado < '4';";

        $mesasAux = AccesoDatos::ObtenerConsulta($sql);
        if (sizeof($mesasAux) > 0) {
            $retorno = 1;
        }
        return $retorno;
    }

    public static function BorrarMesa($mesa)
    {
        $retorno = 0;
        $mesaAux = AccesoDatos::retornarObjetoActivo($id, 'mesa', 'Mesa');
        if ($mesaAux != null) {
            $estaOcupada = Mesa::EstaOcupada($mesaAux[0]);
            $retorno = 2;
            if ($estaOcupada == 0) {
                $consulta = $conexion->prepararConsulta(
                    "UPDATE mesa
                                SET activo = :activo , actualizado = :actualizado
                                WHERE id = $id");
                $fecha = new DateTime(date("d-m-Y"));
                $consulta->bindValue(':actualizado', date_format($fecha, 'Y-m-d H:i:s'));
                $consulta->bindValue(':activo', '0', PDO::PARAM_STR);
                $consulta->execute();
                $retorno = 1;
            }
        }
        return $retorno;

    }
}
