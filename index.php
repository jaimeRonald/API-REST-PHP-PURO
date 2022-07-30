<?php 
// require_once 'clases/conexion/conexion.php';

// $conexion=new Conexion;

// $sql="insert into pacientes(DNI) values('2393939333')";
// var_dump($conexion->nonQuery($sql));



 
?>

<style>
    .clip{
        position: relative;
        display: grid;
        place-items: center;

        width: 90px;
        height: 90px;
    }
    .clip img{
        width: 90%;
        height: 90%;
    }
    .clip:before,
    .clip::after{
        content:"";
        position:absolute;
        left: 0;
        top: 0;
        width: 110%;
        height: 110%;
        margin: -5%;
        box-shadow: inset 0 0 0 2px #40c328;
        animation: clipMe 3s linear infinite;
    }

    .clip::before{
        animation-delay: -1.5s;
    }
    @keyframes clipMe{
        0%, 100% { clip:rect(0 ,100px ,2px,0);}
        25%{clip: rect(0, 24px,100px,0); }    
        50%{clip:rect(98px,100px,100px,0);}
        75%{clip:rect(0,100px,100px,98px);}
    }
</style>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="clip">
        <img src="assets/logo.png" alt="">
    </div>
</body>
</html>