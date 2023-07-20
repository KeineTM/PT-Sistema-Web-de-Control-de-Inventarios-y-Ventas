<?php
if(!isset($_POST['buscarProducto-txt'])) return;
$palabraClave = $_POST['buscarProducto-txt'];
$resultado = ControladorProductos::ctrlBuscarTodos($palabraClave);

if(!is_array($resultado)) echo $resultado;
else if(count($resultado) < 1) echo 'No hay coincidencias para "' . $palabraClave . '"...';
else {
?>
<section class="contenedor-productos">
    <?php foreach($resultado as $producto) { ?>
        <article class="tarjeta-producto">
            <img src="<?= $producto['foto_url'] ?>" alt="Imagen <?= $producto['nombre'] ?>">
            <span>
                <h3><?= $producto['nombre'] ?></h3>
                <ul>
                    <li><?= $producto['producto_id'] ?></li>
                    <li class="mayusculas"><?= $producto['categoria'] ?></li>
                    <li>Unidades: <?= $producto['unidades'] ?></li>
                    <li>Precio: $<?= $producto['precio_venta'] ?></li>
                    <li><a href="index.php?pagina=ventas&opciones=alta&idProducto-txt=<?= $producto['producto_id'] ?>">Agregar a la venta</a></li>
                </ul>
            </span>
        </article>
    <?php }
}
?>
</section>