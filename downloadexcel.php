<?php
    require 'vendor/autoload.php';

    use PhpOffice\PhpSpreadsheet\IOFactory;
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    include "./instalacion/conexion.php";

    if($_GET['factura']) {
    
    $factura = $_GET['factura'];
    
    $consultarNombres = $conect->prepare('SELECT c.nombre as nombre_cliente,c.email,c.telefono,u.nombre as nombre_usuario,u.apellido_paterno,c.provincia,c.distrito,f.fecha FROM factura f
    INNER JOIN cliente c ON f.id_cliente = c.id_cliente
    INNER JOIN usuario u ON f.id_usuario = u.id_usuario WHERE f.id_factura = :id_factura');
    $consultarNombres->execute([
        'id_factura'=>$factura
    ]);
    $personas = $consultarNombres->fetch();
    
    $consultarProductos = $conect->prepare('SELECT d.num_detalle,p.descripcion,d.cantidad,d.precio FROM detalle d
    INNER JOIN producto p ON d.id_producto = p.id_producto WHERE d.id_factura = :id_factura');
    $consultarProductos->execute([
        'id_factura'=>$factura
    ]);
    $productos = $consultarProductos->fetchAll();

    /* Inicio de configuracion - Libreria */

    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('./template.xlsx');

    $worksheet = $spreadsheet->getActiveSheet();

    $styleArray = [
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['argb' => '00000000'],
            ],
        ],
    ];
    
    $spreadsheet
    ->getActiveSheet()
    ->getStyle('B10:F10')
    ->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB('D9D9D9');
    
    $spreadsheet
    ->getActiveSheet()
    ->getStyle('E2:E8')
    ->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB('D9D9D9');

    $worksheet->getCell('F2')->setValue($personas['fecha']);
    $worksheet->getCell('F3')->setValue($factura);
    $worksheet->getCell('F4')->setValue($personas['nombre_cliente']);
    $worksheet->getCell('F5')->setValue($personas['provincia'].','.$personas['distrito']);
    $worksheet->getCell('F6')->setValue($personas['telefono']);
    $worksheet->getCell('F7')->setValue($personas['email']);
    $worksheet->getCell('F8')->setValue($personas['nombre_usuario'].' '.$personas['apellido_paterno']);

    $i=11;

    $subtotal = 0;
    $itbmstotal =0;

    foreach ($productos as $datos => $valor){

        $worksheet->getCell('B'.$i)->setValue($valor['num_detalle']);
        $worksheet->getCell('C'.$i)->setValue($valor['descripcion']);
        $worksheet->getCell('D'.$i)->setValue($valor['precio']);
        $worksheet->getCell('E'.$i)->setValue($valor['cantidad']);
        $worksheet->getCell('F'.$i)->setValue($valor['cantidad']*$valor['precio']);
        $subtotal += $valor['cantidad']*$valor['precio'];
        $itbmstotal += $valor['cantidad']*$valor['precio'] * 0.07;
        
        $worksheet->getStyle('B'.$i.':F'.$i)->applyFromArray($styleArray);

        $i++;
    }
    
    $worksheet->getCell('E'.$i)->setValue('SUBTOTAL');
    $worksheet->getCell('E'.$i+1)->setValue('ITBMS 07.00%');
    $worksheet->getCell('E'.$i+2)->setValue('TOTAL');
    
    $worksheet->getCell('F'.$i)->setValue($subtotal);
    $worksheet->getCell('F'.$i+1)->setValue(number_format($itbmstotal,2));
    $worksheet->getCell('F'.$i+2)->setValue(number_format($subtotal+$itbmstotal,2));

    $spreadsheet
    ->getActiveSheet()
    ->getStyle('E'.$i.':E'.$i+2)
    ->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB('D9D9D9');
    
    $worksheet->getStyle('E'.$i.':E'.$i+2)->applyFromArray($styleArray);
    $worksheet->getStyle('F'.$i.':F'.$i+2)->applyFromArray($styleArray);


    $writer = new Xlsx($spreadsheet);
    

    /* Salida */

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="factura.xlsx"');
    ob_end_clean();
    $writer->save('php://output');

    }
    else{
        header("Location: login.php");
    }

?>