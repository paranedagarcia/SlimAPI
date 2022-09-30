<?php

class DB
{ 
  private $host = 'localhost';
  private $user = 'apigerosalud';
  private $pass = '';
  private $dbname = 'gerosalud';
  private $port = '3306';

  public function connect() {
    $conn_str = "mysql:host=$this->host;dbname=$this->dbname;port=$this->port";
    $conn = new PDO($conn_str, $this->user, $this->pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $conn;
  }

}