<?php 
require_once("../../conexao.php"); 
@session_start();

$cliente = $_POST['cliente'];
$veiculo = @$_POST['veiculo'];

$descricao = $_POST['descricao'];
$data_entrega = $_POST['data_entrega'];
$garantia = $_POST['garantia'];
$valor = $_POST['valor'];
$obs = $_POST['obs'];

$valor = str_replace(',', '.', $valor);

// Definir um valor padrão se o campo estiver vazio
if ($valor == "") {
    $valor = "0.00"; // Defina o valor que deseja como padrão
}

$id = $_POST['txtid2'];

// VERIFICAR SE O CLIENTE EXISTE
$query = $pdo->query("SELECT * FROM clientes WHERE cpf = '$cliente'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);

// Se o cliente não existir, permitir o cadastro como "Cliente Não Cadastrado"
if ($total_reg == 0) {
    $cliente = "Cliente Não Cadastrado";
}

// VERIFICAR SE O VEÍCULO EXISTE
$query = $pdo->query("SELECT * FROM veiculos WHERE id = '$veiculo'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);

// Se o veículo não existir, permitir o cadastro como "Veículo Não Cadastrado"
if ($total_reg == 0) {
    $veiculo = "Veículo Não Cadastrado";
}

// Verificar se é um novo orçamento ou uma edição
if ($id == "") {
    $res = $pdo->prepare("INSERT INTO orcamentos SET cliente = :cliente, veiculo = :veiculo, descricao = :descricao, valor = :valor, data_entrega = :data_entrega, garantia = :garantia, mecanico = '$_SESSION[cpf_usuario]', data = curDate(), obs = :obs, status = 'Aberto'");    
} else {
    $res = $pdo->prepare("UPDATE orcamentos SET cliente = :cliente, veiculo = :veiculo, descricao = :descricao, valor = :valor, data_entrega = :data_entrega, garantia = :garantia, mecanico = '$_SESSION[cpf_usuario]', obs = :obs WHERE id = '$id'");
}

$res->bindValue(":cliente", $cliente);
$res->bindValue(":veiculo", $veiculo);
$res->bindValue(":descricao", $descricao);
$res->bindValue(":valor", $valor);
$res->bindValue(":data_entrega", $data_entrega);
$res->bindValue(":garantia", $garantia);
$res->bindValue(":obs", $obs);

$res->execute();

echo 'Salvo com Sucesso!';
?>
