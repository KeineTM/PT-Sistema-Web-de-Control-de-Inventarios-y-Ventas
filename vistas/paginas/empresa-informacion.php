<?php
$consulta = ControladorEmpresa::ctrlLeer();
$consulta_redes = ControladorEmpresa::leerRedSocial();

?>

<table class="tabla">
    <thead>
        <tr>
            <th colspan="2">
                <h2>Empresa <?= $consulta[0]['nombre_tienda'] ?></h2>
            </th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>RFC</td>
            <td><?= $consulta[0]['rfc'] ?></td>
        </tr>
        <tr>
            <td>Razón Social</td>
            <td><?= $consulta[0]['razon_social'] ?></td>
        </tr>
        <tr>
            <td>Nombre de la tienda</td>
            <td><?= $consulta[0]['nombre_tienda'] ?></td>
        </tr>
        <tr>
            <td>Descripción</td>
            <td><?= $consulta[0]['descripcion'] ?></td>
        </tr>
        <tr>
            <td>Calle</td>
            <td><?= $consulta[0]['calle'] ?></td>
        </tr>
        <tr>
            <td>Número</td>
            <td><?= $consulta[0]['numero'] ?></td>
        </tr>
        <tr>
            <td>Ciudad</td>
            <td><?= $consulta[0]['ciudad'] ?></td>
        </tr>
        <tr>
            <td>Estado</td>
            <td><?= $consulta[0]['estado'] ?></td>
        </tr>
        <tr>
            <td>Código Postal</td>
            <td><?= $consulta[0]['codigo_postal'] ?></td>
        </tr>
        <tr>
            <td>Email</td>
            <td><?= $consulta[0]['email'] ?></td>
        </tr>
        <tr>
            <td>Logo</td>
            <td><?= $consulta[0]['logo'] ?></td>
        </tr>
        <?php foreach ($consulta_redes as $red) { ?>
            <tr>
                <td><?= $red['nombre_red'] ?></td>
                <td><?= $red['url'] ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<?php if ($_SESSION['tipoUsuarioSesion'] === 'Administrador') { ?>
    <a class="boton-form enviar" href="index.php?pagina=empresa&opciones=editar">Editar<br>información</a>
<?php } ?>