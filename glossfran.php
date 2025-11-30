<?php
session_start();
include('conexao.php');

// Inicializa o carrinho na sessão se ainda não existir
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

// Adicionar ao carrinho
if (isset($_POST['adicionar'])) {
    $produto = [
        'nome' => 'Gloss Labial',
        'preco' => 29.90,
        'quantidade' => 1
    ];

    // Se já estiver no carrinho, aumenta a quantidade
    $encontrado = false;
    foreach ($_SESSION['carrinho'] as &$item) {
        if ($item['nome'] == $produto['nome']) {
            $item['quantidade'] += 1;
            $encontrado = true;
            break;
        }
    }
    unset($item);

    if (!$encontrado) {
        $_SESSION['carrinho'][] = $produto;
    }

    $mensagem = "Produto adicionado ao carrinho!";
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gloss Labial • Charmed da Rebeca</title>
    <style>
        body{
            color: white;
            font-family: Arial, Helvetica, sans-serif; 
            background-color: blueviolet;
            margin: 0;
        }
        ul { display: flex; justify-content: space-between; list-style-type: none; align-items: center; padding: 16px;  }
        nav { background: #5a1bb3; padding: 15px; margin-top: -7px; margin-right: -7px ; margin-left: -7px; }
        nav ul { list-style-type: none; padding: 0; align-items: center; display: flex; justify-content: space-between; }
        nav a { color: white; text-decoration: none; font-size: 18px; }
        .right-buttons { display: flex; gap: 20px; }
        nav a { color: white; text-decoration: none; font-size: 18px; display: flex; align-items: center; gap: 8px; transition: 0.3s; }
        nav a:hover { color: #ffd9ff; transform: scale(1.05); }
        .site-btn { display: inline-block; padding: 12px 28px; background: linear-gradient(135deg, #7d2cff, #a64fff); color: white; font-size: 20px; font-weight: bold; border-radius: 35px; text-decoration: none; border: 3px solid #d4b0ff; transition: 0.3s ease; box-shadow: 0 5px 15px rgba(0,0,0,0.3); }
        .site-btn:hover { background: linear-gradient(135deg, #a64fff, #c27fff); transform: scale(1.08); border-color: #e0c0ff; box-shadow: 0 7px 18px rgba(0,0,0,0.35); }
        .menu-btn { display: inline-block; padding: 10px 18px; background: linear-gradient(135deg, #5a1bb3, #7d2cff); color: white; font-size: 17px; font-weight: bold; border-radius: 25px; text-decoration: none; border: 2px solid #b08eff; transition: 0.3s ease; box-shadow: 0 4px 10px rgba(0,0,0,0.25); }
        .menu-btn:hover { background: linear-gradient(135deg, #7d2cff, #9a4cff); transform: scale(1.07); border-color: #d7c0ff; box-shadow: 0 6px 14px rgba(0,0,0,0.35); }
        .dropdown { position: relative; }
        .dropdown-menu { display: none; position: absolute; right: 0; background: #7d2cff; list-style: none; padding: 10px 0; margin: 5px 0 0 0; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.25); z-index: 1000; }
        .dropdown-menu li a { display: block; padding: 10px 20px; color: white; text-decoration: none; transition: 0.3s; }
        .dropdown-menu li a:hover { background: #5a1bb3; transform: scale(1.03); }
        .dropdown:hover .dropdown-menu { display: block; }
        .product-container { display: flex; justify-content: center; align-items: flex-start; gap: 50px; margin: 50px 20px; flex-wrap: wrap; }
        .product-image img { width: 300px; border-radius: 12px; border: 5px solid black; }
        .product-info { max-width: 400px; }
        .product-info h2 { color: #ffd9ff; }
        .product-info p { font-size: 18px; margin: 10px 0; }
        .product-info .price { font-weight: bold; font-size: 22px; margin: 15px 0; }
        .mensagem { text-align: center; font-size: 18px; color: #ffd9ff; margin-top: 20px; }
    </style>
</head>
<body>

<!-- MENU -->
<nav>
    <ul>
        <li>
            <a href="painel.php" class="site-btn">Charmed da Rebeca</a>
        </li>
        <div class="right-buttons">
            <?php if (isset($_SESSION['nome'])): ?>
                <li class="dropdown">
                    <a class="menu-btn">👤 <?php echo $_SESSION['nome']; ?> ▼</a>
                    <ul class="dropdown-menu">
                        <li><a href="minhaconta.php">📄 Minha Conta</a></li>
                        <li><a href="pedidos.php">🛍 Meus Pedidos</a></li>
                        <li><a href="logout.php">🚪 Sair</a></li>
                    </ul>
                </li>
            <?php else: ?>
                <li><a href="login.php" class="menu-btn">👤 Login</a></li>
            <?php endif; ?>
            <li><a href="carrinho.php" class="menu-btn">🛒 Carrinho</a></li>
        </div>
    </ul>
</nav>

<!-- PRODUTO -->
<div class="product-container">
    <div class="product-image">
        <img src="./img/gloss.png.jpg" alt="Gloss Labial">
    </div>
    <div class="product-info">
        <h2>Gloss Labial</h2>
        <p>Deixe seus lábios radiantes com nosso gloss labial hidratante, com brilho intenso e longa duração.</p>
        <p class="price">R$ 29,90</p>
        <form method="post">
            <button type="submit" name="adicionar" class="site-btn">Adicionar ao Carrinho 🛒</button>
        </form>
        <?php if(isset($mensagem)) echo "<p class='mensagem'>$mensagem</p>"; ?>
    </div>
</div>

</body>
</html>
