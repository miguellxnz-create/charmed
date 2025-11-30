<?php
session_start();
include('conexao.php');

$erro = '';
$sucesso = '';

if (isset($_POST['email']) && isset($_POST['senha']) && isset($_POST['confirmar_senha'])) {

    $email = $mysqli->real_escape_string($_POST['email']);
    $senha = $mysqli->real_escape_string($_POST['senha']);
    $confirmar_senha = $mysqli->real_escape_string($_POST['confirmar_senha']);

    if ($senha !== $confirmar_senha) {
        $erro = "As senhas não coincidem!";
    } else {

        $busca = $mysqli->query("SELECT * FROM usuario WHERE Email = '$email'");
        if ($busca->num_rows > 0) {
            $erro = "Este e-mail já está cadastrado!";
        } else {
            // CADASTRO SEM HASH (mantido igual ao seu)
            $sql = "INSERT INTO usuario (Email, senha) VALUES ('$email', '$senha')";
            if ($mysqli->query($sql)) {
                $sucesso = "Cadastro realizado com sucesso! <a href='login.php'>Entrar</a>";
            } else {
                $erro = "Erro ao cadastrar: " . $mysqli->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Criar Conta • Charmed da Rebeca</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<script src="https://unpkg.com/@phosphor-icons/web"></script>

<style>

:root {
    --primary: #8B5CF6;
    --primary-dark: #7C3AED;
    --secondary: #EDE9FE;
    --white: #fff;
    --danger: #ef4444;
    --success: #10B981;
    --gray: #6B7280;
    --bg-page: #F9FAFB;
}

/* ---------------- GLOBAL ---------------- */
body {
    margin: 0;
    background: var(--bg-page);
    font-family: 'Poppins', sans-serif;
}

/* ---------------- HEADER ---------------- */
header {
    padding: 20px;
    background: var(--white);
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 15px;
}

header a {
    font-size: 1.7rem;
    color: var(--primary);
    text-decoration: none;
}

header h1 {
    font-size: 1.3rem;
    color: var(--primary-dark);
    margin: 0;
}

/* ---------------- CARD ---------------- */
.register-box {
    max-width: 400px;
    margin: 50px auto;
    padding: 35px;
    background: var(--white);
    border-radius: 16px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.register-box h2 {
    text-align: center;
    font-size: 1.8rem;
    color: var(--primary-dark);
    margin-bottom: 25px;
}

/* ---------------- INPUTS ---------------- */
.input-group {
    margin-bottom: 18px;
}

.input-group label {
    font-weight: 600;
    margin-bottom: 6px;
    display: block;
}

.input-group input {
    width: 100%;
    padding: 12px;
    border-radius: 10px;
    border: 2px solid var(--primary);
    outline: none;
    font-size: 16px;
}

.input-group input:focus {
    border-color: var(--primary-dark);
}

/* ---------------- BUTTON ---------------- */
.btn-register {
    width: 100%;
    padding: 14px;
    background: var(--primary);
    border: none;
    color: var(--white);
    font-size: 18px;
    font-weight: 600;
    border-radius: 12px;
    cursor: pointer;
    margin-top: 10px;
    transition: .25s;
}

.btn-register:hover {
    background: var(--primary-dark);
    transform: scale(1.04);
}

/* ---------------- ERRO & SUCESSO ---------------- */
.error {
    background: #ffe3e3;
    color: var(--danger);
    padding: 10px;
    text-align: center;
    border-radius: 8px;
    margin-bottom: 15px;
    font-weight: 600;
}

.success {
    background: #d1fae5;
    color: var(--success);
    padding: 10px;
    text-align: center;
    border-radius: 8px;
    margin-bottom: 15px;
    font-weight: 600;
}

/* ---------------- LOGIN LINK ---------------- */
.login-link {
    text-align: center;
    margin-top: 15px;
}

.login-link a {
    color: var(--primary);
    font-weight: 600;
    text-decoration: none;
}

.login-link a:hover {
    color: var(--primary-dark);
}

/* ---------------- RESPONSIVO ---------------- */
@media (max-width: 500px) {
    .register-box {
        margin: 30px 15px;
        padding: 28px;
    }
}

</style>

</head>
<body>

<header>
    <a href="login.php"><i class="ph ph-arrow-left"></i></a>
    <h1>Criar nova conta</h1>
</header>

<div class="register-box">

    <h2>Cadastro</h2>

    <?php if ($erro != ''): ?>
        <p class="error"><?php echo $erro; ?></p>
    <?php endif; ?>

    <?php if ($sucesso != ''): ?>
        <p class="success"><?php echo $sucesso; ?></p>
    <?php endif; ?>

    <form method="POST">

        <div class="input-group">
            <label>Email</label>
            <input type="email" name="email" placeholder="Digite seu email" required>
        </div>

        <div class="input-group">
            <label>Senha</label>
            <input type="password" name="senha" placeholder="Digite sua senha" required>
        </div>

        <div class="input-group">
            <label>Confirmar Senha</label>
            <input type="password" name="confirmar_senha" placeholder="Confirme sua senha" required>
        </div>

        <button type="submit" class="btn-register">Cadastrar</button>

        <p class="login-link">Já possui conta?  
            <a href="login.php">Entrar</a>
        </p>
    </form>

</div>

</body>
</html>
