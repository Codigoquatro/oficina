<?php 
require_once("conexao.php");

// Criar automaticamente o usuário admin se não existir
function criarUsuarioAdmin($pdo, $email_adm) {
    $query = $pdo->query("SELECT COUNT(*) as total FROM usuarios WHERE nivel = 'admin'");
    $res = $query->fetch(PDO::FETCH_ASSOC);
    
    if ($res['total'] == 0) {
        $pdo->prepare("INSERT INTO usuarios (nome, cpf, email, senha, nivel) VALUES (?, ?, ?, ?, ?)")
            ->execute(['Administrador', '000.000.000-00', $email_adm, '123', 'admin']);
    }
}

// Excluir orçamentos antigos
function excluirOrcamentosAntigos($pdo, $dias) {
    $dataLimite = date('Y-m-d', strtotime("-$dias days"));
    $pdo->prepare("DELETE FROM orcamentos WHERE data <= ?")->execute([$dataLimite]);
}

// Executar funções
criarUsuarioAdmin($pdo, $email_adm);
excluirOrcamentosAntigos($pdo, $excluir_orcamento_dias);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Faça seu Login</title>
    <link rel="shortcut icon" href="img/logo-favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/login.css">
</head>
<body>
    <div class="login text-center">
        <img src="img/logo-horizontal-branca.png" width="270" class="mb-4">
        <form method="post" action="autenticar.php">
            <input class="input" type="email" name="email" placeholder="Email" required />
            <input class="input" type="password" name="senha" placeholder="Senha" required />
            <button type="submit" class="btn btn-light btn-block">Logar</button>
            <small><a href="#" data-bs-toggle="modal" data-bs-target="#modalRecuperar" class="text-light">Recuperar Senha?</a></small>
        </form>
    </div>

    <!-- Modal Recuperar Senha -->
    <div class="modal fade" id="modalRecuperar" tabindex="-1" aria-labelledby="modalRecuperarLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Recuperar Senha</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form-recuperar">
                    <div class="modal-body">
                        <label>Seu Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                        <small><div id="mensagem"></div></small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-info">Recuperar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $("#form-recuperar").submit(function (event) {
            event.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                url: "recuperar.php",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (mensagem) {
                    $('#mensagem').removeClass().addClass(mensagem.trim() === "Sua senha foi Enviada para seu Email!" ? 'text-success' : 'text-danger').text(mensagem);
                }
            });
        });
    </script>
</body>
</html>
