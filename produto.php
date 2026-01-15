<?php
session_start();
include('conexao2.php');

if (!isset($_GET['id'])) { die("Produto não especificado!"); }

$produto_id = intval($_GET['id']);

$sql = "SELECT * FROM produtos WHERE id = $produto_id";
$result = $mysqli->query($sql);
if ($result->num_rows == 0) { die("Produto não encontrado!"); }

$produto = $result->fetch_assoc();

$imagens_extra = [];
if (!empty($produto['imagens'])) {
    $imagens_extra = explode(',', $produto['imagens']);
}

$avaliacoes = [
    ['usuario'=>'Maria','estrela'=>5,'comentario'=>'Produto excelente!'],
    ['usuario'=>'João','estrela'=>4,'comentario'=>'Gostei, mas poderia ser maior.'],
    ['usuario'=>'Ana','estrela'=>5,'comentario'=>'Amei o brilho e a textura!']
];

$relacionados = [];
$sql_rel = "SELECT * FROM produtos WHERE id != $produto_id LIMIT 4";
$res_rel = $mysqli->query($sql_rel);
while($r = $res_rel->fetch_assoc()) { $relacionados[] = $r; }

$logado = isset($_SESSION['codigo']);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?php echo $produto['nome']; ?> | Charmed da Rebeca</title>

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

*{ margin:0; padding:0; box-sizing:border-box; font-family:"Poppins", sans-serif; }

body{ background:var(--bg-page); color:var(--text-dark); }

header{
    background:var(--white);
    position:sticky;
    top:0;
    z-index:50;
    padding:1rem;
    box-shadow:0 1px 4px rgba(0,0,0,0.1);
}

.nav-top{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:1rem;
}

.brand{
    font-size:1.3rem;
    font-weight:700;
    color:var(--primary);
}

.brand-flex{
    display:flex;
    align-items:center;
    gap:10px;
}

.icon-btn{
    width:40px;
    height:40px;
    background:var(--secondary);
    border:none;
    border-radius:50%;
    display:flex;
    justify-content:center;
    align-items:center;
    color:var(--primary-dark);
    cursor:pointer;
    font-size:22px;
    transition:.2s;
}
.icon-btn:hover{
    background:var(--primary);
    color:white;
}

.actions{
    display:flex;
    gap:12px;
}

.search-container{ position:relative; }
.search-input{
    width:100%;
    padding:14px 15px 14px 45px;
    background:#F3F4F6;
    border:1px solid #E5E7EB;
    border-radius:12px;
}
.search-icon{
    position:absolute;
    top:50%;
    left:14px;
    transform:translateY(-50%);
    color:var(--text-gray);
}

.product-page{
    width:95%;
    max-width:1100px;
    margin:2rem auto;
    display:flex;
    gap:2rem;
    flex-wrap:wrap;
}

.left-box{
    flex:1 1 450px;
}
.main-img-container{
    width:100%;
    border-radius:16px;
    overflow:hidden;
    background:#eee;
    box-shadow:var(--shadow);
}
#main-img{
    width:100%;
    transition:.3s;
}
#main-img.zoomed{ transform:scale(1.6); }

.thumbnail-container{
    margin-top:10px;
    display:flex;
    gap:10px;
}
.thumbnail-container img{
    width:70px; height:70px;
    object-fit:cover;
    border-radius:10px;
    cursor:pointer;
    border:2px solid transparent;
    transition:.2s;
}
.thumbnail-container img:hover{
    border-color:var(--primary);
}

.right-box{
    flex:1 1 450px;
    background:var(--white);
    padding:1.5rem;
    border-radius:16px;
    box-shadow:var(--shadow);
}

.right-box h1{
    font-size:1.7rem;
    font-weight:700;
    margin-bottom:.5rem;
}

.price{
    font-size:1.6rem;
    font-weight:700;
    color:var(--primary);
    margin:10px 0;
}

.stock{
    font-weight:600;
    margin-bottom:10px;
}
.stock.in{ color:green; }
.stock.out{ color:red; }

.avaliacoes{ margin:20px 0; }
.avaliacao-item{
    padding:10px 0;
    border-bottom:1px solid #eee;
}
.star{ color:var(--primary); }

.btn{
    width:100%;
    padding:14px;
    border:none;
    border-radius:12px;
    font-size:1rem;
    font-weight:600;
    cursor:pointer;
    margin-top:10px;
    transition:.2s;
}
.btn-cart{
    background:var(--primary);
    color:white;
}
.btn-cart:hover{ background:var(--primary-dark); }

.btn-buy{
    background:var(--secondary);
    color:var(--primary-dark);
}
.btn-buy:hover{
    background:var(--primary);
    color:white;
}

.related-products{
    width:95%;
    max-width:1100px;
    margin:2rem auto;
}
.related-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit, minmax(180px, 1fr));
    gap:1rem;
}
.related-item{
    background:var(--white);
    padding:10px;
    border-radius:12px;
    box-shadow:var(--shadow);
    text-align:center;
}
.related-item img{
    width:100%;
    height:150px;
    object-fit:cover;
    border-radius:12px;
}
.related-item a{
    display:block;
    padding:8px;
    margin-top:8px;
    background:var(--primary);
    color:white;
    border-radius:10px;
}

@media (max-width:768px){
    .product-page{ flex-direction:column; }
}
</style>

<script>
function trocarImagem(src){ document.getElementById("main-img").src = src; }
function zoomIn(img){ img.classList.add("zoomed"); }
function zoomOut(img){ img.classList.remove("zoomed"); }
</script>

</head>
<body>

<header>
    <div class="nav-top">

        <div class="brand-flex">
            <a href="painel.php" class="icon-btn"><i class="ph ph-arrow-left"></i></a>
            <div class="brand">Charmed da Rebeca</div>
        </div>

        <div class="actions">
            <a href="login.php" class="icon-btn"><i class="ph ph-user"></i></a>
            <a href="protect.php" class="icon-btn"><i class="ph ph-shopping-cart"></i></a>
        </div>

    </div>

    <form action="busca.php" method="get" class="search-container">
        <i class="ph ph-magnifying-glass search-icon"></i>
        <input type="text" name="termo" class="search-input" placeholder="Buscar..." required>
    </form>
</header>

<div class="product-page">

    <div class="left-box">
        <div class="main-img-container">
            <img id="main-img" src="img/<?php echo $produto['imagem']; ?>" 
                 onmouseover="zoomIn(this)" onmouseout="zoomOut(this)">
        </div>

        <?php if(!empty($imagens_extra)): ?>
        <div class="thumbnail-container">
            <?php foreach($imagens_extra as $img): ?>
                <img src="img/<?php echo trim($img); ?>" onclick="trocarImagem(this.src)">
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <div class="right-box">

        <h1><?php echo $produto['nome']; ?></h1>

        <div class="price">R$<?php echo number_format($produto['preco'],2,',','.'); ?></div>

        <div class="stock <?php echo $produto['estoque']>0?'in':'out'; ?>">
            <?php echo $produto['estoque']>0 ? "Em estoque" : "Indisponível"; ?>
        </div>

        <div class="avaliacoes">
            <?php foreach($avaliacoes as $a): ?>
                <div class="avaliacao-item">
                    <strong><?php echo $a['usuario']; ?></strong>
                    <?php for($i=0;$i<$a['estrela'];$i++): ?><span class="star">★</span><?php endfor; ?>
                    <p><?php echo $a['comentario']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <form method="POST" action="<?php echo $logado ? 'painel.php' : 'protect.php'; ?>">
            <input type="hidden" name="produto_nome" value="<?php echo $produto['nome']; ?>">
            <input type="hidden" name="produto_img" value="<?php echo $produto['imagem']; ?>">
            <input type="hidden" name="produto_preco" value="<?php echo $produto['preco']; ?>">
            <button type="submit" name="add_carrinho" class="btn btn-cart">🛒 Adicionar ao Carrinho</button>
        </form>

        <form method="GET" action="checkout.php">
            <input type="hidden" name="produto_id" value="<?php echo $produto['id']; ?>">
            <button type="submit" class="btn btn-buy">💜 Comprar Agora</button>
        </form>

    </div>
</div>

<div class="related-products">
    <h2 style="margin-bottom:10px;">Produtos Relacionados</h2>

    <div class="related-grid">
        <?php foreach($relacionados as $r): ?>
        <div class="related-item">
            <img src="img/<?php echo $r['imagem']; ?>">
            <h3><?php echo $r['nome']; ?></h3>
            <strong style="color:var(--primary);">R$<?php echo number_format($r['preco'],2,',','.'); ?></strong>
            <a href="produto.php?id=<?php echo $r['id']; ?>">Ver Produto</a>
        </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>

