<?php
function geraCodigoParcela($idTitular){
	$con = condb();
	
	$codVerifica = "";
	$testa = TRUE;
	while($testa){
		$codVerifica = $idTitular.rand(1000, 9999).date('y');
		
		$sql = mysqli_query($con, "SELECT * FROM carnes WHERE codCarne = '$codVerifica' and idTitular = $idTitular");
		
		if(mysqli_num_rows($sql) == 0){
			$testa = FALSE;
			return $codVerifica;
		}
		
	}
}
?>