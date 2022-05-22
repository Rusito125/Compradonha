<?php

class UsuariosBD {

    public function __construct() {
        require_once 'AccesoBD.php';
    }

    function setUsuario($nombre, $apellidos, $DNI, $telefono, $calle, $numero, $piso, $puerta, $comunidad, $provincia, $poblacion, $cp, $mail, $username, $passwd) {
        $correcto = false;
        $pdo = new AccesoBD();
        $pdo = $pdo->abrirConexion();

        $insertar = $pdo->prepare("INSERT into Usuarios (id, nombre, apellidos, DNI, telefono, calle, numero, piso, puerta, cp, poblacion, codProvincia, codComunidad, mail, id_rol) VALUES (0,?,?,?,?,?,?,?,?,?,?,?,?,?,1)");
        if ($insertar->execute(array($nombre, $apellidos, $DNI, $telefono, $calle, $numero, $piso, $puerta, $cp, $poblacion, $provincia, $comunidad, $mail))) {
            $insertar = $pdo->prepare("INSERT into Sesiones (id_usuario, username, fecha, passwd) VALUES ((SELECT max(id) from Usuarios), ?, now(), ?)");
            if ($insertar->execute(array($username, hash("sha256", $passwd)))) {
                $correcto = true;
            } else {
                echo "Ha fallado la creación de una cuenta de usuario";
            }
        } else {
            echo "Ha fallado la creación del usuario";
        }
        $pdo = null;
        return $correcto;
    }

    function getUsernames() {
        $pdo = new AccesoBD();
        $pdo = $pdo->abrirConexion();
        $usernames = null;
        $result = $pdo->query("SELECT username FROM sesiones");
        if ($result->rowCount() > 0) {
            $usernames = $result->fetchAll();
        }
        return $usernames;
    }

    function getUsuarioByUsername($username) {
        $pdo = new AccesoBD();
        $pdo = $pdo->abrirConexion();
        $result = $pdo->query("SELECT u.*,p.nombre as provincia,c.nombre as comunidad FROM usuarios u JOIN provincia p ON p.codProvincia = u.codProvincia JOIN comunidades c ON c.codComunidad = u.codComunidad WHERE u.id = (SELECT id_usuario FROM sesiones WHERE username = '$username')");
        $usuario = null;
        if ($result->rowCount() > 0) {
            $usuario = $result->fetch();
        }
        return $usuario;
    }

    function comprobarPasswd($username, $passwd) {
        $pdo = new AccesoBD();
        $pdo = $pdo->abrirConexion();
        $passwCorrecta = false;
        $result = $pdo->query("SELECT passwd FROM sesiones WHERE username = '$username'");
        if ($result->rowCount() > 0) {
            $contra = $result->fetch();
            if ($contra["passwd"] == hash("sha256", $passwd)) {
                $passwCorrecta = true;
            }
        }
        return $passwCorrecta;
    }

    function existeUsuario($username) {
        $pdo = new AccesoBD();
        $pdo = $pdo->abrirConexion();
        $existe = false;
        if ($pdo->query("SELECT * FROM sesiones WHERE username = '$username'")) {
            $existe = true;
        }
        return $existe;
    }

    function reestablecerContra($username, $passwd) {
        $pdo = new AccesoBD();
        $pdo = $pdo->abrirConexion();
        $correcto = false;
        $insertar = $pdo->prepare("UPDATE sesiones SET passwd=? WHERE username=?");
        if ($insertar->execute(array(hash("sha256", $passwd), $username))) {
            $insertar = $pdo->prepare("UPDATE usuarios SET " . ($telefono != null ? "telefono=?," : "") . " " . ($calle != null ? "calle=?," : "") . " " . ($numero != null ? "numero=?," : "") . " $piso=?, $puerta=?, $mail=? WHERE id=(SELECT id_usuario FROM sesiones WHERE username='?')");
            $correcto = true;
        } else {
            echo "Ha fallado el cambio de contraseña";
        }
        return $correcto;
    }

}

?>