<!DOCTYPE html>
<!--
    TODO
        quitar hardcoded javascript
        vaciar inventario solo cuando se haga la compra y no cuando se añada al carro (o dejar así como si al añadir a la cesta fueran reservados)
-->
<html>
    <head>
        <meta charset="UTF-8">
        <?php
        session_start();
        require_once '../archivosBD/ProductosBD.php';
        require_once '../archivosBD/CarroBD.php';
        require_once '../archivosBD/UsuariosBD.php';
        $usuariosBD = new UsuariosBD();
        $carroBD = new CarroBD();
        $productosBD = new ProductosBD();
        $producto = $productosBD->getProductoPorId($_GET["id"]);
        ?>
        <title><?= $producto["nombre"] ?></title>
        <script src="https://kit.fontawesome.com/3b88ef1ad2.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" type="text/css" href="../../estilos/normalize.css"/>
        <link rel="stylesheet" type="text/css" href="../../estilos/estilos.css"/>
        <link rel="stylesheet" type="text/css" href="../../estilos/estilosProducto.css"/>
        <link rel="icon" href="../../img/varios/favicon.png"/>
    </head>
    <body>
        <?php
        require_once '../cabeceraFooter.php';
        $cabeceraFooter = new CabeceraFooter();
        $cabeceraFooter->cabecera();
        $value = 1;
        $productoCarro = null;
        if (isset($_SESSION["username"])) {
            $usuario = $usuariosBD->getUsuarioByUsername($_SESSION["username"]);
            $productoCarro = $carroBD->getProductoCarro($usuario["id"], $_GET["id"]);
            if ($productoCarro != null) {
                $value = $productoCarro["cantidad"];
            }
        }
        ?>
        <main>
            <section>  
                <div id="divImg"><img src="data:image/jpg;base64,<?= base64_encode($producto["imagen"]) ?>"/></div>
                <div id="info">
                    <h1><?= $producto["nombre"] ?></h1>
                    <h3><?= $producto["precio"] ?>€</h3>
                    <?= $producto["descripcion"] != null ? "<p>" . $producto["descripcion"] . "</p>" : "" ?>
                    <?php
                    if ($productoCarro == null && $producto["inventario"] == 0 || $productoCarro != null && ($producto["inventario"] + $productoCarro["cantidad"]) == 0) {
                        echo "<h2 style='color:red'>Producto agotado</h2>";
                    } else {
                        ?>
                        <form method="post">
                            <input type="number" name="cantidad" min="1" max="<?= $producto["inventario"] > 10 ? 10 : $producto["inventario"] + (($productoCarro != null) ? $productoCarro["cantidad"] : 0) ?>" value="<?= $value ?>"/>
                            <button name="<?= $producto["id"] ?>">Añadir a la cesta</button>                        
                        </form>
                    <?php } ?>
                </div>
                <?php
                if (isset($_POST[$producto["id"]])) {
                    if (isset($_SESSION["username"])) {
                        if ($carroBD->setProductoCarro($usuario["id"], $producto["id"], $_POST["cantidad"])) {
                            if ($productoCarro != null) {
                                $productosBD->ajustarInventario($producto["id"], -$productoCarro["cantidad"]);
                            }
                            if ($productosBD->ajustarInventario($producto["id"], $_POST["cantidad"])) {
                                if ($carroBD->crearEvento($producto["id"], $usuario["id"])) {
                                    ?>
                                    <script>
                                        location.href = "productos.php";
                                    </script>
                                    <?php
                                }
                            } else {
                                echo "Error al actualizar el inventario";
                            }
                        } else {
                            echo "Error al añadir el producto al carro";
                        }
                    } else {
                        ?>
                        <script>
                            alert("Debe iniciar sesión para poder comprar");
                            location.href = "../usuarios/iniciar.php?action=iniciar";
                        </script>
                        <?php
                    }
                }
                ?>
            </section>
        </main>
        <footer>
            <div>              
                <a href="../gestor.php">Contacto</a>
                <a>Quienes somos</a>
            </div>
        </footer>            
    </body>
</html>