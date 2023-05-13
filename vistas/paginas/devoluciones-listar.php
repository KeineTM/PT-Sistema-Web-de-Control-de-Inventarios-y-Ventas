<?php
# Definición de fechas:
date_default_timezone_set('America/Mazatlan');

$fecha_fin = date("Y-m-d") . " 23:59:00"; # HOY
$fecha_inicio = date("Y-m-d", strtotime($fecha_fin."- 1 month")) . " 00:00:00"; # HACE UN MES
$titulo = 'Tabla de devoluciones el mes';

$consulta = ControladorOperaciones::ctrlLeerOperacionesPorRangoDeFecha($fecha_inicio, $fecha_fin, 'DE');

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
foreach($consulta as $fila) {
    $operacion = [
        'operacion_id' => $fila['operacion_id'],
        'subtotal' => $fila['subtotal'],
        'total' => $fila['total'],
        'notas' => $fila['notas'],
        'metodo' => $fila['metodo'],
        'fecha' => $fila['fecha'],
        'empleado_id' => $fila['empleado_id'],
        'nombre_completo' => $fila['nombre_completo']
    ];
    array_push($lista_operaciones, $operacion);
}

# 2 Elimina operaciones duplicadas (cuando incluyen más de 1 producto)
$lista_operaciones = array_unique($lista_operaciones, SORT_REGULAR);
?> 
<section class="contenedor__tabla">
    <h3 class="tabla__titulo"><?= $titulo ?></h3>
    <p>Puede acceder la información completa de la devolución y editarlos haciendo clic en los <span class="texto-rosa">Detalles</span> de la devolución.</p><br>
    <!-- -------------Tabla de devoluciones por tiempo ---------- -->
    <table class="tabla">
        <thead>
            <tr>
                <th>Folio</th>
                <th>Productos<br>incluidos</th>
                <th>Subtotal</th>
                <th>Total</th>
                <th>Motivo</th>
                <th>Método<br>de<br>pago</th>
                <th>Fecha<br>y<br>hora</th>
                <th>Empleado</th>
            </tr>
        </thead>
        <tbody>
            <!-- Contenido -->
            <?php foreach($lista_operaciones as $operacion) {  ?>
            <tr>
                <td><a class="texto-rosa" href="index.php?pagina=devoluciones&opciones=detalles&folio=<?=$operacion['operacion_id']?>"><?= preg_replace('/^0+/', '',$operacion['operacion_id'])?><br>Detalles</a></td>
                <td>
                    <ol class="celda__lista">
                    <?php 
                    # Enlista los productos correspondientes al id
                    for($i = 0; $i < sizeof($consulta); $i++) {
                        if($consulta[$i]['operacion_id'] == $operacion['operacion_id'])
                            echo '<li>' . $consulta[$i]['unidades'] . " x " . $consulta[$i]['nombre'] . '</li>';
                    }
                    ?>
                    </ol>
                </td>
                <td>$<?= $operacion['subtotal'] ?></td>
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
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <!-- ------------- Fin tabla ---------- -->
</section>