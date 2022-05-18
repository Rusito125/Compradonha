<?php

class ProductosBD {

    public function __construct() {
        require_once 'AccesoBD.php';
    }

    function cargarProductoGestor($id = null) {
        $pdo = new AccesoBD();
        $pdo = $pdo->abrirConexion();
        if ($id != null) {
            $result = $pdo->query("SELECT * FROM productos WHERE id = $id");
            if ($result->rowCount() > 0) {
                $producto = $result->fetch();
            }
        }
        $result = $pdo->query("SELECT * FROM tipos_producto");
        if ($result->rowCount() > 0) {
            $tiposProducto = $result->fetchAll();
            ?>
            <form method="post" enctype="multipart/form-data" id="miForm">
                <h4>Añadir productos</h4>      
                <label for="nombre">Nombre: </label>
                <input type="text" name="nombre" value="<?= $id != null ? $producto["nombre"] : "" ?>"/>
                <label for="nombre">Descripción: </label>
                <textarea size="500" name="descripcion" value="<?= $id != null ? $producto["descripcion"] : "" ?>"></textarea>
                <label for="precio">Precio: </label>
                <input type="text" name="precio" pattern="^([0-9]*)(\.[0-9]{2})?$" value="<?= $id != null ? $producto["precio"] : "" ?>"/>
                <label for="image">Imagen: </label>
                <input type="file" accept="image/*" id="image" name="image" onchange="cargar()"/>
                <?= $id != null ? '<img src="data:image/jpg;base64,' . base64_encode($producto['imagen']) . '" width="200px" height="100px" id="preview">' : "" ?>
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
                            preview.width = 200;
                            preview.height = 100;
                            preview.id = "preview";
                            document.getElementById("miForm").insertBefore(preview, document.getElementById("antesTipo"));
                        }
                        preview.src = URL.createObjectURL(img);
                    }
                }
            </script>
            <?php
        }
        if (isset($_POST["submit"])) {
            if (isset($_POST["nombre"]) && isset($_POST["precio"])) {
                try {
                    if ($this->comprobarImagen($id)) {
                        $imgContenido = $producto['imagen'];
                    } else {
                        $image = $_FILES['image']['tmp_name'];
                        $imgContenido = file_get_contents($image);
                    }
                    $nombre = $_POST["nombre"];
                    if(isset($_POST["descripcion"])){
                        $descripcion = $_POST["descripcion"];
                    } else{
                        $descripcion = null;
                    }
                    $precio = $_POST["precio"];                    
                    $tipoP = $_POST["tipo"];
                    if ($id != null) {
                        $actualizar = $pdo->prepare("UPDATE Productos SET nombre=?, descripcion=?, precio = ?, imagen = ?, id_tipo = ? WHERE id=?");
                        if ($actualizar->execute(array($nombre, $descripcion, $precio, $imgContenido, $tipoP, $id))) {
                            ?>
                            <script>
                                location.href = "?action=ver";
                            </script>
                            <?php
                        } else {
                            echo "<h3>Ha fallado la actualización del producto</h3>";
                        }
                    } else {
                        $insertar = $pdo->prepare("INSERT into Productos (id, nombre, descripcion, fecha, precio, imagen, id_tipo) VALUES (0,?,?,now(),?,?,?)");
                        if ($insertar->execute(array($nombre, $descripcion, $precio, $imgContenido, $tipoP))) {
                            echo "<h3>Producto añadido corretamente.</h3>";
                        } else {
                            echo "<h3>Ha fallado la creación del producto.</h3>";
                        }
                    }
                } catch (Exception $e) {
                    echo "Error! " . $e;
                    die();
                }
            } else {
                echo "<h3 style='color: red'>Error al añadir/modificar un producto, asegúrese de que ha introducido todos los datos</h3>";
            }
        }
        $pdo = null;
    }
    
    private function comprobarImagen($id) {
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
        $pdo = new AccesoBD();
        $pdo = $pdo->abrirConexion();
        $result = $pdo->query("SELECT p.id,p.nombre,p.descripcion,p.fecha,p.precio,p.imagen,tp.tipo FROM productos p JOIN tipos_producto tp ON p.id_tipo = tp.id ORDER BY p.fecha DESC");

        if ($result->rowCount() > 0) {
            $productos = $result->fetchAll();
            echo "<table border=1>";
            echo "<tr><th>Nombre</th><th>Descripción</th><th>Fecha</th><th>Precio</th><th>Imagen</th><th>Tipo</th><th>Editar</th><th>Borrar</th></tr>";
            foreach ($productos as $producto) {
                ?>
                <tr>
                    <td><?= $producto["nombre"] ?></td>
                    <td><?=$producto["descripcion"]?></td>
                    <td><?= $producto["fecha"] ?></td>
                    <td><?= $producto["precio"] ?>€</td>
                    <td><img src="data:image/jpg;base64,<?= base64_encode($producto['imagen']) ?>" width="200px" height="100px"></td>
                    <td><?= $producto["tipo"] ?></td>
                    <td><a href="?action=editar&id=<?= $producto["id"] ?>">Editar</a></td>
                    <td><a href="?action=borrar&id=<?= $producto["id"] ?>">Borrar</a></td>
                </tr>
                <?php
            }
            echo "</table>";
        } else {
            echo "<h2>No existe ningún producto en la base de datos</h2>";
        }
        echo "<a href='gestor.php'>Volver</a>";
        $pdo = null;
    }

    function borrarProductoGestor($id) {
        if (isset($id)) {
            $pdo = new AccesoBD();
            $pdo = $pdo->abrirConexion();
            $borrar = $pdo->prepare("DELETE FROM PRODUCTOS WHERE ID = ?");
            if ($borrar->execute(array($id))) {
                echo "<h3>Producto borrado corretamente.</h3>";
            } else {
                echo "<h3>Ha fallado el borrado del producto.</h3>";
            }
        }
        echo "<a href='?action=ver'>Volver a la lista de productos</a>";
        echo "<a href='gestor.php'>Volver a la página de gestiones</a>";
        $pdo = null;
    }

    function getProductos() {
        $pdo = new AccesoBD();
        $pdo = $pdo->abrirConexion();

        $result = $pdo->query("SELECT p.id,p.nombre,p.descripcion,p.precio,p.fecha,p.imagen,tp.tipo FROM productos p JOIN tipos_producto tp ON p.id_tipo = tp.id ORDER BY p.fecha DESC");

        if ($result->rowCount() > 0) {
            $productos = $result->fetchAll();
        }
        $pdo = null;
        return $productos;
    }
    
    function getTiposProducto(){
        $pdo = new AccesoBD();
        $pdo = $pdo->abrirConexion();
        
        $result = $pdo->query("SELECT * FROM tipos_producto");
        
        if($result->rowCount() > 0){
            $tiposProducto = $result->fetchAll();
        }
        $pdo = null;
        return $tiposProducto;
    }
    
    function getProductosDeTipo($tipo){
        $pdo = new AccesoBD();
        $pdo = $pdo->abrirConexion();
        $result = $pdo->query("SELECT * FROM productos WHERE id_tipo = $tipo");
        if($result->rowCount() > 0){
            $productos = $result->fetchAll();
        } else{
            $productos = null;
        }
        $pdo = null;
        return $productos;
    }
    
    function getProductoPorId($id){
        $pdo = new AccesoBD();
        $pdo = $pdo->abrirConexion();
        $result = $pdo->query("SELECT * FROM productos WHERE id = $id");
        if($result->rowCount() > 0){
            $producto = $result->fetch();
        }
        $pdo = null;
        return $producto;
    }

}
?>
