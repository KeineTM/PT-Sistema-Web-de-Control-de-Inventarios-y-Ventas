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
    <title>Gestor de Inventarios y Punto de Venta</title>

    <!-- Hojas de estilos -->
    <link rel="stylesheet" href="vistas/css/reset.css">
    <link rel="stylesheet" href="vistas/css/general.css">
    <link rel="stylesheet" href="vistas/css/main/login.css">
    <link rel="stylesheet" href="vistas/css/footer/footer.css">    

    <!-- Fuentes -->
    <link href="https://fonts.googleapis.com/css2?family=Kalam&family=Montserrat:wght@300;400;700&family=Open+Sans:wght@300;400;700&display=swap" rel="stylesheet">

</head>
<body>
    <header>
    </header>

    <main>
        <?php include "modulos/main/login.php" ?>
    </main>
    
    <footer>
        <?php include "modulos/footer/footer.php" ?>
    </footer>

    <script src="vistas/js/main/validacion.js"></script>
</body>
</html>