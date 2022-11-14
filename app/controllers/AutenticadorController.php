<?php
require_once './models/AutentificadorJWT.php';
require_once './interfaces/IApiUsable.php';

class AutentificadorController extends AutentificadorJWT
{
    public function CrearTokenLogin ($request, $response,$args)
    {
        $parametros = $request->getParsedBody();
        $usuarioEnBD = Usuario::obtenerUsuario($parametros["mail"]);
        // echo $parametros["mail"];
        if($usuarioEnBD !=null)
        {
            if(password_verify($parametros["clave"],$usuarioEnBD->clave))
            {
                $datos = array(
                    'mail' => $usuarioEnBD->mail, 
                    'clave' => $usuarioEnBD->clave,
                    "perfil"=> $usuarioEnBD->perfil
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

