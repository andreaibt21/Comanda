<?php
include_once "db/AccesoDatos.php";

class Cliente
{
    public $id;
    public $nombre;
    public $activo;
    public $creado;
    public $actualizado;

    // public function __construct($nombre)
    // {
    //     $this->nombre = $nombre;
    // }

    public static function crearCliente($cliente)//listo
    {
        $retorno = null;
        try
        {

            $clienteAux = AccesoDatos::retornarObjetoPorCampo($cliente->nombre, "nombre", "cliente", "Cliente");
            // var_dump( "clienteAux");
            // var_dump( $clienteAux);
            if ($clienteAux == null) {

                $objAccesoDatos = AccesoDatos::obtenerInstancia();
                $consulta = $objAccesoDatos->prepararConsulta(
                    "INSERT INTO cliente (nombre, activo, creado, actualizado)
                 VALUES (:nombre, :activo, :creado, :actualizado)");
                $consulta->bindValue(':nombre', $cliente->nombre, PDO::PARAM_STR);
                $consulta->bindValue(':activo', '1', PDO::PARAM_STR);
                $fecha = new DateTime(date("d-m-Y H:i:s"));
                $consulta->bindValue(':creado', date_format($fecha, 'Y-m-d H:i:s'));
                $consulta->bindValue(':actualizado', date_format($fecha, 'Y-m-d H:i:s'));
                $consulta->execute();
                //  $retorno = $objAccesoDatos->obtenerUltimoId();

                $retorno = 1;
            } else {
                Cliente::modificarCliente($cliente);
                $retorno = 2;

            }
        } catch (Throwable $mensaje) {
            printf("Error al conectar en la base de datos: <br> $mensaje .<br>");
        } finally {
            return $retorno;
        }
    }

    public static function modificarCliente($cliente) //listo
    {
        try
        {
            $retorno = 3;
            $clienteAux = AccesoDatos::retornarObjetoPorCampo($cliente->nombre, "nombre", "cliente", "Cliente");
            // $clienteAux = AccesoDatos::retornarObjetoActivo($cliente->id, 'cliente', 'Cliente');
            if ($clienteAux !== null) {
                // var_dump($clienteAux);
                $clienteAuxNombre = AccesoDatos::retornarObjetoPorCampo($cliente->nombre, 'nombre', 'cliente', 'Cliente');
                $retorno = 2;
                $objAccesoDato = AccesoDatos::obtenerInstancia();
                if ($clienteAuxNombre == null) {$consulta = $objAccesoDato->prepararConsulta(
                    "UPDATE cliente
                    SET nombre = :nombre,
                        activo = :activo,
                        actualizado = :actualizado
                    WHERE id = :id");
                    $consulta->bindValue(':id', $cliente->id, PDO::PARAM_STR);
                    $consulta->bindValue(':nombre', $cliente->nombre, PDO::PARAM_STR);
                    $consulta->bindValue(':activo', $cliente->activo, PDO::PARAM_STR);
                    $fecha = new DateTime(date("d-m-Y H:i:s"));
                    $consulta->bindValue(':actualizado', date_format($fecha, 'Y-m-d H:i:s'));
                    $consulta->execute();
                    $retorno = 1;
                }
            }
        } catch (Throwable $mensaje) {
            printf("Error al conectar en la base de datos: <br> $mensaje .<br>");
        } finally {
            return $retorno;

        }

    }

    
    public static function obtenerTodos()//listo
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT *
            FROM cliente"
        );
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Cliente');
    }



}
