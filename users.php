<?php

    include "./instalacion/conexion.php";

    session_start();
    if (!array_key_exists('email', $_SESSION) || !array_key_exists('rol', $_SESSION)) {
      header('Location: ./login.php');
      die;
    }

    
    $allowedRoles = ['Admin'];

    if (!in_array($_SESSION['rol'], $allowedRoles)) {
      header('Location: ./index.php');
      die;
    }

    include "./instalacion/conexion.php";
    $consultaGeneral = $conect->prepare('SELECT u.id_usuario,u.id_rol,u.nombre,u.apellido_paterno,u.email,u.estado,r.nombre AS rol_name  FROM usuario u
    INNER JOIN usuario_rol r ON u.id_rol = r.id_rol');
    $consultaGeneral->execute();
    $usuarios = $consultaGeneral->fetchAll();
?>
<!DOCTYPE html>
<html :class="{ 'theme-dark': dark }" x-data="data()" lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Clients - Nova Systems</title>
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
              Manage Users
            </h2>

            <a
              class="flex items-center justify-between p-4 mb-8 text-sm font-semibold text-purple-100 bg-purple-600 rounded-lg shadow-md focus:outline-none focus:shadow-outline-purple"
              href="./add_user.php"
            >
              <div class="flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
</svg>
                <span>Add new user</span>
              </div>
              <span>Go to registration &RightArrow;</span>
            </a>

            
            <h4
              class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300"
            >
              Table user
            </h4>
            <div class="w-full overflow-hidden rounded-lg shadow-xs">
              <div class="w-full overflow-x-auto">
                <table id="table" class="w-full whitespace-no-wrap">
                  <thead>
                    <tr
                      class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800"
                    >
                      <th class="px-4 py-3">Name</th>
                      <th class="px-4 py-3">Rol</th>
                      <th class="px-4 py-3">Email</th>
                      <th class="px-4 py-3">Estado</th>
                      <th class="px-4 py-3">Actions</th>
                    </tr>
                  </thead>
                  <tbody
                    class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800"
                  >
                  <?php foreach ($usuarios as $datos => $valor):?>
                    <tr class="text-gray-700 dark:text-gray-400">
                      <td class="px-4 py-3">
                        <div class="flex items-center text-sm">
                            <p class="font-semibold"><?= $valor['nombre'].' '.$valor['apellido_paterno'] ?></p>
                        </div>
                      </td>
                      <td class="px-4 py-3 text-sm">
                      <?= $valor['rol_name']; ?>
                      </td>
                      <td class="px-4 py-3 text-sm">
                      <?= $valor['email']; ?>
                      </td>
                      <td class="px-4 py-3 text-xs">

                        <?php if ($valor['estado']==1):?>
                            <span
                          class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100"
                        >
                        Activo
                        </span>

                        <?php else: ?>
                            <span class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full dark:text-red-100 dark:bg-red-700">
                          Inactivo
                        </span>
                        <?php endif; ?>
                      </td>
                      <td class="px-4 py-3">
                        <div class="flex items-center space-x-4 text-sm">
                          <a
                            class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
                            aria-label="Edit"
                            href="./edit_user.php?id_usuario=<?= $valor['id_usuario'] ?>"
                          >
                            <svg
                              class="w-5 h-5"
                              aria-hidden="true"
                              fill="currentColor"
                              viewBox="0 0 20 20"
                            >
                              <path
                                d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"
                              ></path>
                            </svg>
                          </a>
                          <a
                            class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
                            aria-label="Delete"
                            href="./inactivo.php?id_usuario=<?= $valor['id_usuario'] ?>"
                          >
                          <!-- Si el usuario es inactivo, no puede iniciar sesion -->
                            <svg
                              class="w-5 h-5"
                              aria-hidden="true"
                              fill="currentColor"
                              viewBox="0 0 20 20"
                            >
                              <path
                                fill-rule="evenodd"
                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                clip-rule="evenodd"
                              ></path>
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
