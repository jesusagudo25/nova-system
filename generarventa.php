<?php

    include "./instalacion/conexion.php";

    $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

    if ($contentType === "application/json") {

        $content = trim(file_get_contents("php://input"));

        $decoded = json_decode($content, true);

        if(is_array($decoded)) {
            session_start();

            $id_usuario = $_SESSION['id'];
            $id_cliente = $decoded['datos']['id_cliente'];
            $num_detalle = $decoded['datos']['num_detalle'];
            $id_producto = $decoded['datos']['id_producto'];
            $cantidad = $decoded['datos']['cantidad'];
            $precio = $decoded['datos']['precio'];

            $nuevaFactura = $conect->prepare('INSERT INTO factura(id_cliente, id_usuario) VALUES (:id_cliente, :id_usuario)');
            
            $nuevaFactura->execute([
                'id_cliente'=>$id_cliente,
                'id_usuario'=>$id_usuario
            ]);

            $consultarID_Factura = $conect->prepare('SELECT id_factura FROM factura ORDER BY id_factura DESC LIMIT 1');
            $consultarID_Factura->execute();
            $factura = $consultarID_Factura->fetch();

            for($i = 1, $j=0; $i<=$num_detalle;$i++,$j++){
                $nuevaFactura = $conect->prepare('INSERT INTO detalle(num_detalle, id_factura,id_producto,cantidad,precio) VALUES (:num_detalle, :id_factura, :id_producto, :cantidad, :precio)');
            
                $nuevaFactura->execute([
                    'num_detalle'=>$i,
                    'id_factura'=>$factura['id_factura'],
                    'id_producto'=>$id_producto[$j],
                    'cantidad'=>$cantidad[$j],
                    'precio'=>$precio[$j]
                ]);

                $consultarStock = $conect->prepare('SELECT stock FROM producto WHERE id_producto = :id_producto');
                $consultarStock->execute([
                    'id_producto' => $id_producto[$j]
                ]);
                $stock = $consultarStock->fetch();
                
                $newstock = $stock['stock'] - $cantidad[$j];

                $actualizarStock= $conect->prepare('UPDATE producto SET stock = :newstock WHERE id_producto = :id_producto');
                $actualizarStock->execute(
                    [
                        'newstock' => $newstock,
                        'id_producto' => $id_producto[$j]
                    ]
                );


            }

            include "./layout/notificarcompra.php";
            echo json_encode($factura['id_factura']);

        } else {
            header("Location: login.php");
        }
    }

?>