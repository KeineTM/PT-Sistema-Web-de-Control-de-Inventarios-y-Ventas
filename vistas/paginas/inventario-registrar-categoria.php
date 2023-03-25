<?php
# Esta hoja de php recibe la petición de la función asíncrona del archivo inventario-alta-categoria-asincrona.js
require '../../controlador/ctrlInventario.php';
require '../../modelo/mdlInventario.php';

$categoria = $_POST['categoria-txt'];

# Ejecuta el registro desde el API FETCH
$resultado = ControladorProductos::ctrlRegistrarCategoria($categoria);

echo $resultado;
?>