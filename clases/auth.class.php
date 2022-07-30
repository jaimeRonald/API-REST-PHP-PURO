<?php 
require_once 'conexion/conexion.php';
require_once 'respuestas.class.php';

class Auth extends Conexion{

    public function login($json){
        $_respuestas= new Respuestas;
        $datos=json_decode($json,true);// true es para que lo haga asiciativo

        if(!isset($datos['usuario']) || !isset($datos['password'])){
            // error con los campos 
            return $_respuestas->error_400();
        }else{
            $usuario=$datos['usuario'];
            $password=$datos['password'];
            $password=parent::encriptar($password);// paretn es para acceder a un metod de una clase padre (Conexion)

            $datos=$this->obtenerDatosUsuario($usuario);
            if($datos){
            // si existe el usuario
                if($password==$datos[0]['Password']){
                        if($datos[0]['Estado']=='Activo'){
                            // crear el token 
                            $verificar  = $this->insertarToken($datos[0]['UsuarioId']);
                            if($verificar){
                                // si se guardo
                                $result = $_respuestas->response;
                                $result["result"] = array(
                                    "token" => $verificar
                                );
                                return $result;
                            }else{
                            return $_respuestas->error_500("Error interno , no se pudo guardar verificar token");

                            }
                        }else{
                            return $_respuestas->error_200("Usuario inactivo");

                        }
                }else{
                    return $_respuestas->error_200("El password es invalido");
                }
            }else{
                return $_respuestas->error_200('El usuario no existe ');
            }
        }

    }

    private function obtenerDatosUsuario($correo){
        $query="select usuarioId,Password,Estado from usuarios where Usuario='$correo'";
        $datos=parent::obtenerDatos($query);
        if(isset($datos[0]['usuarioId'])){
            return $datos;
        }else{
            return 0;
        }

    }

    private function insertarToken($UsuarioId){
        $val=true;
        $token =bin2hex(openssl_random_pseudo_bytes(16,$val));
        $date=date('Y-m-d H:i');
        $estado='Activo';
        $query="insert into usuarios_token (UsuarioId,Token,Estado,Fecha) values('$UsuarioId','$token','$estado','$date')";
        $verifica = parent::nonQuery($query);
        if($verifica){
            return $token;
        }else{
            return 0;
        }
    }
}