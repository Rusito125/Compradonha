<?php
// conexión con ajax para el select de provincias
if (isset($_GET["codComunidad"])) {
    require_once 'AccesoBD.php';
    $pdo = new AccesoBD();
    $pdo = $pdo->abrirConexion();
    if (isset($_GET["prov"])) {
        $provinciaNom = $_GET["prov"];
    } else {
        $provinciaNom = null;
    }
    $result = $pdo->query("SELECT * FROM Provincia WHERE codComunidad = " . $_GET["codComunidad"]);
    if ($result->rowCount() > 0) {
        $provincias = $result->fetchAll();
    }
    foreach ($provincias as $provincia) {
        ?>
        <option value="<?= $provincia["codProvincia"] ?>" <?= $provinciaNom == $provincia["nombre"] ? " selected" : "" ?>><?= $provincia["nombre"] ?></option>
        <?php
    }
    $pdo = null;
} else if (isset($_GET["cargarComunidades"])) {
    require_once 'AccesoBD.php';
    $pdo = new AccesoBD();
    $pdo = $pdo->abrirConexion();
    if (isset($_GET["comu"])) {
        $comunidadNom = $_GET["comu"];
    } else {
        $comunidadNom = null;
    }
    $result = $pdo->query("SELECT * FROM Comunidades");
    if ($result->rowCount() > 0) {
        $comunidades = $result->fetchAll();
    }
    foreach ($comunidades as $comunidad) {
        ?>
        <option value="<?= $comunidad["codComunidad"] ?>" <?= $comunidadNom == $comunidad["nombre"] ? " selected" : "" ?>><?= $comunidad["nombre"] ?></option>
        <?php
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