<?php
    if(isset($_SESSION['validarSesion']))
    $usuarioLogeado = ControladorUsuarios::ctrlConsultarUsuarios($_SESSION['idUsuarioSesion'])
?>

<span class="boton-abrir" id="btn-abrir-menu"></span>

<aside class="menu-contenedor" id="menu-contenedor">
    <nav class="menu-lista">
        <ul class="menu-principal">
            <li class="menu__opciones" id="inicio"><a class="menu__opciones-url destacado" href="index.php?pagina=inicio-usuario" class="menu__opciones-url"><img src="vistas/img/house.svg" alt="Inicio" class="icono-menu">Inicio</a></li>
            <li class="menu__opciones">
                <a class="menu__opciones-url destacado" href="#"><img src="vistas/img/cash-register.svg" alt="Ventas" class="icono-menu">Ventas</a>
                <ul class="menu-secundario">
                    <li class="menu-secundario__opciones"><a class="" href="index.php?pagina=ventas&opciones=alta">Crear Venta</a></li>
                    <li class="menu-secundario__opciones"><a class="" href="index.php?pagina=ventas&opciones=catalogo&pag=1">Buscar Producto</a></li>
                    <li class="menu-secundario__opciones"><a class="" href="index.php?pagina=ventas&opciones=listar&tiempo=dia">Ventas del día</a></li>
                    <li class="menu-secundario__opciones"><a class="" href="index.php?pagina=ventas&opciones=listar&tiempo=semana">Ventas de la semana</a></li>
                </ul>
            </li>
            <li class="menu__opciones">
                <a class="menu__opciones-url destacado" href="#" class="menu__opciones-url"><img src="vistas/img/handshake-angle.svg" alt="Apartados" class="icono-menu">Apartados</a>
                <ul class="menu-secundario">
                    <li class="menu-secundario__opciones"><a class="" href="index.php?pagina=apartados&opciones=alta">Crear Apartado</a></li>
                    <li class="menu-secundario__opciones"><a class="" href="index.php?pagina=apartados&opciones=listar">Apartados del mes</a></li>
                </ul>
            </li>
            <li class="menu__opciones">
                <a class="menu__opciones-url destacado" href="#" class="menu__opciones-url"><img src="vistas/img/rotate-left.svg" alt="Devoluciones" class="icono-menu">Devoluciones</a>
                <ul class="menu-secundario">
                    <li class="menu-secundario__opciones"><a class="" href="index.php?pagina=devoluciones&opciones=alta">Crear Devolución</a></li>
                    <li class="menu-secundario__opciones"><a class="" href="index.php?pagina=devoluciones&opciones=listar">Devoluciones del mes</a></li>
                </ul>
            </li>
            <li class="menu__opciones">
                <a class="menu__opciones-url destacado" href="#" class="menu__opciones-url"><img src="vistas/img/tags.svg" alt="Inventario" class="icono-menu">Inventario</a>
                <ul class="menu-secundario">
                    <li class="menu-secundario__opciones"><a class="" href="index.php?pagina=inventario&opciones=alta">Registrar Producto</a></li>
                    <li class="menu-secundario__opciones"><a class="" href="index.php?pagina=inventario&opciones=listar&pag=1">Catálogo</a></li>
                    <li class="menu-secundario__opciones"><a class="" href="index.php?pagina=inventario&opciones=categoria">Categorías</a></li>
                </ul>
            </li>
            <li class="menu__opciones">
                <a class="menu__opciones-url destacado" href="#" class="menu__opciones-url"><img src="vistas/img/address-book.svg" alt="Directorio" class="icono-menu">Directorio</a>
                <ul class="menu-secundario">
                    <li class="menu-secundario__opciones"><a class="" href="index.php?pagina=directorio&opciones=alta">Registrar Contacto</a></li>
                    <li class="menu-secundario__opciones"><a class="" href="index.php?pagina=directorio&opciones=listar">Lista de Contactos</a></li>
                </ul>
            </li>
            
            <?php if($_SESSION['tipoUsuarioSesion'] === 'Administrador') echo ('
            
            <li class="menu__opciones">
                <a class="menu__opciones-url destacado" href="index.php?pagina=reportes" class="menu__opciones-url"><img src="vistas/img/chart-line.svg" alt="Reportes" class="icono-menu">Reportes</a>
            </li>
            <li class="menu__opciones">
                <a class="menu__opciones-url destacado" href="#" class="menu__opciones-url"><img src="vistas/img/users-line.svg" alt="Personal" class="icono-menu">Personal</a>
                <ul class="menu-secundario">
                    <li class="menu-secundario__opciones"><a class="" href="index.php?pagina=personal&opciones=alta">Registrar Empleado</a></li>
                    <li class="menu-secundario__opciones"><a class="" href="index.php?pagina=personal&opciones=listar">Lista de Empleados</a></li>
                </ul>
            </li>
            
            ');?>
            
            <li class="menu__opciones">
                <a class="menu__opciones-url destacado" href="index.php?pagina=empresa" class="menu__opciones-url"><img src="vistas/img/shop.svg" alt="Empresa" class="icono-menu">Empresa</a>
            </li>
            <li class="menu__opciones">
                <a class="menu__opciones-url destacado" href="index.php?pagina=salir" class="menu__opciones-url"><img src="vistas/img/right-from-bracket.svg" alt="Salir" class="icono-menu"> Salir</a>
            </li>
            
        </ul>
    </nav>
</aside>
<script src="vistas/js/modulos/menu-despliegue.js"></script>
