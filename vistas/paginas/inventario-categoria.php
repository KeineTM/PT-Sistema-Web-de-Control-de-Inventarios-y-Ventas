<?php
$lista_categorias = ControladorProductos::ctrlCategorias();

?>
<article class="contenedor__formularios-categorias">
    <section id="formulario-categorias">
        <span class="formulario__encabezado">
            <img class="formulario__icono" src="vistas/img/file-invoice.svg" alt="Formulario">
            <h2>Formulario de Registro de categoría</h2>
            <span class="alerta" id="alerta-categoria"></span>
        </span>

        <form action="post" id="formulario-alta-categoria">
            <label for="categoria-txt">Agregar categoría</label>
            <input class="campo mayusculas" type="text" placeholder="Categoria" id="categoria-txt" name="categoria-txt" autocomplete="off" minlength="3" maxlength="50" required>

            <fieldset class="formulario__botones-contenedor">
                <button class="boton-form enviar" id="btnRegistrarCategoria">Agregar</button>
            </fieldset>
        </form>
    </section>

    <hr>

    <section id="formulario-categorias">
        <span class="formulario__encabezado">
            <img class="formulario__icono" src="vistas/img/pen-to-square.svg" alt="Edicion">
            <h2>Formulario de Edición de categoría</h2>
            <span class="alerta" id="alerta-edicion-categoria"></span>
        </span>
        <form action="post" id="formulario-edicion-categoria">
            <label for="categoriaProducto-txt">Seleccione la categoría a editar:</label>
            <select class="campo mayusculas" id="categoriaProducto-txt" name="categoriaProductoEditar-txt" data-form="categoriaID" required>
                <option disabled selected>SELECCIONE CATEGORÍA...</option>
                <?php
                foreach($lista_categorias as $categoria) { ?>
                <option class="mayusculas" value="<?= $categoria['categoria_id'] ?>">
                    <?= $categoria['categoria'] ?>
                </option>'
                <?php } ?>
            </select>

            <label for="categoria_editar-txt">Nombre de la categoría:</label>
            <input class="campo mayusculas" type="text" placeholder="Categoria" id="categoria_editar-txt" name="categoria_editar-txt" autocomplete="off" minlength="3" maxlength="50" required>

            Para retirar esta categoría de las opciones del Inventario seleccione la opción 'Dar de baja' y después haga clic en 'Editar'.<br><br>
            Para reintegrarla, seleccione la opción 'Activo' y después haga clic en 'Editar'.
            <fieldset class="formulario__fieldset-2-columnas">
                <label for="estado-activo">Activo</label>
                <input type="radio" id='estado-activo' name="estadoCategoria-txt" value=1 data-form="estado" required>
                <label for="estado-inactivo">Dar de baja</label>
                <input type="radio" id='estado-inactivo' name="estadoCategoria-txt" value=0 data-form="estado" required>
            </fieldset>

            <fieldset class="formulario__botones-contenedor">
                <button class="boton-form enviar" id="btnEditarCategoria">Editar</button>
            </fieldset>
        </form>
    </section>
</article>