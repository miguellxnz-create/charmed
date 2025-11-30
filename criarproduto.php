<?php
session_start();
include('conexao2.php'); // conexão com o banco de produtos

$msg = "";

if (isset($_POST['cadastrar'])) {

    $nome = $_POST['nome'];
    $preco = $_POST['preco'];
    $descricao = $_POST['descricao'];

   // Upload da imagem
    if (!empty($_FILES['imagem']['name'])) {

        $img_nome = $_FILES['imagem']['name'];
        $img_tmp = $_FILES['imagem']['tmp_name'];

        // Caminho da pasta onde suas imagens ficam (PASTA /img/)
        $destino = "img/" . $img_nome;

        // Move a imagem para a pasta
        if (move_uploaded_file($img_tmp, $destino)) {

            // INSERE NO BANCO
            $sql = "INSERT INTO produtos (nome, preco, descrição, imagem)
                    VALUES ('$nome', '$preco', '$descricao', '$img_nome')";

            if ($mysqli->query($sql)) {
                $msg = "✔ Produto cadastrado com sucesso!";
            } else {
                $msg = "Erro ao cadastrar no banco: " . $mysqli->error;
            }

        } else {
            $msg = "❌ Erro ao enviar a imagem!";
        }

    } else {
        $msg = "❌ Você precisa selecionar uma imagem!";
    }
}

// EXCLUIR PRODUTO
if (isset($_GET['excluir'])) {
    $id_excluir = intval($_GET['excluir']);
    // Pega a imagem para excluir do servidor
    $res = $mysqli->query("SELECT imagem FROM produtos WHERE id = $id_excluir");
    if ($res->num_rows > 0) {
        $produto = $res->fetch_assoc();
        if (file_exists('img/'.$produto['imagem'])) unlink('img/'.$produto['imagem']);
    }
    $mysqli->query("DELETE FROM produtos WHERE id = $id_excluir");
    header("Location: criarproduto.php");
    exit;
}

// PEGAR PRODUTOS
$result = $mysqli->query("SELECT * FROM produtos ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Painel de Produtos • Charmed da Rebeca</title>
<style>
body { font-family: Arial, Helvetica, sans-serif; background: #f7f7f7; margin:0; padding:0;}
.container { width: 900px; margin: 30px auto; background: #fff; padding: 30px; border-radius: 15px; box-shadow:0 5px 15px rgba(0,0,0,0.2);}
h1 { text-align:center; color:#7d2cff; margin-bottom:20px;}
input[type="text"], input[type="number"], textarea, input[type="file"] {
    width:100%; padding:10px; margin-bottom:15px; border-radius:8px; border:1px solid #ccc; box-sizing:border-box;
}
button { width:100%; padding:12px; background: linear-gradient(135deg, #7d2cff, #a64fff); border:none; color:white; font-size:18px; font-weight:bold; border-radius:35px; cursor:pointer; transition:0.3s ease;}
button:hover { background: linear-gradient(135deg, #a64fff, #c27fff); transform:scale(1.05);}
.msg { text-align:center; margin-bottom:15px; color:green; font-weight:bold;}
.table-produtos { width:100%; border-collapse: collapse; margin-top:30px;}
.table-produtos th, .table-produtos td { border:1px solid #ccc; padding:10px; text-align:center;}
.table-produtos img { width:80px; height:80px; border-radius:8px;}
.action-btn { padding:5px 10px; border:none; border-radius:5px; cursor:pointer; color:white; }
.edit { background: #4CAF50; }
.delete { background: #f44336; }
</style>
</head>
<body>
<div class="container">
    <h1>Painel de Produtos</h1>

    <?php if ($msg) echo "<div class='msg'>$msg</div>"; ?>

    <h2>Adicionar Produto</h2>
    
<form method="POST" enctype="multipart/form-data">

    <label>Nome do Produto:</label>
    <input type="text" name="nome" required>

    <label>Preço:</label>
    <input type="number" step="0.01" name="preco" required>

    <label>Descrição:</label>
    <textarea name="descricao" rows="4" required></textarea>

    <label>Imagem do Produto:</label>
    <input type="file" name="imagem" accept="image/*" required>

    <button type="submit" name="cadastrar">Cadastrar Produto</button>

</form>


    <h2>Produtos Cadastrados</h2>
    <table class="table-produtos">
        <tr>
            <th>ID</th>
            <th>Imagem</th>
            <th>Nome</th>
            <th>Preço</th>
            <th>Descrição</th>
            <th>Ações</th>
        </tr>
        <?php while($produto = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $produto['id']; ?></td>
            <td><img src="img/<?php echo $produto['imagem']; ?>" alt="<?php echo $produto['nome']; ?>"></td>
            <td><?php echo $produto['nome']; ?></td>
            <td>R$ <?php echo number_format($produto['preco'],2,',','.'); ?></td>
            <td><?php echo $produto['descrição']; ?></td>
            <td>
                <a href="editarproduto.php?id=<?php echo $produto['id']; ?>"><button class="action-btn edit">Editar</button></a>
                <a href="?excluir=<?php echo $produto['id']; ?>" onclick="return confirm('Deseja realmente excluir este produto?');"><button class="action-btn delete">Excluir</button></a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>
 