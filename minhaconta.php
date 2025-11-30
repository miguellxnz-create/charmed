<?php
session_start();
include('conexao.php');

// usuário não logado → manda para login
if (!isset($_SESSION['codigo'])) {
    header("Location: login.php");
    exit;
}

// BUSCAR DADOS DO USUÁRIO
$codigo = $_SESSION['codigo'];
$sql = $mysqli->query("SELECT * FROM usuario WHERE codigo = '$codigo'");
$usuario = $sql->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Minha Conta • Charmed da Rebeca</title>

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

/* =================== GLOBAL =================== */
body {
    margin: 0;
    background: var(--bg-page);
    font-family: 'Poppins', sans-serif;
}

/* =================== HEADER =================== */
header {
    background: var(--white);
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 14px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.11);
    position: sticky;
    top: 0;
    z-index: 20;
}

.back-btn {
    font-size: 1.7rem;
    color: var(--primary);
    text-decoration: none;
}

.header-title {
    font-size: 1.35rem;
    font-weight: 600;
    color: var(--primary-dark);
}

/* =================== CONTAINER =================== */
.container {
    max-width: 700px;
    margin: 45px auto;
    background: var(--white);
    padding: 30px;
    border-radius: 16px;
    box-shadow: var(--shadow);
}

/* =================== TÍTULO =================== */
.container h2 {
    font-size: 1.8rem;
    text-align: center;
    margin-bottom: 25px;
    color: var(--primary-dark);
}

/* =================== INFO =================== */
.info-box {
    background: var(--secondary);
    padding: 20px;
    border-radius: 12px;
    border-left: 6px solid var(--primary-dark);
    margin-bottom: 25px;
}

.info-box p {
    margin: 10px 0;
    font-size: 1rem;
    color: var(--text-dark);
}

/* =================== BOTÕES =================== */
.btn-area {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.btn {
    background: var(--primary);
    color: var(--white);
    padding: 14px;
    border-radius: 10px;
    text-align: center;
    text-decoration: none;
    font-weight: 600;
    transition: 0.25s;
    font-size: 17px;
}

.btn:hover {
    background: var(--primary-dark);
    transform: scale(1.03);
}

.btn-danger {
    background: var(--danger);
}

.btn-danger:hover {
    background: #dc2626;
}

/* RESPONSIVIDADE */
@media(max-width: 600px) {
    .container {
        margin: 25px 14px;
        padding: 22px;
    }

    .container h2 {
        font-size: 1.6rem;
    }

    .btn {
        font-size: 16px;
        padding: 12px;
    }
}

</style>
</head>
<body>

<!-- HEADER -->
<header>
    <a href="painel.php" class="back-btn">
        <i class="ph ph-arrow-left"></i>
    </a>
    <span class="header-title">Minha Conta</span>
</header>

<div class="container">

    <h2>Olá, <?php echo $_SESSION['nome']; ?> 👋</h2>

    <div class="info-box">
        <p><strong>Nome:</strong> <?php echo $usuario['nome']; ?></p>
        <p><strong>Email:</strong> <?php echo $usuario['Email']; ?></p>
        <p><strong>ID da Conta:</strong> <?php echo $usuario['codigo']; ?></p>
    </div>

    <div class="btn-area">
        <a href="editarperfil.php" class="btn">✏ Editar Perfil</a>
        <a href="pedidos.php" class="btn">🛍 Meus Pedidos</a>
        <a href="logout.php" class="btn btn-danger">🚪 Sair da Conta</a>
    </div>

</div>

</body>
</html>
