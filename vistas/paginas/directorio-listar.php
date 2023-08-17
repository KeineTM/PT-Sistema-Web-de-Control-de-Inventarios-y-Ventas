<form class="boton-main" id="barra-busqueda">
    <input type="text" class="campo" name="buscarContacto-txt" autocomplete="off" placeholder="Teléfono o Nombre" minlength="3" maxlength="240" required>
    <button class="boton enviar" id="btnBuscar"><img src="vistas/img/magnifying-glass.svg" alt=""></button>
</form>
<span class="alerta" id="alertaBuscar"></span>

<?php
$consulta = ControladorContactos::ctrlLeer();
$titulo = 'Directorio de contactos';
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