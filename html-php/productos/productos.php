<!DOCTYPE html>
<!-- 
    quitar hardcoded javascript
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Productos</title>
        <script src="https://kit.fontawesome.com/3b88ef1ad2.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" type="text/css" href="../../estilos/normalize.css"/>
        <link rel="stylesheet" type="text/css" href="../../estilos/estilos.css"/>
        <link rel="stylesheet" type="text/css" href="../../estilos/estilosProductos.css"/>
        <link rel="icon" href="../../img/varios/favicon.png"/>
    </head>
    <body>
        <?php
        session_start();
        require_once '../archivosBD/ProductosBD.php';

        $productosBD = new ProductosBD();
        require_once '../cabeceraFooter.php';
        $cabeceraFooter = new CabeceraFooter();
        $cabeceraFooter->cabecera();
        ?>        
        <main>                          
            <aside>                
                <input type="checkbox" id="menu-bar"/>
                <label for="menu-bar"><i class="fa-solid fa-bars"></i></label>                
                <div id="filtro">
                    <h2>Filtrar</h2>
                    <div>
                        <?php
                        if (!isset($_POST["limpiar"])) {
                            foreach ($_POST as $key => $value) {
                                if (substr($key, 0, 4) == "tipo") {
                                    $marcados[] = $value;
                                }
                            }
                        }

                        if (!isset($marcados)) {
                            $marcados = null;
                        }

                        $tiposProducto = $productosBD->getTiposProducto();
                        echo "<form id='casillas' method='post'>";
                        foreach ($tiposProducto as $tipo) {
                            ?>
                            <div><input type='checkbox' value="<?= $tipo["id"] ?>" name='tipo<?= $tipo["id"] ?>' id='tipo<?= $tipo["id"] ?>' onchange='cambiaTipo()' <?= $marcados != null ? (in_array($tipo["id"], $marcados) ? "checked" : "") : "" ?>/> <label for='tipo<?= $tipo["id"] ?>'><?= $tipo["tipo"] ?></label></div>
                        <?php } ?>
                        <button name='limpiar'>Limpiar Filtros</button>
                        </form>
                    </div>
                    <script>
                        function cambiaTipo(e) {
                            casillas = document.getElementById("casillas");
                            casillas.submit();
                        }
                    </script>
                </div>
            </aside>

            <section>
                <h1>Lista de productos</h1>
                <article>
                    <?php
                    if ($marcados != null && $marcados[0] != "") {
                        foreach ($marcados as $marcado) {
                            $productos = $productosBD->getProductosDeTipo($marcado);
                            if ($productos != null) {
                                mostrarProductos($productos);
                            } else {
                                echo '<h2>No hay productos de ese tipo <i class="fa-solid fa-face-sad-tear"></i></h2>';
                            }
                        }
                    } else {
                        $productos = $productosBD->getProductos();
                        mostrarProductos($productos);
                    }

                    function mostrarProductos($productos) {
                        foreach ($productos as $producto) {
                            ?>
                            <div class="producto">
                                <a href="producto?id=<?= $producto["id"] ?>"><img src="data:image/jpg;base64,<?= base64_encode($producto["imagen"]) ?>"/></a>
                                <div><a href="producto?id=<?= $producto["id"] ?>"><h3><?= $producto["nombre"] ?></h3></a><a href="producto?id=<?= $producto["id"] ?>"><h4><?= $producto["precio"] ?>€</h4></a></div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </article>
            </section>            
        </main>
        <?php
        $cabeceraFooter->footer();
        ?>
    </body>
</html>
