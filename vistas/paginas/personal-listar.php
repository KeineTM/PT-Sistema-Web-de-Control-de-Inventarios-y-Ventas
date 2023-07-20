<?php
$consulta = ControladorUsuarios::ctrlConsultarUsuarios();
$titulo = 'Directorio de empleados';
# Valida el resultado de la consulta
# Si no es una lista es porque retornó un error
# Si es una lista vacía es porque no encontró coincidencias
if (!is_array($consulta) || sizeof($consulta) === 0) {
    echo '<p class="alerta-roja">Ocurrió un error: No hay registros o hay un problema con la base de datos</p>';
    die();
}
?> 
<section class="contenedor__tabla">
    <h3 class="tabla__titulo"><?= $titulo ?></h3>
    <p>Puede acceder la información completa del empleado y editarla haciendo clic en los <span class="texto-rosa">Detalles</span> del ID de usuario.</p><br>
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
            <?php foreach($consulta as $usuario) {  ?>
            <tr>
                <td><a class="texto-rosa" href="index.php?pagina=personal&opciones=detalles&id=<?=$usuario['usuario_id']?>"><?=$usuario['usuario_id']?><br>Detalles</a></td>
                <td><?= $usuario['nombre'] . ' ' . $usuario['apellido_paterno'] . ' ' . $usuario['apellido_materno'] ?></td>
                <td><?= $usuario['telefono']?></td>
                <td><?= $usuario['rfc'] ?></td>
                <td><?= ($usuario['estado'] === 1) ?'Sí' :'No' ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <!-- ------------- Fin tabla ---------- -->
</section>