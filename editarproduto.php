<?php
session_start();
include('conexao2.php'); // conexão com o banco de produtos

if (!isset($_GET['id'])) {
    die("Produto não especificado!");
}

$produto_id = intval($_GET['id']);

// PEGAR DADOS DO PRODUTO
$res = $mysqli->query("SELECT * FROM produtos WHERE id = $produto_id");
if ($res->num_rows == 0) {
    die("Produto não encontrado!");
}

$produto = $res->fetch_assoc();
$msg = "";

// ATUALIZAR PRODUTO
if (isset($_POST['atualizar_produto'])) {
    $nome = $mysqli->real_escape_string($_POST['nome']);
    $preco = floatval($_POST['preco']);
    $descricao = $mysqli->real_escape_string($_POST['descrição']);

    $sql = "UPDATE produtos SET nome='$nome', preco=$preco, descrição='$descricao'";

    // Verifica se enviou nova imagem
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
        $pasta = 'img/';
        if (!is_dir($pasta)) mkdir($pasta, 0755, true);
        $arquivo = basename($_FILES['imagem']['name']);
        $extensao = strtolower(pathinfo($arquivo, PATHINFO_EXTENSION));
        $novo_nome = uniqid() . '.' . $extensao;
        $caminho = $pasta . $novo_nome;

        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho)) {
            // Deleta imagem antiga
            if (file_exists('img/'.$produto['imagem'])) unlink('img/'.$produto['imagem']);
            $sql .= ", imagem='$novo_nome'";
        } else {
            $msg = "Erro ao enviar nova imagem.";
        }
    }

    $sql .= " WHERE id = $produto_id";

    if ($mysqli->query($sql)) {
        $msg = "Produto atualizado com sucesso!";
        // Atualiza dados do produto na página
        $res = $mysqli->query("SELECT * FROM produtos WHERE id = $produto_id");
        $produto = $res->fetch_assoc();
    } else {
        $msg = "Erro ao atualizar produto: " . $mysqli->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Editar Produto • Charmed da Rebeca</title>
<style>
body { font-family: Arial, Helvetica, sans-serif; background: #f7f7f7; margin:0; padding:0;}
.container { width: 600px; margin: 30px auto; background: #fff; padding: 30px; border-radius: 15px; box-shadow:0 5px 15px rgba(0,0,0,0.2);}
h1 { text-align:center; color:#7d2cff; margin-bottom:20px;}
input[type="text"], input[type="number"], textarea, input[type="file"] {
    width:100%; padding:10px; margin-bottom:15px; border-radius:8px; border:1px solid #ccc; box-sizing:border-box;
}
button { width:100%; padding:12px; background: linear-gradient(135deg, #7d2cff, #a64fff); border:none; color:white; font-size:18px; font-weight:bold; border-radius:35px; cursor:pointer; transition:0.3s ease;}
button:hover { background: linear-gradient(135deg, #a64fff, #c27fff); transform:scale(1.05);}
.msg { text-align:center; margin-bottom:15px; color:green; font-weight:bold;}
img { max-width:200px; margin-bottom:15px; border-radius:12px; }
</style>
</head>
<body>
<div class="container">
    <h1>Editar Produto</h1>

    <?php if ($msg) echo "<div class='msg'>$msg</div>"; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Nome do Produto:</label>
        <input type="text" name="nome" value="<?php echo htmlspecialchars($produto['nome']); ?>" required>

        <label>Preço:</label>
        <input type="number" name="preco" value="<?php echo $produto['preco']; ?>" step="0.01" required>

        <label>Descrição:</label>
        <textarea name="descrição" rows="4" required><?php echo htmlspecialchars($produto['descrição']); ?></textarea>

        <label>Imagem Atual:</label><br>
        <img src="img/<?php echo $produto['imagem']; ?>" alt="<?php echo $produto['nome']; ?>"><br>

        <label>Nova Imagem (opcional):</label>
        <input type="file" name="imagem" accept="image/*">

        <button type="submit" name="atualizar_produto">Atualizar Produto</button>
    </form>

    <p style="text-align:center; margin-top:20px;">
        <a href="criarproduto.php" style="text-decoration:none; color:#7d2cff;">&larr; Voltar ao Painel de Produtos</a>
    </p>
</div>
</body>
</html>
