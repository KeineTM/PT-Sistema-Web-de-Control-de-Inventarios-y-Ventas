<!-- Esta plantilla está modularizada, funcionando como una estructura que llama a los elementos que la componen.
    
    Debido a que es incluída desde el index.php, todas las url en HTML deben considerarse desde la ubicación de este último,
    pero las url de php se consideran desde la ubicación de plantilla.php.
    
    El flujo es el siguiente: 
    index.php   ->  controlador/ctrlPlantilla   <->  vistas/plantilla.php   ->  index.php
                    ^-(cuando se necesite)modelo/?.php
-->

<?php
    # Recordar que al trabajar con sesiones, cada página del sitio en php debe iniciar con session_start();
    # En este caso sólo se usa en esta página plantilla, ya que su contenido es dinámico
    session_start();
?>

<!-- Inicia el código de la plantilla -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Carga el título correspondiente a la página -->
    <title><?php echo ControladorPlantilla::ctrlTitulo() ?></title>

    <!-- Hojas de estilos -->
    <link rel="stylesheet" href="vistas/css/reset.css">
    <link rel="stylesheet" href="vistas/css/general.css">
    <link rel="stylesheet" href="vistas/css/menu.css">
    <link rel="stylesheet" href="vistas/css/header.css">  
    <link rel="stylesheet" href="vistas/css/footer.css">
    <link rel="stylesheet" href="vistas/css/paginas/login.css">

    <!-- Fuentes -->
    <link href="https://fonts.googleapis.com/css2?family=Kalam&family=Montserrat:wght@300;400;700&family=Open+Sans:wght@300;400;700&display=swap" rel="stylesheet">

</head>

<body>
    <header>
        <?php include "modulos/header.php" ?>

        <!-- Valida la existencia de una sesión activa para incluir el menú -->
        <?php if(isset($_SESSION['validarSesion'])) include "modulos/menu.php" ?>
    </header>

    <main>
        <?php
            ##########################################################################################
            # Controlador de contenido de la página
            # Valida la sesión, determina el tiempo de usuario y muestra el contenido correspondiente
            ControladorPlantilla::ctrlContenido();
        ?>
    </main>
    
    <footer>
        <?php include "modulos/footer.php" ?>
    </footer>
</body>
</html>