<?php
session_start();
include('conexao2.php');

// Verifica se está logado
if (!isset($_SESSION['codigo'])) {
    header("Location: login.php");
    exit;
}

$usuarioCodigo = $_SESSION['codigo'];

// Pega pedidos do usuário
$pedido_id = $mysqli->query("SELECT * FROM pedidos WHERE usuario_id = $usuarioCodigo ORDER BY id DESC");

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Meus Pedidos • Charmed da Rebeca</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<?php while($p = $consulta->fetch_assoc()): ?>

<div style="border:1px solid #999;padding:10px;margin:10px;">
    <strong>Pedido #<?php echo $p['id']; ?></strong><br>
    Valor: R$ <?php echo $p['valor']; ?><br>
    Status: <b><?php echo $p['status']; ?></b><br>
    Data: <?php echo $p['data']; ?>
</div>

<?php endwhile; ?>
<style>
body {
    font-family: 'Poppins', sans-serif;
    margin: 0;
    background: #f9f5ff;
    color: #333;
}

/* NAVBAR */
nav {
    background: #5a1bb3;
    padding: 15px 20px;
}
nav ul {
    display: flex;
    justify-content: space-between;
    list-style: none;
    margin: 0; padding: 0;
    align-items: center;
}
nav a {
    text-decoration: none;
    color: #fff;
    font-weight: 600;
    padding: 6px 12px;
    border-radius: 8px;
    transition: .3s;
}
nav a:hover {
    background: #7d2cff;
}
.right-buttons {
    display: flex;
    gap: 15px;
    align-items: center;
}

/* DROPDOWN */
.dropdown {
    position: relative;
}
.dropdown-menu {
    display: none;
    position: absolute;
    top: 110%;
    right: 0;
    background: #7d2cff;
    padding: 10px 0;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}
.dropdown-menu li a {
    display: block;
    padding: 10px 20px;
    color: white;
}
.dropdown:hover .dropdown-menu { display: block; }

/* CONTAINER */
.orders-container {
    max-width: 900px;
    margin: 40px auto;
    padding: 20px;
}
.orders-container h2 {
    text-align: center;
    color: #5a1bb3;
    margin-bottom: 30px;
}

/* CARD DE PEDIDOS */
.order-box {
    background: #fff;
    border-radius: 14px;
    padding: 20px;
    margin-bottom: 18px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.order-info h3 { margin: 0; color: #5a1bb3; }
.order-info p { margin: 4px 0; }

/* STATUS */
.status {
    padding: 5px 10px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 14px;
}
.status-entregue { background: #b2f2bb; color: #1f7a1f; }
.status-enviado { background: #ffe8a3; color: #8f6c00; }
.status-aguardando { background: #ffd4d4; color: #b30000; }

/* BOTÃO DETALHES */
.details-btn {
    background: #5a1bb3;
    color: #fff;
    padding: 10px 16px;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    font-weight: 600;
    transition: .3s;
}
.details-btn:hover { background: #7d2cff; transform: scale(1.05); }

/* RESPONSIVO */
@media(max-width: 600px) {
    .order-box { flex-direction: column; align-items: flex-start; gap: 10px; }
    .details-btn { align-self: flex-end; }
}
</style>
</head>
<body>

<nav>
    <ul>
        <li><a href="painel.php">Charmed da Rebeca</a></li>
        <div class="right-buttons">
            <?php if(isset($_SESSION['nome'])): ?>
            <li class="dropdown">
                <a>👤 <?php echo $_SESSION['nome']; ?> ▼</a>
                <ul class="dropdown-menu">
                    <li><a href="minhaconta.php">📄 Minha Conta</a></li>
                    <li><a href="pedidos.php">🛍 Meus Pedidos</a></li>
                    <li><a href="logout.php">🚪 Sair</a></li>
                </ul>
            </li>
            <?php else: ?>
            <li><a href="login.php">👤 Login</a></li>
            <?php endif; ?>
            <li><a href="carrinho.php">🛒 Carrinho</a></li>
        </div>
    </ul>
</nav>

<div class="orders-container">
    <h2>Meus Pedidos</h2>

    <?php
    if($pedidosSql->num_rows > 0){
        while($pedido = $pedidosSql->fetch_assoc()){
            // Define cor do status
            $statusClass = 'status-aguardando';
            if($pedido['status'] == 'Enviado') $statusClass = 'status-enviado';
            elseif($pedido['status'] == 'Entregue') $statusClass = 'status-entregue';
            ?>
            <div class="order-box">
                <div class="order-info">
                    <h3>Pedido #<?php echo $pedido['id']; ?></h3>
                    <p>Data: <?php echo date('d/m/Y', strtotime($pedido['data'])); ?></p>
                    <span class="status <?php echo $statusClass; ?>"><?php echo $pedido['status']; ?></span>
                </div>
                <button class="details-btn" onclick="window.location='detalhespedido.php?id=<?php echo $pedido['id']; ?>'">Ver Detalhes</button>
            </div>
        <?php
        }
    } else {
        echo "<p style='text-align:center; color:#555;'>Você ainda não fez nenhum pedido.</p>";
    }
    ?>
</div>

</body>
</html>
