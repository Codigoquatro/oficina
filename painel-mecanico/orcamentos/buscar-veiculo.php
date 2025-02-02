<?php
require_once("../../conexao.php");

if (isset($_POST['cpf'])) {
    $cpf = $_POST['cpf'];
    $veiculo = isset($_POST['veiculo']) ? $_POST['veiculo'] : '';

    // Verifica se o CPF existe na tabela de clientes
    $query = $pdo->query("SELECT * FROM clientes WHERE cpf = '$cpf'");
    $res = $query->fetchAll(PDO::FETCH_ASSOC);

    if (count($res) == 0) {
        echo 'O cliente não existe, CPF Incorreto';
        exit();
    }

    // Se o cliente existe, retorna os veículos
    echo '<select name="veiculo" class="form-control" id="veiculo">';

    $query = $pdo->query("SELECT * FROM veiculos WHERE cliente = '$cpf' ORDER BY id DESC");
    $res = $query->fetchAll(PDO::FETCH_ASSOC);

    foreach ($res as $veiculo_data) {
        $nome_reg = $veiculo_data['marca'] . ' - ' . $veiculo_data['modelo'];
        $id_reg = $veiculo_data['id'];

        // Se o veículo for o selecionado, marca como selected
        $selected = ($veiculo == $id_reg) ? 'selected' : '';

        echo '<option value="' . $id_reg . '" ' . $selected . '>' . $nome_reg . '</option>';
    }

    echo '</select>';
} else {
    echo 'CPF não enviado';
}
?>
