<?php
session_start();
require_once '../archivosBD/ProductosBD.php';
require_once '../cabeceraFooter.php';
require_once '../archivosBD/CarroBD.php';
require_once '../archivosBD/UsuariosBD.php';
$url = "http://" . $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . "/perperpab/Compradonha/html-php/";

function mostrarCarro($carroBD, $idUsuario) {
    $productosCarro = $carroBD->getProductosCarro($idUsuario);
    if ($productosCarro != null) {
        $precioTotal = 0;
        foreach ($productosCarro as $productoCarro) {
            $precioTotal += ($productoCarro["cantidad"] * $productoCarro["precio"]);
            ?>
            <div class="productoCarro">
                <div>
                    <h3><a href="<?= $GLOBALS["url"] ?>productos/producto.php?id=<?= $productoCarro["id"] ?>"><?= $productoCarro["nombre"] ?></a></h3>
                    <h4><?= $productoCarro["precio"] ?>€</h4>
                    <h4>Cantidad: <?= $productoCarro["cantidad"] ?> <a onclick="borrarCarroProducto(<?= $productoCarro["id"] ?>)"><i class="fa-solid fa-trash-can"></i></a></h4>
                </div>
                <img src="data:image/jpg;base64,<?= base64_encode($productoCarro['imagen']) ?>" onclick="verProducto(<?= $productoCarro["id"] ?>)">
                <script>
                    function verProducto(idProducto) {
                        location.href = "<?= $GLOBALS["url"] ?>productos/producto.php?id=" + idProducto;
                    }
                </script>
            </div>
            <?php
        }
        ?>
        <h3>Precio total: <?= $precioTotal ?>€</h3>
        <button class='caja' onclick="location.href = '<?= $GLOBALS["url"] ?>compras/compra.php'">Pasar a caja</button>
        <?php
    } else {
        ?>
        <h3>No hay productos en el carro</h3>
        <button class='caja' onclick="location.href = '<?= $GLOBALS["url"] ?>productos/productos.php'">Empezar a comprar</button>
        <?php
    }
}

$_action = isset($_GET["action"]) ? $_GET["action"] : "";

// conexión con ajax para borrar producto del carro
if (isset($_GET["id"]) && isset($_GET["action"]) && $_GET["action"] == "borrar") {
    $usuariosBD = new UsuariosBD();
    $usuario = $usuariosBD->getUsuarioByUsername($_SESSION["username"]);
    $carroBD = new CarroBD();
    $productosBD = new ProductosBD();
    $cantidad = $carroBD->getProductoCarro($usuario["id"], $_GET["id"])["cantidad"];
    if ($carroBD->borrarProductoCarro($usuario["id"], $_GET["id"])) {
        if ($productosBD->ajustarInventario($_GET["id"], -$cantidad)) {
            if ($carroBD->borrarEvento($_GET["id"], $usuario["id"])) {
                mostrarCarro($carroBD, $usuario["id"]);
            }
        } else {
            echo "<h2>Error al actualizar el inventario</h2>";
        }
    } else {
        echo "<h2>Error al borrar el producto del carro</h2>";
    }
} else {
    ?>
    <!DOCTYPE html>
    <!-- 
        TODO
            funciones y diseño acabados        
    --> 
    <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Carro - Compradoña</title>
            <script src="https://kit.fontawesome.com/3b88ef1ad2.js" crossorigin="anonymous"></script>
            <link rel="stylesheet" type="text/css" href="../../estilos/normalize.css"/>
            <link rel="stylesheet" type="text/css" href="../../estilos/estilos.css"/>
            <link rel="stylesheet" type="text/css" href="../../estilos/estilosCarro.css"/>
            <link rel="icon" href="../../img/varios/favicon.png"/>        
        </head>
        <body>  
            <?php
            $cabeceraFooter = new CabeceraFooter();
            $cabeceraFooter->cabecera();
            ?>
            <main>
                <div id='carroGordo'>
                    <?php
                    if (isset($_SESSION["username"])) {
                        $carroBD = new CarroBD();
                        $usuariosBD = new UsuariosBD();
                        $usuario = $usuariosBD->getUsuarioByUsername($_SESSION["username"]);
                        mostrarCarro($carroBD, $usuario["id"]);
                    } else {
                        ?>
                        <h3>Inicie sesión para empezar a comprar</h3>
                        <button class='caja' onclick="location.href = '<?= $url ?>usuarios/iniciar.php?action=iniciar'">Iniciar sesión</button>
                        <?php
                    }
                    ?>
                </div>
            </main>
            <script>
                function borrarCarroProducto(idProducto) {
                    var xhttp = new XMLHttpRequest();
                    xhttp.onreadystatechange = function () {
                        if (this.readyState == 4 && this.status == 200) {
                            document.getElementById("carroGordo").innerHTML = this.responseText;
                        }
                    };
                    xhttp.open("GET", "<?= $url ?>compras/carro.php?action=borrar&id=" + idProducto, true);
                    xhttp.send();
                }
            </script>
            <?php
            $cabeceraFooter->footer();
            ?>
        </body>
    </html>
    <?php
}
?>   
