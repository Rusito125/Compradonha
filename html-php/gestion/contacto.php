<!DOCTYPE html>
<!--
    TODO
        funciones y diseño listos
-->
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">        
        <title>Quienes somos - Compradoña</title>
        <script src="https://kit.fontawesome.com/3b88ef1ad2.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" type="text/css" href="../../estilos/normalize.css"/>
        <link rel="stylesheet" type="text/css" href="../../estilos/estilos.css"/>
        <link rel="stylesheet" type="text/css" href="../../estilos/estilosContacto.css"/>
        <link rel="icon" href="../../img/varios/favicon.png"/>
    </head>
    <body>
        <?php
        require_once '../cabeceraFooter.php';
        session_start();
        $cabeceraFooter = new CabeceraFooter();
        $cabeceraFooter->cabecera();
        ?>
        <main>            
            <section id="formulario">
                <h2>Formulario de contacto</h2>
                <form onsubmit="alert('Formulario enviado')">
                    <fieldset>
                        <label for="mail">Correo electrónico: </label><input type="email" id="mail" name="mail" required/>
                        <label for="motivo">Motivo: </label><input type="text" id="motivo" name="motivo" required/>
                        <label for="mensaje">Mensaje: </label><textarea id="mensaje" name="mensaje" required></textarea>
                        <button>Enviar</button>
                    </fieldset>
                </form>
            </section>
            <section id="redes">
                <h2>Nuestras Redes</h2>
                <p>
                    <a><i class="fa-brands fa-facebook"></i></a>
                    <a><i class="fa-brands fa-twitter"></i></a>
                    <a><i class="fa-brands fa-instagram"></i></a>
                    <a><i class="fa-brands fa-tiktok"></i></a>
                    <a><i class="fa-brands fa-google-plus-g"></i></a>
                </p>
            </section>
        </main>
        <?php
        $cabeceraFooter->footer();
        ?>
    </body>
