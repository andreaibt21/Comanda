<?php
class AccesoDatos
{
    private static $objAccesoDatos;
    private $objetoPDO;

    private function __construct()
    {
        try {
            $this->objetoPDO = new PDO('mysql:host=' . $_ENV['MYSQL_HOST'] . ';dbname=' . $_ENV['MYSQL_DB'] . ';charset=utf8', $_ENV['MYSQL_USER'], $_ENV['MYSQL_PASS'], array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            $this->objetoPDO->exec("SET CHARACTER SET utf8");
        } catch (PDOException $e) {
            print "Error: " . $e->getMessage();
            die();
        }
    }

    public static function obtenerInstancia()
    {
        if (!isset(self::$objAccesoDatos)) {
            self::$objAccesoDatos = new AccesoDatos();
        }
        return self::$objAccesoDatos;
    }

    public function prepararConsulta($sql)
    {
        return $this->objetoPDO->prepare($sql);
    }

    public function obtenerUltimoId()
    {
        return $this->objetoPDO->lastInsertId();
    }

    public function __clone()
    {
        trigger_error('ERROR: La clonación de este objeto no está permitida', E_USER_ERROR);
    }
    public static function obtenerTodos($tabla, $clase)
    {
        $sql = "SELECT * FROM $tabla;";
        return AccesoDatos::ObtenerConsulta($sql, $clase);
    }

    public static function ObtenerConsulta($sql, $clase = null)
    {
        try {
            $conexion = AccesoDatos::obtenerInstancia();
            $consulta = $conexion->prepararConsulta($sql);
            $consulta->execute();
            $retorno = $consulta->fetchAll(PDO::FETCH_CLASS, $clase);
        } catch (Throwable $mensaje) {
            printf("Error de la Base de datos: <br> $mensaje .<br>");
        } finally {
            return $retorno;
        }
    }

    public static function retornarObjetoActivoPorCampo($valor, $campo, $tabla, $clase)
    {
        $sql =
            "SELECT *
             FROM $tabla
             WHERE $tabla.$campo = '$valor'
             AND $tabla.activo = 1";
        return AccesoDatos::ObtenerConsulta($sql, $clase);
    }

    public static function retornarObjetoPorCampo($valor, $campo, $tabla, $clase)
    {
        $sql = "SELECT *
                 FROM $tabla
                 WHERE $tabla.$campo = '$valor'";
        return AccesoDatos::ObtenerConsulta($sql, $clase);
    }
    public static function retornarObjetoActivo($id, $tabla, $clase)
    {
        $sql = "SELECT * 
                FROM $tabla 
                WHERE  $tabla.id = $id
                AND $tabla.activo = 1";

        return AccesoDatos::ObtenerConsulta($sql, $clase);
    }
    public static function ObtenerPedidosPorSector($sector)
    {
        $sql = "SELECT  pedido_producto.id,
                        pedido_producto.id_pedido AS pedido, 
                        producto.nombre AS producto, 
                        pedido_producto.cantidad AS cantidad, 
                        CASE 
                        WHEN pedido_producto.estado = 0 THEN 'Pendiente' 
                        WHEN pedido_producto.estado = 1 THEN 'En preparación' 
                        WHEN pedido_producto.estado = 2 THEN 'Listo' 
                        ELSE 'Error' end
                        as Estado 
                FROM pedido_producto pedido_producto 
                    LEFT JOIN producto  ON pedido_producto.id_producto = producto.id
                    LEFT JOIN sector ON producto.id_sector = sector.id
                WHERE sector.id = $sector and pedido_producto.estado < 2
                ORDER BY pedido_producto.id_pedido, pedido_producto.creado";

          $conexion = AccesoDatos::obtenerInstancia();
          $consulta = $conexion->prepararConsulta($sql);
          //var_dump($consulta);
          $consulta->execute();
        return $consulta->fetchAll();

    }
}
