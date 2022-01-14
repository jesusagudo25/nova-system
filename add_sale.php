<?php

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
    <title>Nova Systems - Forms</title>
    <?php include "./layout/head.php"; ?>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
  </head>
  <body>
    <div
      class="flex h-screen bg-gray-50 dark:bg-gray-900"
      :class="{ 'overflow-hidden': isSideMenuOpen}"
    >
    <?php include "./layout/aside.php"; ?>

<div class="flex flex-col flex-1 w-full">
  
  <?php include "./layout/header.php"; ?>

        <main class="h-full pb-16 overflow-y-auto">
          <div class="container px-6 mx-auto grid">
            <h2
              class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200"
            >
              Add sale
            </h2>
            <!-- CTA -->
            <a
              class="flex items-center justify-between p-4 mb-8 text-sm font-semibold text-purple-100 bg-purple-600 rounded-lg shadow-md focus:outline-none focus:shadow-outline-purple"
              href="./sales.php"
            >
              <div class="flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
</svg>

                <span>SELLER: <?= $_SESSION['nombre']?></span>
              </div>
              <span>View more &RightArrow;</span>
            </a>

            <!-- General elements -->
            <h4
              class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300"
            >
            Client data
            </h4>
            <div
              class="flex justify-between flex-wrap	items-center px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800"
            >
              <label class="text-sm">
                <span class="text-gray-700 dark:text-gray-400">Name</span>
                <input
                  class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                  placeholder="Enter name"
                  id="nom_cliente"
                  autocomplete="off"
                  require
                />
                <span class="text-xs text-red-600 dark:text-green-400" id="informar">

              </span>
              </label>
              <label class="text-sm">
                <span class="text-gray-700 dark:text-gray-400">Phone</span>
                <input
                  class="block w-full mt-1 placeholder-gray-600 bg-gray-100 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input cursor-not-allowed"
                  disabled
                  require
                  id="tel_cliente"
                />
              </label>
              <label class="text-sm">
                <span class="text-gray-700 dark:text-gray-400">Email</span>
                <input
                  class="block w-full mt-1 placeholder-gray-600 bg-gray-100 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input cursor-not-allowed"
                  disabled
                  require
                  id="cor_cliente"
                />
              </label>
              <label class="text-sm">
                <span class="text-gray-700 dark:text-gray-400">Address</span>
                <input
                  class="block w-full mt-1 placeholder-gray-600 bg-gray-100 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input cursor-not-allowed"
                  disabled
                  require
                  id="dir_cliente"
                />
              </label>

            </div>

            <h4
              class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300"
            >
            Sale Data
            </h4>
            <div
              class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800"
            >

              <label class="block text-sm">
                <span class="text-gray-700 dark:text-gray-400">
                Find it
                </span>
                <input
                  class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                  placeholder="Search product code"
                  id="cod_producto"
                />
              </label>
              <table class="w-full whitespace-no-wrap mt-8 rounded-lg overflow-hidden">
                  <thead>
                    <tr class="text-sm font-semibold tracking-wide text-left font-semibold uppercase bg-gray-100 dark:text-gray-400 dark:bg-gray-800">
                      <th class="px-4 py-3">Descripcion</th>
                      <th class="px-4 py-3">Precio</th>
                      <th class="px-4 py-3">Cantidad</th>
                      <th class="px-4 py-3">Total</th>
                      <th class="px-4 py-3">Acción</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800" id="detalle_venta">

                  </tbody>
                  <tfoot class="text-sm bg-gray-100" id="detalle_totales"><tr>

                  </tfoot>
              </table>

                <div class="hidden text-sm mt-8" id="acciones">
                  <span class="text-gray-700 dark:text-gray-400">
                  Acciones
                  </span>
                  <div class="flex items-center">
                    <button class="mt-1 flex items-center justify-between mr-5 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple" id="anular">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
</svg>
                      <span>Anular</span>
                    </button>
                    <button class="mt-1 flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple" id="generar">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
</svg>
                      <span>Generar venta</span>
                    </button>
                  </div>
              </div>
            </div>
            </div>
          </div>
        </main>
      </div>
    </div>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="./assets/js/funciones.js"></script>
  </body>
</html>
