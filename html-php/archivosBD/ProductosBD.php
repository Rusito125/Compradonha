<?php

class ProductosBD {

    public function __construct() {
        require_once 'AccesoBD.php';
    }

    function setProducto($nombre, $descripcion, $precio, $inventario, $imgContenido, $tipoP) {
        $pdo = new AccesoBD();
        $pdo = $pdo->abrirConexion();
        $correcto = false;
        $insertar = $pdo->prepare("INSERT into Productos (id, nombre, descripcion, fecha, precio, inventario, imagen, id_tipo) VALUES (0,?,?,now(),?,?,?,?)");
        if ($insertar->execute(array($nombre, $descripcion, $precio, $inventario, $imgContenido, $tipoP))) {
            $correcto = true;
        } else{
            var_dump($insertar->errorInfo());
        }
        $pdo = null;
        return $correcto;
    }

    function updateProducto($nombre, $descripcion, $precio, $inventario, $imgContenido, $tipoP, $id) {
        $pdo = new AccesoBD();
        $pdo = $pdo->abrirConexion();
        $correcto = false;
        $actualizar = $pdo->prepare("UPDATE Productos SET nombre=?, descripcion=?, precio = ?, inventario = ?, imagen = ?, id_tipo = ? WHERE id=?");
        if ($actualizar->execute(array($nombre, $descripcion, $precio, $inventario, $imgContenido, $tipoP, $id))) {
            $correcto = true;
        }
        $pdo = null;
        return $correcto;
    }

    function borrarProducto($id) {
        $pdo = new AccesoBD();
        $pdo = $pdo->abrirConexion();
        $borrar = $pdo->prepare("DELETE FROM PRODUCTOS WHERE ID = ?");
        $correcto = false;
        if ($borrar->execute(array($id))) {
            $correcto = true;
        }
        $pdo = null;
        return $correcto;
    }

    function getProductos() {
        $pdo = new AccesoBD();
        $pdo = $pdo->abrirConexion();

        $result = $pdo->query("SELECT p.id,p.nombre,p.descripcion,p.fecha,p.precio,p.inventario,p.imagen,p.id_tipo,tp.tipo FROM productos p JOIN tipos_producto tp ON p.id_tipo = tp.id ORDER BY p.fecha DESC");

        $productos = null;
        if ($result->rowCount() > 0) {
            $productos = $result->fetchAll();
        }
        $pdo = null;
        return $productos;
    }

    function getTiposProducto() {
        $pdo = new AccesoBD();
        $pdo = $pdo->abrirConexion();

        $result = $pdo->query("SELECT * FROM tipos_producto ORDER BY tipo");
        $tiposProducto = null;
        if ($result->rowCount() > 0) {
            $tiposProducto = $result->fetchAll();
        }
        $pdo = null;
        return $tiposProducto;
    }

    function getProductosDeTipo($tipo) {
        $pdo = new AccesoBD();
        $pdo = $pdo->abrirConexion();
        $result = $pdo->query("SELECT * FROM productos WHERE id_tipo = $tipo");
        if ($result->rowCount() > 0) {
            $productos = $result->fetchAll();
        } else {
            $productos = null;
        }
        $pdo = null;
        return $productos;
    }

    function getProductoPorId($id) {
        $pdo = new AccesoBD();
        $pdo = $pdo->abrirConexion();
        $result = $pdo->query("SELECT * FROM productos WHERE id = $id");
        if ($result->rowCount() > 0) {
            $producto = $result->fetch();
        }
        $pdo = null;
        return $producto;
    }

    function ajustarInventario($id, $cantidad) {
        $pdo = new AccesoBD();
        $pdo = $pdo->abrirConexion();
        $correcto = false;
        $ajustar = $pdo->prepare("UPDATE productos SET inventario = (inventario - ?) WHERE id = ?");
        if ($ajustar->execute(array($cantidad, $id))) {
            $correcto = true;
        }
        $pdo = null;
        return $correcto;
    }

    function getProductosOrdenPrecio($orden) {
        $pdo = new AccesoBD();
        $pdo = $pdo->abrirConexion();
        if ($orden == "bajo") {
            $orden = "ASC";
        } else if ($orden == "alto") {
            $orden = "DESC";
        }
        $result = $pdo->query("SELECT * FROM productos ORDER BY precio $orden");
        if ($result->rowCount() > 0) {
            $productos = $result->fetchAll();
        }
        $pdo = null;
        return $productos;
    }

}
?>
