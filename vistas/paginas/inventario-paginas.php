<div class="contenedor-productos">
<?php
$listaProductos = (ControladorProductos::ctrlLeerTodos());

foreach ($listaProductos as $registro => $producto) {
?>

<div class="tarjeta-producto">
    <img src="<?php echo $producto['foto_url'] ?>" alt="Imagen <?php echo $producto['nombre'] ?>">
    <span>
        <h3><?php echo $producto['nombre'] ?></h3>
        <ul>
            <li><?php echo $producto['producto_id'] ?></li>
            <li>Categoría: <?php echo $producto['categoria'] ?></li>
            <li>Precio de venta: $<?php echo $producto['precio_venta'] ?></li>
            <li><a href="index.php?pagina=inventario&opciones=<?php echo $producto['producto_id'] ?>" data-edit>Ver detalles y editar</a></li>
        </ul>
    </span>
</div>
<?php
}

?>
</div>