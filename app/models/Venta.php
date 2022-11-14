<?php
include_once "../herramientas/herramientas.php";
class Venta
{
    public $id;
    public $mail;
    public $idCripto;
    public $fechaCompra;
    public $cantidad;
    public $foto;

    public function crearVenta()
    {
        
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "INSERT 
                INTO ventas (idCripto, mail, foto, fechaCompra cantidad) 
                VALUES (:idCripto, :mail, :foto, :fechaCompra, :cantidad)"
        );
  
        $consulta->bindValue(':idCripto', $this->idCripto, PDO::PARAM_STR);
        $consulta->bindValue(':mail',  $this->mail, PDO::PARAM_STR);
        $consulta->bindValue(':foto',  $this->foto, PDO::PARAM_STR);
        $fecha = date("Y-m-d H:i:s");
        $consulta->bindValue(':fechaCompra',  $fecha);
        $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT * 
                FROM ventas"
        );
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Venta');
    }

    public static function obtenerVenta($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT * 
                FROM ventas 
                WHERE id = :id"
        );
        $consulta->bindValue(':id', $id, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Venta');
    }

    public function modificarVenta($venta)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta(
            "UPDATE criptomonedas 
                SET idCripto = :idCripto,
                    mail=:mail , 
                    foto= :foto, 
                    fechaCompra = :fechaCompra,
                    cantidad= :cantidad 
           WHERE id = :id"
        );
        $consulta->bindValue(':id', $venta->id, PDO::PARAM_STR);
        $consulta->bindValue(':idCripto', $venta->idCripto, PDO::PARAM_STR);
        $consulta->bindValue(':mail', $venta->mail, PDO::PARAM_STR);
        $consulta->bindValue(':fechaCompra',  $venta->fechaCompra, PDO::PARAM_STR);
        $consulta->bindValue(':cantidad', $venta->cantidad, PDO::PARAM_STR);
        $consulta->bindValue(':foto', $venta->foto, PDO::PARAM_STR);
        $consulta->execute();
    }
    /*    public static function borrarVenta($idCripto)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta(
            "UPDATE criptomonedas 
                SET fechaCompra = :fechaCompra 
              WHERE idCripto = :idCripto"
        );
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':idCripto', $idCripto, PDO::PARAM_INT);
        $consulta->bindValue(':fechaCompra', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();
    }
*/

    public static function obtenerVentaPorCripto($cripto)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT ventas.mailUsuario FROM ventas 
            INNER JOIN criptomonedas ON criptomonedas.id = ventas.idCripto 
            WHERE criptomonedas.nombre = 'Bitcoin'"
        );
        $consulta->bindValue(':cripto', $cripto, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function obtenerVentaParametros($pais,$fechaInicio,$fechaFinal)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT * FROM ventas 
                    INNER JOIN criptomonedas 
                            ON ventas.idCripto = criptomonedas.id 
                            WHERE criptomonedas.nacionalidad = :pais 
                            AND ventas.fechaCompra > :fechaInicio 
                            AND ventas.fechaCompra < :fechaFinal");
        $consulta->bindValue(':pais', $pais, PDO::PARAM_STR);
        $consulta->bindValue(':fechaInicio', $fechaInicio, PDO::PARAM_STR);
        $consulta->bindValue(':fechaFinal', $fechaFinal, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Venta');
    }
    /* será nombrada por el nombre de la cripto ,el
        nombre del cliente más la fecha en la carpeta /
        FotosCripto ->cualquier usuario registrado(JWT */
   
}
