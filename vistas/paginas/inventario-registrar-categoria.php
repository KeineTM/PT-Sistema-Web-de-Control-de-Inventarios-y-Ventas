<?php
require '../../controlador/ctrlInventario.php';
require '../../modelo/mdlInventario.php';

$categoria = $_POST['categoria-txt'];

ControladorProductos::ctrlRegistrarCategoria($categoria);
?>