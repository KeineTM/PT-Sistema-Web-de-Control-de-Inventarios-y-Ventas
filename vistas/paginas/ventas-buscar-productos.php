<?php ControladorOperaciones::ctrlBuscarProductos() ?>
<!-- Barra de búsqueda -->
<form class="boton-main" method="post" id="barra-busqueda">
    <input type="text" class="campo" name="buscarProducto-txt" autocomplete="off" id="buscarProducto-txt" placeholder="Buscar por nombre..." maxlength="80" min='3' required>
    <button class="boton enviar" id="btnBuscarProducto"><img src="vistas/img/magnifying-glass.svg" alt="Buscar"></button>
</form>
<span class="alerta" id="alertaBuscar"></span>
<!-- ------------------------------------------- -->

<?php
if(!isset($_GET['clave'])) {
    echo '<p class="alerta-roja">No se ingresaron datos para la búsqueda.</p>';
    die();
}

$palabraClave = $_GET['clave'];
$productosPorPagina = 20;
$pagina = 1;

if (isset($_GET['pag'])) $pagina = intval($_GET['pag']);

$limit = $productosPorPagina; # No. productos en pantalla
$offset = ($pagina - 1) * $productosPorPagina; # Saltado de productos en páginas != 1

$modelo = new ModeloProductos();
$conteo = $modelo->mdlConteoProductosBuscados($palabraClave, true); # Recupera el no. de productos

if ($conteo[0]['conteo'] === 0) {
    echo '<p class="alerta-roja">No hay coincidencias.</p>';
    die();
}

// Calcula el no. de páginas totales
$paginas = ceil($conteo[0]['conteo'] / $productosPorPagina);

// Retorna los productos por página
$productos = $modelo->mdlLeerParaPaginacionDeBusqueda($limit, $offset, $palabraClave, true);
?>
<h3 class="destacado"><?= $conteo[0]['conteo'] ?> resultados para "<?= $palabraClave ?>":</h3>

<p>Página <?= $pagina ?> de <?= $paginas ?></p>

<ul class="paginacion">
    <!-- Si la página actual es mayor a uno, mostramos el botón para ir una página atrás -->
    <?php if ($pagina > 1) { ?>
        <li>
            <a href="index.php?pagina=inventario&opciones=listar&pag=<?php echo $pagina - 1 ?>">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
    <?php } ?>

    <!-- Enlaces para ir a todas las páginas.-->
    <?php for ($x = 1; $x <= $paginas; $x++) { ?>
        <li class="<?php if ($x == $pagina) echo "activa" ?>">
            <a href="index.php?pagina=inventario&opciones=listar&pag=<?php echo $x ?>">
                <?php echo $x ?></a>
        </li>
    <?php } ?>
    <!-- Si la página actual es menor al total de páginas, mostramos un botón para ir una página adelante -->
    <?php if ($pagina < $paginas) { ?>
        <li>
            <a href="index.php?pagina=inventario&opciones=listar&pag=<?php echo $pagina + 1 ?>">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    <?php } ?>
</ul>

<section class="contenedor-productos">
    <?php foreach ($productos as $producto) { ?>
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
    <?php } ?>
</section>

<ul class="paginacion">
    <?php if ($pagina > 1) { ?>
        <li>
            <a href="index.php?pagina=inventario&opciones=listar&pag=<?php echo $pagina - 1 ?>">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
    <?php } ?>

    <?php for ($x = 1; $x <= $paginas; $x++) { ?>
        <li class="<?php if ($x == $pagina) echo "activa" ?>">
            <a href="index.php?pagina=inventario&opciones=listar&pag=<?php echo $x ?>">
                <?php echo $x ?></a>
        </li>
    <?php } ?>

    <?php if ($pagina < $paginas) { ?>
        <li>
            <a href="index.php?pagina=inventario&opciones=listar&pag=<?php echo $pagina + 1 ?>">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    <?php } ?>
</ul>