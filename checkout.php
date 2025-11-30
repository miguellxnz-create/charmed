<?php
session_start();
include('conexao2.php');

if (!isset($_SESSION['codigo'])) {
    die("Você precisa estar logado para acessar o checkout!");
}

$usuario_id = $_SESSION['codigo'];
$produtos_checkout = [];
$total = 0;

// 1) Produto único
if (isset($_GET['produto_id'])) {
    $produto_id = intval($_GET['produto_id']);
    $sql = "SELECT * FROM produtos WHERE id = $produto_id";
    $resultado = $mysqli->query($sql);

    if ($resultado->num_rows == 0) {
        die("Produto não encontrado!");
    }

    $produto = $resultado->fetch_assoc();
    $produto['quantidade'] = 1;
    $produtos_checkout[] = $produto;
    $total = $produto['preco'];

// 2) Carrinho inteiro
} else {
    $sql = "SELECT * FROM carrinho WHERE usuario_id = $usuario_id";
    $resultado = $mysqli->query($sql);

    if ($resultado->num_rows == 0) {
        die("Carrinho vazio ou produto não especificado.");
    }

    while ($item = $resultado->fetch_assoc()) {
        $subtotal = $item['produto_preco'] * $item['quantidade'];
        $total += $subtotal;
        $produtos_checkout[] = $item;
    }
}

// 3) Processar formulário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome_completo = $_POST['nome_completo'];
    $telefone = $_POST['telefone'];
    $cep = $_POST['cep'];
    $rua = $_POST['rua'];
    $numero = $_POST['numero'];
    $complemento = $_POST['complemento'];
    $bairro = $_POST['bairro'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];
    $metodo_pagamento = $_POST['metodo_pagamento'];

    $numero_cartao = $_POST['numero_cartao'] ?? null;
    $validade_cartao = $_POST['validade_cartao'] ?? null;
    $cvv_cartao = $_POST['cvv_cartao'] ?? null;

    if ($metodo_pagamento == 'Pix') {
        header("Location: pagamentopix.php");
        exit;
    } elseif ($metodo_pagamento == 'Cartão de Crédito' || $metodo_pagamento == 'Boleto') {
        header("Location: invalido.php");
        exit;
    }

    $sql = "INSERT INTO compras (usuario_id, nome_completo, telefone, cep, rua, numero, complemento, bairro, cidade, estado, metodo_pagamento, numero_cartao, validade_cartao, cvv_cartao, total) 
            VALUES ('$usuario_id', '$nome_completo', '$telefone', '$cep', '$rua', '$numero', '$complemento', '$bairro', '$cidade', '$estado', '$metodo_pagamento', '$numero_cartao', '$validade_cartao', '$cvv_cartao', '$total')";

    if ($mysqli->query($sql)) {
        $mysqli->query("DELETE FROM carrinho WHERE usuario_id = $usuario_id");
        echo "<script>alert('Compra realizada com sucesso!'); window.location.href='painel.php';</script>";
    } else {
        echo "<script>alert('Erro ao realizar a compra. Tente novamente.');</script>";
        echo "<br>Erro: " . $mysqli->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Checkout</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<script src="https://unpkg.com/@phosphor-icons/web"></script>

<style>
:root {
    --primary: #8B5CF6;
    --primary-dark: #7C3AED;
    --secondary: #EDE9FE;
    --text-dark: #1F2937;
    --text-gray: #6B7280;
    --white: #fff;
    --bg-page: #F9FAFB;
    --shadow: 0 4px 6px rgba(0,0,0,0.1);
}

*{margin:0;padding:0;box-sizing:border-box;font-family:"Poppins",sans-serif;}
body{background:var(--bg-page);color:var(--text-dark);}

/* HEADER */
header{
    background:var(--white);
    padding:1rem;
    position:sticky;
    top:0;
    box-shadow:var(--shadow);
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.brand-flex{
    display:flex;
    align-items:center;
    gap:10px;
}

.icon-btn{
    width:38px;
    height:38px;
    background:var(--secondary);
    border-radius:50%;
    display:flex;
    justify-content:center;
    align-items:center;
    color:var(--primary-dark);
    cursor:pointer;
    font-size:22px;
    transition:.2s;
    text-decoration:none;
}
.icon-btn:hover{
    background:var(--primary);
    color:var(--white);
}

.brand{
    font-size:1.4rem;
    font-weight:700;
    color:var(--primary);
}

/* CONTEÚDO */
.checkout-container{
    width:95%;
    max-width:1100px;
    margin:2rem auto;
    display:flex;
    gap:2rem;
    flex-wrap:wrap;
}

/* CARTÕES */
.card{
    background:var(--white);
    padding:1.5rem;
    border-radius:16px;
    box-shadow:var(--shadow);
    flex:1 1 420px;
}

.card h2{
    margin-bottom:1rem;
    color:var(--primary-dark);
}

label{
    font-weight:600;
    margin-top:10px;
    display:block;
    color:var(--text-dark);
}

input, select{
    width:100%;
    padding:12px;
    border-radius:12px;
    border:1px solid #ddd;
    margin-top:6px;
    background:#F3F4F6;
}

/* 🔥 A ÚNICA COISA ALTERADA AQUI */
.radio-group{
    margin-top:16px;
    display:flex;
    flex-direction:column; 
    align-items:flex-start;
    gap:0px;
}

.radio-option{
    display:flex;
    align-items:center;
    gap:5px;
    justify-content:flex-start;
    margin-bottom:6px;
}

/* RESUMO */
.resumo-item{
    padding:10px 0;
    border-bottom:1px solid #eee;
}

.total{
    font-size:1.6rem;
    font-weight:700;
    color:var(--primary-dark);
    margin-top:1rem;
}

/* BOTÃO */
.btn-finalizar{
    width:100%;
    padding:16px;
    font-size:1.1rem;
    margin-top:20px;
    background:var(--primary);
    border:none;
    border-radius:14px;
    color:white;
    cursor:pointer;
    transition:.2s;
}
.btn-finalizar:hover{
    background:var(--primary-dark);
}
input, textarea, select {
    width: 100%;
    padding: 12px;
    border-radius: 12px;
    background: var(--card);
    border: 2px solid #1a1a1a;
    color: var(--text);
    font-size: 1rem;
    transition: 0.3s border-color;
}

input:focus, textarea:focus, select:focus {
    border-color: var(--primary);
    outline: none;
}

</style>

</head>
<body>

<header>
    <div class="brand-flex">
        <a href="painel.php" class="icon-btn"><i class="ph ph-arrow-left"></i></a>
        <div class="brand">Checkout</div>
    </div>

    <a href="protect.php" class="icon-btn"><i class="ph ph-shopping-cart"></i></a>
</header>

<div class="checkout-container">

    <!-- ENDEREÇO -->
    <form method="POST" class="card">
        <h2>Endereço de Entrega</h2>

        <label>Nome Completo</label>
        <input type="text" name="nome_completo" required>

        <label>Telefone</label>
        <input type="text" name="telefone" required>

        <label>CEP</label>
        <input type="text" name="cep" required>

        <label>Rua</label>
        <input type="text" name="rua" required>

        <label>Número</label>
        <input type="text" name="numero" required>

        <label>Complemento</label>
        <input type="text" name="complemento">

        <label>Bairro</label>
        <input type="text" name="bairro" required>

        <label>Cidade</label>
        <input type="text" name="cidade" required>

        <label>Estado</label>
        <input type="text" name="estado" required>

        <!-- MÉTODO DE PAGAMENTO -->
        <h2 style="margin-top:20px;">Pagamento</h2>

        <div class="radio-group">
            <label class="radio-option">
                <input type="radio" name="metodo_pagamento" value="Pix" checked> Pix
            </label>
            <label class="radio-option">
                <input type="radio" name="metodo_pagamento" value="Cartão de Crédito"> Cartão de Crédito
            </label>
            <label class="radio-option">
                <input type="radio" name="metodo_pagamento" value="Boleto"> Boleto
            </label>
        </div>

        <div id="cartao-info" style="display:none;">
            <h3 style="margin-top:15px;">Dados do Cartão</h3>

            <label>Número do Cartão</label>
            <input type="text" name="numero_cartao">

            <label>Validade</label>
            <input type="text" name="validade_cartao">

            <label>CVV</label>
            <input type="text" name="cvv_cartao">
        </div>

        <button type="submit" class="btn-finalizar">Finalizar Compra</button>

    </form>

    <!-- RESUMO DO PEDIDO -->
    <div class="card">
        <h2>Resumo do Pedido</h2>

        <?php foreach ($produtos_checkout as $p): ?>
            <div class="resumo-item">
                <strong><?php echo $p['produto_nome'] ?? $p['nome']; ?></strong><br>
                Quantidade: <?php echo $p['quantidade']; ?><br>
                Preço: R$ <?php echo number_format($p['produto_preco'] ?? $p['preco'], 2, ',', '.'); ?>
            </div>
        <?php endforeach; ?>

        <div class="total">Total: R$ <?php echo number_format($total, 2, ',', '.'); ?></div>
    </div>

</div>

<script>
const radios = document.querySelectorAll('input[name="metodo_pagamento"]');
const cartaoInfo = document.getElementById('cartao-info');

radios.forEach(r => {
    r.addEventListener('change', () => {
        cartaoInfo.style.display = r.value === "Cartão de Crédito" ? "block" : "none";
    });
});
</script>

</body>
</html>
