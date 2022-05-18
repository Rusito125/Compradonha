<!DOCTYPE html>
<!--
    TODO
        quitar hardcoded javascript
        falta cambiar $_SESSION["carro"] por acceso a BD carro
-->
<html>
    <head>
        <meta charset="UTF-8">
        <?php
        session_start();
        require_once '../archivosBD/ProductosBD.php';
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
        ?>
        <main>
            <section>  
                <div id="divImg"><img src="data:image/jpg;base64,<?= base64_encode($producto["imagen"]) ?>"/></div>
                <div id="info">
                    <h1><?= $producto["nombre"] ?></h1>
                    <h3><?= $producto["precio"] ?>€</h3>
                    <?= $producto["descripcion"] != null ? "<p>" . $producto["descripcion"] . "</p>" : "" ?>
                    <form method="post">
                        <button name="<?= $producto["id"] ?>">Añadir a la cesta</button>
                    </form>
                </div>
                <?php
                if (isset($_POST[$producto["id"]])) {
                    if (isset($_SESSION["username"])) {
                        $_SESSION["carro"][] = $producto["id"];
                        ?>
                <script>
                    location.href = "productos.php";
                </script>
                <?php
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