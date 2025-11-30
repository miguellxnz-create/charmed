<?php
session_start();
include('conexao.php');

$erro = '';

if (isset($_POST['email']) && isset($_POST['senha'])) {

    if (strlen($_POST['email']) == 0) {
        $erro = "Preencha seu e-mail";
    } else if (strlen($_POST['senha']) == 0) {
        $erro = "Preencha sua senha";
    } else {

        $email = $mysqli->real_escape_string($_POST["email"]);
        $senha = $mysqli->real_escape_string($_POST["senha"]);

        // LOGIN (SEM HASH)
        $sql_code = "SELECT * FROM usuario WHERE Email = '$email' AND senha = '$senha' LIMIT 1";
        $sql_query = $mysqli->query($sql_code);

        if ($sql_query->num_rows == 1) {
            $usuario = $sql_query->fetch_assoc();
            $_SESSION['codigo'] = $usuario['codigo'];
            $_SESSION['nome']  = $usuario['nome'];
            header("Location: painel.php");
            exit;
        } else {
            $erro = "E-mail ou senha incorretos!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login • Charmed da Rebeca</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<script src="https://unpkg.com/@phosphor-icons/web"></script>

<style>

:root {
    --primary: #8B5CF6;
    --primary-dark: #7C3AED;
    --secondary: #EDE9FE;
    --white: #fff;
    --danger: #ef4444;
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

/* ---------------- LOGIN CARD ---------------- */
.login-box {
    max-width: 380px;
    margin: 60px auto;
    padding: 35px;
    background: var(--white);
    border-radius: 16px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.login-box h2 {
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
.btn-login {
    width: 100%;
    padding: 14px;
    background: var(--primary);
    color: var(--white);
    font-size: 17px;
    font-weight: 600;
    border-radius: 12px;
    border: none;
    cursor: pointer;
    margin-top: 10px;
    transition: .25s;
}

.btn-login:hover {
    background: var(--primary-dark);
    transform: scale(1.04);
}

/* ---------------- ERRO ---------------- */
.error {
    background: #ffe3e3;
    color: var(--danger);
    padding: 10px;
    text-align: center;
    font-weight: bold;
    border-radius: 8px;
    margin-bottom: 15px;
}

/* ---------------- REGISTER LINK ---------------- */
.register {
    text-align: center;
    margin-top: 18px;
    font-size: 15px;
}

.register a {
    color: var(--primary);
    font-weight: 600;
    text-decoration: none;
}

.register a:hover {
    color: var(--primary-dark);
}

/* RESPONSIVO */
@media(max-width: 500px) {
    header h1 {
        font-size: 1.1rem;
    }
    .login-box {
        margin: 30px 15px;
        padding: 28px;
    }
}

</style>
</head>
<body>

<header>
    <a href="index.php"><i class="ph ph-arrow-left"></i></a>
    <h1>Entrar na sua conta</h1>
</header>

<div class="login-box">

    <h2>Login</h2>

    <?php if($erro != ''): ?>
    <p class="error"><?php echo $erro; ?></p>
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

        <button type="submit" class="btn-login">Entrar</button>

        <p class="register">
            Não possui conta?  
            <a href="criarconta.php">Criar conta</a>
        </p>

    </form>
</div>

</body>
</html>
