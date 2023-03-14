<!-- Esta plantilla está modularizada, 
funcionando como una estructura que llama a los elementos que la componen.
Debido a que es llamada desde el index.php, 
todas las url en HTML deben considerarse desde la ubicación de este último,
pero las url de php se consideran desde la ubicación de plantilla.php. -->
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
        <!-- Esta clase se llama gracias a que ya fue invocada desde el index.php que llama a la plantilla.php
        Evita cargar el menú si la url se dirige al login -->
        <?php ControladorPlantilla::ctrlMenu() ?>
    </header>

    <main>
        <!--Aquí se incluye el contenido de main de acuerdo con el valor de pagina en el url: -->
        <?php ControladorPlantilla::ctrlContenido() ?>
    </main>
    
    <footer>
        <?php include "modulos/footer.php" ?>
    </footer>
</body>
</html>