<!DOCTYPE html>
<!--
    TODO 
        falta por añadir las nuevas secciones de la bd
        falta por permitir editar usuario
        al cerrar sesión se debe guardar el carro para cuando se vuelva a iniciar
        falta por hacer sección de últimas compras
        falta por hacer sección de cupones
        quitar hardcoded javascript
-->
<html>
    <head>
        <meta charset="UTF-8">
        <script src="https://kit.fontawesome.com/3b88ef1ad2.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" type="text/css" href="../../estilos/normalize.css"/>
        <link rel="stylesheet" type="text/css" href="../../estilos/estilos.css"/>
        <link rel="stylesheet" type="text/css" href="../../estilos/estilosPerfil.css"/>
        <link rel="icon" href="../../img/varios/favicon.png"/>    
        <title>Perfil</title>
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
            require_once '../cabeceraFooter.php';
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
                    <div>                        
                        <ul>
                            <li>Nombre de usuario: <b><?= $_SESSION["username"] ?></b></li>
                            <li>Nombre y apellidos: <b><?= $usuario["nombre"] ?> <?= $usuario["apellidos"] ?></b></li>
                            <li>DNI: <b><?= $usuario["DNI"] ?></b></li>
                            <li>Telefono: <b><?= $usuario["telefono"] ?></b></li>
                            <li>Dirección: <b><?= $usuario["calle"] ?> N<?= $usuario["numero"] ?> <?= $usuario["piso"] ?><?= $usuario["puerta"] ?></b></li>
                            <li>Código postal: </li>
                            <li>Población: </li>
                            <li>Provincia: </li>
                            <li>Correo electrónico: <b><?= $usuario["mail"] ?></b></li>
                            <li><a href="#">Editar pefil</a></li>
                        </ul>
                    </div>
                </section>
                <div id="comprasCup">
                    <section id="compras">
                        <h2>Últimas compras</h2>
                    </section>
                    <section id="cupones">
                        <h2>Tus cupones</h2>
                    </section>
                </div>
            </main>            
            <?php
            $cabeceraFooter->footer();
        }

        function cerrarSesion() {
            unset($_SESSION["username"]);
            unset($_SESSION["carro"]);
            header("Location: http://mercadonha.es/");
        }
        ?>
    </body>
</html>
