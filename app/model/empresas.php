<?php
	include_once ("dbconnect.php");
	include_once ("funcoes/criptaSenha.php");
	
	class EmpresasModel extends dbconnect{
		
		public $retorno;
		

		public function cadastrar($nome, $cnpj, $endereco){

			$this->retorno = mysqli_query($this->con, "INSERT INTO `empresas`(`nome`, `cnpj`, `endereco`, `dataCadastro`) VALUES ('$nome', '$cnpj', '$endereco', NOW())") or die(mysqli_error($this->con));

			return $this->retorno;

		}

		public function buscar(){

			$this->retorno = mysqli_query($this->con, "SELECT * FROM empresas ");

			return $this->retorno;

		}

		public function buscarDados($id){

			$this->retorno = mysqli_query($this->con, "SELECT * FROM empresas WHERE id = $id");

			return $this->retorno;

		}	

		public function editar($id, $nome, $cnpj, $endereco){

			$this->retorno = mysqli_query($this->con, "UPDATE `empresas` SET `nome`='$nome',`cnpj`='$cnpj',`endereco`='$endereco' WHERE id = $id");

			return $this->retorno;

		}

		public function deletar($id){

			$this->retorno = mysqli_query($this->con, "UPDATE `empresas` SET `ativo`= 0 WHERE id = $id");

			return $this->retorno;

		}

		public function ativar($id){

			$this->retorno = mysqli_query($this->con, "UPDATE `empresas` SET `ativo`= 1 WHERE id = $id");

			return $this->retorno;

		}


		
	}

?>