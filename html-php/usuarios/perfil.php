<!DOCTYPE html>
<!--
    TODO        
        quitar hardcoded javascript
-->
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://kit.fontawesome.com/3b88ef1ad2.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" type="text/css" href="../../estilos/normalize.css"/>
        <link rel="stylesheet" type="text/css" href="../../estilos/estilos.css"/>
        <link rel="stylesheet" type="text/css" href="../../estilos/estilosPerfil.css"/>
        <link rel="icon" href="../../img/varios/favicon.png"/>    
        <title>Perfil - Compradoña</title>
    </head>
    <body>
        <?php
        session_start();
        if (isset($_SESSION["username"])) {
            $_action = isset($_GET["action"]) ? $_GET["action"] : "";
            switch ($_action) {
                case "cerrar":
                    cerrarSesion();
                    break;
                default:
                    verUsuario();
            }
        } else {
            header('HTTP/1.0 403 Forbidden');

            echo "<h1 style='color:red'>Acceso prohibido</h1>";
        }

        function verUsuario() {
            require_once '../archivosBD/UsuariosBD.php';
            require_once '../archivosBD/CompraBD.php';
            require_once '../cabeceraFooter.php';
            require_once '../archivosBD/ComunidadesBD.php';
            $url = "http://" . $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . "/perperpab/Compradonha/html-php/";
            $cabeceraFooter = new CabeceraFooter();
            $cabeceraFooter->cabecera();
            ?>                
            <main>
                <section id="perfil">
                    <h1>Perfil</h1>
                    <?php
                    $usuariosBD = new UsuariosBD();
                    $usuario = $usuariosBD->getUsuarioByUsername($_SESSION["username"]);
                    ?>
                    <h3>Hola <?= $usuario["nombre"] ?></h3>
                    <div id="divPerfil">                        
                        <ul>
                            <li><span>Nombre de usuario:</span><b><?= $_SESSION["username"] ?></b></li>
                            <li><span>Nombre y apellidos:</span><b><?= $usuario["nombre"] ?> <?= $usuario["apellidos"] ?></b></li>
                            <li><span>DNI:</span><b><?= $usuario["DNI"] ?></b></li>
                            <li><span>Telefono:</span><b><?= $usuario["telefono"] ?></b></li>
                            <li><span>Dirección:</span><b><?= $usuario["calle"] ?> N<?= $usuario["numero"] ?> <?= $usuario["piso"] ?><?= $usuario["puerta"] ?></b></li>
                            <li><span>Código postal:</span><b><?= $usuario["cp"] ?></b></li>
                            <li><span>Población:</span><b><?= $usuario["poblacion"] ?></b></li>
                            <li><span>Provincia:</span><b><?= $usuario["provincia"] ?></b></li>
                            <li><span>Comunidad Autónoma:</span><b><?= $usuario["comunidad"] ?></b></li>
                            <li><span>Correo electrónico:</span><b><?= $usuario["mail"] ?></b></li>
                            <li id="editarPerfil"><div><button class="open-modal" data-open="modal" onclick="mostrarComunidades()">Editar pefil</button></div></li>
                        </ul>
                    </div>
                    <div class="modal" id="modal">
                        <div class="modal-dialog">
                            <header class="modal-header">
                                Editar Perfil
                                <button class="close-modal" aria-label="close modal" data-close>✕</button>
                            </header>
                            <section class="modal-content">    
                                <form onsubmit="return validar()" method="post">
                                    <label for="username">Nombre de usuario: </label>
                                    <input type="text" name="username" id="username" maxlength="20" required value="<?= $_SESSION["username"] ?>"/>                            
                                    <label for="passwVieja">Contraseña vieja: </label>
                                    <input type="password" name="passwVieja" id="passwVieja" minlength="6" maxlength="20" required/>                                
                                    <label for="passw">Contraseña nueva: </label>
                                    <input type="password" name="passw" id="passw" minlength="6" maxlength="20"/>
                                    <label class="aviso contra">La contraseña debe tener entre 6 y 20 caracteres y al menos una minúscula, una mayúscula y un número</label>
                                    <label for="passw2">Repita la contraseña nueva: </label>
                                    <input type="password" name="passw2" id="passw2" minlength="6" maxlength="20"/>
                                    <label for="nombre">Nombre: </label>
                                    <input type="text" name="nombre" id="nombre" maxlength="20" required value="<?= $usuario["nombre"] ?>"/>
                                    <label for="apellidos">Apellidos: </label>
                                    <input type="text" name="apellidos" id="apellidos" maxlength="30" required value="<?= $usuario["apellidos"] ?>"/>
                                    <label for="DNI">DNI: </label>
                                    <input type="text" name="DNI" id="DNI" minlength="9" maxlength="9" required value="<?= $usuario["DNI"] ?>"/>
                                    <label for="telefono">Teléfono: </label>
                                    <input type="text" name="telefono" id="telefono" minlength="9" maxlength="9" required value="<?= $usuario["telefono"] ?>"/>
                                    <label for="calle">Calle: </label>
                                    <input type="text" name="calle" id="calle" maxlength="50" required value="<?= $usuario["calle"] ?>"/>
                                    <label for="numero">Número: </label>
                                    <input type="text" name="numero" id="numero" maxlength="5" required value="<?= $usuario["numero"] ?>"/>
                                    <label for="piso">Piso: </label>
                                    <input type="number" name="piso" id="piso" maxlength="2" required value="<?= $usuario["piso"] ?>"/>
                                    <label for="puerta">Puerta: </label>
                                    <input type="text" name="puerta" id="puerta" maxlength="1" required value="<?= $usuario["puerta"] ?>"/>
                                    <label for="comunidad">Comunidad Autónoma: </label>
                                    <select name="comunidad" id="comunidad" onchange="mostrarProvincias(this.value)" required>
                                        <option selected>Selecciona una Comunidad Autónoma</option>                                    
                                    </select>                            
                                    <label for="provincia">Provincia: </label>
                                    <select name="provincia" id="provincia" required>
                                        <option selected>Selecciona una Comunidad Autónoma</option>
                                    </select>
                                    <script>
                                        function mostrarProvincias(codComunidad) {
                                            var xhttp = new XMLHttpRequest();
                                            xhttp.open("GET", "../archivosBD/ComunidadesBD.php?codComunidad=" + codComunidad + "&prov=<?= $usuario["provincia"] ?>", false);
                                            xhttp.send();
                                            document.getElementById("provincia").innerHTML = xhttp.responseText;
                                        }

                                        function mostrarComunidades() {
                                            var xhttp = new XMLHttpRequest();
                                            xhttp.open("GET", "../archivosBD/ComunidadesBD.php?cargarComunidades&comu=<?= $usuario["comunidad"] ?>", false);
                                            xhttp.send();
                                            document.getElementById("comunidad").innerHTML = xhttp.responseText;
                                            mostrarProvincias(<?= $usuario["codComunidad"] ?>);
                                        }
                                    </script>
                                    <label for="poblacion">Población: </label>
                                    <input type="text" name="poblacion" id="poblacion" maxlength="44" required value="<?= $usuario["poblacion"] ?>"/>
                                    <label for="cp">Código postal: </label>
                                    <input type="text" name="cp" id="cp" maxlength="5" required value="<?= $usuario["cp"] ?>"/>                          
                                    <label for="mail">Correo electrónico: </label>
                                    <input type="email" name="mail" id="mail" maxlength="40" required value="<?= $usuario["mail"] ?>"/>
                                    <button name="botonEditar">Modificar Perfil</button>     
                                </form>
                                <script>
                                    // variables
                                    let nombre = document.getElementById("nombre");
                                    let apellidos = document.getElementById("apellidos");
                                    let DNI = document.getElementById("DNI");
                                    let telefono = document.getElementById("telefono");
                                    let passw = document.getElementById("passw");
                                    let passw2 = document.getElementById("passw2");
                                    let cp = document.getElementById("cp");

                                    // event listeners
                                    nombre.addEventListener("change", e => {
                                        let aviso = getAviso(e.target);
                                        if (!/^([A-Z\u00C0-\u00DC]([a-z\u00E0-\u00FC]*))(\ [A-Z\u00C0-\u00DC]([a-z\u00E0-\u00FC]*))*$/.test(e.target.value)) {
                                            aviso.innerText = "Nombre no válido";
                                            e.target.insertAdjacentElement('afterend', aviso);
                                        } else {
                                            aviso.remove();
                                        }
                                    });

                                    apellidos.addEventListener("change", e => {
                                        let aviso = getAviso(e.target);
                                        if (!/^([A-Z\u00C0-\u00DC]([a-z\u00E0-\u00FC]*))(\ [A-Z\u00C0-\u00DC]([a-z\u00E0-\u00FC]*))*$/.test(e.target.value)) {
                                            aviso.innerText = "Apellidos no válidos";
                                            e.target.insertAdjacentElement('afterend', aviso);
                                        } else {
                                            aviso.remove();
                                        }
                                    });

                                    DNI.addEventListener("change", e => {
                                        let aviso = getAviso(e.target);
                                        if (!comprobarDni(e.target.value)) {
                                            aviso.innerText = "DNI no válido";
                                            e.target.insertAdjacentElement('afterend', aviso);
                                        } else {
                                            aviso.remove();
                                        }
                                    });

                                    telefono.addEventListener("change", e => {
                                        let aviso = getAviso(e.target);
                                        if (!/^([6-9])([0-9]{8})$/.test(e.target.value)) {
                                            aviso.innerText = "Teléfono no válido";
                                            e.target.insertAdjacentElement('afterend', aviso);
                                        } else {
                                            aviso.remove();
                                        }
                                    });

                                    passw.addEventListener("input", e => {
                                        let aviso = getAviso(e.target);
                                        if (!/^(?=\w*\d)(?=\w*[A-Z])(?=\w*[a-z])\S{6,20}$/.test(e.target.value)) {
                                            aviso.innerText = "Contraseña no válida";
                                            e.target.insertAdjacentElement('afterend', aviso);
                                        } else {
                                            aviso.remove();
                                        }
                                        eventoPassw2();
                                    });

                                    passw2.addEventListener("input", eventoPassw2);
                                    function passCoinciden() {
                                        let coinciden = true;
                                        if (passw.value != passw2.value) {
                                            coinciden = false;
                                        }
                                        return coinciden;
                                    }

                                    cp.addEventListener("change", e => {
                                        let aviso = getAviso(e.target);
                                        if (!/^[0-9]{5}$/.test(e.target.value)) {
                                            aviso.innerText = "Código postal no válido";
                                            e.target.insertAdjacentElement('afterend', aviso);
                                        } else {
                                            aviso.remove();
                                        }
                                    });

                                    // funciones
                                    function eventoPassw2() {
                                        let aviso = getAviso(document.getElementById("passw2"));
                                        if (!passCoinciden()) {
                                            aviso.innerText = "Las contraseñas no coinciden";
                                            passw2.insertAdjacentElement('afterend', aviso);
                                        } else {
                                            aviso.remove();
                                        }
                                    }

                                    function getAviso(elemento) {
                                        let aviso;
                                        if (document.getElementById("aviso" + elemento.id)) {
                                            aviso = document.getElementById("aviso" + elemento.id);
                                        } else {
                                            aviso = document.createElement("label");
                                            aviso.id = "aviso" + elemento.id;
                                            aviso.className = "aviso";
                                        }
                                        return aviso;
                                    }

                                    function validar() {
                                        let correcto = true;
                                        if (!/^([A-Z\u00C0-\u00DC]([a-z\u00E0-\u00FC]*))(\ [A-Z\u00C0-\u00DC]([a-z\u00E0-\u00FC]*))*$/.test(nombre.value)) {
                                            correcto = false;
                                        }
                                        if (!/^([A-Z\u00C0-\u00DC]([a-z\u00E0-\u00FC]*))(\ [A-Z\u00C0-\u00DC]([a-z\u00E0-\u00FC]*))*$/.test(apellidos.value)) {
                                            correcto = false;
                                        }
                                        if (!comprobarDni(DNI.value)) {
                                            correcto = false;
                                        }
                                        if (!/^([6-9])([0-9]{8})$/.test(telefono.value)) {
                                            correcto = false;
                                        }
                                        if (!/^((?=\w*\d)(?=\w*[A-Z])(?=\w*[a-z])\S{6,20})?$/.test(passw.value)) {
                                            correcto = false;
                                        }
                                        if (!passCoinciden()) {
                                            correcto = false;
                                        }
                                        return correcto;
                                    }

                                    function comprobarDni(dni) {
                                        let pattern = /^\d{8}[a-zA-Z]/;
                                        let correcto = false;
                                        if (pattern.test(dni)) {
                                            let nombre = dni.substr(0, dni.length - 1);
                                            let letraIntroduida = dni.substr(dni.length - 1, 1);
                                            let letraEsperada = 'TRWAGMYFPDXBNJZSQVHLCKET';
                                            nombre = nombre % 23;
                                            letraEsperada = letraEsperada.substring(nombre, nombre + 1);
                                            if (letraEsperada === letraIntroduida.toUpperCase()) {
                                                correcto = true;
                                            }
                                        }
                                        return correcto;
                                    }
                                </script>
                                <?php
                                if (isset($_POST["botonEditar"])) {
                                    $usernameUsado = false;
                                    $correoUsado = false;
                                    $passIncorrecta = false;

                                    $listaUsernames = $usuariosBD->getUsernames();
                                    if ($listaUsernames != null) {
                                        foreach ($listaUsernames as $username) {
                                            if ($_SESSION["username"] == $username["username"]) {
                                                $usernameUsado = false;
                                            } else if ($_POST["username"] == $username["username"]) {
                                                $usernameUsado = true;
                                            }
                                        }
                                    }

                                    $listaUsuarios = $usuariosBD->getUsuarios();
                                    if ($listaUsuarios != null) {
                                        foreach ($listaUsuarios as $mail) {
                                            if ($_POST["mail"] == $mail["mail"]) {
                                                if ($usuario["mail"] != $mail["mail"]) {
                                                    $correoUsado = true;
                                                }
                                            }
                                        }
                                    }

                                    if (!$usuariosBD->comprobarPasswd($_SESSION["username"], hash("sha256", $_POST["passwVieja"]))) {
                                        $passIncorrecta = true;
                                    }



                                    if (!$passIncorrecta) {
                                        if (!$usernameUsado) {
                                            if (!$correoUsado) {
                                                if ($_POST["passw"] != "") {
                                                    $passw = hash("sha256", $_POST["passw"]);
                                                } else {
                                                    $passw = null;
                                                }
                                                if ($usuariosBD->actualizarUsuario($usuario["id"], $_POST["provincia"], $_POST["comunidad"], $_POST["nombre"], $_POST["apellidos"], $_POST["DNI"], $_POST["telefono"], $_POST["calle"], $_POST["numero"], $_POST["piso"], $_POST["puerta"], $_POST["cp"], $_POST["poblacion"], $_POST["mail"], $_POST["username"], $passw)) {
                                                    $_SESSION["username"] = $_POST["username"];
                                                    ?>
                                                    <script>
                                                        location.href = "perfil.php";
                                                    </script>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <script>
                                                    alert("EL CORREO ELECTRÓNICO INDICADO YA ESTÁ REGISTRADO");
                                                </script>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <script>
                                                alert("EL NOMBRE DE USUARIO YA ESTÁ EN USO");
                                            </script>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <script>
                                            alert("LA CONTRASEÑA VIEJA ES ERRÓNEA");
                                        </script>
                                        <?php
                                    }
                                }
                                ?>
                            </section>
                        </div>
                    </div>
                    <script>
                        const openEls = document.querySelectorAll("[data-open]");
                        const isVisible = "is-visible";

                        for (const el of openEls) {
                            el.addEventListener("click", function () {
                                const modalId = this.dataset.open;
                                document.getElementById(modalId).classList.add(isVisible);
                            });
                        }

                        const closeEls = document.querySelectorAll("[data-close]");

                        for (const el of closeEls) {
                            el.addEventListener("click", function () {
                                this.parentElement.parentElement.parentElement.classList.remove(isVisible);
                            });
                        }

                        document.addEventListener("click", e => {
                            if (e.target == document.querySelector(".modal.is-visible")) {
                                document.querySelector(".modal.is-visible").classList.remove(isVisible);
                            }
                        });

                        document.addEventListener("keyup", e => {
                            if (e.key == "Escape" && document.querySelector(".modal.is-visible")) {
                                document.querySelector(".modal.is-visible").classList.remove(isVisible);
                            }
                        });
                    </script>
                </section>
                <section id="compras">
                    <h2>Últimas compras</h2>                        
                    <?php
                    $compraBD = new CompraBD();
                    $ultimasCompras = $compraBD->getComprasOrdenFecha($usuario["id"]);
                    if ($ultimasCompras != null) {                        
                        ?>
                        <div id="productos">
                            <?php                            
                            foreach($ultimasCompras as $ultimaCompra) {
                                ?>
                                <div class="producto">
                                    <div class="imagen"><img onclick="location.href = '<?= $url ?>productos/producto.php?id=<?= $ultimaCompra["id_producto"] ?>'" src="data:image/jpg;base64,<?= base64_encode($ultimaCompra["imagen"]) ?>"/></div>
                                    <div class="datos">
                                        <a href="<?= $url ?>productos/producto.php?id=<?= $ultimaCompra["id_producto"] ?>"><b><?= $ultimaCompra["nombre"] ?></b></a>
                                        <span><?= $ultimaCompra["precio"] ?>€</span>
                                        <span>Cantidad: <?= $ultimaCompra["cantidad"] ?></span>
                                        <span><b>Fecha: </b><?= $ultimaCompra["fecha"] ?></span>
                                    </div>  
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    } else {
                        ?>
                        <h4>Todavía no has comprado nada con nosotros</h4>
                        <button onclick="location.href = '../productos/productos.php'">Compra ahora</button>
                        <?php
                    }
                    ?>
                </section>                    
            </main>            
            <?php
            $cabeceraFooter->footer();
        }

        function cerrarSesion() {
            setcookie("PHPSESSID","valor",time()-3600);
            unset($_SESSION["username"]);
            session_unset();
            session_destroy();            
            header("Location: ../../index.php");
        }
        ?>
    </body>
</html>
