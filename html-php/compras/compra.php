<?php
require_once '../archivosBD/ProductosBD.php';
require_once '../archivosBD/CarroBD.php';
require_once '../archivosBD/UsuariosBD.php';
session_start();

function verProductos($productosCarro) {
    ?>    
    <h3>Productos</h3>
    <?php
    $sumaProductos = 0;
    $envioCargado = false;
    foreach ($productosCarro as $productoCarro) {
        ?>
        <div>
            <div class="producto">
                <div class="imagen"><img src="data:image/jpg;base64,<?= base64_encode($productoCarro['imagen']) ?>"/></div>
                <div class="datos">
                    <span><b><?= $productoCarro["nombre"] ?></b></span>
                    <span><?= $productoCarro["precio"] ?>€</span>
                    <span id="spanCantidad<?= $productoCarro["id"] ?>">Cantidad: <?= $productoCarro["cantidad"] ?> <a class="cambiarCantidad" onclick="mostrarInput('cantidad' +<?= $productoCarro["id"] ?>, this)">Cambiar</a><div class="cantidad" id="cantidad<?= $productoCarro["id"] ?>"><input id="inputCantidad<?= $productoCarro["id"] ?>" type="number" min="1" max="<?= $productoCarro["inventario"] > 10 ? 10 : ($productoCarro["inventario"] + $productoCarro["cantidad"]) ?>" value="<?= $productoCarro["cantidad"] ?>"/> <a onclick="actualizarCantidad('actualizarCantidad',<?= $productoCarro["id"] ?>)">Actualizar</a> | <a onclick="actualizarCantidad('borrarProductoCarro',<?= $productoCarro["id"] ?>)">Eliminar</a></div></span>
                </div>               
            </div>
            <?php
            if (!$envioCargado) {
                ?>
                <div class="envio">
                    <h4>Elige una opción de envío: </h4>
                    <div><input type="radio" name="envio" id="envioGratis" value="gratis" <?= isset($_GET["envio"]) ? ($_GET["envio"] == "gratis" ? "checked" : "") : "checked" ?> onchange="cambiarEnvio(this)"/> <label for="envioGratis">GRATIS Envío estándar</label></div>
                    <div><input type="radio" name="envio" id="envioPremium" value="premium" <?= isset($_GET["envio"]) ? ($_GET["envio"] == "premium" ? "checked" : "") : "" ?> onchange="cambiarEnvio(this)"/> <label for="envioPremium">3,99€ Envío Premium</label></div>
                </div>
                <?php
                $envioCargado = true;
            }
            ?>
        </div>   
        <?php
        $sumaProductos += $productoCarro["precio"] * $productoCarro["cantidad"];
    }
    return $sumaProductos;
}

$carroBD = new CarroBD();
$usuariosBD = new UsuariosBD();
$productosBD = new ProductosBD();
if (isset($_SESSION["username"])) {
    $usuario = $usuariosBD->getUsuarioByUsername($_SESSION["username"]);
    if ($carroBD->getProductosCarro($usuario["id"]) == null) {
        header("Location: ../../index.php");
    } else if (isset($_GET["actualizarPrecio"])) {
        $productosCarro = $carroBD->getProductosCarro($usuario["id"]);
        $sumaProductos = 0;
        foreach ($productosCarro as $productoCarro) {
            $sumaProductos += $productoCarro["precio"] * $productoCarro["cantidad"];
        }
        echo $sumaProductos;
    } else if (isset($_GET["actualizarCantidad"]) && isset($_GET["cantidad"]) && isset($_GET["envio"])) {
        $producto = $productosBD->getProductoPorId($_GET["actualizarCantidad"]);
        $productoCarro = $carroBD->getProductoCarro($usuario["id"], $producto["id"]);
        if ($carroBD->setProductoCarro($usuario["id"], $producto["id"], $_GET["cantidad"])) {
            if ($productoCarro != null) {
                $productosBD->ajustarInventario($producto["id"], -$productoCarro["cantidad"]);
            }
            if ($productosBD->ajustarInventario($producto["id"], $_GET["cantidad"])) {
                if ($carroBD->crearEvento($producto["id"], $usuario["id"])) {
                    $productosCarro = $carroBD->getProductosCarro($usuario["id"]);
                    verProductos($productosCarro);
                }
            }
        }
    } else if (isset($_GET["borrarProductoCarro"]) && isset($_GET["cantidad"]) && isset($_GET["envio"])) {
        $producto = $productosBD->getProductoPorId($_GET["borrarProductoCarro"]);
        $cantidad = $carroBD->getProductoCarro($usuario["id"], $producto["id"])["cantidad"];
        if ($carroBD->borrarProductoCarro($usuario["id"], $producto["id"])) {
            if ($productosBD->ajustarInventario($producto["id"], -$cantidad)) {
                if ($carroBD->borrarEvento($producto["id"], $usuario["id"])) {
                    $productosCarro = $carroBD->getProductosCarro($usuario["id"]);
                    if ($productosCarro != null) {
                        verProductos($productosCarro);
                    }
                }
            }
        }
    } else {
        ?>
        <!--
            TODO
                funciones y diseño listos
        -->
        <!DOCTYPE html>
        <html>
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <title>Comprar - Compradoña</title>
                <script src="https://kit.fontawesome.com/3b88ef1ad2.js" crossorigin="anonymous"></script>
                <link rel="stylesheet" type="text/css" href="../../estilos/normalize.css"/>
                <link rel="stylesheet" type="text/css" href="../../estilos/estilos.css"/>
                <link rel="stylesheet" type="text/css" href="../../estilos/estilosCompra.css"/>
                <link rel="icon" href="../../img/varios/favicon.png"/>        
            </head>
            <body>     
                <?php
                require_once '../cabeceraFooter.php';
                $cabeceraFooter = new CabeceraFooter();
                $cabeceraFooter->cabecera();
                $productosCarro = $carroBD->getProductosCarro($usuario["id"]);
                ?>
                <div>
                    <a href="../../index.php"><img src="../../img/varios/logo.png"/></a>
                </div>
                <main>
                    <h1>Revisar tu pedido</h1>
                    <div>
                        <section>
                            <article id="datos">
                                <div>
                                    <h4>Dirección de envío</h4>
                                    <?= $usuario["nombre"] . " " . $usuario["apellidos"] ?><br/>
                                    <?= $usuario["calle"] ?><br/>
                                    <?= $usuario["numero"] ?>, <?= $usuario["piso"] . $usuario["puerta"] ?><br/>
                                    <?= $usuario["provincia"] ?>, <?= $usuario["poblacion"] ?> <?= $usuario["cp"] ?><br/>
                                    <?= $usuario["comunidad"] ?> España<br/>
                                    Teléfono: <?= $usuario["telefono"] ?>
                                </div>
                                <div>
                                    <h4>Método de pago</h4>
                                    <div><input type="radio" name="pago" value="tarjeta" id="tarjeta" checked> <label for="tarjeta">Tarjeta</label></div>
                                    <div><input type="radio" name="pago" value="paypal" id="paypal"> <label for="paypal">PayPal</label></div>
                                    <div><input type="radio" name="pago" value="efectivo" id="efectivo"> <label for="efectivo">Efectivo</label></div>
                                </div>                                
                            </article>
                            <article id="productos">                            
                                <?php
                                $sumaProductos = verProductos($productosCarro);
                                ?>               
                            </article>
                            <script>
                    function actualizarCantidad(accion, idProducto) {
                        let envio = document.querySelector("input[name='envio']:checked").value;
                        var xhttp = new XMLHttpRequest();
                        let cantidad = document.getElementById("inputCantidad" + idProducto).value;
                        xhttp.open("GET", "?" + accion + "=" + idProducto + "&cantidad=" + cantidad + "&envio=" + envio, false);
                        xhttp.send();
                        let productos = document.getElementById("productos");
                        productos.innerHTML = xhttp.responseText;
                        if (productos.innerHTML === "") {
                            location.href = "../../index.php";
                        } else {
                            actualizarPrecio(idProducto);
                        }
                    }

                    function actualizarPrecio(idProducto) {
                        var xhttp = new XMLHttpRequest();
                        xhttp.open("GET", "?actualizarPrecio=" + idProducto, false);
                        xhttp.send();
                        sumaProductos = xhttp.responseText;
                        document.getElementById("precioProductos").innerHTML = sumaProductos + "€";
                        document.getElementById("total").innerHTML = "<h3>" + (Number(sumaProductos) + Number(sumaEnvio)) + "€</h3>";
                    }

                    function mostrarInput(idCantidad, enlace) {
                        enlace.style.display = "none";
                        document.getElementById(idCantidad).style.display = "inline-block";
                    }
                            </script>
                        </section>
                        <aside>
                            <button class="botonesCompra">Comprar ahora</button>
                            <h3>Resumen del pedido</h3>
                            <div><div>Productos:</div><div id="precioProductos"></div></div>
                            <div><div>Envío:</div><div id="precioEnvio">0€</div></div>
                            <div><div><h3>Total:</h3></div><div id="total"><h3></h3></div></div>
                        </aside>
                        <section id="botonAbajo"><button class="botonesCompra">Comprar ahora</button></section>
                        <script>
                            var sumaEnvio = 0;
                            let sumaProductos = <?= $sumaProductos ?>;
                            document.getElementById("precioProductos").innerHTML = sumaProductos + "€";
                            document.getElementById("total").innerHTML = "<h3>" + (sumaEnvio + sumaProductos) + "€</h3>";

                            let botonesCompra = document.getElementsByClassName("botonesCompra");
                            for (botonCompra of botonesCompra) {
                                botonCompra.onclick = function () {
                                    location.href = 'finCompra.php?envio=' + sumaEnvio;
                                };
                            }

                            function cambiarEnvio(radio) {
                                sumaEnvio = 0;
                                if (radio.checked) {
                                    if (radio.id.includes("Gratis")) {
                                        if (sumaEnvio != 0) {
                                            sumaEnvio -= 3.99;
                                        }
                                        sumaEnvio += 0;
                                    } else {
                                        sumaEnvio += 3.99;
                                    }
                                }
                                document.getElementById("precioEnvio").innerHTML = sumaEnvio + "€";
                                for (botonCompra of botonesCompra) {
                                    botonCompra.onclick = function () {
                                        location.href = 'finCompra.php?envio=' + sumaEnvio;
                                    };
                                }
                                document.getElementById("total").innerHTML = "<h3>" + (sumaEnvio + Number(sumaProductos)) + "€</h3>";
                            }



                        </script>
                    </div>
                </main>
                <?php
                $cabeceraFooter->footer();
                ?>
            </body>
        </html>
        <?php
    }
} else {
    header("Location: ../../index.php");
}
?>
