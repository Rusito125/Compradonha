<?php
require_once '../archivosBD/ProductosBD.php';
require_once '../archivosBD/CarroBD.php';
require_once '../archivosBD/UsuariosBD.php';
require_once '../archivosBD/CompraBD.php';
session_start();
if (isset($_SESSION["username"]) && isset($_GET["envio"])) {
    $url = "http://" . $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . "/perperpab/Compradonha/html-php/";
    $usuariosBD = new UsuariosBD();
    $carroBD = new CarroBD();
    $compraBD = new CompraBD();
    $usuario = $usuariosBD->getUsuarioByUsername($_SESSION["username"]);
    $productosCarro = $carroBD->getProductosCarro($usuario["id"]);
    if ($productosCarro != null) {
        $correcto = false;
        $correctoCarro = false;
        $ultima = $compraBD->getUltimaCompraGeneral();
        $idCompra = 0;
        if ($ultima != null) {
            $idCompra = ($ultima[0]["id"] + 1);
        }
        foreach ($productosCarro as $productoCarro) {
            if ($carroBD->borrarEvento($productoCarro["id"], $usuario["id"])) {
                if ($compraBD->setCompra($idCompra, $usuario["id"], $productoCarro["id"], $productoCarro["cantidad"])) {
                    $correcto = true;
                }
            }
        }

        if ($correcto) {
            if ($carroBD->vaciarCarro($usuario["id"])) {
                $correctoCarro = true;
            }
        }
        
        ?>
        <!DOCTYPE html>
        <!--
            TODO
                funciones y diseño hechos
        -->
        <html>
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <title>Gracias por su compra - Compradoña</title>
                <script src="https://kit.fontawesome.com/3b88ef1ad2.js" crossorigin="anonymous"></script>
                <link rel="stylesheet" type="text/css" href="../../estilos/normalize.css"/>
                <link rel="stylesheet" type="text/css" href="../../estilos/estilos.css"/>
                <link rel="stylesheet" type="text/css" href="../../estilos/estilosFinCompra.css"/>
                <link rel="icon" href="../../img/varios/favicon.png"/>        
            </head>
            <body>
                <?php
                require_once '../cabeceraFooter.php';
                $cabeceraFooter = new CabeceraFooter();
                $cabeceraFooter->cabecera();
                ?>
                <main>
                    <section>
                        <h1>Gracias por comprar con nosotros</h1>   
                        <div id="productos">
                            <?php
                            if ($correctoCarro) {
                                $ultimaCompra = $compraBD->getUltimaCompra($usuario["id"]);
                                $sumaProductos = 0;
                                foreach ($ultimaCompra as $producto) {
                                    ?>
                                    <div class="producto">
                                        <div class="imagen"><img onclick="location.href = '<?= $url ?>productos/producto.php?id=<?= $producto["id_producto"] ?>'" src="data:image/jpg;base64,<?= base64_encode($producto['imagen']) ?>"/></div>
                                        <div class="datos">
                                            <a href="<?= $url ?>productos/producto.php?id=<?= $producto["id_producto"] ?>"><b><?= $producto["nombre"] ?></b></a>
                                            <span><?= $producto["precio"] ?>€</span>
                                            <span>Cantidad: <?= $producto["cantidad"] ?></span>
                                        </div>  
                                    </div>
                                    <?php
                                    $sumaProductos += ($producto["precio"] * $producto["cantidad"]);
                                }
                            }
                            ?>
                            <div><h4>Envío: <?= $_GET["envio"] ?>€</h4></div>
                            <div><h3>Precio Total: <?= ($sumaProductos + $_GET["envio"]) ?>€</h3></div>
                        </div>
                    </section>
                </main>
                <?php
                $cabeceraFooter->footer();
                ?>
            </body>
        </html>
        <?php
    } else {
        header("Location: ../../index.php");
    }
} else {
    header("Location: ../../index.php");
}
?>
    
