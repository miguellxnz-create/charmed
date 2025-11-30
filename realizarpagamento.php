<?php
session_start();
include('conexao2.php'); // Conexão com o banco de dados

// Verifica se o usuário está logado
if (!isset($_SESSION['codigo'])) {
    header("Location: login.php");
    exit();
}

// Verifica se o ID do pedido foi passado
if (!isset($_GET['pedido_id'])) {
    die("Pedido não especificado!");
}

$pedido_id = intval($_GET['pedido_id']);

// Busca os dados do pedido no banco de dados
$sql = "SELECT * FROM pedidos WHERE id = $pedido_id AND usuario_id = " . $_SESSION['codigo'];
$result = $mysqli->query($sql);

if ($result->num_rows == 0) {
    die("Pedido não encontrado ou você não tem permissão para visualizar este pedido.");
}

$pedido = $result->fetch_assoc();

// Verifica o status do pagamento
$status_pagamento = $pedido['status_pagamento'];

// Processo de pagamento - Simulação de pagamento
if (isset($_POST['realizar_pagamento'])) {
    $status_pagamento = 'aprovado';  // Simulando pagamento aprovado

    // Atualiza o status de pagamento no banco
    $sql_update = "UPDATE pedidos SET status_pagamento = '$status_pagamento' WHERE id = $pedido_id";
    if ($mysqli->query($sql_update)) {
        $mensagem = "Pagamento aprovado com sucesso!";
    } else {
        $mensagem = "Erro ao processar o pagamento.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Realizar Pagamento</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; background-color: #f3f3f3; }
        .container { width: 60%; margin: auto; background-color: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
        h1 { text-align: center; }
        .pedido-info { margin-bottom: 20px; }
        .pedido-info p { font-size: 18px; }
        .btn { background-color: #6b22d6; color: white; padding: 12px 20px; border: none; border-radius: 5px; cursor: pointer; width: 100%; font-size: 18px; }
        .btn:hover { background-color: #5a1bb3; }
    </style>
</head>
<body>

<div class="container">
    <h1>Detalhes do Pedido</h1>
    
    <div class="pedido-info">
        <p><strong>Produto:</strong> <?php echo $pedido['produto_nome']; ?></p>
        <p><strong>Preço:</strong> R$ <?php echo number_format($pedido['preco'], 2, ',', '.'); ?></p>
        <p><strong>Quantidade:</strong> <?php echo $pedido['quantidade']; ?></p>
        <p><strong>Status do pagamento:</strong> <?php echo ucfirst($pedido['status_pagamento']); ?></p>
    </div>

    <?php if (isset($mensagem)) { ?>
        <div style="text-align: center; margin: 10px 0; font-weight: bold;">
            <p><?php echo $mensagem; ?></p>
        </div>
    <?php } ?>

    <form method="POST" action="">
        <button type="submit" name="realizar_pagamento" class="btn">Realizar Pagamento</button>
    </form>
    
</div>

</body>
</html>
