FORMULARIO DE EDICION
<?php 
if(!isset($_GET['folio'])) {
    echo '<p class="destacado">No se ha selecionado ningún folio...</p>';
    return;
}

$folio = $_GET['folio'];

echo '<p class="destacado">Folio: ' . $folio . '<p class="destacado">';
?>