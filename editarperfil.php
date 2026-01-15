<?php
session_start();
include('conexao.php');


if (!isset($_SESSION['codigo'])) {
    header("Location: login.php");
    exit;
}

$codigo = $_SESSION['codigo'];


$sql = $mysqli->query("SELECT * FROM usuario WHERE codigo = '$codigo'");
$usuario = $sql->fetch_assoc();


if (isset($_POST['nome'])) {

    $nome = $mysqli->real_escape_string($_POST['nome']);
    $email = $mysqli->real_escape_string($_POST['email']);

    if (!empty($_POST['senha'])) {
        $senhaNova = $mysqli->real_escape_string($_POST['senha']);
        $sqlUpdate = "UPDATE usuario SET nome='$nome', Email='$email', senha='$senhaNova' WHERE codigo='$codigo'";
    } else {
        $sqlUpdate = "UPDATE usuario SET nome='$nome', Email='$email' WHERE codigo='$codigo'";
    }

    if ($mysqli->query($sqlUpdate)) {
        $_SESSION['nome'] = $nome;
        echo "<script>alert('✔ Perfil atualizado com sucesso!'); window.location='painel.php';</script>";
        exit;
    } else {
        echo "<script>alert('Erro ao atualizar: " . $mysqli->error . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Editar Perfil • Charmed da Rebeca</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<script src="https://unpkg.com/@phosphor-icons/web"></script>

<style>

:root {
    --primary: #8B5CF6;
    --primary-dark: #7C3AED;
    --white: #ffffff;
    --bg-page: #F9FAFB;
    --gray: #6B7280;
    --danger: #ef4444;
}

body {
    margin: 0;
    background: var(--bg-page);
    font-family: 'Poppins', sans-serif;
}


header {
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    background: var(--white);
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
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

.edit-box {
    max-width: 420px;
    margin: 50px auto;
    padding: 35px;
    background: var(--white);
    border-radius: 18px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.1);
}

.edit-box h2 {
    text-align: center;
    margin-bottom: 25px;
    color: var(--primary-dark);
    font-size: 1.8rem;
}


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
    border-radius: 12px;
    font-size: 16px;
    border: 2px solid var(--primary);
    outline: none;
}

.input-group input:focus {
    border-color: var(--primary-dark);
}


.btn-save {
    width: 100%;
    padding: 14px;
    font-size: 18px;
    font-weight: 600;
    border: none;
    border-radius: 12px;
    background: var(--primary);
    color: var(--white);
    cursor: pointer;
    margin-top: 10px;
    transition: .25s;
}

.btn-save:hover {
    background: var(--primary-dark);
    transform: scale(1.03);
}


@media (max-width: 500px) {
    .edit-box {
        margin: 30px 15px;
        padding: 28px;
    }
}

</style>

</head>
<body>

<header>
    <a href="painel.php"><i class="ph ph-arrow-left"></i></a>
    <h1>Editar Perfil</h1>
</header>

<div class="edit-box">

    <h2>Meus Dados</h2>

    <form method="POST">

        <div class="input-group">
            <label>Nome completo</label>
            <input type="text" name="nome" required value="<?php echo $usuario['nome']; ?>">
        </div>

        <div class="input-group">
            <label>Email</label>
            <input type="email" name="email" required value="<?php echo $usuario['Email']; ?>">
        </div>

        <div class="input-group">
            <label>Nova senha (opcional)</label>
            <input type="password" name="senha" placeholder="Deixe em branco para não alterar">
        </div>

        <button type="submit" class="btn-save">Salvar alterações</button>

    </form>

</div>

</body>
</html>

