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
        <link rel="stylesheet" type="text/css" href="../../estilos/estilosQuienes.css"/>
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
            <section>
                <div>
                    <h1>¿Quiénes somos?</h1>
                    <p>
                        Somos una pequeña empresa independiente que busca facilitar las
                        compras online de productos variados de diferentes supermercados
                        proporcionando un envío rápido y eficaz.
                    </p>
                </div>
                <div id="importantes">
                    <div>
                        <h3>Nuestro fundador</h3>
                        <img src="../../img/varios/FundadorCompradonha.jpg">
                        <p>
                            Originario de un pequeño pueblo de Galicia, Santiago es nuestro
                            fundador. En la empresa todo el mundo le conoce como Santi.
                            Gracias a él hemos podido llegar a dónde estamos ahora.
                        </p>
                    </div>
                    <div>
                        <h3>Nuestro CEO actual</h3>
                        <img src="../../img/varios/CEOCompradonha.gif">
                        <p>
                            Cristian es nuestro CEO actual y se encarga de todas las 
                            coordinaciones importantes de la empresa. Sin él, nada de esto
                            sería posible.
                        </p>
                    </div>
                </div>
            </section>
        </main>
        <?php
        $cabeceraFooter->footer();
        ?>
    </body>
</html>
