<?php 
require_once 'conexion/conexion.php';
require_once 'respuestas.class.php';

class Pacientes  extends Conexion{
    private $table='pacientes';
    private $datosPaciente=[];
    private $pacienteId="";
    // private $dni="";
    // private $nombre="";
    // private $dirccion="";
    // private $codigoPostal="";
    // private $genero="";
    // private $telefono="";
    // private $fechaNacimiento="";
     private $token="";
     private $imagen="";



    public function listaPacientes($pagina=1){
        $inicio=0;
        $cantidad=5;
        if($pagina>1){
            $inicio=($cantidad*($pagina-1)+1);
            $cantidad=$cantidad*$pagina;
        }
        $query ="select PacienteId ,Nombre ,DNI,Telefono,Correo from   ".$this->table." Limit $inicio,$cantidad";
        $datos=parent::obtenerDatos($query);

        return ($datos);
    }

    public function obtenerPaciente($pacienteId){
        $query="select *from pacientes ".$this->table." where PacienteId= $pacienteId";
        return parent::obtenerDatos($query);
    }
    public function post($json){
        $_respuestas=new respuestas;
        $datos=json_decode($json,true);
        if(!isset($datos["token"])){
            // error no se recivio un token 
            return $_respuestas->error_401();
        }else{
            $this->token=$datos["token"];
            $resul=$this->buscarToken();
            if($resul){
                // return $resul;
                if(!isset($datos['Nombre']) || !isset($datos['DNI']) ||!isset($datos['Correo'])){
                    return $_respuestas->error_400();
                }else{
                    foreach($datos as $indice=>$valor){
                        if(isset($valor)){  // lo lenamos por medio de un array
                            if($indice=="imagen"){
                                $resultImg=$this->procesarImagen($datos["imagen"]);
                                // $this->imagen=$resultImg;
                                $this->datosPaciente[$indice]=$resultImg;//  al vez que seteamos el array del a clase

                              }else{
                                $this->datosPaciente[$indice]=$valor;//  al vez que seteamos el array del a clase

                              }
                
                        }
                    }
              
                    $resp=$this->insertarPaciente();

                    if($resp){
                        $respuesta=$_respuestas->response;
                        $respuesta["result"]=array(
                            "pacienteId"=>$resp
                        );
                        return $respuesta;
        
                    }else{
                        return $_respuestas->error_500();
                    }
                  
                }
            }else{
                return $_respuestas->error_401('el token no existe o esta inabilitado');
            }

        }


        

    }
    private function procesarImagen($img){
        $direccion=dirname(__DIR__)."\assets\\";
        $partes=explode(";base64,",$img);
        $extencion=explode('/',mime_content_type($img))[1];
        $imagen_base64=base64_decode($partes[1]);

        $file=$direccion.uniqid().".".$extencion;

        file_put_contents($file,$imagen_base64);

        $nuevadireccion=str_replace('\\','/',$file);
        return $nuevadireccion;


    }

    public function insertarPaciente(){
        $query="";$cadena="";
        foreach($this->datosPaciente as $indice=>$values){
            if($indice!='token'){
                if($indice!='Correo' ){
                    $cadena.=" '".$values."', ";
    
                }else{
                    $cadena.=" '".$values."' ";
                    
                }
            }
           
        }

        $query ="INSERT INTO ".$this->table."(DNI,Nombre,imagen,Correo) values(".$cadena.")" ;
        //  print_r($query);

         $resp=parent::nonQueryId($query);
         if($resp){
            return $resp;
         }else{
            return 0;
         }
       
    }

    public function put($json){
        $_respuestas=new respuestas;
        $datos=json_decode($json,true);

        // print_r($datos["datos"]);
        // print_r($datos["members"][0]["powers"][0]);
        if(!isset($datos["token"])){
            // error no se recivio un token 
            return $_respuestas->error_401();
        }else{
            if(!isset($datos["id"])){
                return $_respuestas->error_400();
            }else{
                $this->pacienteId=$datos["id"];
                foreach($datos["datos"] as $indice=>$valor){
                    if(isset($valor)){
                        $this->datosPaciente[$indice]=$valor;//  al vez que seteamos el array del a clase
                    }
                }
                $resp=$this->editarPaciente(); // SE VALIDARA EN FRONTEND QEU SEA 3 PARAMETROS (dni, correo,nombre)
                if($resp){
                    $respuesta=$_respuestas->response;
                    $respuesta["result"]=array(
                        "pacienteId"=>$this->pacienteId  // devolvera el id afectado
                    );
                    return $respuesta;
                }else{
                    return $_respuestas->error_500();
                }
            }
        }
           
        

        
    }

    public function editarPaciente(){
        $query="";$cadena="";
        foreach($this->datosPaciente as $indice=>$values){
                if($indice!='Correo'){
                    $cadena.=" ".$indice."='".$values."', ";
                }else{
                    $cadena.=" ".$indice."='".$values."' ";
               }
            
        }
        $query ="UPDATE ".$this->table." SET ".$cadena."  where PacienteId=".$this->pacienteId ;
        $resp=parent::nonQuery($query);
         if($resp>=1){
            return $resp;
         }else{
            return 0;
         }
    }


    // DELETE PACIENTAES

    public function delete($json){
        $_respuestas=new respuestas;
        $datos=json_decode($json,true);

        if(!isset($datos["id"])){
            return $_respuestas->error_400();
        }else{
            $this->pacienteId=$datos["id"];
            // foreach($datos["datos"] as $indice=>$valor){
            //     if(isset($valor)){
            //         $this->datosPaciente[$indice]=$valor;//  al vez que seteamos el array del a clase
            //     }
            // }
            $resp=$this->eliminarPaciente();
            if($resp){
                $respuesta=$_respuestas->response;
                $respuesta["result"]=array(
                    "pacienteId"=>$this->pacienteId  // devolvera el id afectado
                );
                return $respuesta;
            }else{
                return $_respuestas->error_500();
            }
        }
    }

    public function eliminarPaciente(){
        $query="";$cadena="";
         
        $query ="DELETE FROM ".$this->table."  where PacienteId=".$this->pacienteId ;
        //  print_r($query);
        $resp=parent::nonQuery($query);
         if($resp>=1){
            return $resp;
         }else{
            return 0;
         }
    }


    private function buscarToken(){
        $query ="SELECT Token from usuarios_token where Token='".$this->token."'";
        $resp=parent::obtenerDatos($query);
        if($resp>=1){
            return $resp;
        }else{
            return 0;
        }

    }

    private function actualizarToken(){
        $hora=date("Y-m-d H:m:s");
        $query ="UPDATE usuarios_token SET fecha='".$hora."'";

        $resp=parent::nonQuery($query);
        if($resp>=1){
            return $resp;
        }else{
            return 0;
        }

    }


}