<?php
	include_once ("dbconnect.php");
	include_once ("funcoes/criptaSenha.php");
	
	class UsuariosModel extends dbconnect{
		
		public $retorno;
		

		public function cadastrar($nome,$email,$telefone,$dataNascimento,$cidade){

			$this->retorno = mysqli_query($this->con, "INSERT INTO `usuarios`(`nome`, `email`, `telefone`, `dataNascimento`, `cidade`, `dataCadastro`) VALUES ('$nome', '$email', '$telefone', '$dataNascimento', '$cidade', NOW())") or die(mysqli_error($this->con));

			return $this->retorno;

		}

		public function buscar(){

			$this->retorno = mysqli_query($this->con, "SELECT * FROM usuarios ");

			return $this->retorno;

		}

		public function buscarDados($id){

			$this->retorno = mysqli_query($this->con, "SELECT * FROM usuarios WHERE id = $id");

			return $this->retorno;

		}	

		public function editar($id, $nome, $email, $telefone, $dataNascimento, $cidade){

			$this->retorno = mysqli_query($this->con, "UPDATE `usuarios` SET `nome`='$nome',`email`='$email',`telefone`='$telefone',`dataNascimento`='$dataNascimento',`cidade`='$cidade' WHERE  id = $id");

			return $this->retorno;

		}

		public function deletar($id){

			$this->retorno = mysqli_query($this->con, "UPDATE `usuarios` SET `ativo`= 0 WHERE id = $id");

			return $this->retorno;

		}

		public function ativar($id){

			$this->retorno = mysqli_query($this->con, "UPDATE `usuarios` SET `ativo`= 1 WHERE id = $id");

			return $this->retorno;

		}

		public function buscarEmpresas($id){

			$this->retorno = mysqli_query($this->con, "SELECT * FROM empresas WHERE ativo = 1");

			return $this->retorno;

		}

		public function verificarEmpresaUsuario($id){

			$this->retorno = mysqli_query($this->con, "SELECT * FROM `empresausuario` WHERE idUsuario = $id");

			return $this->retorno;

		}

		public function cadastrarEmpresas($id, $empresas){

			$x = 1;
			$length = count($empresas);
			$resultado = '';
			foreach ($empresas as $key) {
				if ($x < $length) {
					$resultado .= $key.',';
				}else{
					$resultado .= $key;
				}
				$x++;
			}
			
			$this->retorno = mysqli_query($this->con, "INSERT INTO `empresausuario`(`idUsuario`, `idEmpresas`, `dataCadastro`) VALUES ('$id', '$resultado', NOW() )");

			return $this->retorno;

		}

		
	}

?>