<?php

class Conexao {
    private $host = "localhost";
    private $db = "bd_estacionamento";
    private $user = "root";
    private $pass = "";

    public function conectar(){
        try{
            return new PDO('mysql:host='.$this->host.';dbname='.$this->db, $this->user, $this->pass);
        } catch (PDOException $e){
            echo "Não foi possível se conectar ao banco de dados <br>". $e->getMessage();
        }
    }
}