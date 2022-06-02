<?php

class CompraBD {

    public function __construct() {
        require_once 'AccesoBD.php';
    }

    public function setCompra($idCompra, $idUsuario, $idProducto, $cantidad) {
        $pdo = new AccesoBD();
        $pdo = $pdo->abrirConexion();
        $correcto = false;
        $add = $pdo->prepare("INSERT into Compras(id, id_usuario, id_producto, fecha, cantidad) VALUES (?,?,?,now(),?)");
        if ($add->execute(array($idCompra, $idUsuario, $idProducto, $cantidad))) {
            $correcto = true;
        }
        $pdo = null;
        return $correcto;
    }

    public function getUltimaCompra($idUsuario) {
        $pdo = new AccesoBD();
        $pdo = $pdo->abrirConexion();
        $resultado = $pdo->query("SELECT c.*, p.nombre,p.descripcion,p.precio,p.imagen,p.id_tipo FROM Compras c JOIN Productos p ON p.id = c.id_producto WHERE c.id = (SELECT max(id) from compras) and c.id_usuario = $idUsuario");
        if ($resultado->rowCount() > 0) {
            $ultimaCompra = $resultado->fetchAll();
        } else {
            $ultimaCompra = null;
        }
        $pdo = null;
        return $ultimaCompra;
    }

    public function getUltimaCompraGeneral() {
        $pdo = new AccesoBD();
        $pdo = $pdo->abrirConexion();
        $resultado = $pdo->query("SELECT c.*, p.nombre,p.descripcion,p.precio,p.imagen,p.id_tipo FROM Compras c JOIN Productos p ON p.id = c.id_producto WHERE c.id = (SELECT max(id) from compras)");
        if ($resultado->rowCount() > 0) {
            $ultimaCompra = $resultado->fetchAll();
        } else {
            $ultimaCompra = null;
        }
        $pdo = null;
        return $ultimaCompra;
    }

    public function getComprasOrdenFecha($idUsuario) {
        $pdo = new AccesoBD();
        $pdo = $pdo->abrirConexion();
        $resultado = $pdo->query("SELECT c.*, p.nombre,p.descripcion,p.precio,p.imagen,p.id_tipo FROM Compras c JOIN Productos p ON p.id = c.id_producto WHERE id_usuario = $idUsuario ORDER BY c.fecha DESC");
        if ($resultado->rowCount() > 0) {
            $ultimasCompra = $resultado->fetchAll();
        } else {
            $ultimasCompra = null;
        }
        $pdo = null;
        return $ultimasCompra;
    }

}
