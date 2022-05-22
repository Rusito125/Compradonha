<?php

// conexión con ajax para el select de provincias
if (isset($_GET["codComunidad"])) {
    require_once 'AccesoBD.php';
    $pdo = new AccesoBD();
    $pdo = $pdo->abrirConexion();
    $result = $pdo->query("SELECT * FROM Provincia WHERE codComunidad = " . $_GET["codComunidad"]);
    if ($result->rowCount() > 0) {
        $provincias = $result->fetchAll();
    }
    foreach ($provincias as $provincia) {
        echo "<option value='" . $provincia["codProvincia"] . "'>" . $provincia["nombre"] . "</option>";
    }
    $pdo = null;
}

class ComunidadesBD {

    public function __construct() {
        require_once 'AccesoBD.php';
    }

    function getComunidades() {
        $pdo = new AccesoBD();
        $pdo = $pdo->abrirConexion();
        $result = $pdo->query("SELECT * FROM Comunidades");
        if ($result->rowCount() > 0) {
            $comunidades = $result->fetchAll();
        }
        $pdo = null;
        return $comunidades;
    }

}

?>