<?php
session_start();
include('conexao2.php'); 

if (isset($_POST['add_carrinho'])) {
    $usuario_id = $_SESSION['codigo'];
    $nome = $_POST['produto_nome'];
    $preco = isset($_POST['produto_preco']) ? $_POST['produto_preco'] : 0;
    $img = $_POST['produto_img'];

    $sql = "INSERT INTO carrinho (usuario_id, produto_nome, produto_preco, produto_img)
            VALUES ('$usuario_id', '$nome', '$preco', '$img')";
    $mysqli->query($sql) or die($mysqli->error);

    echo "<script>alert('Produto adicionado ao carrinho!');</script>";
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Charmed da Rebeca</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<script src="https://unpkg.com/@phosphor-icons/web"></script>

<style>

:root {
    --primary: #8B5CF6;
    --primary-dark: #7C3AED;
    --secondary: #EDE9FE;
    --text-dark: #1F2937;
    --text-gray: #6B7280;
    --white: #ffffff;
    --bg-page: #F9FAFB;
    --shadow: 0 4px 6px rgba(0,0,0,0.1);
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    background: var(--bg-page);
    padding-bottom: 60px;
}

header {
    background: var(--white);
    padding: 1rem;
    position: sticky;
    top: 0;
    z-index: 10;
    box-shadow: 0 1px 4px rgba(0,0,0,0.1);
}

.nav-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.brand {
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--primary);
}

.actions {
    display: flex;
    gap: 12px;
}

.icon-btn {
    background: var(--secondary);
    border: none;
    width: 42px;
    height: 42px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary);
    cursor: pointer;
    transition: 0.2s;
}

.icon-btn:hover {
    transform: scale(1.05);
}

.search-container {
    margin-top: 12px;
    position: relative;
}

.search-input {
    width: 100%;
    padding: 12px 14px 12px 40px;
    border-radius: 12px;
    border: 1px solid #ddd;
    background: #F3F4F6;
    outline: none;
}

.search-input:focus {
    border-color: var(--primary);
    background: var(--white);
    box-shadow: 0 0 0 2px rgba(139, 92, 246, 0.25);
}

.search-icon {
    position: absolute;
    top: 50%;
    left: 12px;
    transform: translateY(-50%);
    color: var(--text-gray);
}

main {
    padding: 1.5rem;
}

.section-title {
    font-size: 1.2rem;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 1rem;
}

.product-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

.product-card {
    background: var(--white);
    border-radius: 14px;
    box-shadow: var(--shadow);
    overflow: hidden;
    transition: 0.2s;
}

.product-card:hover {
    transform: scale(1.02);
}

.image-container {
    width: 100%;
    padding-top: 100%;
    position: relative;
}

.product-img {
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 100%;
    object-fit: cover;
    border-bottom: 1px solid #eee;
}

.product-info {
    padding: 12px;
}

.product-name {
    font-weight: 600;
    font-size: 0.95rem;
    height: 42px;
}

.product-price {
    color: var(--primary);
    font-weight: 700;
    font-size: 1rem;
    margin-top: 6px;
}

.btn-buy {
    width: 100%;
    margin-top: 10px;
    padding: 10px;
    border: none;
    background: var(--primary);
    color: var(--white);
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    justify-content: center;
    gap: 6px;
}

.btn-buy:hover {
    background: var(--primary-dark);
}

@media (min-width: 768px) {
    .product-grid {
        grid-template-columns: repeat(4, 1fr);
    }
    body {
        max-width: 1100px;
        margin: auto;
    }
}

</style>
</head>
<body>

<header>

    <div class="nav-top">
        <div class="brand">Charmed da Rebeca</div>

        <div class="actions">
            <!-- Login -->
            <?php if(isset($_SESSION['nome'])): ?>
                <a href="minhaconta.php" class="icon-btn"><i class="ph ph-user"></i></a>
            <?php else: ?>
                <a href="login.php" class="icon-btn"><i class="ph ph-user"></i></a>
            <?php endif; ?>

            <!-- Carrinho -->
            <a href="carrinho.php" class="icon-btn"><i class="ph ph-shopping-cart"></i></a>
        </div>
    </div>

    <form action="busca.php" method="get" class="search-container">
        <i class="ph ph-magnifying-glass search-icon"></i>
        <input type="text" name="termo" class="search-input" placeholder="Pesquisar produtos..." required>
    </form>

</header>

<main>
    <h2 class="section-title">Produtos</h2>

    <div class="product-grid">

        <?php
        $sql_produtos = "SELECT * FROM produtos";
        $result_produtos = $mysqli->query($sql_produtos);

        if ($result_produtos->num_rows > 0) {
            while ($p = $result_produtos->fetch_assoc()) {

                echo '
                <div class="product-card">
                    <div class="image-container">
                        <img src="./img/'.$p['imagem'].'" class="product-img" alt="'.$p['nome'].'">
                    </div>

                    <div class="product-info">
                        <h3 class="product-name">'.$p['nome'].'</h3>
                        <div class="product-price">R$ '.number_format($p['preco'], 2, ',', '.').'</div>
                        <a href="produto.php?id='.$p['id'].'">
                            <button class="btn-buy"><i class="ph ph-bag"></i> Ver produto</button>
                        </a>
                    </div>
                </div>
                ';
            }
        } else {
            echo "<p>Nenhum produto encontrado.</p>";
        }
        ?>

    </div>

</main>

</body>
</html>

