<?php
require_once './models/AutentificadorJWT.php';
require_once './interfaces/IApiUsable.php';

class AutentificadorController extends AutentificadorJWT
{
    public function CrearTokenLogin ($request, $response,$args)
    {
        $parametros = $request->getParsedBody();
        $usuarioEnBD = Usuario::obtenerUsuario($parametros["id"]);
      
        if($usuarioEnBD !=null)
        {
            if(password_verify($parametros["clave"],$usuarioEnBD->clave))
            {
                $datos = array(
                    'dni' => $usuarioEnBD->dni, 
                    'clave' => $usuarioEnBD->clave,
                    "tipo"=> $usuarioEnBD->tipo
                );

                $token = AutentificadorJWT::CrearToken($datos);
                $payload = json_encode(array('jwt' => $token));
                $response->getBody()->write($payload);

            }else{
                $response->getBody()->write("Error en los datos ingresados");
            }
        }else{
            $response->getBody()->write("El usuario no existe");
        }

        return $response
        ->withHeader('Content-Type', 'application/json');
    }
}
?>

