<?php
session_start();
include('conexao2.php');

if (!isset($_SESSION['codigo'])) {
    die("Você precisa estar logado para acessar o carrinho!");
}


if (isset($_GET['remover'])) {
    $id_remover = intval($_GET['remover']);
    $mysqli->query("DELETE FROM carrinho WHERE id = $id_remover AND usuario_id = ".$_SESSION['codigo']);
    header("Location: carrinho.php");
    exit;
}


if (isset($_POST['atualizar_quantidade'])) {
    foreach ($_POST['quantidade'] as $id => $qtd) {
        $qtd = max(1, intval($qtd));
        $mysqli->query("UPDATE carrinho SET quantidade = $qtd WHERE id = $id AND usuario_id = ".$_SESSION['codigo']);
    }
    header("Location: carrinho.php");
    exit;
}

$usuario_id = $_SESSION['codigo'];
$result = $mysqli->query("SELECT * FROM carrinho WHERE usuario_id = $usuario_id");

$total = 0;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Carrinho • Charmed da Rebeca</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<script src="https://unpkg.com/@phosphor-icons/web"></script>

<style>

:root {
    --primary: #8B5CF6;
    --primary-dark: #7C3AED;
    --secondary: #EDE9FE;
    --white: #fff;
    --text-dark: #1F2937;
    --text-gray: #6B7280;
    --bg-page: #F9FAFB;
    --shadow: 0px 4px 10px rgba(0,0,0,0.08);
    --danger: #ef4444;
}

body {
    margin: 0;
    background: var(--bg-page);
    font-family: 'Poppins', sans-serif;
}


header {
    background: var(--white);
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 14px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.1);
    position: sticky;
    top: 0;
    z-index: 20;
}

.back-btn {
    text-decoration: none;
    color: var(--primary);
    font-size: 1.6rem;
}

.header-title {
    font-size: 1.3rem;
    font-weight: 600;
    color: var(--primary-dark);
}


.container {
    max-width: 900px;
    margin: 20px auto;
    background: var(--white);
    padding: 20px;
    border-radius: 14px;
    box-shadow: var(--shadow);
}


table {
    width: 100%;
    border-collapse: collapse;
}

th {
    padding: 12px;
    font-size: 14px;
    color: var(--text-gray);
    border-bottom: 1px solid #ddd;
}

td {
    padding: 14px 8px;
    text-align: center;
    border-bottom: 1px solid #eee;
}

.product-img {
    width: 70px;
    height: 70px;
    border-radius: 10px;
    object-fit: cover;
}


input[type="number"] {
    width: 55px;
    padding: 6px;
    border: 1px solid #ccc;
    border-radius: 8px;
    text-align: center;
}


.btn {
    padding: 10px 20px;
    background: var(--primary);
    color: var(--white);
    text-decoration: none;
    border-radius: 8px;
    display: inline-block;
    margin: 8px 5px;
    transition: 0.3s;
    font-weight: 600;
    border: none;
    cursor: pointer;
}

.btn:hover {
    background: var(--primary-dark);
}

.btn-danger {
    background: var(--danger);
}

.btn-danger:hover {
    background: #dc2626;
}


.total-box {
    margin-top: 20px;
    font-size: 1.3rem;
    font-weight: 600;
    color: var(--text-dark);
    text-align: right;
}


@media (max-width: 700px) {
    td img {
        width: 55px;
        height: 55px;
    }

    td:nth-child(1) {
        font-size: 13px;
    }

    table th:nth-child(3), td:nth-child(3),
    table th:nth-child(4), td:nth-child(4) {
        display: none;
    }
}

</style>
</head>
<body>

<header>
    <a href="painel.php" class="back-btn"><i class="ph ph-arrow-left"></i></a>
    <span class="header-title">Meu Carrinho</span>
</header>

<div class="container">

<form method="POST" action="carrinho.php">

<table>
    <thead>
        <tr>
            <th>Produto</th>
            <th>Preço</th>
            <th>Qtd.</th>
            <th>Subtotal</th>
            <th>Remover</th>
        </tr>
    </thead>

    <tbody>

<?php
if ($result->num_rows > 0) {
    while ($item = $result->fetch_assoc()) {

        $subtotal = $item['produto_preco'] * $item['quantidade'];
        $total += $subtotal;

        echo "
        <tr>
            <td>
                <img src='./img/{$item['produto_img']}' class='product-img'><br>
                {$item['produto_nome']}
            </td>

            <td>R$ ".number_format($item['produto_preco'],2,',','.')."</td>

            <td>
                <input type='number' name='quantidade[{$item['id']}]' value='{$item['quantidade']}' min='1'>
            </td>

            <td>R$ ".number_format($subtotal,2,',','.')."</td>

            <td>
                <a href='carrinho.php?remover={$item['id']}' class='btn btn-danger'>
                    <i class='ph ph-trash'></i>
                </a>
            </td>
        </tr>
        ";
    }
} else {
    echo "<tr><td colspan='5'>Seu carrinho está vazio!</td></tr>";
}
?>

    </tbody>
</table>

<br>
<button type="submit" name="atualizar_quantidade" class="btn">Atualizar Quantidade</button>
</form>

<div class="total-box">
    Total: R$ <?php echo number_format($total,2,',','.'); ?>
</div>

<br>

<a href="painel.php" class="btn">Continuar Comprando</a>
<a href="checkout.php" class="btn">Finalizar Compra</a>

</div>

</body>
</html>

