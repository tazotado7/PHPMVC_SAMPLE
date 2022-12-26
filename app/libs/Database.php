<?php

namespace libs\database;


Class Database{
 
	private $server = "mysql:host=127.0.0.1;dbname=iaiage_igora;charset=utf8";
	private $username = "555";
	private $password = "555";
	private $options  = array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,);
	protected $conn;
 	
	public function open(){
 		try{
 			$this->conn = new \PDO($this->server, $this->username, $this->password, $this->options); 
			$this->conn->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
 			return $this->conn;
 		}
 		catch (\PDOException $e){
 			echo "There is some problem in connection: " . $e->getMessage();
 		}
	 
    }
 
	public function close(){
   		$this->conn = null;
 	}

 

  public function insert($sql,$array)
  { 
    $conn = $this->open();

    $output = null;
        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute($array);
            $output = $conn->lastInsertId();
          
        } catch (\PDOException $e) {
            $output = $e->getMessage();
        }
    $this->close();

        return $output;
  }
  

  public static function Select_singlle($sql,$data)
  { 
    $conn = self::open();  

    $output = null;
        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute($data); 
            $output = $stmt->fetch(); 
          
        } catch (\PDOException $e) {
            $output = $e->getMessage();
        }
    self::close();

    return $output; 
  }

  public static function Select_all($sql,$data)
  { 
    $conn = self::open();  

    $output = null;
        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute($data); 
            $output = $stmt->fetchAll(); 
          
        } catch (\PDOException $e) {
            $output = $e->getMessage();
        }
    self::close();

    return $output; 
  }

 
}

 