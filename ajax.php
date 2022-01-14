<?php

    include "./instalacion/conexion.php";

    header("Content-Type: text/html;charset=utf-8");
    
    $datos = array();

    if(isset($_POST['scliente'])){

        $nombre = $_POST['scliente'];
        $consulta = $conect->prepare("SELECT * FROM cliente WHERE nombre LIKE CONCAT('%',:nombre,'%') AND estado = 1");
        $consulta->execute(['nombre'=>$nombre]);
        
        while ($row = $consulta->fetch(PDO::FETCH_ASSOC)) {
            $datos[] = array("telefono"=>$row['telefono'],"correo"=>$row['email'],"direccion"=>$row['provincia'].','.$row['distrito'],"label"=>$row['nombre'],"id"=>$row['id_cliente']);
        }
    
        echo json_encode($datos);

    }
    else if(isset($_POST['sprod'])){

        $codigo = $_POST['sprod'];
        $consulta = $conect->prepare("SELECT * FROM producto WHERE codigo LIKE CONCAT('%',:codigo,'%') AND stock > 0 AND estado = 1 ");
        $consulta->execute(['codigo'=>$codigo]);
        
        while ($row = $consulta->fetch(PDO::FETCH_ASSOC)) {
            $datos[] = array("id_producto"=>$row['id_producto'],"label"=>$row['codigo'],"value"=>$row['descripcion'],"precio"=>$row['precio'],"stock"=>$row['stock']);
        }
    
        echo json_encode($datos);
    }
    else{
        header("Location: login.php");
    }


?>