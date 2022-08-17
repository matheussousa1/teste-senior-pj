<?php 
include_once('../model/empresas.php');
$con = condb();

//for handle post action and perform operations 
if(isset($_GET['acao']) && $_GET['acao'] != ''){
    switch ($_GET['acao']) {
        case 'cadastrar'://for like any post
            cadastrar($con, $_GET);
        break;
        case 'buscar':
        	buscar($con, $_GET);
        break;
        case 'buscarDados':
        	buscarDados($con, $_GET);
        break;
        case 'editar':
        	editar($con, $_GET);
        break;
        case 'deletar':
        	deletar($con, $_GET);
        break;
        case 'ativar':
        	ativar($con, $_GET);
        break;
        case 'buscarEmpresas':
        	buscarEmpresas($con, $_GET);
        break;
        case 'cadastrarEmpresas':
        	cadastrarEmpresas($con, $_GET);
        break;
    }
}

function cadastrar($con, $dados){

	$model = new EmpresasModel;

	$nome = $dados['nome'];
	$cnpj = $dados['cnpj'];
	$endereco = $dados['endereco'];

	$model->cadastrar($nome, $cnpj, $endereco);

	$res = array();
	if($model->retorno){
        $res['status'] = 200;
    }else{
        $res['status'] = 511;
    }

    echo json_encode($res);
}



function buscar($con){

	$model = new EmpresasModel;

	$model->buscar();

	$data = array();
	while($res = mysqli_fetch_object($model->retorno)) {

		if($res->ativo == 1){
			$status = '<button type="button" id_user="'.$res->id.'" nome_user="'.$res->nome.'" class="btn  btn-danger btndel " data-toggle="tooltip" data-placement="top" title="Inativar"><i class="fas fa-trash-alt"></i></button>';
		}else{
			$status = '<button type="button" id_user="'.$res->id.'" nome_user="'.$res->nome.'" class="btn  btn-success btnativar " data-toggle="tooltip" data-placement="top" title="Ativar"><i class="fas fa-check"></i></button>';
		}

		$button = '<button type="button" id_user="'.$res->id.'" class="btn  btn-info btnedit mr-2" data-toggle="tooltip" data-placement="top" title="Alterar Dados"><i class="fas fa-edit"></i></button>';

		$button .= $status;

		$data['data'][] = array(
			'id' => $res->id,
			'nome' => $res->nome,
			'cnpj' => $res->cnpj,
			'endereco' => $res->endereco,
			'dataCadastro' => date("d/m/Y H:i:s", strtotime($res->dataCadastro)),
			'ativo' => $res->ativo,
			'button' => $button,
		);
	}
	echo json_encode($data);
}

function buscarDados($con, $dados){

	$id = $dados['id'];

	$model = new EmpresasModel;

	$model->buscarDados($id);

	$array = array();
	while($res = mysqli_fetch_object($model->retorno)){
		$array['id']= $res->id;
		$array['cnpj']= $res->cnpj;
		$array['endereco']= $res->endereco;
		$array['nome']= $res->nome;
	}
	echo json_encode($array);
}

function editar($con, $dados){

	$model = new EmpresasModel;

	$nome = $dados['nome'];
	$cnpj = $dados['cnpj'];
	$endereco = $dados['endereco'];
	$id = $dados['idObj'];

	$model->editar($id, $nome, $cnpj, $endereco);

	$res = array();
	if($model->retorno){
		$res['status'] = 200;
    }else{
        $res['status'] = 511;
    }

    echo json_encode($res);
}

function deletar($con, $dados){

	$model = new EmpresasModel;

	$id = $dados['id'];

	$model->deletar($id);

	$res = array();
	if($model->retorno){
		$res['status'] = 200;
    }else{
        $res['status'] = 511;
    }

    echo json_encode($res);
}

function ativar($con, $dados){

	$model = new EmpresasModel;

	$id = $dados['id'];

	$model->ativar($id);

	$res = array();
	if($model->retorno){
		$res['status'] = 200;
    }else{
        $res['status'] = 511;
    }

    echo json_encode($res);
}

function buscarEmpresas($con, $dados){

	$model = new EmpresasModel;

	$id = $dados['id'];

	$model->buscarEmpresas($id);

	$data = array();
	while($res = mysqli_fetch_object($model->retorno)) {

		// verificar se a empresa ja pertence ao usuario
		$retornoEmpresas = mysqli_query($con, "SELECT * FROM `empresausuario` WHERE idUsuario = $id order by id desc");
		$selected = 0;
		if(mysqli_num_rows($retornoEmpresas)){
			$resEmpresa = mysqli_fetch_object($retornoEmpresas); 
			$separar = explode(',', $resEmpresa->idEmpresas);
			foreach ($separar as $key) {
				if($key == $res->id){
					$selected = 1;
				}
			}	
		}
		

		$data[] = array(
			'id' => $res->id,
			'nome' => $res->nome,
			'selected' => $selected
		);
	}
	echo json_encode($data);
}

function cadastrarEmpresas($con, $dados){

	$model = new EmpresasModel;

	$id = $dados['idObj'];
	$empresas = $dados['empresas'];
	
	$model->cadastrarEmpresas($id, $empresas);

	$res = array();
	if($model->retorno){
		$res['status'] = 200;
    }else{
        $res['status'] = 511;
    }

    echo json_encode($res);
}

?>