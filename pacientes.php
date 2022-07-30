<?php 

require_once 'clases/pacientes.class.php';
require_once 'clases/respuestas.class.php';

$_respuestas=new Respuestas;
$_pacientes=new Pacientes;

if($_SERVER['REQUEST_METHOD']=='GET'){
        if(isset($_GET['page'])){
            $pagina=$_GET['page'];
            $listaPacientes=$_pacientes->listaPacientes($pagina);
            header('Content-Type:application/json');
            echo json_encode($listaPacientes);
            http_response_code(200);

        }else if(isset($_GET['id'])){
            $PACIENTEid=$_GET['id'];
            $datosPaciente=$_pacientes->obtenerPaciente($PACIENTEid);
            header('Content-Type:application/json');
            echo json_encode($datosPaciente);
            http_response_code(200);

        }
}else{
    if($_SERVER['REQUEST_METHOD']=='POST'){
        // RECIVIMOS LOS DATOS ENVIADOS 
        $postBody=file_get_contents('php://input');
        // ENVAMSO LOS DATOS AL MANEJADOR 
        $datosPaciente=$_pacientes->post($postBody);
        // devolvemos una respuesta
        header('Content-type:application/json');
        if(isset($datosPaciente['result']['error_id'])){
            $responseCode=$datosPaciente['result']['error_id'];
            http_response_code($responseCode);
        }else{
            http_response_code(200);
        }
        echo json_encode($datosPaciente);
        // print_r($resp);
        // return param
    }else{
        if($_SERVER['REQUEST_METHOD']=='PUT'){
           // RECIVIMOS LOS DATOS ENVIADOS 
            $postBody=file_get_contents('php://input');
              // ENVAMSO LOS DATOS AL MANEJADOR 
            $datosPaciente=$_pacientes->put($postBody);
            header('Content-type:application/json');

            if(isset($datosPaciente['result']['error_id'])){
                $responseCode=$datosPaciente['result']['error_id'];
                http_response_code($responseCode);
            }else{
                http_response_code(200);
            }
            echo  json_encode($datosPaciente);
            // print_r($datosPaciente);

        }else{
            if($_SERVER['REQUEST_METHOD']=='DELETE'){

                $header=getallheaders();
                if(isset($header["id"]) && isset($header["token"])){// si lo recibe por headers 
                    $data=[
                        "token"=>$header["token"],
                        "id"=>$header["id"]
                    ];
                    $postBody=json_encode($data);
                }else{
                    $postBody=file_get_contents('php://input');// si lo recive por boby como ya como json 

                }
                
                // ENVAMSO LOS DATOS AL MANEJADOR 
              $datosPaciente=$_pacientes->delete($postBody);
              header('Content-type:application/json');
  
              if(isset($datosPaciente['result']['error_id'])){
                  $responseCode=$datosPaciente['result']['error_id'];
                  http_response_code($responseCode);
              }else{
                  http_response_code(200);
              }
              echo  json_encode($datosPaciente);
            }else{
                header('Content-type:application/json');
                $datosArray=$_respuestas->error_405();
                echo json_encode($datosArray);
            }
           
        }
    }
}