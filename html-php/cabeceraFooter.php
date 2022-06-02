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
        <div id="contenedor_carga">
            <div id="carga"></div>
        </div>
        <script>
            window.onload = function () {
                let contenedor = document.getElementById("contenedor_carga");
                contenedor.style.visibility = "hidden";
                contenedor.style.opacity = "0";
                setTimeout(eliminarContenedor, 1000);
            }
            function eliminarContenedor() {
                document.getElementById("contenedor_carga").remove();
            }
        </script>  
        <header>            
            <nav>
                <a id="logo" href="<?= $urlIndex ?>index.php"></a>
                <a href="<?= $url ?>productos/productos.php">Productos</a>
                <a href="#" onclick="mostrarMenuUsuario()"><i class="fa-solid fa-user"></i></a>
                <div id="menuUsuario" class="ocultar ocultos">
                    <?php
                    if (isset($_SESSION["username"])) {
                        $usuario = $usuariosBD->getUsuarioByUsername($_SESSION["username"]);
                        ?>
                        <a href="<?= $url ?>usuarios/perfil.php">Mi perfil</a>
                        <?php
                        if ($usuario["id_rol"] == 2) {
                            ?>
                            <a href="<?= $url ?>gestion/Gestor.php">Menu de gestión</a>
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
                            menuUsuario.className = "ocultar ocultos";
                            checkUsuario = false;
                        } else {
                            menuUsuario.className = "mostrar ocultos";
                            checkUsuario = true;
                        }
                    }
                </script>
                <a href="#" id="enlaceBusqueda"><i class="fa-solid fa-magnifying-glass"></i></a>  
                <div id="cuadroBusqueda" class="ocultar ocultos"><form id="formBusca" action="<?= $url ?>productos/productos.php" onsubmit="return buscar()"><input type="text" name="cuadroBusqueda" id="inputBusca"/><a href="#" onclick="buscar()"><i class="fa-solid fa-magnifying-glass"></i></a></form></div>
                <script>
                    let enlace = document.getElementById("enlaceBusqueda");

                    enlace.addEventListener("click", mostrarCuadro);

                    var checkCuadro = false;
                    function mostrarCuadro() {
                        let cuadro = document.getElementById('cuadroBusqueda');
                        if (checkCuadro) {
                            cuadro.className = "ocultar ocultarCuadro ocultos";
                            checkCuadro = false;
                        } else {
                            cuadro.className = "mostrar mostrarCuadro ocultos";
                            checkCuadro = true;
                        }
                    }

                    function buscar() {
                        let formBusca = document.getElementById("formBusca");
                        let correcto = true;
                        let inputBusca = document.getElementById('inputBusca');
                        if (inputBusca.value === "") {
                            correcto = false;
                        }
                        if (correcto) {
                            formBusca.submit();
                        }
                        return correcto;
                    }

                </script>
                <a href="#" onclick="mostrarCarro()"><i class="fa-solid fa-cart-shopping"></i></a>
                <div id="carro" class="ocultarCarro">
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
                            <button class='caja' onclick="location.href = '<?= $url ?>compras/compra.php'">Pasar a caja</button>
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
                        xhttp.open("GET", "<?= $url ?>compras/carro.php?action=borrar&id=" + idProducto, true);
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
                            location.href = "<?= $url ?>compras/carro.php";
                        } else {
                            let carro = document.getElementById("carro");
                            if (checkCarro) {
                                carro.className = "ocultarCarro";
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
                <a href="<?= $url ?>gestion/contacto.php">Contacto</a>
                <a href='<?= $url ?>gestion/quienes.php'>Quienes somos</a>
            </div>
        </footer>
        <?php
    }

}
