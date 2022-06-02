<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
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
            case "crear":
                echo "<title>Verificación de correo - Compradoña</title>";
                break;
            case "verificado":
                echo "<title>Verificación - Compradoña</title>";
                break;
        }
        require_once '../archivosBD/UsuariosBD.php';
        ?>        
    </head>
    <body>
        <?php
        if (isset($_SESSION["username"]) && $_action == "crear") {
            header("Location: perfil.php");
        }
        require_once '../cabeceraFooter.php';
        $cabeceraFooter = new CabeceraFooter();
        $cabeceraFooter->cabecera();
        ?>

        <main>
            <?php
            switch ($_action) {
                case "crear":
                    verificarCorreo();
                    break;
                case "verificado":
                    correoVerificado();
                    break;
            }

            function verificarCorreo() {
                require_once '../archivosBD/UsuariosBD.php';
                $usuariosBD = new UsuariosBD();
                $usuario = $usuariosBD->getUsuarioByUsername($_GET["user"]);
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->SMTPSecure = 'tls';
                    $mail->Host = 'smtp.ionos.es';
                    $mail->Port = 587;
                    $mail->SMTPAuth = true;
                    $mail->Username = 'contacto@mercadonha.es';
                    $mail->Password = 'CompradonhaContacto123.';

                    $mail->setFrom('contacto@mercadonha.es', 'Verificación de cuenta');
                    $mail->addAddress($usuario["mail"], $usuario["nombre"]);

                    $mail->isHTML(true);
                    $mail->Subject = "Verificación de cuenta";
                    $mail->Body = "Hola " . $usuario["nombre"] . " " . $usuario["apellidos"] . ".<br/>"
                            . "Este correo ha sido generado automáticamente porque has creado una cuenta.<br/>"
                            . "Si usted es " . $usuario["nombre"] . " haga click en el siguiente enlace para finalizar su registro:<br/>"
                            . "<a href='http://mercadonha.es/html-php/usuarios/verifica?action=verificado&cod=" . hash("sha256", $_GET["user"]) . "'>Verificar cuenta</a><br/>"
                            . "Si usted no es " . $usuario["nombre"] . " ignore este correo y bórrelo.<br/>"
                            . "Gracias por confiar en nosotros.<br/>"
                            . "Compradoña";
                    $mail->CharSet = 'UTF-8';

                    $mail->send();
                    ?><section class="inicio">
                        <h2>Verificación de correo electrónico</h2>
                        <h3>Se le ha enviado un correo de verificación (Revise spam)</h3>
                    </section>
                    <?php
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
            }

            function correoVerificado() {
                require_once '../archivosBD/UsuariosBD.php';
                $usuariosBD = new UsuariosBD();
                $sesion = $usuariosBD->getUserByCod($_GET["cod"]);
                if ($sesion != null) {
                    if ($usuariosBD->verificarSesion($sesion["username"])) {
                        $_SESSION["username"] = $sesion["username"];
                        ?>
                        <script>
                            location.href = "perfil.php";
                        </script>
                        <?php
                    } else {
                        echo "<h3>Error al verificar su cuenta</h3>";
                    }
                } else {
                    echo "<h3>Error al verificar su cuenta</h3>";
                }
            }
            ?>
        </main>
        <?php
        $cabeceraFooter->footer();
        ?>
    </body>
</html>