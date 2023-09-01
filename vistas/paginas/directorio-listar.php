<?php ControladorContactos::ctrlBuscarTodos() ?>
<!-- Barra de búsqueda -->
<form class="boton-main" method="post" id="barra-busqueda">
    <input type="text" class="campo" name="buscarContacto-txt" autocomplete="off" placeholder="Teléfono o Nombre" minlength="3" maxlength="240" required>
    <button class="boton enviar" id="btnBuscar"><img src="vistas/img/magnifying-glass.svg" alt=""></button>
</form>
<span class="alerta" id="alertaBuscar"></span>
<!-- ------------------------------------------- -->

<?php
$registrosPorPagina = 20;
$pagina = 1;
$tipo = '';

if(isset($_GET['ordenar'])) {
    switch($_GET['ordenar']) {
        case('proveedores'): $tipo = 1; break;
        case('clientes'): $tipo = 2; break;
        case('servicios'): $tipo = 3; break;
    }
}

if (isset($_GET['pag'])) $pagina = intval($_GET['pag']);

$limit = $registrosPorPagina; # No. registros en pantalla
$offset = ($pagina - 1) * $registrosPorPagina; # Saltado de registros en páginas != 1

$conteo = ControladorContactos::ctrlConteoRegistros($id='', $tipo); # Recupera el no. de registros

if ($conteo[0]['conteo'] === 0) {
    echo '<p class="alerta-roja">No hay contactos registrados.</p>';
    die();
}

// Calcula el no. de páginas totales
$paginas = ceil($conteo[0]['conteo'] / $registrosPorPagina);

// Retorna los registros por página
$consulta = ControladorContactos::ctrlLeerParaPaginacion($limit, $offset, $tipo);

$titulo = 'Directorio de contactos';
# Valida el resultado de la consulta
# Si no es una lista es porque retornó un error
# Si es una lista vacía es porque no encontró coincidencias
if (!is_array($consulta) || sizeof($consulta) === 0) {
    echo '<p class="alerta-roja">Ocurrió un error: No hay registros o hay un problema con la base de datos</p>';
    die();
}
?>

<p>Página <?= $pagina ?> de <?= $paginas ?></p>

<section class="contenedor__tabla">
    <h3 class="tabla__titulo"><?= $titulo ?></h3>
    <p>Puede acceder la información completa del contacto y editarlos haciendo clic en los <span class="texto-rosa">Detalles</span> del número de teléfono.</p><br>
    
    <label for="lista-filtrar-txt">Filtrar por:</label>
    <select name="lista-filtrar-txt" id="lista-filtrar-txt" class="campo ancho-automatico">
        <option disabled selected>Seleccionar...</option>
        <option value="Clientes">Clientes</option>
        <option value="Servicios">Servicios</option>
        <option value="Proveedores">Proveedores</option>
        <option value="Todos">Todos</option>
    </select>
    <br><br>
    
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