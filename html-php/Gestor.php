<!DOCTYPE html>
<!--
    TODO
        faltan estilos
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Gestión Productos</title>
    </head>
    <body>
        <?php
        require_once './archivosBD/ProductosBD.php';

        $productosBD = new ProductosBD();

        $_action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'escoge';
        switch ($_action) {
            case 'add':
                $productosBD->cargarProductoGestor();
                break;
            case 'ver':
                $productosBD->verProductosGestor();
                break;
            case 'editar':
                $productosBD->cargarProductoGestor($_GET["id"]);
                break;
            case 'borrar':
                $productosBD->borrarProductoGestor($_GET["id"]);
                break;
            case 'escoge':
            default:
                escogeGestion();
                break;
        }

        function escogeGestion() {
            ?>
            <h2>Bienvenido al apartado de gestión</h2>
            <h3>¿Qué desea realizar?</h3>
            <a href="?action=add">Cargar un producto</a>
            <a href="?action=ver">Ver productos</a>
            <a href="../index.php">Página principal</a>
            <?php
        }
        ?>
    </body>
</html>
