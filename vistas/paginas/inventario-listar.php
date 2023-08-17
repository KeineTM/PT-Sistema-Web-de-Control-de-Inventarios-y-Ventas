<?php
$productosPorPagina = 20;
$pagina = 1;

if (isset($_GET['pag'])) $pagina = intval($_GET['pag']);

$limit = $productosPorPagina; # No. productos en pantalla
$offset = ($pagina - 1) * $productosPorPagina; # Saltado de productos en páginas != 1

$modelo = new ModeloProductos();
$conteo = $modelo->mdlConteoProductos(); # Recupera el no. de productos

if ($conteo[0]['conteo'] === 0) {
    echo 'No hay productos registrados.';
    die();
}

// Calcula el no. de páginas totales
$paginas = ceil($conteo[0]['conteo'] / $productosPorPagina);

// Retorna los productos por página
$productos = $modelo->mdlLeerParaPaginacion($limit, $offset);
?>
<br>
<h2>Total de productos: <?= $conteo[0]['conteo'] ?></h2>

<!-- Barra de búsqueda -->
<form class="boton-main" id="barra-busqueda">
    <input type="text" class="campo" name="buscarProducto-txt" autocomplete="off" id="buscarProducto-txt" placeholder="Buscar..." maxlength="80" min='3' required>
    <button class="boton enviar" id="btnBuscarProducto"><img src="vistas/img/magnifying-glass.svg" alt="Buscar"></button>
</form>
<span class="alerta" id="alertaBuscar"></span>
<!-- ------------------------------------------- -->

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
                    <li>Código: <?= $producto['producto_id'] ?></li>
                    <li class="mayusculas"><?= $producto['categoria'] ?></li>
                    <li>Unidades: <?= ($producto['unidades'] !== 0) ? $producto['unidades'] : 'Agotado' ?></li>
                    <li>Precio de venta: $<?= $producto['precio_venta'] ?></li>
                    <li><a href="index.php?pagina=inventario&opciones=detalles&id=<?= $producto['producto_id'] ?>">Ver detalles y editar</a></li>
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