<?php 
require_once("../../conexao.php"); 

$id = $_POST['id'];

// BUSCAR O VALOR DO ORÇAMENTO
$query_orc = $pdo->query("SELECT * FROM orcamentos where id = '$id' ");
$res_orc = $query_orc->fetchAll(PDO::FETCH_ASSOC);
$valor_orc = $res_orc[0]['valor'];
$cliente = $res_orc[0]['cliente'];
$mecanico = $res_orc[0]['mecanico'];
$data_entrega = $res_orc[0]['data_entrega'];
$veiculo = $res_orc[0]['veiculo'];
$garantia = $res_orc[0]['garantia'];
$obs = $res_orc[0]['obs'];

// BUSCAR SERVIÇOS VINCULADOS AO ORÇAMENTO
$query_cat = $pdo->query("SELECT * FROM orc_serv where orcamento = '$id' ");
$res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
$tem_servico = @count($res_cat) > 0;
$total_ser = 0;

if ($tem_servico) {
    foreach ($res_cat as $row) {
        $serv = $row['servico'];
        $query_ser = $pdo->query("SELECT * FROM servicos where id = '$serv' ");
        $res_ser = $query_ser->fetchAll(PDO::FETCH_ASSOC);
        $total_ser += $res_ser[0]['valor'];
    }
    $nome_servico = @count($res_cat) > 1 ? @count($res_cat) . ' Serviços' : $res_ser[0]['nome'];
} else {
    $nome_servico = "Não Lançado!";
}

// CALCULAR TOTAL DOS PRODUTOS
$total_prod = 0;
$query = $pdo->query("SELECT * FROM orc_prod where orcamento = '$id' ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if (@count($res) > 0) {
    foreach ($res as $row) {
        $prod = $row['produto'];
        $query_pro = $pdo->query("SELECT * FROM produtos where id = '$prod' ");
        $res_pro = $query_pro->fetchAll(PDO::FETCH_ASSOC);
        $valor_prod = $res_pro[0]['valor_venda'];
        $estoque = $res_pro[0]['estoque'] - 1;

        $total_prod += $valor_prod;

        // ABATER DO ESTOQUE E LANÇAR NA VENDA
        $pdo->query("UPDATE produtos SET estoque = '$estoque' where id = '$prod' ");
        $pdo->query("INSERT INTO vendas SET produto = '$prod', valor = '$valor_prod', funcionario = '$mecanico', data = curDate(), id_orc = '$id' ");
    }
}

$total_pagar = $valor_orc + $total_prod + $total_ser;

// INSERIR NA TABELA DE CONTAS A RECEBER
$pdo->query("INSERT INTO contas_receber SET descricao = 'Orçamento', valor = '$total_pagar', adiantamento = '0', mecanico = '$mecanico', cliente = '$cliente', data = curDate(), pago = 'Não', id_servico = '$id' ");

// CRIAR OS APENAS SE HOUVER SERVIÇOS
if ($tem_servico) {
    $pdo->query("INSERT INTO os SET descricao = '$nome_servico', valor = '$total_pagar', mecanico = '$mecanico', cliente = '$cliente', data_entrega = '$data_entrega', concluido = 'Não', valor_mao_obra = '$valor_orc', data = curDate(), veiculo = '$veiculo', garantia = '$garantia', obs = '$obs', tipo = 'Orçamento', id_orc = '$id' ");
}

// ATUALIZAR STATUS DO ORÇAMENTO
$pdo->query("UPDATE orcamentos SET status = 'Aprovado' WHERE id = '$id'");

// ENTRADA DO VEÍCULO NO CONTROLE APENAS SE NÃO EXISTIR REGISTRO
$query = $pdo->query("SELECT * FROM controles where veiculo = '$veiculo' ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if (@count($res) == 0) {
    $pdo->query("INSERT INTO controles SET veiculo = '$veiculo', mecanico = '$mecanico', data = curDate(), descricao = '$nome_servico' ");
}

echo 'Aprovado com Sucesso!';
?>