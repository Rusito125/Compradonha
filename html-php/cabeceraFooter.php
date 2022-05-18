<?php

class CabeceraFooter {

    function cabecera() {
        $url = "http://" . $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . "/perperpab/Mercadonha/html-php/";
        $carpeta = $_SERVER["DOCUMENT_ROOT"] . "/perperpab/Mercadonha/html-php/";
        $urlIndex = "http://" . $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . "/perperpab/Mercadonha/public_html/";
        require_once $carpeta . 'archivosBD/ProductosBD.php';
        require_once $carpeta . 'archivosBD/UsuariosBD.php';
        $productosBD = new ProductosBD();
        $usuariosBD = new UsuariosBD();
        ?>
        <header>            
            <nav>
                <a id="logo" href="<?=$urlIndex?>index.php"></a>
                <a href="<?=$url?>productos/productos.php">Productos</a>
                <a href="#" onclick="mostrarMenuUsuario()"><i class="fa-solid fa-user"></i></a>
                <div id="menuUsuario" class="ocultar">
                    <?php
                    if (isset($_SESSION["username"])) {
                        $usuario = $usuariosBD->getUsuarioByUsername($_SESSION["username"]);
                        ?>
                        <a href="<?=$url?>usuarios/perfil.php">Mi perfil</a>
                        <?php
                        if($usuario["id_rol"] == 2){
                            ?>
                        <a href="<?=$url?>Gestor.php">Menu de gestión</a>
                        <?php
                        }
                        ?>
                        <a href="<?=$url?>usuarios/perfil.php?action=cerrar">Cerrar sesión</a>
                        <?php
                    } else {
                        ?>
                        <a href="<?=$url?>usuarios/iniciar.php?action=iniciar">Iniciar Sesión</a>
                        <a href="<?=$url?>usuarios/iniciar.php?action=registro">Registrarse</a>
                    <?php } ?>
                </div>
                <script>
                    var checkUsuario = false;
                    function mostrarMenuUsuario() {
                        let menuUsuario = document.getElementById("menuUsuario");
                        if (checkUsuario) {
                            menuUsuario.className = "ocultar";
                            checkUsuario = false;
                        } else {
                            menuUsuario.className = "mostrar";
                            checkUsuario = true;
                        }
                    }
                </script>
                <a href="#"><i class="fa-solid fa-magnifying-glass"></i></a>
                <a href="#" onclick="mostrarCarro()"><i class="fa-solid fa-cart-shopping"></i></a>
                <div id="carro" class="ocultar">
                    <?php
                    if (isset($_SESSION["carro"])) {
                        foreach ($_SESSION["carro"] as $idProducto) {
                            $producto = $productosBD->getProductoPorId($idProducto);
                            ?>
                            <div class="productoCarro">
                                <div>
                                    <h3><?= $producto["nombre"] ?></h3>
                                    <h4><?= $producto["precio"] ?>€ <a href='<?=$url?>usuarios/carro.php?action=borrar&id=<?= $producto["id"] ?>'><i class="fa-solid fa-trash-can"></i></a></h4>
                                </div>
                                <img src="data:image/jpg;base64,<?= base64_encode($producto['imagen']) ?>">
                            </div>
                            <?php
                        }
                        ?>
                        <button onclick="location.href = '<?=$url?>usuarios/carro.php'">Pasar a caja</button>
                        <?php
                    } else {
                        ?>
                        <h3>No hay productos en el carro</h3>
                        <button onclick="location.href = '<?=$url?>productos/productos.php'">Empezar a comprar</button>
                        <?php
                    }
                    ?>
                </div>
                <script>
                    var checkCarro = false;
                    function mostrarCarro() {
                        let carro = document.getElementById("carro");
                        if (checkCarro) {
                            carro.className = "ocultar";
                            checkCarro = false;
                        } else {
                            carro.className = "mostrarCarro";
                            checkCarro = true;
                        }
                    }
                </script>
            </nav>
        </header>
        <?php
    }

    function footer() {
        $url = "http://" . $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . "/perperpab/Mercadonha/html-php/";
        ?>
        <footer>
            <div>              
                <a>Contacto</a>
                <a>Quienes somos</a>
            </div>
        </footer>
        <?php
    }

}
