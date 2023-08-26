<?php ControladorContactos::ctrlBuscarTodos() ?>
<!-- Barra de búsqueda -->
<form class="boton-main" method="post" id="barra-busqueda">
    <input type="text" class="campo" name="buscarContacto-txt" autocomplete="off" placeholder="Teléfono o Nombre" minlength="3" maxlength="240" required>
    <button class="boton enviar" id="btnBuscar"><img src="vistas/img/magnifying-glass.svg" alt=""></button>
</form>
<span class="alerta" id="alertaBuscar"></span>
<!-- ------------------------------------------- -->

<?php
if(!isset($_GET['clave'])) {
    echo '<p class="alerta-roja">No se ingresaron datos para la búsqueda.</p>';
    die();
}

$palabra_clave = $_GET['clave'];

$registrosPorPagina = 20;
$pagina = 1;

if (isset($_GET['pag'])) $pagina = intval($_GET['pag']);

$limit = $registrosPorPagina; # No. registros en pantalla
$offset = ($pagina - 1) * $registrosPorPagina; # Saltado de registros en páginas != 1

$modelo = new ModeloContactos();
$conteo = $modelo->mdlConteoRegistros(); # Recupera el no. de registros

if ($conteo[0]['conteo'] === 0) {
    echo '<p class="alerta-roja">No hay contactos registrados.</p>';
    die();
}

// Calcula el no. de páginas totales
$paginas = ceil($conteo[0]['conteo'] / $registrosPorPagina);

// Retorna los registros por página
$consulta = ControladorContactos::ctlBuscarEnFullText($palabra_clave, $limit, $offset);

# Valida el resultado de la consulta
# Si no es una lista es porque retornó un error
# Si es una lista vacía es porque no encontró coincidencias
if (!is_array($consulta) || sizeof($consulta) === 0) {
    echo '<p class="alerta-roja">Ocurrió un error: No hay registros o hay un problema con la base de datos</p>';
    die();
}
?>
<h3 class="destacado"><?= $conteo[0]['conteo'] ?> resultados para "<?= $palabra_clave ?>":</h3>

<p>Página <?= $pagina ?> de <?= $paginas ?></p>

<section class="contenedor__tabla">
    <p>Puede acceder la información completa del contacto y editarlos haciendo clic en los <span class="texto-rosa">Detalles</span> del número de teléfono.</p><br>
    <!-- -------------Tabla de ventas por tiempo ---------- -->
    <table class="tabla">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th>Notas</th>
                <th>Tipo</th>
            </tr>
        </thead>
        <tbody>
            <!-- Contenido -->
            <?php foreach ($consulta as $contacto) {  ?>
                <tr>
                    <td><?= $contacto['nombre'] . ' ' . $contacto['apellido_paterno'] . ' ' . $contacto['apellido_materno'] ?></td>
                    <td><a class="texto-rosa" href="index.php?pagina=directorio&opciones=detalles&id=<?= $contacto['contacto_id'] ?>"><?= $contacto['contacto_id'] ?><br>Detalles</a></td>
                    <td><?= $contacto['notas'] ?></td>
                    <td><?= $contacto['tipo_contacto'] ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <!-- ------------- Fin tabla ---------- -->
</section>

<ul class="paginacion">
    <?php if ($pagina > 1) { ?>
        <li>
            <a href="index.php?pagina=directorio&opciones=listar&pag=<?php echo $pagina - 1 ?>">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
    <?php } ?>

    <?php for ($x = 1; $x <= $paginas; $x++) { ?>
        <li class="<?php if ($x == $pagina) echo "activa" ?>">
            <a href="index.php?pagina=directorio&opciones=listar&pag=<?php echo $x ?>">
                <?php echo $x ?></a>
        </li>
    <?php } ?>

    <?php if ($pagina < $paginas) { ?>
        <li>
            <a href="index.php?pagina=directorio&opciones=listar&pag=<?php echo $pagina + 1 ?>">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    <?php } ?>
</ul>