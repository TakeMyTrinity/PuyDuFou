<?php

// Classe modèle représentant la base de données
class bddManager
{
    private $host = 'localhost';
    private $username = 'root';
    private $password = 'M180417*';
    private $database = 'puydufou';

    // Méthode pour établir une connexion à la base de données
    public function connect()
    {
        try {
            $conn = new PDO("mysql:host=$this->host;dbname=$this->database", $this->username, $this->password);
            // définir le mode d'erreur de PDO sur exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }
}