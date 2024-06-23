<?php

class DatabaseService
{
    private $host;
    private $db;
    private $user;
    private $pass;
    private $port;
    private $charset;
    private $pdo;

    public function __construct()
    {
        $config = require_once 'config.inc.php';
        $this->host = $config['host'];
        $this->db = $config['database'];
        $this->user = $config['username'];
        $this->pass = $config['password'];
        $this->port = $config['port'];
        $this->charset = 'utf8mb4';

        $dsn = "mysql:host=$this->host;dbname=$this->db;charset=$this->charset;port=$this->port";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        
        $maxAttempts = 10;
        for ($attempts = 0; $attempts < $maxAttempts; $attempts++) {
            try {
                $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
                break; // Successful connection, exit the loop
            } catch (\PDOException $e) {
                if ($attempts == $maxAttempts - 1) {
                    throw $e; // Re-throw the exception on last attempt
                }
                sleep(3); // Wait for a few seconds before trying again
            }
        }
    }

    public function getConnection()
    {
        return $this->pdo;
    }
}