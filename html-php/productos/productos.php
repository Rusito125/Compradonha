<!DOCTYPE html>
<!-- 
    quitar hardcoded javascript
    cambiar color de fondo de filtro de móvil en modo claro
    cambiar filtro a ajax
-->
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Productos - Compradoña</title>
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
                        $marcados = null;
                        if (!isset($_POST["limpiar"])) {
                            foreach ($_POST as $key => $value) {
                                if (substr($key, 0, 4) == "tipo") {
                                    $marcados[] = $value;
                                }
                            }
                        }
                        $orden = null;
                        if (!isset($_POST["limpiar"])) {
                            if (isset($_POST["filtroPrecio"])) {
                                $orden = $_POST["filtroPrecio"];
                                if ($orden == "") {
                                    $orden = null;
                                }
                            }
                        }

                        $hayMarcado = false;

                        $tiposProducto = $productosBD->getTiposProducto();
                        echo "<form id='casillas' method='post'>";
                        foreach ($tiposProducto as $tipo) {
                            ?>
                            <div><input type='checkbox' value="<?= $tipo["id"] ?>" name='tipo<?= $tipo["id"] ?>' id='tipo<?= $tipo["id"] ?>' onchange='cambiaTipo()' <?= $marcados != null ? (in_array($tipo["id"], $marcados) ? "checked" : "") : "" ?>/> <label for='tipo<?= $tipo["id"] ?>'><?= $tipo["tipo"] ?></label></div>
                        <?php } ?>
                        <select name="filtroPrecio" onchange="cambiaTipo()">
                            <option value="" <?= $orden == null ? "selected" : "" ?>>Orden normal</option>
                            <option value="bajo" <?= $orden == "bajo" ? "selected" : "" ?>>Precio más bajo</option>
                            <option value="alto" <?= $orden == "alto" ? "selected" : "" ?>>Precio más alto</option>
                        </select>
                        <button id='limpiar' name='limpiar'>Limpiar Filtros</button>                        
                        </form>
                    </div>
                    <script>
                        function cambiaTipo() {
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
                    if (isset($_GET["cuadroBusqueda"])) {
                        if ($orden != null) {
                            $productos = $productosBD->getProductosOrdenPrecio($orden);
                        } else {
                            $productos = $productosBD->getProductos();
                        }
                        $hayProductos = false;
                        foreach ($productos as $producto) {
                            if (strpos(strtoupper($producto["nombre"]), strtoupper($_GET["cuadroBusqueda"])) !== false || strpos(strtoupper($_GET["cuadroBusqueda"]), strtoupper($producto["nombre"])) !== false || strpos(strtoupper($producto["descripcion"]), strtoupper($_GET["cuadroBusqueda"])) !== false) {
                                if ($marcados != null) {
                                    foreach ($marcados as $marcado) {
                                        if ($producto["id_tipo"] == $marcado) {
                                            $hayProductos = true;
                                            mostrarProducto($producto);
                                        }
                                    }
                                } else {
                                    $hayProductos = true;
                                    mostrarProducto($producto);
                                }
                            }
                        }
                        if (!$hayProductos) {
                            echo '<h2>No se ha encontrado ningún producto <i class="fa-solid fa-face-sad-tear"></i></h2>';
                        }
                    } else if ($marcados != null && $marcados[0] != "") {
                        if ($orden != null) {
                            $productos = $productosBD->getProductosOrdenPrecio($orden);
                        } else {
                            $productos = $productosBD->getProductos();
                        }
                        foreach ($productos as $producto) {
                            foreach ($marcados as $marcado) {
                                if ($producto["id_tipo"] == $marcado) {
                                    mostrarProducto($producto);
                                    $hayMarcado = true;
                                }
                            }
                        }
                        if (!$hayMarcado) {
                            echo '<h2>No hay productos de ese tipo <i class="fa-solid fa-face-sad-tear"></i></h2>';
                        }
                    } else {
                        if ($orden != null) {
                            $productos = $productosBD->getProductosOrdenPrecio($orden);
                        } else {
                            $productos = $productosBD->getProductos();
                        }
                        foreach ($productos as $producto) {
                            mostrarProducto($producto);
                        }
                    }

                    function mostrarProducto($producto) {
                        ?>
                        <div class="producto">
                            <a href="producto?id=<?= $producto["id"] ?>"><img src="data:image/jpg;base64,<?= base64_encode($producto["imagen"]) ?>"/></a>
                            <div><a href="producto?id=<?= $producto["id"] ?>"><h3><?= $producto["nombre"] ?></h3></a><a href="producto?id=<?= $producto["id"] ?>"><h4><?= $producto["precio"] ?>€</h4></a></div>
                        </div>
                        <?php
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
