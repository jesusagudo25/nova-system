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
    
    if(!isset($_GET['id_usuario'])){
        header("Location: products.php");
    }

    $id= $_GET["id_usuario"];
    $consultaPorId = $conect->prepare('SELECT * FROM usuario WHERE id_usuario = :id');
    $consultaPorId->execute(
        ['id'=>$id]
    );
    $usuario = $consultaPorId->fetch();

?>
<!DOCTYPE html>
<html :class="{ 'theme-dark': dark }" x-data="data()" lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Nova Systems - Add product</title>
    <?php include "./layout/head.php"; ?>
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
            Edit user
            </h2>
            <!-- CTA -->
            <a
              class="flex items-center justify-between p-4 mb-8 text-sm font-semibold text-purple-100 bg-purple-600 rounded-lg shadow-md focus:outline-none focus:shadow-outline-purple"
              href="./clients.php"
            >
              <div class="flex items-center">
                <svg
                  class="w-5 h-5 mr-2"
                  fill="currentColor"
                  viewBox="0 0 20 20"
                >
                  <path
                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                  ></path>
                </svg>
                <span>Manage users</span>
              </div>
              <span>View more &RightArrow;</span>
            </a>
            
            <h4
              class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300"
            >
            User data
            </h4>
            <div
              class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800"
            >
            <form method="POST">
              <label class="block text-sm">
                <span class="text-gray-700 dark:text-gray-400">Nombre</span>
                <input
                  class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                  placeholder="Jes??s"
                  required
                  name="nombre"
                  value="<?= $usuario['nombre']?>"
                />
                <span class="text-xs text-red-600 dark:text-green-400 informar">

              </span>
              </label>
              
              <label class="block text-sm">
                <span class="text-gray-700 dark:text-gray-400">Apellido</span>
                <input
                  class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                  placeholder="Agudo"
                  name="apellido"
                  required
                  value="<?= $usuario['apellido_paterno']?>"
                />
                <span class="text-xs text-red-600 dark:text-green-400 informar">

              </span>
              </label>

              <label class="block text-sm">
                <span class="text-gray-700 dark:text-gray-400">Email</span>
                <input
                  class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                  placeholder="jagudo25@gmail.com"
                  type="email"
                  name="email"
                  required
                  value="<?= $usuario['email']?>"
                />
                <span class="text-xs text-red-600 dark:text-green-400 informar">

              </span>
              </label>

              <label class="block mt-4 text-sm">
                <span class="text-gray-700 dark:text-gray-400">
                  User role
                </span>
                <select class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" name="id_rol"
                required>
                  <option value="1" <?= $usuario['id_rol'] == 1  ? 'selected' : '' ?>>User</option>
                  <option value="2" <?= $usuario['id_rol'] == 2  ? 'selected' : '' ?>>Admin</option>
                </select>
              </label>

              <label class="block mt-4 text-sm">
                <span class="text-gray-700 dark:text-gray-400">
                  Estado
                </span>
                <select class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray"
                name="estado"
                required>
                <option value="0" <?= $usuario['estado'] == 0  ? 'selected' : '' ?>>Inactivo</option>
                <option value="1" <?= $usuario['estado'] == 1  ? 'selected' : '' ?>>Activo</option>
                </select>
              </label>

              <input type="hidden" name="id_usuario" value="<?= $id?>">
              
              <hr class="my-8">

              <div class="flex items-center" style="justify-content: center;">
                <input type="submit" value="Actualizar usuario" class="px-4 py-2 text-sm font-medium leading-5 text-center text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                </div>
              </form>
            </div>

            <!-- Password -->
            <h4
              class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300"
            >
            Change password
            </h4>
            <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <form method="POST">
            <label class="block text-sm">
                <span class="text-gray-700 dark:text-gray-400">Password</span>
                <input
                  class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                  placeholder="***************"
                  type="password"
                  name="password"
                  required
                  maxlength="25"
                  minlength="8"
                />
                <span class="text-xs text-red-600 dark:text-green-400 informar">

              </span>
            </label>
            
            <input type="hidden" name="id_usuario" value="<?= $id?>">

            <hr class="my-8">
            <div class="flex items-center" style="justify-content: center;">
                <input type="submit" value="Actualizar contrase??a" class="px-4 py-2 text-sm font-medium leading-5 text-center text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                </div>
            </form>
            </div>

          </div>
        </main>
      </div>
    </div>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="./assets/js/validateuser.js"></script>
  </body>
</html>