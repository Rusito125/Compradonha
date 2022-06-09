<!DOCTYPE html>
<!-- 
     TODO:          
          falta editar el section registro para mostrar datos del usuario cuando éste esté conectado
          opcional cambiar algunos diseños a bootstrap
          quitar hardcoded javascript
-->
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Compradoña</title>
        <script src="https://kit.fontawesome.com/3b88ef1ad2.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" type="text/css" href="estilos/normalize.css"/>
        <link rel="stylesheet" type="text/css" href="estilos/estilos.css"/>
        <link rel="stylesheet" type="text/css" href="estilos/estilosIndex.css"/>
        <link rel="icon" href="img/varios/favicon.png"/>        
    </head>
    <body>     
        <?php
        session_start();
        require_once 'html-php/archivosBD/ProductosBD.php';
        require_once 'html-php/cabeceraFooter.php';
        require_once './html-php/juego/juegoDino.php';
        $cabeceraFooter = new CabeceraFooter();
        $cabeceraFooter->cabecera();
        $productosBD = new ProductosBD();
        ?>
        <main>
            <section id="bienvenido">
                <h1>Bienvenido a Compradoña</h1>
                <button onclick="location.href = 'html-php/productos/productos.php'">Empezar a comprar</button>
            </section>
            <div id="regNov">
                <section id="registro">
                    <?php
                    if (isset($_SESSION["username"])) {
                        require_once 'html-php/archivosBD/UsuariosBD.php';
                        $usuariosBD = new UsuariosBD();
                        $usuario = $usuariosBD->getUsuarioByUsername($_SESSION["username"]);
                        $juegos = new Juegos;
                        ?>
                        <h2>Bienvenido <?= $usuario["nombre"] ?></h2>
                        <button onclick="location.href = 'html-php/usuarios/perfil'">Mi perfil</button>    
                        <?php
                        $juegos->juegoDino();
                    } else {
                        ?>
                        <div>
                            <h2>¿Todavía no eres socio?</h2>
                            <button onclick="location.href = 'html-php/usuarios/iniciar.php?action=registro'">Registrarse</button>
                        </div>
                        <button onclick="location.href = 'html-php/usuarios/iniciar.php?action=iniciar'">Iniciar sesión</button>
                    <?php } ?>
                </section>
                <section id="novedades">
                    <h1>Novedades</h1>
                    <div id="imgNov">
                        <?php
                        $productos = $productosBD->getProductos();
                        if ($productos != null) {
                            $cantidadProductos = sizeof($productos) < 4 ? sizeof($productos) : 4;
                            for ($i = 0; $i < $cantidadProductos; $i++) {
                                ?>
                                <div><a href="html-php/productos/producto?id=<?= $productos[$i]["id"] ?>"><?= $productos[$i]["nombre"] ?></a><img src="data:image/jpg;base64,<?= base64_encode($productos[$i]['imagen']) ?>"></div>                        
                                <?php
                            }
                        }
                        ?>
                    </div>
                </section>
            </div>
        </main>
        <?php
        $cabeceraFooter->footer();
        ?>
    </body>
</html>
