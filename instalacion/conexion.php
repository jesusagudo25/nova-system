<?php
    $host = 'localhost';
    $contra = 'panama09';
    $usuario = 'jagudo';
    $nombreBD = 'sistema_inventario';

    try{
        $hostPDO = "mysql:host=$host;dbname=$nombreBD;charset=utf8";
        $conect = new PDO($hostPDO,$usuario,$contra);

        $conect->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

    }catch(PDOException $e){
        die('DB ERROR: '.$e->getMessage());
    }

?>
