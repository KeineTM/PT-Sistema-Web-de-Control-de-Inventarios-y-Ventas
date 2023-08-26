<?php 
$modulo = $_GET['pagina'];
ControladorOperaciones::ctrlBuscarTodos($modulo)  
?>
<!-- Barra de búsqueda -->
<form class="boton-main" method="post" id="barra-busqueda">
    <input type="number" step="any" class="campo" name="buscarOperacion-txt" autocomplete="off" id="buscarOperacion-txt" placeholder="Buscar..." maxlength="18" min='1' required>
    <button class="boton enviar" id="btnBuscarOperacion"><img src="vistas/img/magnifying-glass.svg" alt=""></button>
</form>
<span class="alerta" id="alertaBuscar"></span>
<!-- ------------------------------------------- -->

<?php
# Definición de fechas:
date_default_timezone_set('America/Mexico_City');

$fecha_fin = date("Y-m-d") . " 23:59:00"; # HOY
$fecha_inicio = date("Y-m-d", strtotime($fecha_fin . "- 1 month")) . " 00:00:00"; # HACE UN MES
$titulo = 'Tabla de apartados el mes';

$tipo_operacion_id = 'AP';
$registrosPorPagina = 20;
$pagina = 1;

if (isset($_GET['pag'])) $pagina = intval($_GET['pag']);

$limit = $registrosPorPagina; # No. registros en pantalla
$offset = ($pagina - 1) * $registrosPorPagina; # Saltado de registros en páginas != 1

$modelo = new ModeloOperaciones();
$conteo = $modelo->mdlConteoRegistros($fecha_inicio, $fecha_fin, $tipo_operacion_id); # Recupera el no. de registros

if ($conteo[0]['conteo'] === 0) {
    echo '<p class="alerta-roja">No hay operaciones registradas.</p>';
    die();
}

// Calcula el no. de páginas totales
$paginas = ceil($conteo[0]['conteo'] / $registrosPorPagina);

$consulta = ControladorOperaciones::ctrlLeerOperacionesPorRangoDeFecha($fecha_inicio, $fecha_fin, $tipo_operacion_id, $limit, $offset);

# Valida el resultado de la consulta
# Si no es una lista es porque retornó un error
# Si es una lista vacía es porque no encontró coincidencias
if (!is_array($consulta) || sizeof($consulta) === 0) {
    echo '<p class="alerta-roja">No hay datos para estas fechas</p>';
    die();
}

#-------------- Organización de la información--------------
# 1 Extrae datos asociados a la tabla operaciones y abonos, 
$lista_operaciones = [];
foreach ($consulta as $fila) {
    $operacion = [
        'operacion_id' => $fila['operacion_id'],
        'subtotal' => $fila['subtotal'],
        'total' => $fila['total'],
        'notas' => $fila['notas'],
        'metodo' => $fila['metodo'],
        'fecha' => $fila['fecha'],
        'empleado_id' => $fila['empleado_id'],
        'nombre_completo' => $fila['nombre_completo'],
        'nombre_cliente' => $fila['nombre_cliente'],
        'estado' => $fila['estado']
    ];
    array_push($lista_operaciones, $operacion);
}

# 2 Elimina operaciones duplicadas (cuando incluyen más de 1 producto)
$lista_operaciones = array_unique($lista_operaciones, SORT_REGULAR);
?>
<section class="contenedor__tabla">
    <h3 class="tabla__titulo"><?= $titulo ?></h3>
    <p>Puede acceder la información completa del apartado y editarlos haciendo clic en <span class="texto-rosa">Detalles</span>.</p><br>
    <!-- -------------Tabla de apartados por tiempo ---------- -->
    <table class="tabla">
        <thead>
            <tr>
                <th>Folio</th>
                <th>Cliente</th>
                <th>Total</th>
                <th>Notas</th>
                <th>Método<br>de<br>pago</th>
                <th>Fecha<br>y<br>hora</th>
                <th>Empleado</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <!-- Contenido -->
            <?php foreach ($lista_operaciones as $operacion) {  ?>
                <tr>
                    <td><a class="texto-rosa" href="index.php?pagina=apartados&opciones=detalles&folio=<?= $operacion['operacion_id'] ?>"><?= preg_replace('/^0+/', '', $operacion['operacion_id']) ?><br>Detalles</a></td>
                    <td><?= $operacion['nombre_cliente'] ?></td>
                    <td>$<?= $operacion['total'] ?></td>
                    <td><?= $operacion['notas'] ?></td>
                    <td><?= $operacion['metodo'] ?></td>
                    <td>
                        <?php
                        $fecha_formateada = strtotime($operacion['fecha']);
                        setlocale(LC_TIME, 'es_ES.UTF-8');
                        #echo strftime("%A, %d de %B de %Y", $fecha_formateada);
                        $fecha_formateada = date_create($operacion['fecha']);
                        echo date_format($fecha_formateada, 'g:ia d/m/y')
                        ?>
                    </td>
                    <td><?= $operacion['nombre_completo'] ?></td>
                    <td><?php echo ($operacion['estado']) ? 'Pagado' : 'Pendiente' ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <!-- ------------- Fin tabla ---------- -->
</section>

<ul class="paginacion">
    <?php if ($pagina > 1) { ?>
        <li>
            <a href="index.php?pagina=apartados&opciones=listar&pag=<?php echo $pagina - 1 ?>">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
    <?php } ?>

    <?php for ($x = 1; $x <= $paginas; $x++) { ?>
        <li class="<?php if ($x == $pagina) echo "activa" ?>">
            <a href="index.php?pagina=apartados&opciones=listar&pag=<?php echo $x ?>">
                <?php echo $x ?></a>
        </li>
    <?php } ?>

    <?php if ($pagina < $paginas) { ?>
        <li>
            <a href="index.php?pagina=apartados&opciones=listar&pag=<?php echo $pagina + 1 ?>">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    <?php } ?>
</ul>