<?php
session_start();
include('conexao2.php'); 

// Adicionar ao carrinho
if (isset($_POST['add_carrinho'])) {
    $usuario_id = $_SESSION['codigo'];
    $nome = $_POST['produto_nome'];
    $preco = $_POST['produto_preco'];
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
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1),
                       0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }

        body { background: var(--bg-page); color: var(--text-dark); }

        /* HEADER */
        header {
            background: var(--white);
            position: sticky;
            top: 0;
            z-index: 50;
            padding: 1rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .nav-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .brand {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary);
        }

        .actions {
            display: flex;
            gap: 12px;
        }

        .icon-btn {
            width: 45px;
            height: 45px;
            background: var(--secondary);
            border: none;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: var(--primary);
            cursor: pointer;
            font-size: 22px;
            transition: .2s;
        }

        /* SEARCH */
        .search-container {
            position: relative;
        }

        .search-input {
            width: 100%;
            padding: 14px 15px 14px 45px;
            background: #F3F4F6;
            border: 1px solid #E5E7EB;
            border-radius: 12px;
            font-size: .95rem;
        }

        .search-icon {
            position: absolute;
            top: 50%;
            left: 14px;
            transform: translateY(-50%);
            color: var(--text-gray);
            font-size: 20px;
        }

        /* GRID PRODUTOS */
        main { padding: 1.5rem 1rem; }

        .section-title {
            font-size: 1.15rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .product-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        /* CARD PRODUTO */
        .product-card {
            background: var(--white);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--shadow);
            display: flex;
            flex-direction: column;
        }

        .image-container {
            width: 100%;
            padding-top: 100%;
            position: relative;
            background: #f0f0f0;
        }

        .product-img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-info {
            padding: 12px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .product-name {
            font-size: .9rem;
            font-weight: 600;
            margin-bottom: 5px;
            height: 38px;
            overflow: hidden;
        }

        .product-price {
            color: var(--primary);
            font-size: 1rem;
            font-weight: 700;
            margin-top: auto;
        }

        .btn-view {
            margin-top: 10px;
            padding: 10px;
            background: var(--primary);
            color: white;
            text-align: center;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            display: block;
        }

        /* DESKTOP */
        @media (min-width: 768px) {
            .product-grid { grid-template-columns: repeat(4, 1fr); }
            body { max-width: 1100px; margin: auto; }
        }
    </style>
</head>
<body>

<header>
    <div class="nav-top">
        <div class="brand">Charmed da Rebeca</div>

        <div class="actions">
            <a href="login.php" class="icon-btn"><i class="ph ph-user"></i></a>
            <a href="protect.php" class="icon-btn"><i class="ph ph-shopping-cart"></i></a>
        </div>
    </div>

    <!-- BARRA DE PESQUISA FUNCIONAL -->
    <form action="busca.php" method="get" class="search-container">
        <i class="ph ph-magnifying-glass search-icon"></i>
        <input type="text" name="termo" class="search-input" placeholder="Buscar produtos..." required>
    </form>
</header>

<main>
    <h2 class="section-title">Produtos</h2>

    <div class="product-grid">
        <?php
        $sql = "SELECT * FROM produtos";
        $result = $mysqli->query($sql);

        if ($result->num_rows > 0) {
            while ($p = $result->fetch_assoc()) {

                echo '
                <div class="product-card">
                    <div class="image-container">
                        <img src="./img/'.$p['imagem'].'" class="product-img">
                    </div>

                    <div class="product-info">
                        <h3 class="product-name">'.$p['nome'].'</h3>
                        <span class="product-price">R$ '.number_format($p['preco'], 2, ',', '.').'</span>
                        <a href="produto.php?id='.$p['id'].'" class="btn-view">Ver Produto</a>
                    </div>
                </div>';
            }
        } else {
            echo "<p>Nenhum produto encontrado.</p>";
        }
        ?>
    </div>
</main>

</body>
</html>
