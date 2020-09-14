<?php
class DB{
	private $servername;
	private $username;
	private $password;
	private $databaseName;
	private $connection;
	
	//Costruttore
	function __construct($servername, $username, $password, $databaseName){
		$this->servername = $servername;
		$this->username = $username;
		$this->password = $password;
		$this->databaseName = $databaseName;
	}
	
	//Getter
	public function get_servername(){ return $this->servername; }
	public function get_databasename(){  return $this->databaseName; }
	public function get_username(){  return $this->username; }
	public function get_connection(){  return $this->connection; }
	
	public function get_fields_names($result){
		$finfo = $result->fetch_fields();
		$names = array();
        foreach ($finfo as $val) {
			$names[] = $val->name;
		}
		return $names;
	}

	public function create_connection(){
		$this->connection = new mysqli($this->servername,$this->username,$this->password,$this->databaseName);
		if($this->connection->connect_error)
			die("Connection failed: ".$this->connection->connect_error);
	}
	
	//Aggiungi record
	// public function insert_into_database($name, $email){
	// 	$sql = "INSERT INTO `studenti` (`nome`, `email`) VALUES ('$name', '$email')";
	// 	$this->connection->query($sql);
	// }
	
	public function use_query($sql){
		$data = $this->connection->query($sql);
		return $data;
	}

	public function close_connection(){
		$this->connection->close();
	}
}
?>