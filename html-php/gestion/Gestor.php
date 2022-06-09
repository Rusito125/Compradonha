<?php
require_once '../archivosBD/ProductosBD.php';
require_once '../cabeceraFooter.php';
session_start();
$_action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'escoge';
if ($_action != 'borrar') {
    ?>
    <!DOCTYPE html>
    <!--
        TODO
            Diseño y funciones listas
    -->
    <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Gestión Productos - Compradoña</title>
            <script src="https://kit.fontawesome.com/3b88ef1ad2.js" crossorigin="anonymous"></script>
            <link rel="stylesheet" type="text/css" href="../../estilos/normalize.css"/>
            <link rel="stylesheet" type="text/css" href="../../estilos/estilos.css"/>
            <link rel="stylesheet" type="text/css" href="../../estilos/estilosGestor.css"/>
            <link rel="icon" href="../../img/varios/favicon.png"/>        
        </head>
        <body>
            <?php
            $cabeceraFooter = new CabeceraFooter();
            $cabeceraFooter->cabecera();
            ?>
            <main>
                <section>
                    <?php
                }
                switch ($_action) {
                    case 'add':
                        cargarProductoGestor();
                        break;
                    case 'ver':
                        ?>
                        <button id='botonVolver' onclick="location.href = 'gestor.php'">Volver</button>
                        <?php
                        verProductosGestor();
                        break;
                    case 'editar':
                        cargarProductoGestor($_GET["id"]);
                        break;
                    case 'borrar':
                        borrarProductoGestor($_GET["id"]);
                        break;
                    case 'escoge':
                    default:
                        escogeGestion();
                        break;
                }

                function cargarProductoGestor($id = null) {
                    $productosBD = new ProductosBD();
                    if ($id != null) {
                        $producto = $productosBD->getProductoPorId($id);
                    }
                    $tiposProducto = $productosBD->getTiposProducto();
                    ?>
                    <form method="post" enctype="multipart/form-data" id="miForm">
                        <h4>Añadir productos</h4>      
                        <label for="nombre">Nombre: </label>
                        <input type="text" name="nombre" maxlength="20" value="<?= $id != null ? $producto["nombre"] : "" ?>" required/>
                        <label for="nombre">Descripción: </label>
                        <textarea size="500" name="descripcion"><?= $id != null ? $producto["descripcion"] : "" ?></textarea>
                        <label for="precio">Precio: </label>
                        <input type="text" name="precio" pattern="^([0-9]*)(\.[0-9]{2})?$" value="<?= $id != null ? $producto["precio"] : "" ?>" required/>
                        <label for="inventario">Inventario: </label>
                        <input type="text" name="inventario" pattern="^[0-9]+$" value="<?= $id != null ? $producto["inventario"] : "" ?>" required/>
                        <label for="image">Imagen: </label>
                        <input type="file" accept="image/*" id="image" name="image" onchange="cargar()"/>
                        <?= $id != null ? '<img src="data:image/jpg;base64,' . base64_encode($producto['imagen']) . '" id="preview">' : "" ?>
                        <label for="tipo" id="antesTipo">Tipo: </label>
                        <select name="tipo">
                            <?php
                            foreach ($tiposProducto as $tipoProducto) {
                                ?>
                                <option value="<?= $tipoProducto["id"] ?>" <?= $id != null ? ($producto["id_tipo"] == $tipoProducto["id"] ? "selected" : "") : "" ?>><?= $tipoProducto["tipo"] ?></option>
                                <?php
                            }
                            ?>
                        </select>
                        <button name="submit"><?= $id != null ? "Modificar producto" : "Cargar Producto" ?></button>
                    </form>
                    <?= $id != null ? "<a href='?action=ver'>Volver a la lista de productos</a>" : '<a href="gestor.php">Volver</a>' ?>
                    <script>
                        function cargar(e) {
                            let imagen = document.getElementById("image");
                            if (imagen.files.length > 0) {
                                img = imagen.files[0];
                                if (document.getElementById("preview")) {
                                    preview = document.getElementById("preview");
                                } else {
                                    preview = document.createElement("img");
                                    preview.id = "preview";
                                    document.getElementById("miForm").insertBefore(preview, document.getElementById("antesTipo"));
                                }
                                preview.src = URL.createObjectURL(img);
                            }                           
                        }
                    </script>
                    <?php
                    if (isset($_POST["submit"])) {
                        // redundante
                        if (isset($_POST["nombre"]) && isset($_POST["precio"]) && isset($_POST["inventario"])) {
                            try {
                                if (comprobarImagen($id)) {
                                    $imgContenido = $producto['imagen'];
                                } else {
                                    if ($_FILES['image']['error'] == 0) {
                                        $image = $_FILES['image']['tmp_name'];
                                    } else {
                                        var_dump($_FILES["image"]["error"]);
                                    }
                                    $imgContenido = file_get_contents($image);
                                }
                                $nombre = $_POST["nombre"];
                                if (isset($_POST["descripcion"])) {
                                    $descripcion = $_POST["descripcion"];
                                } else {
                                    $descripcion = null;
                                }
                                $precio = $_POST["precio"];
                                $inventario = $_POST["inventario"];
                                $tipoP = $_POST["tipo"];
                                if ($imgContenido != null) {
                                    if ($id != null) {
                                        if ($productosBD->updateProducto($nombre, $descripcion, $precio, $inventario, $imgContenido, $tipoP, $id)) {
                                            ?>
                                            <script>
                                                location.href = "?action=ver";
                                            </script>
                                            <?php
                                        } else {
                                            echo "<h3>Ha fallado la actualización del producto</h3>";
                                        }
                                    } else {
                                        if ($productosBD->setProducto($nombre, $descripcion, $precio, $inventario, $imgContenido, $tipoP)) {
                                            echo "<h3>Producto añadido corretamente.</h3>";
                                        } else {
                                            echo "<h3>Ha fallado la creación del producto.</h3>";
                                        }
                                    }
                                } else {
                                    echo "<h3 style='color: red'>Error al añadir/modificar un producto, asegúrese de que ha introducido todos los datos necesarios</h3>";
                                }
                            } catch (Exception $e) {
                                echo "Error! " . $e;
                                die();
                            }
                        } else {
                            echo "<h3 style='color: red'>Error al añadir/modificar un producto, asegúrese de que ha introducido todos los datos necesarios</h3>";
                        }
                    }
                }

                function comprobarImagen($id) {
                    $existeImagen = false;
                    if ($_FILES["image"]["tmp_name"] != "") {
                        $revisar = getimagesize($_FILES["image"]["tmp_name"]);
                        if ($revisar !== false) {
                            $existeImagen = false;
                        }
                    } else if ($id != null) {
                        $existeImagen = true;
                    }
                    return $existeImagen;
                }

                function verProductosGestor() {
                    $productosBD = new ProductosBD();
                    $productos = $productosBD->getProductos();
                    if ($productos != null) {
                        echo "<table border=1>";
                        echo "<tr><th>Nombre</th><th>Descripción</th><th>Fecha</th><th>Precio</th><th>Inventario</th><th>Imagen</th><th>Tipo</th><th>Editar</th><th>Borrar</th></tr>";
                        foreach ($productos as $producto) {
                            ?>
                            <tr>
                                <td><?= $producto["nombre"] ?></td>
                                <td><?= $producto["descripcion"] ?></td>
                                <td><?= $producto["fecha"] ?></td>
                                <td><?= $producto["precio"] ?>€</td>
                                <td><?= $producto["inventario"] ?></td>
                                <td><img src="data:image/jpg;base64,<?= base64_encode($producto['imagen']) ?>"></td>
                                <td><?= $producto["tipo"] ?></td>
                                <td><a href="?action=editar&id=<?= $producto["id"] ?>">Editar</a></td>
                                <td><a onclick="borrarProducto(<?= $producto["id"] ?>)">Borrar</a></td>
                            </tr>
                            <?php
                        }
                        ?>                        
                        <script>
                            function borrarProducto(idProducto) {
                                var xhttp = new XMLHttpRequest();
                                xhttp.onreadystatechange = function () {
                                    if (this.readyState == 4 && this.status == 200) {
                                        document.getElementsByTagName("table")[0].innerHTML = this.responseText;
                                    }
                                };
                                xhttp.open("GET", "?action=borrar&id=" + idProducto, true);
                                xhttp.send();
                            }
                        </script>
                        <?php
                        echo "</table>";
                    } else {
                        echo "<h2>No existe ningún producto en la base de datos</h2>";
                    }
                    ?>     
                    <script>
                        function isMobile() {
                            return (
                                    (navigator.userAgent.match(/Android/i)) ||
                                    (navigator.userAgent.match(/webOS/i)) ||
                                    (navigator.userAgent.match(/iPhone/i)) ||
                                    (navigator.userAgent.match(/iPod/i)) ||
                                    (navigator.userAgent.match(/BlackBerry/i))
                                    );
                        }
                        if (isMobile()) {
                            alert("Debe acceder desde un ordenador de escritorio para poder ver y editar los productos");
                            location.href = "Gestor.php";
                        }

                    </script>
                    <div id="alertaPantalla"><h3>Maximice su navegador para poder ver y editar los productos</h3></div>
                    <?php
                }

                function borrarProductoGestor($id) {
                    if (isset($id)) {
                        $productosBD = new ProductosBD();
                        if ($productosBD->borrarProducto($id)) {
                            verProductosGestor();
                        } else {
                            echo "<h3>Ha fallado el borrado del producto.</h3>";
                        }
                    }
                }

                function escogeGestion() {
                    ?>
                    <h2>Bienvenido al apartado de gestión</h2>
                    <h3>¿Qué desea realizar?</h3>
                    <button onclick="location.href = '?action=add'">Cargar un producto</button>
                    <button onclick="location.href = '?action=ver'">Ver productos</button>
                    <?php
                }

                if ($_action != 'borrar') {
                    ?>
                </section>
            </main>
            <?php
            $cabeceraFooter->footer();
            ?>
        </body>
    </html>
    <?php
}
?>
