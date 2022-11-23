<?php

include_once("token/Token.php");
include_once("db/AccesoDatos.php");

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class UsuarioMW
{
    public function ValidarAutenticado($request, $handler)
    {
        try 
        {
            $header = $request->getHeaderLine('Authorization');
            if(!empty($header))
            {
                $token = trim(explode("Bearer", $header)[1]);
                $data = Token::verifyToken($token);
                if($data->tipo != null)
                {
                    return $handler->handle($request);
                }
                throw new Exception("Usuario no autorizado");
            }
            else
            {                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
                throw new Exception("Token vacío");
            }
        } 
        catch (\Throwable $th) 
        {
            $response = new Response();
            $payload = json_encode(array("mensaje" => "ERROR, ".$th->getMessage()));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');;
        }
    }

    public static function ValidarToken($request, $handler)
    {
        $header = $request->getHeaderLine('Authorization');
        $response = new Response();

        if(!empty($header))
        {
            $token = trim(explode("Bearer", $header)[1]);
            Token::verifyToken($token);
            $response = $handler->handle($request);
        }
        else
        {
            $response->getBody()->write(json_encode(array("Token error" => "No hay token.")));
            $response = $response->withStatus(401);
        }
        return  $response->withHeader('Content-Type', 'application/json');
    } 

    public function ValidarCliente($request, $handler)
    {
        try 
        {
            $header = $request->getHeaderLine('Authorization');
            if(!empty($header))
            {
                $token = trim(explode("Bearer", $header)[1]);
                $data = Token::verifyToken($token);
                if(  $data->tipo == 'cliente'  )
                {
                    echo "Cliente autorizado";
                    return $handler->handle($request);
                }
                throw new Exception("Usuario no autorizado");
            }
            else
            {                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
                throw new Exception("Token vacío");
            }
        } 
        catch (\Throwable $th) 
        {
            $response = new Response();
            $payload = json_encode(array("mensaje" => "ERROR, ".$th->getMessage()));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');;
        }
    }

    public function ValidarAdmin($request, $handler)
    {
        try 
        {
            $header = $request->getHeaderLine('Authorization');
            if(!empty($header))
            {
                $token = trim(explode("Bearer", $header)[1]);
                $data = Token::verifyToken($token);
                if($data->tipo == 'admin')
                {
                    echo "Admin autorizado";

                    return $handler->handle($request);
                }
                throw new Exception("Usuario no autorizado");
            }
            else
            {                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
                throw new Exception("Token vacío");
            }
        } 
        catch (\Throwable $th) 
        {
            $response = new Response();
            $payload = json_encode(array("mensaje" => "ERROR, ".$th->getMessage()));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');;
        }
    }

    public function ValidarMozo($request, $handler)
    {
        try 
        {
            $header = $request->getHeaderLine('Authorization');
            if(!empty($header))
            {
                $token = trim(explode("Bearer", $header)[1]);
                $data = Token::verifyToken($token);
                if($data->tipo == 1)
                {
                    echo "Mozo autorizado";

                    return $handler->handle($request);
                }
                throw new Exception("Usuario no autorizado");
            }
            else
            {                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
                throw new Exception("Token vacío");
            }
        } 
        catch (\Throwable $th) 
        {
            $response = new Response();
            $payload = json_encode(array("mensaje" => "ERROR, ".$th->getMessage()));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');;
        }
    }
    
    public function ValidarSocio($request, $handler)
    {
        try 
        {
            $header = $request->getHeaderLine('Authorization');
            if(!empty($header))
            {
                $token = trim(explode("Bearer", $header)[1]);
                $data = Token::verifyToken($token);
                if($data->tipo == 2)
                {
                    echo "Socio autorizado";

                    return $handler->handle($request);
                }
                throw new Exception("Usuario no autorizado");
            }
            else
            {                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
                throw new Exception("Token vacío");
            }
        } 
        catch (\Throwable $th) 
        {
            $response = new Response();
            $payload = json_encode(array("mensaje" => "ERROR, ".$th->getMessage()));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');;
        }
    }

    public function ValidarBartender($request, $handler)
    {
        try 
        {
            $header = $request->getHeaderLine('Authorization');
            if(!empty($header))
            {
                $token = trim(explode("Bearer", $header)[1]);
                $data = Token::verifyToken($token);
                if($data->tipo == 3)
                {
                    echo "Bartender autorizado";

                    return $handler->handle($request);
                }
                throw new Exception("Usuario no autorizado");
            }
            else
            {                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
                throw new Exception("Token vacío");
            }
        } 
        catch (\Throwable $th) 
        {
            $response = new Response();
            $payload = json_encode(array("mensaje" => "ERROR, ".$th->getMessage()));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');;
        }
    }
    public function ValidarCervecero($request, $handler)
    {
        try 
        {
            $header = $request->getHeaderLine('Authorization');
            if(!empty($header))
            {
                $token = trim(explode("Bearer", $header)[1]);
                $data = Token::verifyToken($token);
                if($data->tipo == 4)
                {
                    echo "Cervecero autorizado";

                    return $handler->handle($request);
                }
                throw new Exception("Usuario no autorizado");
            }
            else
            {                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
                throw new Exception("Token vacío");
            }
        } 
        catch (\Throwable $th) 
        {
            $response = new Response();
            $payload = json_encode(array("mensaje" => "ERROR, ".$th->getMessage()));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');;
        }
    }
    public function ValidarCocinero($request, $handler)
    {
        try 
        {
            $header = $request->getHeaderLine('Authorization');
            if(!empty($header))
            {
                $token = trim(explode("Bearer", $header)[1]);
                $data = Token::verifyToken($token);
                if($data->tipo == 5)
                {
                    echo "Cocinero autorizado";

                    return $handler->handle($request);
                }
                throw new Exception("Usuario no autorizado");
            }
            else
            {                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
                throw new Exception("Token vacío");
            }
        } 
        catch (\Throwable $th) 
        {
            $response = new Response();
            $payload = json_encode(array("mensaje" => "ERROR, ".$th->getMessage()));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');;
        }
    }
    public function ValidarRespostero($request, $handler)
    {
        try 
        {
            $header = $request->getHeaderLine('Authorization');
            if(!empty($header))
            {
                $token = trim(explode("Bearer", $header)[1]);
                $data = Token::verifyToken($token);
                if($data->tipo == 6)
                {
                    echo "Repostero autorizado";
                    return $handler->handle($request);
                }
                throw new Exception("Usuario no autorizado");
            }
            else
            {                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
                throw new Exception("Token vacío");
            }
        } 
        catch (\Throwable $th) 
        {
            $response = new Response();
            $payload = json_encode(array("mensaje" => "ERROR, ".$th->getMessage()));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');;
        }
    }


 
}
?>