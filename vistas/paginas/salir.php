<?php
    ControladorUsuarios::ctrlLogoutUsuarios();
    # En este apartado se usa un script de JS porque una vez establecido un header con PHP no es posible modificarlo
    echo "<script>
            window.location = 'index.php';
        </script>";
?>