<?php
  // include autoloader
  require 'vendor/autoload.php';

  // reference the Dompdf namespace
  use Dompdf\Dompdf;
  use Dompdf\Options;

include "./instalacion/conexion.php";

if(isset($_GET['factura'])) {
    
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


  $filename = "factura";
  
  $options = new Options();
  $options->set('isRemoteEnabled', TRUE);

  // instantiate and use the dompdf class
  $dompdf = new Dompdf($options);

  $subtotal = 0;
  $itbmstotal =0;

  $html= '<!DOCTYPE html>
  <html lang="en">
    <head>
      <meta charset="utf-8">
      <title>FACTURA</title>
      <link rel="stylesheet" href="style.css" media="all" />
      <style>
      .clearfix:after {
          content: "";
          display: table;
          clear: both;
        }
        
        a {
          color: #5D6975;
          text-decoration: underline;
        }
        
        body {
          position: relative;
          width: 21cm;  
          height: 29.7cm; 
          margin: 0 auto; 
          color: #001028;
          background: #FFFFFF; 
          font-family: Arial, sans-serif; 
          font-size: 12px; 
          font-family: Arial;
        }
        
        header {
          padding: 10px 0;
          margin-bottom: 30px;
        }
        
        #logo {
          text-align: center;
          margin-bottom: 10px;
        }
        
        #logo img {
          width: 90px;
        }
        
        h1 {
          border-top: 1px solid  #5D6975;
          border-bottom: 1px solid  #5D6975;
          color: #5D6975;
          font-size: 2.4em;
          line-height: 1.4em;
          font-weight: normal;
          text-align: center;
          margin: 0 0 20px 0;
          background: url(dimension.png);
        }
        
        #project {
          float: left;
        }
        
        #project span {
          color: #5D6975;
          text-align: right;
          width: 52px;
          margin-right: 10px;
          display: inline-block;
          font-size: 0.8em;
        }
        
        #company {
          float: right;
          text-align: right;
        }
        
        #project div,
        #company div {
          white-space: nowrap;        
        }
        
        table {
          width: 100%;
          border-collapse: collapse;
          border-spacing: 0;
          margin-bottom: 20px;
        }
        
        table tr:nth-child(2n-1) td {
          background: #F5F5F5;
        }
        
        table th,
        table td {
          text-align: center;
        }
        
        table th {
          padding: 5px 20px;
          color: #5D6975;
          border-bottom: 1px solid #C1CED9;
          white-space: nowrap;        
          font-weight: normal;
        }
        
        table .service,
        table .desc {
          text-align: left;
        }
        
        table td {
          padding: 20px;
          text-align: right;
        }
        
        table td.service,
        table td.desc {
          vertical-align: top;
        }
        
        table td.unit,
        table td.qty,
        table td.total {
          font-size: 1.2em;
        }
        
        table td.grand {
          border-top: 1px solid #5D6975;;
        }
        
        #notices .notice {
          color: #5D6975;
          font-size: 1.2em;
        }
        
        footer {
          color: #5D6975;
          width: 100%;
          height: 30px;
          position: absolute;
          bottom: 0;
          border-top: 1px solid #C1CED9;
          padding: 8px 0;
          text-align: center;
        }
      </style>
    </head>
    <body>
      <header class="clearfix">
        <div id="logo">
          <img src="https://inventory-nova-systems.herokuapp.com/assets/img/logo.png" style="width: 80%">
        </div>
        <h1>INVOICE</h1>
        <div id="project">
          <div><span>FACTURA</span> #'.$factura.'</div>
          <div><span>SELLER</span>'.$personas['nombre_usuario'].' '.$personas['apellido_paterno'].'</div>
          <div><span>CLIENT</span> '.$personas['nombre_cliente'].'</div>
          <div><span>ADDRESS</span>'.$personas['provincia'].','.$personas['distrito'].'</div>
          <div><span>EMAIL</span> <a href="mailto:john@example.com">'.$personas['email'].'</a></div>
          <div><span>PHONE</span> +<a href="mailto:john@example.com">'.$personas['telefono'].'</a></div>
          <div><span>DATETIME</span>'.$personas['fecha'].'</div>

        </div>
      </header>
      <main>
        <table>
          <thead>
            <tr>
              <th class="service">#</th>
              <th class="service">DESCRIPTION</th>
              <th class="desc">PRICE</th>
              <th>QTY</th>
              <th>TOTAL</th>
            </tr>
          </thead>
          <tbody>
          ';
          foreach ($productos as $datos => $valor){
              $html .=
              '<tr>
              <td class="service">'.$valor['num_detalle'] .'</td>
              <td class="desc">'.$valor['descripcion'].'</td>
              <td class="unit">$'.$valor['precio'].'</td>
              <td class="qty">'.$valor['cantidad'].'</td>
              <td class="qty">$'.$valor['cantidad']*$valor['precio'].'</td>
            </tr>';
            $subtotal += $valor['cantidad']*$valor['precio'];
              $itbmstotal += $valor['cantidad']*$valor['precio'] * 0.07;
          }

            

          $html .='<tr>
            <td colspan="4">SUBTOTAL</td>
            <td class="total">'.$subtotal.'</td>
          </tr>
          <tr>
            <td colspan="4">ITBMS 07.00%	</td>
            <td class="total">'.number_format($itbmstotal,2).'</td>
          </tr>
          <tr>
            <td colspan="4" class="grand total">TOTAL</td>
            <td class="grand total">'.number_format($subtotal+$itbmstotal,2).'</td>
          </tr>
          </tbody>
        </table>
        <div id="notices">
          <div>NOTICE:</div>
          <div class="notice">Se aplicará un cargo financiero del 1,5% sobre los saldos impagos después de 30 días.</div>
        </div>
      </main>
      <footer>
      La factura se creó en una computadora y es válida sin la firma y el sello.
      </footer>
    </body>
  </html>
  ';

  $dompdf->loadHtml($html);

  // (Optional) Setup the paper size and orientation
  $dompdf->setPaper('A3', 'portrait');

  // Render the HTML as PDF
  $dompdf->render();

  // Output the generated PDF to Browser
  $dompdf->stream($filename, array("Attachment" => 0));
        }
        else{
          header("Location: login.php");
        }
?>

<!-- Tratar de hacer este documento que sea funcional para todos los pdf...
Imagenes pendiente....
Clases de la tabla... mira la original -->