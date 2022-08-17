<?php 
include_once('../model/usuarios.php');
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

	$model = new UsuariosModel;

	$nome = $dados['nome'];
	$email = $dados['email'];
	$telefone = $dados['telefone'];
	$dataNascimento = $dados['dataNascimento'];
	$cidade = $dados['cidade'];

	$model->cadastrar($nome, $email, $telefone, $dataNascimento, $cidade);

	$res = array();
	if($model->retorno){
        $res['status'] = 200;
    }else{
        $res['status'] = 511;
    }

    echo json_encode($res);
}



function buscar($con){

	$model = new UsuariosModel;

	$model->buscar();

	$data = array();
	while($res = mysqli_fetch_object($model->retorno)) {

		if($res->ativo == 1){
			$status = '<button type="button" id_user="'.$res->id.'" nome_user="'.$res->nome.'" class="btn  btn-danger btndel " data-toggle="tooltip" data-placement="top" title="Inativar"><i class="fas fa-trash-alt"></i></button>';
		}else{
			$status = '<button type="button" id_user="'.$res->id.'" nome_user="'.$res->nome.'" class="btn  btn-success btnativar " data-toggle="tooltip" data-placement="top" title="Ativar"><i class="fas fa-check"></i></button>';
		}

		$button = '<button type="button" id_user="'.$res->id.'" class="btn  btn-primary btnempresas mr-2" data-toggle="tooltip" data-placement="top" title="Empresas"><i class="fas fa-building"></i></button> <button type="button" id_user="'.$res->id.'" class="btn  btn-info btnedit mr-2" data-toggle="tooltip" data-placement="top" title="Alterar Dados"><i class="fas fa-edit"></i></button>';

		$button .= $status;

		// verificar empresas
		$sqlEmpresasUsuario = mysqli_query($con, "SELECT * FROM `empresausuario` WHERE idUsuario = $res->id and ativo = 1 order by id desc");
		if (mysqli_num_rows($sqlEmpresasUsuario)) {
			$resEmpresasUsuario = mysqli_fetch_object($sqlEmpresasUsuario);
			$separarEmpresas = explode(",", $resEmpresasUsuario->idEmpresas);
			$empresas = '';
			foreach ($separarEmpresas as $key) {
				$sqlSelecEmpresas = mysqli_query($con, "SELECT * FROM `empresas` WHERE id = $key");
				$selectEmpresas = mysqli_fetch_object($sqlSelecEmpresas);
				$empresas .= $selectEmpresas->nome.', ';
			}	
		}else{
			$empresas = '';
		}
		

		$data['data'][] = array(
			'id' => $res->id,
			'nome' => $res->nome,
			'email' => $res->email,
			'telefone' => $res->telefone,
			'dataNascimento' => date("d/m/Y", strtotime($res->dataNascimento)),
			'cidade' => $res->cidade,
			'dataCadastro' => date("d/m/Y H:i:s", strtotime($res->dataCadastro)),
			'empresas' => $empresas,
			'ativo' => $res->ativo,
			'button' => $button,
		);
	}
	echo json_encode($data);
}

function buscarDados($con, $dados){

	$id = $dados['id'];

	$model = new UsuariosModel;

	$model->buscarDados($id);

	$array = array();
	while($res = mysqli_fetch_object($model->retorno)){
		$array['id']= $res->id;
		$array['nome']= $res->nome;
		$array['email']= $res->email;
		$array['telefone']= $res->telefone;
		$array['dataNascimento']= $res->dataNascimento;
		$array['cidade']= $res->cidade;
	}
	echo json_encode($array);
}

function editar($con, $dados){

	$model = new UsuariosModel;

	$nome = $dados['nome'];
	$email = $dados['email'];
	$telefone = $dados['telefone'];
	$dataNascimento = $dados['dataNascimento'];
	$cidade = $dados['cidade'];
	$id = $dados['idObj'];

	$model->editar($id, $nome, $email, $telefone, $dataNascimento, $cidade);

	$res = array();
	if($model->retorno){
		$res['status'] = 200;
    }else{
        $res['status'] = 511;
    }

    echo json_encode($res);
}

function deletar($con, $dados){

	$model = new UsuariosModel;

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

	$model = new UsuariosModel;

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

	$model = new UsuariosModel;

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

	$model = new UsuariosModel;

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