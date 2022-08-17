<?php
//chamada das classes internas
   class dbconnect {
		
	    public  $con;

	    function __construct() {
	    	//bando mais saude
	    	$servidor = 'localhost';
	        $usuario = 'root';
	        $senha = '';
	        $db = 'teste_senior';
	    	mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
			try {
			  $this->con = new mysqli($servidor, $usuario, $senha, $db);
			  $this->con->set_charset("utf8mb4");
			} catch(Exception $e) {
			  error_log($e->getMessage());
			  exit('Error connecting to database'); //Should be a message a typical user could understand
			}
			//$this->con = mysqli_connect($servidor, $usuario, $senha, $bd) or die(mysqli_connect_error());
	    }
	}

//chamadas das classes externas
   function condb() { 

	    $servidor = 'localhost';
        $usuario = 'root';
        $senha = '';
        $db = 'teste_senior';  

	    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
			try {
			  $con = new mysqli($servidor, $usuario, $senha, $db);
			  $con->set_charset("utf8mb4");
			} catch(Exception $e) {
			  error_log($e->getMessage());
			  exit('Error connecting to database'); //Should be a message a typical user could understand
			}
			
	    return $con; 
	}

	//date_default_timezone_set('America/Manaus');
?>