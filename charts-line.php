<?php
    include "./instalacion/conexion.php";
    
        $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

        if ($contentType === "application/json") {
    
          $content = trim(file_get_contents("php://input"));
        
          $decoded = json_decode($content, true);
    
          if(is_array($decoded)) {

            $consulta = $conect->prepare("SELECT year(fecha) AS ano,MONTH(fecha) AS mes,COUNT(id_factura) AS total
            FROM factura
            GROUP BY YEAR(fecha),MONTH(fecha)
            ORDER BY YEAR(fecha),MONTH(fecha)");
            $consulta->execute();
            $ventas = $consulta->fetchAll();
    
            echo json_encode($ventas);

          } else {
             echo json_encode('ERROR!');
          }
         }

?>