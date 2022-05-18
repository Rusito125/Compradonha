<!DOCTYPE html>
<!-- 
    TODO
        falta por crear diseño y funciones
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Mercadonha</title>
        <script src="https://kit.fontawesome.com/3b88ef1ad2.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" type="text/css" href="../../estilos/normalize.css"/>
        <link rel="stylesheet" type="text/css" href="../../estilos/estilos.css"/>
        <link rel="stylesheet" type="text/css" href="../../estilos/estilosCarro.css"/>
        <link rel="icon" href="../../img/varios/favicon.png"/>        
    </head>
    <body>     
        <?php
        session_start();
        require_once '../archivosBD/ProductosBD.php';

        $productosBD = new ProductosBD();
        require_once '../cabeceraFooter.php';
        $cabeceraFooter = new CabeceraFooter();
        $cabeceraFooter->cabecera();
        ?>        
        <main>
            
        </main>
        <?php 
        $cabeceraFooter->footer();
        ?>   
    </body>
</html>
