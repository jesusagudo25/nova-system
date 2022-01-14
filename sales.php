<?php

    include "./instalacion/conexion.php";

    session_start();
    if (!array_key_exists('email', $_SESSION) || !array_key_exists('rol', $_SESSION)) {
      header('Location: ./login.php');
      die;
    }

    include "./instalacion/conexion.php";
    $consultaGeneral = $conect->prepare('SELECT f.id_factura,c.nombre AS nombre_cliente,u.nombre AS nombre_usuario,u.apellido_paterno,f.fecha,SUM(cantidad*precio) AS total  FROM factura f
    INNER JOIN detalle d ON f.id_factura = d.id_factura
    INNER JOIN cliente c ON f.id_cliente = c.id_cliente
    INNER JOIN usuario u ON f.id_usuario = u.id_usuario
    GROUP BY d.id_factura');
    $consultaGeneral->execute();
    $ventas = $consultaGeneral->fetchAll();
?>

<!DOCTYPE html>
<html :class="{ 'theme-dark': dark }" x-data="data()" lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sales - Nova Systems</title>
    <?php include "./layout/head.php"; ?>
    
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
    <script src="./assets/js/umd.js" type="text/javascript"></script>

  </head>
  <body>
    <div
      class="flex h-screen bg-gray-50 dark:bg-gray-900"
      :class="{ 'overflow-hidden': isSideMenuOpen}"
    >

      <?php include "./layout/aside.php"; ?>

      <div class="flex flex-col flex-1 w-full">
        
        <?php include "./layout/header.php"; ?>
        
        <!-- Contenido MAIN -->

        <main class="h-full pb-16 overflow-y-auto">
          <div class="container grid px-6 mx-auto">
            <h2
              class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200"
            >
              Manage Sales
            </h2>

            <a
              class="flex items-center justify-between p-4 mb-8 text-sm font-semibold text-purple-100 bg-purple-600 rounded-lg shadow-md focus:outline-none focus:shadow-outline-purple"
              href="./add_sale.php"
            >
              <div class="flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
</svg>
                <span>Add new sale</span>
              </div>
              <span>Go to registration &RightArrow;</span>
            </a>

            
            <h4
              class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300"
            >
              Table sales
            </h4>
            <div class="w-full overflow-hidden rounded-lg shadow-xs">
              <div class="w-full overflow-x-auto">
                <table id="table" class="w-full whitespace-no-wrap">
                  <thead>
                    <tr
                      class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800"
                    >
                      <th class="px-4 py-3">Factura</th>
                      <th class="px-4 py-3">Cliente</th>
                      <th class="px-4 py-3">Vendedor</th>
                      <th class="px-4 py-3">Total</th>
                      <th class="px-4 py-3">Fecha</th>
                      <th class="px-4 py-3">Acciones</th>
                    </tr>
                  </thead>
                  <tbody
                    class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800"
                  >
                  <?php foreach ($ventas as $datos => $valor):?>
                    <tr class="text-gray-700 dark:text-gray-400">
                      <td class="px-4 py-3">
                        <div class="flex items-center text-sm">
                            <p class="font-semibold"><?= $valor['id_factura'] ?></p>
                        </div>
                      </td>
                      <td class="px-4 py-3 text-sm">
                      <?= $valor['nombre_cliente']; ?>
                      </td>
                      <td class="px-4 py-3 text-sm">
                      <?= $valor['nombre_usuario'].' '.$valor['apellido_paterno'] ?>
                      </td>
                      <td class="px-4 py-3 text-xs">
                        <span
                          class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100"
                        >
                        <?= $valor['total'] ?>
                        </span>
                      </td>
                      <td class="px-4 py-3 text-sm">
                      <?= $valor['fecha'] ?>
                      </td>
                      <td class="px-4 py-3">
                        <div class="flex items-center space-x-4 text-sm">
                          <a
                            class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
                            aria-label="Edit"
                            href="./downloadPDF.php?factura=<?= $valor['id_factura'] ?>"
                            target="_blank"
                          >
                          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
        </svg>
                          </a>
                          <a
                            class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
                            aria-label="Delete"
                            href="./downloadexcel.php?factura=<?= $valor['id_factura'] ?>"
                          >
                          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
                          </a>
                        </div>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
              <div id="hide">
                    <div class="form-group" id="columns"></div>
                    <div class="card-block">
                    <div class="card-header" id="titulo">
                    Exportar datos
                      </div>
                        <div class="form-group" id="exportar">
                    <button type="button" class="bttn success export px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple" data-type="csv">Export CSV</button>
                    <button type="button" class="bttn success export px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple" data-type="sql">Export SQL</button>
                    <button type="button" class="bttn success export px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple" data-type="json">Export JSON</button>
                </div>
              </div>
                </div>
            </div>
          </div>
        </main>

        <!-- Fin contenido final -->
      </div>
    </div>

    <script src="./assets/js/utils.js"></script>
    <script>

    var table = document.getElementsByTagName("table")[0];
    var checkboxes = document.getElementById("columns");
    var inputs = [], visible = [], hidden = [];

    var datatable = new simpleDatatables.DataTable(table, {
      perPage: 5,
    });

    datatable.on("datatable.init", function() {
      setCheckboxes()
    });

    function updateColumns() {
      try {
        datatable.columns().show(visible);
        datatable.columns().hide(hidden);
      } catch(e) {
        console.log(e);
      }
    }

    function setCheckboxes() {
      inputs = [];
      visible = [];
      checkboxes.innerHTML = "";

      util.each(datatable.headings, function(i, heading) {
        var checkbox = util.createElement("button");
        var input = util.createElement("input", { type: "checkbox", id: "checkbox-" + i, name: "checkbox" });
        var label = util.createElement("label", { for: "checkbox-" + i, text: heading.textContent });

        checkbox.classList.add("flex", "justify-center","flex-wrap","items-center");

        input.idx = i;

        input.classList.add("text-purple-600" ,"form-checkbox" ,"focus:border-purple-400" ,"focus:outline-none", "focus:shadow-outline-purple" ,"dark:focus:shadow-outline-gray","mr-1");
        if ( datatable.columns().visible(heading.cellIndex) ) {
          input.checked = true;
          visible.push(i);
        } else {
          if ( hidden.indexOf(i) < 0 ) {
            hidden.push(i);
          }
        }

        checkbox.appendChild(input)
        checkbox.appendChild(label)

        checkboxes.appendChild(checkbox);

        inputs.push(input);
      });

      util.each(inputs, function(i, input) {

          input.onchange = function(e) {
            if ( input.checked ) {
              hidden.splice(hidden.indexOf(input.idx), 1);
              visible.push(input.idx);
            } else {
              visible.splice(visible.indexOf(input.idx), 1);
              hidden.push(input.idx);
            }

            updateColumns();
          };
      });
    }

    document.querySelectorAll(".export").forEach(function(el) {
      el.addEventListener("click", function(e) {
        var type = el.dataset.type;

        var data = {
          type: type,
          filename: "my-" + type,
        };

        if ( type === "csv" ) {
          data.columnDelimiter = "|";
        }

        datatable.export(data);
      });
    });

    document.querySelectorAll(".main").forEach(function(el) {
      el.addEventListener("click", e => {
        datatable[el.id]();
        setTimeout(function() {
          document.getElementById("hide").classList.toggle("hidden", !datatable.initialized);
          table.classList.toggle("table", !datatable.initialized);
        }, 10);
      });
    });

  </script>
  <script>
    var cont = document.createElement("div");
    cont.classList.add("dataTable-bottom");
    
    cont.innerHTML+= `                    <div class="card-header">
                    Visibilidad de la columna
                    </div>`;
    cont.appendChild(checkboxes);

    var cont1 = document.createElement("div");
    cont1.classList.add("dataTable-bottom");

    var titulo = document.querySelector('#titulo');
    var exportar = document.querySelector('#exportar');
    cont1.appendChild(titulo);
    cont1.appendChild(exportar);

    var princ = document.querySelector(".dataTable-wrapper");
    princ.appendChild(cont);
    princ.appendChild(cont1);
    
  </script>
  <script src="./assets/js/addstyle.js"></script>
  </body>
</html>
