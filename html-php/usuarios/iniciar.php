<!DOCTYPE html>
<!-- 
    quitar hardcoded javascript
-->
<html>
    <head>
        <meta charset="UTF-8">
        <script src="https://kit.fontawesome.com/3b88ef1ad2.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" type="text/css" href="../../estilos/normalize.css"/>
        <link rel="stylesheet" type="text/css" href="../../estilos/estilos.css"/>
        <link rel="stylesheet" type="text/css" href="../../estilos/estilosIniciar.css"/>
        <link rel="icon" href="../../img/varios/favicon.png"/>  
        <?php
        session_start();
        require '../../PHPMailer-6.6.0/src/Exception.php';
        require '../../PHPMailer-6.6.0/src/PHPMailer.php';
        require '../../PHPMailer-6.6.0/src/SMTP.php';

        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\SMTP;
        use PHPMailer\PHPMailer\Exception;

$_action = isset($_GET["action"]) ? $_GET["action"] : "";
        switch ($_action) {
            case "iniciar":
                echo "<title>Inicio de sesión</title>";
                break;
            case "registro":
                echo "<title>Registrarse</title>";
                break;
            case "olvida":
                echo "<title>Reestablecer contraseña</title>";
                break;
            case "correoEnviado":
                echo "<title>Correo enviado</title>";
                break;
            case "enlaceRecup":
                echo "<title>Crear contraseña</title>";
                break;
        }
        require_once '../archivosBD/UsuariosBD.php';
        ?>        
    </head>
    <body>
        <?php
        if (isset($_SESSION["username"])) {
            header("Location: perfil.php");
        }
        require_once '../cabeceraFooter.php';
        $cabeceraFooter = new CabeceraFooter();
        $cabeceraFooter->cabecera();
        ?>

        <main>
            <?php
            switch ($_action) {
                case "iniciar":
                    iniciarSesion();
                    break;
                case "registro":
                    registrarse();
                    break;
                case "olvida":
                    reestablecerContra();
                    break;
                case "correoEnviado":
                    correoEnviado();
                    break;
                case "enlaceRecup":
                    crearContra();
                    break;
            }

            function iniciarSesion() {
                ?>
                <section class="inicio">
                    <h2>Inicio de sesión</h2>
                    <form method="post">
                        <fieldset>
                            <label for="username">Nombre de usuario: </label>
                            <input type="text" name="username" id="username"/>
                            <label for="passw">Contraseña: </label>
                            <input type="password" name="passw" id="passw"/> 
                            <div><a href="?action=olvida">¿Ha olvidado su contraseña?</a></div>
                        </fieldset>
                        <button>Iniciar sesión</button>
                    </form>
                </section>
                <?php
                if (isset($_POST["username"]) && isset($_POST["passw"])) {
                    $usuariosBD = new UsuariosBD();
                    if ($usuariosBD->comprobarPasswd($_POST["username"], $_POST["passw"])) {
                        $_SESSION["username"] = $_POST["username"];
                        ?>
                        <script>
                            location.href = "perfil.php";
                        </script>
                        <?php
                    } else {
                        ?>
                        <script>
                            alert("Usuario o contraseña incorrectos");
                        </script>
                        <?php
                    }
                }
            }

            function registrarse() {
                require_once '../archivosBD/ComunidadesBD.php';
                $comunidadesBD = new ComunidadesBD();
                ?>
                <section class="registro">
                    <h2>Registrarse</h2>
                    <form onsubmit="return validar()" method="post">
                        <fieldset>
                            <legend>Datos de inicio de sesión</legend>
                            <label for="username">Nombre de usuario: </label>
                            <input type="text" name="username" id="username" maxlength="20" required/>                            
                            <label for="passw">Contraseña: </label>
                            <input type="password" name="passw" id="passw" minlength="6" maxlength="20" required/>
                            <label class="aviso contra">La contraseña debe tener entre 6 y 20 caracteres y al menos una minúscula, una mayúscula y un número</label>
                            <label for="passw2">Repita su contraseña: </label>
                            <input type="password" name="passw2" id="passw2" minlength="6" maxlength="20" required/>
                        </fieldset>
                        <fieldset>
                            <legend>Datos personales</legend>
                            <label for="nombre">Nombre: </label>
                            <input type="text" name="nombre" id="nombre" maxlength="20" required <?= isset($_POST["nombre"]) ? "value='" . $_POST["nombre"] . "'" : "" ?>/>
                            <label for="apellidos">Apellidos: </label>
                            <input type="text" name="apellidos" id="apellidos" maxlength="30" required <?= isset($_POST["apellidos"]) ? "value='" . $_POST["apellidos"] . "'" : "" ?>/>
                            <label for="DNI">DNI: </label>
                            <input type="text" name="DNI" id="DNI" minlength="9" maxlength="9" required <?= isset($_POST["DNI"]) ? "value='" . $_POST["DNI"] . "'" : "" ?>/>
                            <label for="telefono">Teléfono: </label>
                            <input type="text" name="telefono" id="telefono" minlength="9" maxlength="9" required <?= isset($_POST["telefono"]) ? "value='" . $_POST["telefono"] . "'" : "" ?>/>
                            <label for="calle">Calle: </label>
                            <input type="text" name="calle" id="calle" maxlength="50" required <?= isset($_POST["calle"]) ? "value='" . $_POST["calle"] . "'" : "" ?>/>
                            <label for="numero">Número: </label>
                            <input type="text" name="numero" id="numero" maxlength="5" required <?= isset($_POST["numero"]) ? "value='" . $_POST["numero"] . "'" : "" ?>/>
                            <label for="piso">Piso: </label>
                            <input type="number" name="piso" id="piso" maxlength="2" required <?= isset($_POST["piso"]) ? "value='" . $_POST["piso"] . "'" : "" ?>/>
                            <label for="puerta">Puerta: </label>
                            <input type="text" name="puerta" id="puerta" maxlength="1" required <?= isset($_POST["puerta"]) ? "value='" . $_POST["puerta"] . "'" : "" ?>/>
                            <label for="comunidad">Comunidad Autónoma: </label>
                            <select name="comunidad" onchange="mostrarProvincias(this.value)">
                                <option selected>Selecciona una Comunidad Autónoma</option>
                                <?php
                                $comunidades = $comunidadesBD->getComunidades();
                                foreach ($comunidades as $comunidad) {
                                    ?>
                                    <option value="<?= $comunidad["codComunidad"] ?>"><?= $comunidad["nombre"] ?></option>
                                    <?php
                                }
                                ?>
                            </select>                            
                            <label for="provincia">Provincia: </label>
                            <select name="provincia" id="provincias">
                                <option selected>Selecciona una Comunidad Autónoma</option>
                            </select>
                            <script>
                                function mostrarProvincias(codComunidad) {
                                    var xhttp = new XMLHttpRequest();
                                    xhttp.onreadystatechange = function (){
                                      if(this.readyState == 4 && this.status == 200){
                                          document.getElementById("provincias").innerHTML = this.responseText;
                                      }  
                                    };
                                    xhttp.open("GET", "../archivosBD/ComunidadesBD.php?codComunidad="+codComunidad, true);
                                    xhttp.send();
                                }
                            </script>
                            <label for="poblacion">Población: </label>
                            <input type="text" name="poblacion" id="poblacion" maxlength="44" required <?= isset($_POST["poblacion"]) ? "value='" . $_POST["poblacion"] . "'" : "" ?>/>
                            <label for="cp">Código postal: </label>
                            <input type="text" name="cp" id="cp" maxlength="5" required <?= isset($_POST["cp"]) ? "value='" . $_POST["cp"] . "'" : "" ?>/>                          
                            <label for="mail">Correo electrónico: </label>
                            <input type="email" name="mail" id="mail" maxlength="40" required <?= isset($_POST["mail"]) ? "value='" . $_POST["mail"] . "'" : "" ?>/>
                        </fieldset>
                        <fieldset>
                            <legend>Captcha</legend>
                            <label for="captcha">Introduce el texto del captcha</label>
                            <div><img src="captcha.php" alt="CAPTCHA" class="captcha-image"><i class="fas fa-redo refresh-captcha"></i></div>
                            <input type="text" id="captcha" name="captcha_challenge" pattern="[A-Z]{6}">
                            <script>
                                var refreshButton = document.querySelector(".refresh-captcha");
                                refreshButton.onclick = function () {
                                    document.querySelector(".captcha-image").src = 'captcha.php?' + Date.now();
                                };
                            </script>
                        </fieldset>
                        <button>Registrarse</button>
                    </form>
                </section>
                <script>
                    // variables
                    let nombre = document.getElementById("nombre");
                    let apellidos = document.getElementById("apellidos");
                    let DNI = document.getElementById("DNI");
                    let telefono = document.getElementById("telefono");
                    let passw = document.getElementById("passw");
                    let passw2 = document.getElementById("passw2");

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

                    passw.addEventListener("change", e => {
                        let aviso = getAviso(e.target);
                        if (!/^(?=\w*\d)(?=\w*[A-Z])(?=\w*[a-z])\S{6,20}$/.test(e.target.value)) {
                            aviso.innerText = "Contraseña no válida";
                            e.target.insertAdjacentElement('afterend', aviso);
                        } else {
                            aviso.remove();
                        }
                        eventoPassw2();
                    });

                    passw2.addEventListener("change", eventoPassw2);
                    function passCoinciden() {
                        let coinciden = true;
                        if (passw.value != passw2.value) {
                            coinciden = false;
                        }
                        return coinciden;
                    }

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
                        if (!/^(?=\w*\d)(?=\w*[A-Z])(?=\w*[a-z])\S{6,20}$/.test(passw.value)) {
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
                if (isset($_POST['captcha_challenge']) && $_POST['captcha_challenge'] == $_SESSION['captcha_text']) {
                    $usernameUsado = false;
                    $usuariosBD = new UsuariosBD();
                    $listaUsernames = $usuariosBD->getUsernames();
                    if ($listaUsernames != null) {
                        foreach ($listaUsernames as $username) {
                            if ($_POST["username"] == $username["username"]) {
                                $usernameUsado = true;
                            }
                        }
                    }
                    if (!$usernameUsado) {
                        if ($usuariosBD->setUsuario($_POST["nombre"], $_POST["apellidos"], $_POST["DNI"], $_POST["telefono"], $_POST["calle"], $_POST["numero"], $_POST["piso"], $_POST["puerta"], $_POST["mail"], $_POST["username"], $_POST["passw"])) {
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
                            alert("EL NOMBRE DE USUARIO YA ESTÁ EN USO");
                        </script>
            <?php
        }
    }
}

function reestablecerContra() {
    ?>
                <section class="inicio">
                    <h2>Recordar contraseña</h2>
                    <form method="post" action="?action=correoEnviado">
                        <fieldset>
                            <label for="username">Nombre de usuario: </label>
                            <input type="text" name="username" id="username"/>
                            <label for="mail">Correo electróncio: </label>
                            <input type="email" name="mail" id="mail"/>
                        </fieldset>
                        <button>Recordar contraseña</button>
                    </form>
                </section>
                <?php
            }

            function correoEnviado() {
                if (isset($_POST["username"]) && isset($_POST["mail"])) {
                    $usuariosBD = new UsuariosBD();
                    if ($usuariosBD->existeUsuario($_POST["username"])) {
                        $usuario = $usuariosBD->getUsuarioByUsername($_POST["username"]);
                        if ($usuario["mail"] == $_POST["mail"]) {
                            $mensaje = "Recuerde su contraseña bobo";
                            $mail = new PHPMailer(true);
                            try {
                                $mail->isSMTP();
                                $mail->SMTPSecure = 'tls';
                                $mail->Host = 'smtp.ionos.es';
                                $mail->Port = 587;
                                $mail->SMTPAuth = true;
                                $mail->Username = 'contacto@mercadonha.es';
                                $mail->Password = 'JPsiempre123';

                                $mail->setFrom('contacto@mercadonha.es', 'Recordatorio password');
                                $mail->addAddress($usuario["mail"], $usuario["nombre"]);

                                $mail->isHTML(true);
                                $mail->Subject = "Recordar password";
                                $mail->Body = "Hola " . $usuario["nombre"] . " " . $usuario["apellidos"] . ".<br/>"
                                        . "Este correo ha sido generado automáticamente porque has olvidado la contraseña.<br/>"
                                        . "Si usted es " . $usuario["nombre"] . " haga click en el siguiente enlace para crear una nueva contraseña:<br/>"
                                        . "<a href='http://mercadonha.es/usuarios/iniciar?action=enlaceRecup'>Crear nueva contraseña</a><br/>"
                                        . "Si usted no es " . $usuario["nombre"] . " ignore este correo y bórrelo.<br/>"
                                        . "Gracias por confiar en nosotros.<br/>"
                                        . "Mercadonha";
                                $mail->CharSet = 'UTF-8';

                                $mail->send();
                                ?><section class="inicio">
                                    <h2>Recordar contraseña</h2>
                                    <h3>Se ha enviado un correo para recuperar la contraseña (Revise spam)</h3>
                                </section>
                                <?php
                            } catch (Exception $e) {
                                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                            }
                        } else {
                            ?>
                            <script>
                                alert("El correo electrónico no corresponde al usuario indicado");
                                location.href = "?action=olvida";
                            </script>
                            <?php
                        }
                    } else {
                        ?>
                        <script>
                            alert("Usuario no registrado");
                            location.href = "?action=olvida";
                        </script>
                        <?php
                    }
                } else {
                    ?>
                    <script>
                        alert("Usuario o correo electrónico no registrados");
                        location.href = "?action=olvida";
                    </script>
        <?php
    }
}

function crearContra() {
    ?>
                <section class="inicio">
                    <h2>Reestablecer</h2>
                    <form method="post" onsubmit="return validar()">
                        <fieldset>
                            <label for="username">Nombre de usuario: </label>
                            <input type="text" name="username" id="username"/>
                            <label for="passw">Nueva contraseña: </label>
                            <input type="password" name="passw" id="passw" minlength="6" maxlength="20" required/>
                            <label class="aviso contra">La contraseña debe tener entre 6 y 20 caracteres y al menos una minúscula, una mayúscula y un número</label>
                            <label for="passw2">Repita la contraseña: </label>
                            <input type="password" name="passw2" id="passw2" minlength="6" maxlength="20" required/>
                        </fieldset>
                        <button>Crear nueva contraseña</button>
                    </form>
                </section>
                <script>
                    let passw = document.getElementById("passw");
                    let passw2 = document.getElementById("passw2");

                    passw.addEventListener("change", e => {
                        let aviso = getAviso(e.target);
                        if (!/^(?=\w*\d)(?=\w*[A-Z])(?=\w*[a-z])\S{6,20}$/.test(e.target.value)) {
                            aviso.innerText = "Contraseña no válida";
                            e.target.insertAdjacentElement('afterend', aviso);
                        } else {
                            aviso.remove();
                        }
                        eventoPassw2();
                    });

                    passw2.addEventListener("change", eventoPassw2);
                    function passCoinciden() {
                        let coinciden = true;
                        if (passw.value != passw2.value) {
                            coinciden = false;
                        }
                        return coinciden;
                    }

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

                        if (!passCoinciden()) {
                            correcto = false;
                        }

                        return correcto;
                    }
                </script>
                <?php
                if (isset($_POST["username"]) && isset($_POST["passw"])) {
                    $usuariosBD = new UsuariosBD();
                    if ($usuariosBD->existeUsuario($_POST["username"])) {
                        if ($usuariosBD->reestablecerContra($_POST["username"], $_POST["passw"])) {
                            ?>
                            <script>
                                alert("La contraseña ha sido cambiada correctamente");
                                location.href = "?action=iniciar";
                            </script>
                        <?php
                    }
                } else {
                    echo "No existe el usuario";
                }
            }
        }
        ?>
        </main>
<?php
$cabeceraFooter->footer();
?>
    </body>
</html>
