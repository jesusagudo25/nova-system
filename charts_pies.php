<?php
    include "./instalacion/conexion.php";
    
        $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

        if ($contentType === "application/json") {
    
          $content = trim(file_get_contents("php://input"));
        
          $decoded = json_decode($content, true);
    
          if(is_array($decoded)) {

            $consulta = $conect->prepare("SELECT c.nombre, COUNT(p.id_categoria) AS ventas FROM detalle d INNER JOIN producto p ON d.id_producto = p.id_producto INNER JOIN categoria c ON p.id_categoria = c.id_categoria GROUP BY c.id_categoria ORDER BY ventas DESC LIMIT 3");
            $consulta->execute();
            $categorias = $consulta->fetchAll();
    
            echo json_encode($categorias);

          } else {
             echo json_encode('ERROR!');
          }
         }

?>