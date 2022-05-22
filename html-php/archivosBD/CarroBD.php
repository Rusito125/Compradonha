<?php

class CarroBD {

    public function __construct() {
        require_once 'AccesoBD.php';
    }

    function setProductoCarro($idUsuario, $idProducto, $cantidad) {
        $pdo = new AccesoBD();
        $pdo = $pdo->abrirConexion();
        $correcto = false;
        if ($this->getProductoCarro($idUsuario, $idProducto) != null) {
            $actualizarProductoCarro = $pdo->prepare("UPDATE Carro SET cantidad = ? WHERE id_usuario = ? AND id_producto = ?");
            if ($actualizarProductoCarro->execute(array($cantidad, $idUsuario, $idProducto))) {
                $correcto = true;
            }
        } else {
            $add = $pdo->prepare("INSERT into Carro(id_usuario, id_producto, cantidad) VALUES (?,?,?)");
            if ($add->execute(array($idUsuario, $idProducto, $cantidad))) {
                $correcto = true;
            }
        }
        $pdo = null;
        return $correcto;
    }

    function getProductoCarro($idUsuario, $idProducto) {
        $pdo = new AccesoBD;
        $pdo = $pdo->abrirConexion();
        $resultado = $pdo->query("SELECT * FROM carro WHERE id_usuario = $idUsuario AND id_producto = $idProducto");
        if ($resultado->rowCount() > 0) {
            $productoCarro = $resultado->fetch();
        } else {
            $productoCarro = null;
        }
        $pdo = null;
        return $productoCarro;
    }

    function getProductosCarro($idUsuario) {
        $pdo = new AccesoBD();
        $pdo = $pdo->abrirConexion();
        $resultado = $pdo->query("SELECT c.cantidad,p.* FROM carro c JOIN productos p ON p.id=c.id_producto WHERE id_usuario = $idUsuario");
        if ($resultado->rowCount() > 0) {
            $productosCarro = $resultado->fetchAll();
        } else {
            $productosCarro = null;
        }
        $pdo = null;
        return $productosCarro;
    }

    function borrarProductoCarro($idUsuario, $idProducto) {
        $pdo = new AccesoBD();
        $pdo = $pdo->abrirConexion();
        $correcto = false;
        $borrar = $pdo->prepare("DELETE FROM carro WHERE id_usuario = ? AND id_producto = ?");
        if ($borrar->execute(array($idUsuario, $idProducto))) {
            $correcto = true;
        }
        $pdo = null;
        return $correcto;
    }

    function crearEvento($idProducto, $idUsuario) {
        $pdo = new AccesoBD();
        $pdo = $pdo->abrirConexion();
        $correcto = false;
        $evento = $pdo->prepare("create event IF NOT EXISTS vaciar_producto_carro_$idProducto" . "_" . $idUsuario . "_1
                                 on schedule at current_timestamp + interval 1 day - interval 2 second
                                 ON COMPLETION NOT PRESERVE
                                    do
                                    update productos set inventario = inventario + (SELECT cantidad FROM carro WHERE id_usuario = ? AND id_producto = ?) WHERE id = ?;");
        if ($evento->execute(array($idUsuario, $idProducto, $idProducto))) {
            $evento2 = $pdo->prepare("create event IF NOT EXISTS vaciar_producto_carro_$idProducto" . "_" . $idUsuario . "_2
                                      on schedule at current_timestamp + interval 1 day
                                      ON COMPLETION NOT PRESERVE
                                        do
                                        delete from carro where id_usuario = ? and id_producto = ?;");
            if ($evento2->execute(array($idUsuario, $idProducto))) {
                $correcto = true;
            }
        }
        $pdo = null;
        return $correcto;
    }

    function borrarEvento($idProducto, $idUsuario) {
        $pdo = new AccesoBD();
        $pdo = $pdo->abrirConexion();
        $correcto = false;
        $evento = $pdo->prepare("DROP EVENT IF EXISTS vaciar_producto_carro_$idProducto" . "_" . $idUsuario . "_1");
        if ($evento->execute()) {
            $evento2 = $pdo->prepare("DROP EVENT IF EXISTS vaciar_producto_carro_$idProducto" . "_" . $idUsuario . "_2");
            if ($evento2->execute()) {
                $correcto = true;
            }
        }
        $pdo = null;
        return $correcto;
    }

}
