<?php
// backend/common/db.php

require_once __DIR__ . '/config.php';

class Database {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        // Use credentials from config.php
        $host = DB_HOST;
        $username = DB_USER;
        $password = DB_PASS;
        $database = DB_NAME;
        
        $this->connection = new mysqli($host, $username, $password, $database);
        
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance->connection;
    }
}
?>