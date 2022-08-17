<?php
function geraLoginCliente(){

	$con = condb();
	
	$codVerifica = "";
	$testa = TRUE;

	while($testa){
		$codVerifica = rand(0000100, 9999999);
		
		$sqlTitular = mysqli_query($con, "SELECT login FROM titulares WHERE login = '$codVerifica'");
		$sqlDependente = mysqli_query($con, "SELECT login FROM dependentes WHERE login = '$codVerifica'");
		
		if(mysqli_num_rows($sqlTitular) == 0 AND mysqli_num_rows($sqlDependente) == 0){
			$testa = FALSE;
			return $codVerifica;
		}
		
	}
}
?>