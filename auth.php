<?php 
require_once 'clases/auth.class.php';
require_once 'clases/respuestas.class.php';

$_auth=new auth;
$_respuestas=new Respuestas;

if($_SERVER['REQUEST_METHOD']=='POST'){
    // ecivir los datos 

   $postBody=file_get_contents("php://input");
//    enviamos los datos al manejador
   $datosArray=$_auth->login($postBody);

//    devolvemos una respuesta
   header('Content-type:application/json');
   if(isset($datosArray['result']['error_id'])){
      $responseCode=$datosArray['result']['error_id'];
      http_response_code($responseCode);
   }else{
      http_response_code(200);
   }
    
   echo json_encode($datosArray);

}else{
    header('Content-type:application/json');
    $datosArray=$_respuestas->error_405();
    echo json_encode($datosArray);
}