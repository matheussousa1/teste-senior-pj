<?php
	include_once(MODEL.'usuarios.php');
	
	class Usuarios{
		
		public $view;
		public $nivel;
		public $resultado;
		
		public function gerenciar(){
			
			$this->view = "usuarios/gerenciar";
			$this->nivel = 1;

			$model = new UsuariosModel;

			// $model->listarEmpresas();
			// $this->resultado[] = $model->retorno;

		}
	}
?>