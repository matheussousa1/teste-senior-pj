<?php 
include_once('../model/colaborador.php');
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
        case 'buscarHistoricoSalario':
            buscarHistoricoSalario($con, $_GET);
        break;
        case 'vincularUnidade':
            vincularUnidade($con, $_GET);
        break;
        case 'deletarUnidade':
            deletarUnidade($con, $_GET);
        break;
        case 'buscarDadosUnidade':
            buscarDadosUnidade($con, $_GET);
        break;
        case 'editarvincularUnidade':
            editarvincularUnidade($con, $_GET);
        break;
    }
}

function cadastrar($con, $dados){

	$model = new ColaboradoresModel;

	$nome = $dados['nome'];
	$cpf = $dados['cpf'];
	$rg = $dados['rg'];
	$dataNascimento = $dados['dataNascimento'];
	$cep = $dados['cep'];
	$endereco = $dados['endereco'];
	$numero = $dados['numero'];
	$cidade = $dados['cidade'];
	$estado = $dados['estado'];

	$model->cadastrar( $nome, $cpf, $rg, $dataNascimento, $cep, $endereco, $numero, $cidade, $estado);

	$res = array();
	if($model->retorno){
        $res['status'] = 200;
    }else{
        $res['status'] = 511;
    }

    echo json_encode($res);
}



function buscar($con){

	$model = new ColaboradoresModel;

	$model->buscar();

	$data = array();
	while($res = mysqli_fetch_object($model->retorno)) {

		if($res->ativo == 1){
			$status = '<button type="button" id_user="'.$res->id.'" nome_user="'.$res->nome.'" class="btn  btn-danger btndel " data-toggle="tooltip" data-placement="top" title="Inativar"><i class="fas fa-trash-alt"></i></button>';
		}else{
			$status = '<button type="button" id_user="'.$res->id.'" nome_user="'.$res->nome.'" class="btn  btn-success btnativar " data-toggle="tooltip" data-placement="top" title="Ativar"><i class="fas fa-check"></i></button>';
		}

		$button = '<button type="button" id_user="'.$res->id.'" class="btn  btn-primary btnhistoricosalario mr-2" data-toggle="tooltip" data-placement="top" title="Historico Salarios"><i class="fas fa-wallet"></i></button> <button type="button" id_user="'.$res->id.'" class="btn  btn-info btnedit mr-2" data-toggle="tooltip" data-placement="top" title="Alterar Dados"><i class="fas fa-edit"></i></button>';

		$button .= $status;

		$data['data'][] = array(
			'id' => $res->id,
			'nome' => $res->nome,
			'cpf' => $res->cpf,
			'rg' => $res->rg,
			'dataNascimento' => date("d/m/Y", strtotime($res->dataNascimento)),
			'cep' => $res->cep,
			'endereco' => $res->endereco,
			'numero' => $res->numero,
			'cidade' => $res->cidade,
			'estado' => $res->estado,
			'dataCadastro' => date("d/m/Y H:i:s", strtotime($res->dataCadastro)),
			'ativo' => $res->ativo,
			'button' => $button,
		);
	}
	echo json_encode($data);
}

function buscarDados($con, $dados){

	$id = $dados['id'];

	$model = new ColaboradoresModel;

	$model->buscarDados($id);

	$array = array();
	while($res = mysqli_fetch_object($model->retorno)){
		$array['id']= $res->id;
		$array['nome'] = $res->nome;
		$array['cpf'] = $res->cpf;
		$array['rg'] = $res->rg;
		$array['dataNascimento'] = $res->dataNascimento;
		$array['cep'] = $res->cep;
		$array['endereco'] = $res->endereco;
		$array['numero'] = $res->numero;
		$array['cidade'] = $res->cidade;
		$array['estado'] = $res->estado;
	}
	echo json_encode($array);
}

function editar($con, $dados){

	$model = new ColaboradoresModel;

	$nome = $dados['nome'];
	$cpf = $dados['cpf'];
	$rg = $dados['rg'];
	$dataNascimento = $dados['dataNascimento'];
	$cep = $dados['cep'];
	$endereco = $dados['endereco'];
	$numero = $dados['numero'];
	$cidade = $dados['cidade'];
	$estado = $dados['estado'];
	$id = $dados['idObj'];

	$model->editar($id, $nome, $cpf, $rg, $dataNascimento, $cep, $endereco, $numero, $cidade, $estado);

	$res = array();
	if($model->retorno){
		$res['status'] = 200;
    }else{
        $res['status'] = 511;
    }

    echo json_encode($res);
}

function deletar($con, $dados){

	$model = new ColaboradoresModel;

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

	$model = new ColaboradoresModel;

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


function buscarHistoricoSalario($con, $dados){

    $id = $dados['idUser'];

    $model = new ColaboradoresModel;

    $model->buscarHistoricoSalario($id);

    $data = array();
    while($res = mysqli_fetch_assoc($model->retorno)) {

        $button = '<button type="submit" id_user="'.$res['id'].'" class="btn-acoes btn btn-info  btneditunidade" ><i class="fa fa-edit"></i></button>
                    <button type="submit" id_user="'.$res['id'].'" nome_user="'.$res['unidade'].'" class="btn-acoes btn  btn-danger btnRemoverUnidade" ><i class="fa fa-remove"></i></button>';        

        $data['data'][] = array(
            'id' => $res['id'],
            'unidade' => $res['unidade'],
            'button' => $button,
        );
    }
    
    echo json_encode($data);
}

function vincularUnidade($con, $data){

    $model = new ColaboradoresModel;

    $idUser = $data['idUsuarioUnidade'];
    $unidade = $data['unidade'];

    $model->vincularUnidade($idUser, $unidade);

    $res = array();
    if($model->retorno){
        $res['status'] = 200;
    }else{
        $res['status'] = 511;
    }

    echo json_encode($res);
}


?>