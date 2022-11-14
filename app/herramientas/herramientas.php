<?php

class Herramientas
{
    public static function  GuardarImagen($producto, $nombreFoto, $destino)
    {     
        if(!file_exists($destino))
        {
            mkdir($destino, 0777, true);
        }
        $dir = $destino.$nombreFoto;
        move_uploaded_file($producto->foto, $dir);
        return $dir;
    }

    public static function MoverImagen($nombreImagen, $antiguoDir, $nuevoDir)
    {
        if(!file_exists($nuevoDir)) 
        {
            mkdir($nuevoDir, 0777, true);
        }
        rename($antiguoDir.$nombreImagen, $nuevoDir.$nombreImagen);
        return $nuevoDir.$nombreImagen;
    } 
}





?>