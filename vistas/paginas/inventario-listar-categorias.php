<?php 
# Este archivo php recibe la petición de la función asíncrona del archivo 
require '../../controlador/ctrlInventario.php';
require '../../modelo/mdlInventario.php';

$categorias = ControladorProductos::ctrlCategoriasActivas();

# Se envía el array resultante en formato JSON
echo json_encode($categorias);