<?php
class AccesoBD {
        var $dbtype = 'mysql';
        var $dbname = 'db_compradonha';
        var $dbhost = 'localhost';
        var $dbuser = 'root';
        var $dbpass = '';
    
    public function abrirConexion() {
        try {
            $dsn = "{$this->dbtype}:host={$this->dbhost};dbname={$this->dbname}";
            $pdo = new PDO($dsn, $this->dbuser, $this->dbpass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        } catch (PDOException $e) {
            print "Error! " . $e->getMessage();
            die();
        }
        return $pdo;
    }

}
?>