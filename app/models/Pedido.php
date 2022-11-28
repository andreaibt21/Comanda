<?php
require_once './herramientas/herramientas.php';
class Pedido
{
    public $id;
    public $id_mesa;
    public $id_cliente;
    public $id_usuario;
    public $foto;
    public $creado;
    public $fecha_prevista;
    public $fecha_fin;
    public $precio_final;
    public $actualizado;
    public $activo;
    public $estado;
    // 1 - "con cliente esperando pedido”
    // 2 - ”con cliente comiendo”
    // 3 - “con cliente pagando”
    // 4 - “cerrada”.

    public static function crearPedido($pedido) //listo
    {

        $retorno = 0;
        $idMesaAux = AccesoDatos::retornarObjetoActivoPorCampo($pedido->id_mesa, 'id_mesa', 'pedido', 'Pedido');
        if (sizeof($idMesaAux) == 0) {
            $idUsuarioAux = AccesoDatos::retornarObjetoActivoPorCampo($pedido->id_usuario, 'id', 'usuario', 'Usuario');
            $retorno = 2;
            if (sizeof($idUsuarioAux) > 0) {
                $objAccesoDatos = AccesoDatos::obtenerInstancia();
                $consulta = $objAccesoDatos->prepararConsulta(
                    "INSERT
                        INTO  pedido (id_mesa, id_cliente, id_usuario, estado, creado, fecha_prevista, actualizado, activo)
                        VALUES (:id_mesa, :id_cliente, :id_usuario, :estado, :creado, :fecha_prevista, :actualizado, :activo)");
                $consulta->bindValue(':id_mesa', $pedido->id_mesa, PDO::PARAM_STR);
                $consulta->bindValue(':id_cliente', $pedido->id_cliente, PDO::PARAM_STR);
                $consulta->bindValue(':id_usuario', $pedido->id_usuario, PDO::PARAM_STR);
                $consulta->bindValue(':estado', '1', PDO::PARAM_STR);
                $consulta->bindValue(':activo', '1', PDO::PARAM_STR);
                $fecha = new DateTime(date("d-m-Y H:i:s"));
                $consulta->bindValue(':creado', date_format($fecha, 'Y-m-d H:i:s'));
                $consulta->bindValue(':actualizado', date_format($fecha, 'Y-m-d H:i:s'));
                $fecha_prevista = $fecha->modify('+' . $pedido->fecha_prevista . ' minutes');
                $consulta->bindValue(':fecha_prevista', date_format($fecha_prevista, 'Y-m-d H:i:s'), PDO::PARAM_STR);
                $consulta->execute();

                $retorno = 1;
            }
        }
        return $retorno;

    }

    public static function Modificar($pedido) //listo
    {
        $retorno = 0;
        $pedidoAux = AccesoDatos::retornarObjetoActivo($pedido->id, 'pedido', 'Pedido');
        if (sizeof($pedidoAux) > 0) { //($valor, $campo, $tabla, $clase)
            // var_dump($pedidoAux);
            $mesaAux = AccesoDatos::retornarObjetoActivoPorCampo($pedido->id_mesa, 'id', 'pedido', 'Pedido');
            $mesaAuxEnMesa = AccesoDatos::retornarObjetoActivoPorCampo($pedido->id_mesa, 'id', 'mesa', 'Mesa');
            $retorno = 1;
            //var_dump($mesaAuxEnMesa);
            if ($mesaAux == null && $mesaAuxEnMesa != null) {
                $idUsuarioAux = AccesoDatos::retornarObjetoActivoPorCampo($pedido->id_usuario, 'id', 'usuario', 'Usuario');
                // var_dump(  $idUsuarioAux);
                // var_dump( $pedido->id_usuario ."   id ");

                $retorno = 3;
                if (sizeof($idUsuarioAux) > 0) {
                    $objAccesoDato = AccesoDatos::obtenerInstancia();
                    $consulta = $objAccesoDato->prepararConsulta(
                        "UPDATE pedido
                                SET id_mesa = :id_mesa,
                                    id_cliente = :id_cliente,
                                    id_usuario = :id_usuario,
                                    estado = :estado,
                                    fecha_fin = :fecha_fin,
                                    precio_final = :precio_final,
                                    activo = :activo,
                                    foto = :foto,
                                    actualizado = :actualizado
                                WHERE id = :id");
                    $pedidoAux[0]->id_mesa = $pedido->id_mesa;
                    $pedidoAux[0]->id_usuario = $pedido->id_usuario;
                    $consulta->bindValue(':id', $pedidoAux[0]->id, PDO::PARAM_STR);
                    $consulta->bindValue(':id_mesa', $pedidoAux[0]->id_mesa, PDO::PARAM_STR);
                    $consulta->bindValue(':id_cliente', $pedidoAux[0]->id_cliente, PDO::PARAM_STR);
                    $consulta->bindValue(':id_usuario', $pedidoAux[0]->id_usuario);
                    $consulta->bindValue(':estado', $pedidoAux[0]->estado, PDO::PARAM_STR);
                    $consulta->bindValue(':fecha_fin', $pedidoAux[0]->fecha_fin);
                    $consulta->bindValue(':precio_final', $pedidoAux[0]->precio_final);
                    $consulta->bindValue(':activo', $pedidoAux[0]->activo, PDO::PARAM_STR);
                    $consulta->bindValue(':foto', $pedidoAux[0]->foto, PDO::PARAM_STR);
                    $fecha = new DateTime(date("d-m-Y H:i:s"));
                    $consulta->bindValue(':actualizado', date_format($fecha, 'Y-m-d H:i:s'));
                    $consulta->execute();
                    //var_dump($pedidoAux[0]);
                    $retorno = 2;
                }
            }
        }
        return $retorno;
    }

    public static function ObtenerTodos() //listo
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT *
            FROM pedido"
        );
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function borrarPedido($id) //listo
    {
        $retorno = 0;
        $sectorAux = AccesoDatos::retornarObjetoActivo($id, 'pedido', 'Pedido');

        if ($sectorAux != null) {

            $conexion = AccesoDatos::obtenerInstancia();
            $consulta = $conexion->prepararConsulta(
                "UPDATE pedido
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

    public static function CambiarEstado($idPedido, $estado) //listo
    {
        // 1 - "con cliente esperando pedido” - estado inicial
        
        $pedido = AccesoDatos::retornarObjetoActivo($idPedido, 'pedido', 'Pedido');
        // 2 - ”con cliente comiendo”
        $pedido[0]->estado = $estado; //comiendo
        $retorno = 'Mesa pasada a comiendo.';
        switch ($estado) {
            case 3:
                // 3 - “con cliente pagando”
                $pedido[0]->precio_final =  Pedido::CalcularPrecioFinal($idPedido); //calcular precio
                $retorno = $pedido[0]->precio_final;
                break;
                case 4:
                    // 4 - “cerrada”.
                $fecha = new DateTime(date("d-m-Y H:i:s"));
                $pedido[0]->fecha_fin = $fecha->format("Y-m-d H:i:s");
                $retorno = 'Mesa cerrada';
                break;

        }
         $objAccesoDato = AccesoDatos::obtenerInstancia();
         $consulta = $objAccesoDato->prepararConsulta(
             "UPDATE pedido
                     SET 
                         estado = :estado,
                         fecha_fin = :fecha_fin,
                         precio_final = :precio_final,
                         actualizado = :actualizado
                     WHERE id = :id");
   
         $consulta->bindValue(':id', $pedido[0]->id, PDO::PARAM_STR);
         $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
         $consulta->bindValue(':fecha_fin', $pedido[0]->fecha_fin);
         $consulta->bindValue(':precio_final', $pedido[0]->precio_final);
         $fecha = new DateTime(date("d-m-Y H:i:s"));
         $consulta->bindValue(':actualizado', date_format($fecha, 'Y-m-d H:i:s'));
         $consulta->execute();

        return $retorno;
    }

    public static function CalcularPrecioFinal($idPedido) //hacerpedidoproducto
    {
        $precio = 0;

        $sql = "SELECT SUM(precio) 
                    AS precio_final 
                    FROM pedido_producto
                    WHERE id_pedido = $idPedido;"; 
        $conexion = AccesoDatos::obtenerInstancia();
        $consulta = $conexion->prepararConsulta($sql);
        $consulta->execute();
        $precio = $consulta->fetch(); 
        //echo "precio  " . $precio;
        return $precio[0];
    }

}
