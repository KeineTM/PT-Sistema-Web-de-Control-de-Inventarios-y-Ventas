<?php
    if(isset($_SESSION['validarSesion']))
    $usuarioLogeado = ControladorUsuarios::ctrlConsultarUsuarios($_SESSION['idUsuarioSesion'])
?>

<span class="boton-abrir" id="btn-abrir-menu"></span>

<aside class="menu-contenedor" id="menu-contenedor">
    <nav class="menu-lista">
        <ul>
            <li class="menu__opciones"><a href="index.php?pagina=inicio-usuario" class="menu__opciones-url"><img src="vistas/img/house.svg" alt="Inicio" class="icono-menu">Inicio</a></li>
            <li class="menu__opciones">
                <a href="index.php?pagina=ventas" class="menu__opciones-url"><img src="vistas/img/cash-register.svg" alt="Ventas" class="icono-menu">Ventas</a>
                
            </li>
            <li class="menu__opciones"><a href="index.php?pagina=apartados" class="menu__opciones-url"><img src="vistas/img/handshake-angle.svg" alt="Apartados" class="icono-menu">Apartados</a></li>
            <li class="menu__opciones"><a href="index.php?pagina=devoluciones" class="menu__opciones-url"><img src="vistas/img/rotate-left.svg" alt="Devoluciones" class="icono-menu">Devoluciones</a></li>
            <li class="menu__opciones"><a href="index.php?pagina=inventario" class="menu__opciones-url"><img src="vistas/img/tags.svg" alt="Inventario" class="icono-menu">Inventario</a></li>
            <li class="menu__opciones"><a href="index.php?pagina=directorio" class="menu__opciones-url"><img src="vistas/img/address-book.svg" alt="Directorio" class="icono-menu">Directorio</a></li>
            
            <?php if($_SESSION['tipoUsuarioSesion'] === 'Administrador') echo ('
            
            <li class="menu__opciones"><a href="index.php?pagina=reportes" class="menu__opciones-url"><img src="vistas/img/chart-line.svg" alt="Reportes" class="icono-menu">Reportes</a></li>
            <li class="menu__opciones"><a href="index.php?pagina=personal" class="menu__opciones-url"><img src="vistas/img/users-line.svg" alt="Personal" class="icono-menu">Personal</a></li>
            
            '); else ?>
            
            <li class="menu__opciones"><a href="index.php?pagina=empresa" class="menu__opciones-url"><img src="vistas/img/shop.svg" alt="Empresa" class="icono-menu">Empresa</a></li>
            <li class="menu__opciones"><a href="index.php?pagina=salir" class="menu__opciones-url"><img src="vistas/img/right-from-bracket.svg" alt="Salir" class="icono-menu"> Salir</a></li>
            
        </ul>
    </nav>
</aside>
<script src="vistas/js/modulos/menu-despliegue.js"></script>
