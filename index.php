<?php
    include "./instalacion/conexion.php";

    session_start();
    if (!array_key_exists('email', $_SESSION) || !array_key_exists('rol', $_SESSION)) {
      header('Location: ./login.php');
      die;
    }

?>
<!DOCTYPE html>
<html :class="{ 'theme-dark': dark }" x-data="data()" lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard Nova Systems</title>
    <?php include "./layout/head.php"; ?>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.css"
    />
    <script
      src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"
      defer
    ></script>


  </head>
  <body>
    <div
      class="flex h-screen bg-gray-50 dark:bg-gray-900"
      :class="{ 'overflow-hidden': isSideMenuOpen }"
    >

    <?php include "./layout/aside.php"; ?>

      <div class="flex flex-col flex-1 w-full">
        
      <?php include "./layout/header.php"; ?>

      <?php include "./layout/dashboard.php"; ?>

      </div>
    </div>
    <script src="./assets/js/charts-lines.js" defer></script>
    <script src="./assets/js/charts-pies.js" defer></script>
  </body>
</html>
