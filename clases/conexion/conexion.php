<?php 

class Conexion{
    private $server;
    private $user;
    private $password;
    private $database;
    private $port;
    private $conexion;

    
    function __construct(){
        $listadatos = $this->datosConexion();
        foreach ($listadatos as $key => $value) {
            $this->server = $value['server'];
            $this->user = $value['user'];
            $this->password = $value['password'];
            $this->database = $value['database'];
            $this->port = $value['port'];
        }
        $this->conexion = new mysqli($this->server,$this->user,$this->password,$this->database,$this->port);
        if($this->conexion->connect_errno){
            echo "algo va mal con la conexion";
            die();
        }

    }

    private function datosConexion(){
        $direccion = dirname(__FILE__);
        $jsondata = file_get_contents($direccion . "/" . "config");
        return json_decode($jsondata, true);
    }

    public function jaime(){
        if ($result =  $this->conexion->query("select *from usuarios")) {

            /* fetch associative array */
            while ($row = $result->fetch_assoc()) {
                $field1name = $row["Usuario"];
                $field2name = $row["Password"];
                echo $field1name.'<br />';
                echo $field2name.'<br />';
            }
        
            /* free result set */
            $result->free();
        }
    }
    private function convertirUTF8($array){
        array_walk_recursive($array,function(&$item,$key){
            if(!mb_detect_encoding($item,'utf-8',true)){
                $item = utf8_encode($item);
            }
        });
        return $array;
    }


    public function obtenerDatos($sqlstr){
        // $results = $this->conexion->query($sqlstr);
        $resultArray = array();
        // foreach ($results as $key) {
        //     $resultArray[] = $key;
        // }
        if ($result =  $this->conexion->query($sqlstr)) {

            /* fetch associative array */
            while ($row = $result->fetch_assoc()) {
                $resultArray[] = $row;
                // $regitr[]=$row["Usuario"],$row["Password"];
                // array_push($resultArray,)
                // $field1name = $row["Usuario"];
                // $field2name = $row["Password"];
               
            }
        
            /* free result set */
            $result->free();
        }
        return $resultArray;

    }

    public function nonQuery($slqstr){// nos devuelve la fila acetada
        $result=$this->conexion->query($slqstr);
        return $this->conexion->affected_rows;
    }

    public function nonQueryId($slqstr){// usado para los insert 
        $result=$this->conexion->query($slqstr);
        $filas=$this->conexion->affected_rows;
        if($filas >=1){// si hy una fila acetada devuelta respuesta de ñla base de datos 
            return $this->conexion->insert_id;
        }else{
            return 0;
        }
    }


    // encriptar

    protected function encriptar($string){
        return md5($string);
    }





}

