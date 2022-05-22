<?php

class CabeceraFooter {

    function cabecera() {
        $url = "http://" . $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . "/perperpab/Compradonha/html-php/";
        $carpeta = $_SERVER["DOCUMENT_ROOT"] . "/perperpab/Compradonha/html-php/";
        $urlIndex = "http://" . $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . "/perperpab/Compradonha/";
        require_once $carpeta . 'archivosBD/ProductosBD.php';
        require_once $carpeta . 'archivosBD/UsuariosBD.php';
        $productosBD = new ProductosBD();
        $usuariosBD = new UsuariosBD();
        ?>
        <header>            
            <nav>
                <a id="logo" href="<?= $urlIndex ?>index.php"></a>
                <a href="<?= $url ?>productos/productos.php">Productos</a>
                <a href="#" onclick="mostrarMenuUsuario()"><i class="fa-solid fa-user"></i></a>
                <div id="menuUsuario" class="ocultar">
                    <?php
                    if (isset($_SESSION["username"])) {
                        $usuario = $usuariosBD->getUsuarioByUsername($_SESSION["username"]);
                        ?>
                        <a href="<?= $url ?>usuarios/perfil.php">Mi perfil</a>
                        <?php
                        if ($usuario["id_rol"] == 2) {
                            ?>
                            <a href="<?= $url ?>Gestor.php">Menu de gestión</a>
                            <?php
                        }
                        ?>
                        <a href="<?= $url ?>usuarios/perfil.php?action=cerrar">Cerrar sesión</a>
                        <?php
                    } else {
                        ?>
                        <a href="<?= $url ?>usuarios/iniciar.php?action=iniciar">Iniciar Sesión</a>
                        <a href="<?= $url ?>usuarios/iniciar.php?action=registro">Registrarse</a>
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
                <a href="#" id="cuadroBusqueda"><div style="display: none"><form action="<?= $url ?>productos/productos.php" onsubmit="return comprueba()"><input type="text" name="cuadroBusqueda"/></form></div><i class="fa-solid fa-magnifying-glass"></i></a>  
                <script>
                    let cuadro = document.getElementById('cuadroBusqueda');
                    let lupa = cuadro.getElementsByTagName("i")[0];
                    let divCuadro = cuadro.getElementsByTagName("div")[0];
                    let inputCuadro = divCuadro.getElementsByTagName("form")[0].getElementsByTagName("input")[0];
                    let clickado = false;

                    cuadro.onclick = function () {
                        if (!clickado) {
                            if (divCuadro.style.display == "none") {
                                divCuadro.style.display = "inline";
                            }
                            clickado = true;
                        }
                    };

                    lupa.onclick = function () {
                        if (inputCuadro.value != "") {
                            divCuadro.getElementsByTagName("form")[0].submit();
                        }
                    };

                    function comprueba() {
                        let correcto = true;
                        if (inputCuadro.value == "") {
                            correcto = false;
                        }
                        return correcto;
                    }
                </script>
                <a href="#" onclick="mostrarCarro()"><i class="fa-solid fa-cart-shopping"></i></a>
                <div id="carro" class="ocultar">
                    <?php
                    if (isset($_SESSION["username"])) {
                        require_once 'archivosBD/CarroBD.php';
                        $carroBD = new CarroBD();
                        $productosCarro = $carroBD->getProductosCarro($usuariosBD->getUsuarioByUsername($_SESSION["username"])["id"]);
                        if ($productosCarro != null) {
                            $precioTotal = 0;
                            foreach ($productosCarro as $productoCarro) {
                                $precioTotal += ($productoCarro["cantidad"] * $productoCarro["precio"]);
                                ?>
                                <div class="productoCarro">
                                    <div>
                                        <h3><a href="<?= $url ?>productos/producto.php?id=<?= $productoCarro["id"] ?>"><?= $productoCarro["nombre"] ?></a></h3>
                                        <h4><?= $productoCarro["precio"] ?>€</h4>
                                        <h4>Cantidad: <?= $productoCarro["cantidad"] ?> <a onclick="borrarCarroProducto(<?= $productoCarro["id"] ?>)"><i class="fa-solid fa-trash-can"></i></a></h4>
                                    </div>
                                    <img src="data:image/jpg;base64,<?= base64_encode($productoCarro['imagen']) ?>" onclick="verProducto(<?= $productoCarro["id"] ?>)">
                                    <script>
                                        function verProducto(idProducto) {
                                            location.href = "<?= $url ?>productos/producto.php?id=" + idProducto;
                                        }
                                    </script>
                                </div>
                                <?php
                            }
                            ?>
                            <h3>Precio total: <?= $precioTotal ?>€</h3>
                            <button class='caja' onclick="location.href = '<?= $url ?>usuarios/carro.php'">Pasar a caja</button>
                            <?php
                        } else {
                            ?>
                            <h3>No hay productos en el carro</h3>
                            <button class='caja' onclick="location.href = '<?= $url ?>productos/productos.php'">Empezar a comprar</button>
                            <?php
                        }
                    } else {
                        ?>
                        <h3>Inicie sesión para empezar a comprar</h3>
                        <button class='caja' onclick="location.href = '<?= $url ?>usuarios/iniciar.php?action=iniciar'">Iniciar sesión</button>
                        <?php
                    }
                    ?>
                </div>
                <script>
                    function borrarCarroProducto(idProducto) {
                        var xhttp = new XMLHttpRequest();
                        xhttp.onreadystatechange = function () {
                            if (this.readyState == 4 && this.status == 200) {
                                document.getElementById("carro").innerHTML = this.responseText;
                            }
                        };
                        xhttp.open("GET", "<?= $url ?>usuarios/carro.php?action=borrar&id=" + idProducto, true);
                        xhttp.send();
                    }
                </script>
                <script>
                    function isMobile() {
                        return (
                                (navigator.userAgent.match(/Android/i)) ||
                                (navigator.userAgent.match(/webOS/i)) ||
                                (navigator.userAgent.match(/iPhone/i)) ||
                                (navigator.userAgent.match(/iPod/i)) ||
                                (navigator.userAgent.match(/iPad/i)) ||
                                (navigator.userAgent.match(/BlackBerry/i))
                                );
                    }
                    var checkCarro = false;
                    function mostrarCarro() {
                        if (isMobile() != null) {
                            location.href = "<?= $url ?>usuarios/carro.php";
                        } else {
                            let carro = document.getElementById("carro");
                            if (checkCarro) {
                                carro.className = "ocultar";
                                checkCarro = false;
                            } else {
                                carro.className = "mostrarCarro";
                                checkCarro = true;
                            }
                        }
                    }
                </script>
            </nav>
        </header>
        <?php
    }

    function footer() {
        $url = "http://" . $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . "/perperpab/Compradonha/html-php/";
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
