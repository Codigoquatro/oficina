<?php 
require_once("../../conexao.php"); 
@session_start();

$cliente = $_POST['cliente'];
$veiculo = @$_POST['veiculo'];
$servico = $_POST['servico'];

$data_entrega = $_POST['data_entrega'];
$garantia = $_POST['garantia'];
$obs = $_POST['obs'];

$query_cat = $pdo->query("SELECT * FROM servicos where id = '$servico' ");
$res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
$descricao = $res_cat[0]['nome'];
$valor = $res_cat[0]['valor'];

$valor = str_replace(',', '.', $valor);

$id = $_POST['txtid2'];

// Verificar se o serviço foi selecionado
if($servico == ""){
    echo 'O Serviço é Obrigatório!';
    exit();
}

// Se o cliente for informado, verificar se ele existe
if($cliente != ""){
    $query = $pdo->query("SELECT * FROM clientes where cpf = '$cliente' ");
    $res = $query->fetchAll(PDO::FETCH_ASSOC);
    $total_reg = @count($res);
    if($total_reg == 0){
        echo 'O Cliente não está cadastrado ou o CPF está incorreto!';
        exit();
    }
}

// Não verificar se o veículo foi informado (deixa gravar mesmo sem o veículo)
if($veiculo == ""){
    // Não gera erro aqui, apenas avança
}

// Verificar se o valor do serviço foi informado
if($valor == ""){
    echo 'O Valor é Obrigatório!';
    exit();
}

if($id == ""){
    // Inserir nova ordem de serviço
    $res = $pdo->prepare("INSERT INTO os SET cliente = :cliente, veiculo = :veiculo, descricao = :descricao, valor = :valor, data_entrega = :data_entrega, garantia = :garantia, mecanico = '$_SESSION[cpf_usuario]', data = curDate(), obs = :obs, concluido = 'Não', valor_mao_obra = :valor, tipo = 'Serviço'");    
}else{
    // Atualizar ordem de serviço existente
    $res = $pdo->prepare("UPDATE os SET cliente = :cliente, veiculo = :veiculo, descricao = :descricao, valor = :valor, data_entrega = :data_entrega, garantia = :garantia, mecanico = '$_SESSION[cpf_usuario]', obs = :obs, concluido = 'Não', valor_mao_obra = :valor WHERE id = '$id'");
}

$res->bindValue(":cliente", $cliente);
$res->bindValue(":veiculo", $veiculo);
$res->bindValue(":descricao", $descricao);
$res->bindValue(":valor", $valor);
$res->bindValue(":data_entrega", $data_entrega);
$res->bindValue(":garantia", $garantia);
$res->bindValue(":obs", $obs);

$res->execute();

$ult_id = $pdo->lastInsertId();
if($id == ""){
    // Inserir na tabela de contas a receber se for uma nova ordem
    $pdo->query("INSERT INTO contas_receber SET descricao = 'Serviço', valor = '$valor', adiantamento = '0', mecanico = '$_SESSION[cpf_usuario]', cliente = '$cliente', data = curDate(), pago = 'Não', id_servico = '$ult_id' ");
}

// Entrada do veículo (caso o veículo não tenha sido registrado antes)
$query = $pdo->query("SELECT * FROM controles where veiculo = '$veiculo' ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if($total_reg == 0 && $veiculo != ""){
    $pdo->query("INSERT INTO controles SET veiculo = '$veiculo', mecanico = '$_SESSION[cpf_usuario]', data = curDate(), descricao = '$descricao' ");
}

echo 'Salvo com Sucesso!';
?>