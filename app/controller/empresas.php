<?php
	include_once(MODEL.'empresas.php');
	
	class Empresas{
		
		public $view;
		public $nivel;
		public $resultado;
		
		public function gerenciar(){
			
			$this->view = "empresas/gerenciar";
			$this->nivel = 1;

			$model = new EmpresasModel;

			// $model->listarEmpresas();
			// $this->resultado[] = $model->retorno;

		}
	}
?>