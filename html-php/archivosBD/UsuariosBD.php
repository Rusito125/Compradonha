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
            $insertar = $pdo->prepare("INSERT into Sesiones (id_usuario, username, fecha, passwd, verificado, codVerifica) VALUES ((SELECT max(id) from Usuarios), ?, now(), ?, false, ?)");
            if ($insertar->execute(array($username, $passwd, hash("sha256", $username)))) {
                $correcto = true;
            } else {
                echo "Ha fallado la creación de una cuenta de usuario";
            }
        } else {
            ?>
            <script>
                alert("HA FALLADO LA CREACIÓN DEL USUARIO\nAsegúrese de haber introducido todos los valores");
            </script>
            <?php

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
        $result = $pdo->query("SELECT u.*,c.codComunidad,p.nombre as provincia,c.nombre as comunidad FROM usuarios u JOIN provincia p ON p.codProvincia = u.codProvincia JOIN comunidades c ON c.codComunidad = u.codComunidad WHERE u.id = (SELECT id_usuario FROM sesiones WHERE username = '$username')");
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
            if ($contra["passwd"] == $passwd) {
                $passwCorrecta = true;
            }
        }
        return $passwCorrecta;
    }

    function actualizarUsuario($id, $provincia, $comunidad, $nombre, $apellidos, $DNI, $telefono, $calle, $numero, $piso, $puerta, $cp, $poblacion, $mail, $username, $passwd) {
        $correcto = false;
        $pdo = new AccesoBD();
        $pdo = $pdo->abrirConexion();

        $insertar = $pdo->prepare("UPDATE Usuarios SET nombre = ?, apellidos = ?, DNI = ?, telefono = ?, calle = ?, numero = ?, piso = ?, puerta = ?, cp = ?, poblacion = ?, codProvincia = ?, codComunidad  = ?, mail = ? WHERE id = ?");
        if ($insertar->execute(array($nombre, $apellidos, $DNI, $telefono, $calle, $numero, $piso, $puerta, $cp, $poblacion, $provincia, $comunidad, $mail, $id))) {
            $arraySesion;
            if ($passwd == null) {
                $arraySesion = array($username, $id);
                $query = "UPDATE Sesiones SET username = ? WHERE id_usuario = ?";
            } else {
                $arraySesion = array($username, $passwd, $id);
                $query = "UPDATE Sesiones SET username = ?, passwd = ? WHERE id_usuario = ?";
            }
            $insertar = $pdo->prepare($query);
            if ($insertar->execute($arraySesion)) {
                $correcto = true;
            } else {
                echo "Ha fallado la actualización de la cuenta de usuario";
            }
        } else {
            echo "Ha fallado la actualización del usuario";
        }
        $pdo = null;
        return $correcto;
    }

    function getSesion($username) {
        $pdo = new AccesoBD();
        $pdo = $pdo->abrirConexion();
        $sesion = null;
        $result = $pdo->query("SELECT * FROM Sesiones WHERE username = '$username'");
        if ($result->rowCount() > 0) {
            $sesion = $result->fetch();
        }
        return $sesion;
    }

    function getUserByCod($cod) {
        $pdo = new AccesoBD();
        $pdo = $pdo->abrirConexion();
        $sesion = null;
        $result = $pdo->query("SELECT * FROM sesiones WHERE codVerifica = '$cod'");
        if ($result->rowCount() > 0) {
            $sesion = $result->fetch();
        }
        return $sesion;
    }

    function verificarSesion($username) {
        $pdo = new AccesoBD();
        $pdo = $pdo->abrirConexion();
        $correcto = false;        
        $actualizar = $pdo->prepare("UPDATE Sesiones SET verificado = true WHERE username = ?");
        if ($actualizar->execute(array($username))) {
            $correcto = true;
        }
        return $correcto;
    }
function existeUsuario($username) {
        $pdo = new AccesoBD();
        $pdo = $pdo->abrirConexion();
        $existe = false;
        $result = $pdo->query("SELECT * FROM sesiones WHERE username = '$username'");
        if ($result->rowCount() > 0) {
            $existe = true;
        }
        return $existe;
    }

    function reestablecerContra($username, $passwd) {
        $pdo = new AccesoBD();
        $pdo = $pdo->abrirConexion();
        $correcto = false;
        $insertar = $pdo->prepare("UPDATE sesiones SET passwd=? WHERE username=?");
        if ($insertar->execute(array($passwd, $username))) {
            $correcto = true;
        } else {
            echo "Ha fallado el cambio de contraseña";
        }
        return $correcto;
    }

    function getUsuarios() {
        $pdo = new AccesoBD();
        $pdo = $pdo->abrirConexion();
        $usuarios = null;
        $result = $pdo->query("SELECT * FROM usuarios");
        if ($result->rowCount() > 0) {
            $usuarios = $result->fetchAll();
        }
        return $usuarios;
    }

function borrarUsuarioUsername($username){
        $pdo = new AccesoBD();
        $pdo = $pdo->abrirConexion();
        $correcto = false;
        $sesion = $this->getSesion($username);        
        $borrar = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
        if($borrar->execute(array($sesion["id_usuario"]))){
            $correcto = true;
        }
        return $correcto;
    }

}
?>