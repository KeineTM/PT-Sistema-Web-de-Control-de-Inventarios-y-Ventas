<!-- <form class="boton-main" id="barra-busqueda">
    <input type="text" class="campo" name="buscarEmpleado-txt" autocomplete="off" placeholder="Buscar..." minlength="3" maxlength="240" required>
    <button class="boton enviar" id="btnBuscar"><img src="vistas/img/magnifying-glass.svg" alt=""></button>
</form>
<span class="alerta" id="alertaBuscar"></span> -->


<?php
$registrosPorPagina = 20;
$pagina = 1;
$estado = (isset($_GET['ordenar'])) 
    ? (($_GET['ordenar'] === 'activos') ? '1' : '0')
    : '';

if (isset($_GET['pag'])) $pagina = intval($_GET['pag']);

$limit = $registrosPorPagina; # No. registros en pantalla
$offset = ($pagina - 1) * $registrosPorPagina; # Saltado de registros en páginas != 1

$conteo = ControladorUsuarios::ctrlConteoRegistros($estado); # Recupera el no. de registros

if ($conteo[0]['conteo'] === 0) {
    echo '<p class="alerta-roja">No hay contactos registrados.</p>';
    die();
}

// Calcula el no. de páginas totales
$paginas = ceil($conteo[0]['conteo'] / $registrosPorPagina);

// Retorna los registros por página
$consulta = ControladorUsuarios::ctrlLeerParaPaginacion($limit, $offset, $estado);

$titulo = 'Directorio de empleados';

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
    <p>Puede acceder la información completa del empleado y editarla haciendo clic en los <span class="texto-rosa">Detalles</span> del ID de usuario.</p><br>

    <label for="lista-filtrar-txt">Filtrar por:</label>
    <select name="lista-filtrar-txt" id="lista-filtrar-txt" class="campo ancho-automatico">
        <option disabled selected>Seleccionar...</option>
        <option value="Activos">Activos</option>
        <option value="Inactivos">Inactivos</option>
        <option value="Todos">Todos</option>
    </select>
    <br><br>

    <!-- -------------Tabla ---------- -->
    <table class="tabla">
        <thead>
            <tr>
                <th>ID Usuario</th>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th>RFC</th>
                <th>Activo</th>
            </tr>
        </thead>
        <tbody>
            <!-- Contenido -->
            <?php foreach ($consulta as $usuario) {  ?>
                <tr>
                    <td><a class="texto-rosa" href="index.php?pagina=personal&opciones=detalles&id=<?= $usuario['usuario_id'] ?>"><?= $usuario['usuario_id'] ?><br>Detalles</a></td>
                    <td><?= $usuario['nombre'] . ' ' . $usuario['apellido_paterno'] . ' ' . $usuario['apellido_materno'] ?></td>
                    <td><?= $usuario['telefono'] ?></td>
                    <td><?= $usuario['rfc'] ?></td>
                    <td><?= ($usuario['estado'] === 1) ? 'Sí' : 'No' ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <!-- ------------- Fin tabla ---------- -->
</section>

<ul class="paginacion">
    <?php if ($pagina > 1) { ?>
        <li>
            <a href="index.php?pagina=personal&opciones=listar&pag=<?php echo $pagina - 1 ?>">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
    <?php } ?>

    <?php for ($x = 1; $x <= $paginas; $x++) { ?>
        <li class="<?php if ($x == $pagina) echo "activa" ?>">
            <a href="index.php?pagina=personal&opciones=listar&pag=<?php echo $x ?>">
                <?php echo $x ?></a>
        </li>
    <?php } ?>

    <?php if ($pagina < $paginas) { ?>
        <li>
            <a href="index.php?pagina=personal&opciones=listar&pag=<?php echo $pagina + 1 ?>">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    <?php } ?>
</ul>