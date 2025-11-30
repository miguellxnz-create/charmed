<?php
session_start();
include('conexao2.php'); // Conexão com o banco

if (!isset($_GET['termo']) || empty(trim($_GET['termo']))) {
    die("Nenhum termo de pesquisa informado!");
}

$termo = $mysqli->real_escape_string(trim($_GET['termo']));

// Busca produtos pelo nome
$sql = "SELECT * FROM produtos WHERE nome LIKE '%$termo%'";
$result = $mysqli->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Resultados da Busca | Charmed da Rebeca</title>
<style>
body { font-family: Arial, Helvetica, sans-serif; margin:0; padding:0; background:#f3f3f3; }
header { padding:20px; text-align:center; }
h1 { color:#5a1bb3; }
.products-container { display:flex; flex-wrap:wrap; gap:20px; justify-content:center; margin:20px; }
.product-box { background:white; border-radius:12px; padding:15px; width:220px; text-align:center; box-shadow:0 4px 8px rgba(0,0,0,0.1); }
.product-box img { width:150px; height:150px; object-fit:cover; border-radius:10px; }
.product-box h3 { font-size:18px; margin:10px 0; }
.product-box p { font-weight:bold; color:#B12704; margin:5px 0; }
.site-btn { display:inline-block; padding:10px 20px; margin-top:10px; background:#5a1bb3; color:white; border-radius:25px; text-decoration:none; transition:0.3s; }
.site-btn:hover { background:#7d2cff; transform:scale(1.05); }
.no-results { text-align:center; font-size:20px; margin-top:50px; color:#555; }
</style>
</head>
<body>

<header>
    <h1>Resultados da busca por "<?php echo htmlspecialchars($termo); ?>"</h1>
    <a href="painel.php" class="site-btn">⬅ Voltar</a>
</header>

<div class="products-container">
<?php
if ($result->num_rows > 0) {
    while($produto = $result->fetch_assoc()) {
        echo '<div class="product-box">';
        echo '<img src="./img/'.$produto['imagem'].'" alt="'.$produto['nome'].'">';
        echo '<h3>'.$produto['nome'].'</h3>';
        echo '<p>R$ '.number_format($produto['preco'], 2, ',', '.').'</p>';
        echo '<a href="produto.php?id='.$produto['id'].'" class="site-btn">Ver Produto</a>';
        echo '</div>';
    }
} else {
    echo '<p class="no-results">Nenhum produto encontrado.</p>';
}
?>
</div>

</body>
</html>
