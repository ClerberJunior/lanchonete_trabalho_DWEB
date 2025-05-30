<?php
$conn = new mysqli("127.0.0.1", "root", "root", "lanchecleber");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$erro = "";
$sucesso = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $descricao = $_POST["descricao"];
    $preco = $_POST["preco"];
    $estoque = $_POST["qtdeEstoque"];

    $stmt = $conn->prepare("INSERT INTO produtos (nome, preco, descricao, estoque, ativo) VALUES (?, ?, ?, ?, 1)");
    $stmt->bind_param("sdsi", $nome, $preco, $descricao, $estoque);

    if ($stmt->execute()) {
        $sucesso = "Produto cadastrado com sucesso!";
    } else {
        $erro = "Erro ao cadastrar: " . $stmt->error;
    }

    $stmt->close();
}
?>
<?php
include_once '../config.php'; 

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Cadastro de Produtos</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/cadastro_produto.css" />
    
</head>
<body class="light-theme">

<main class="container">
    <?php include_once(__DIR__ . '/../header.php');
 ?>
    
        <h1>Cadastro de Produtos</h1>

        <?php if ($erro): ?>
            <div style="color: red; margin-bottom: 20px;"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <?php if ($sucesso): ?>
            <div style="color: green; margin-bottom: 20px;"><?= htmlspecialchars($sucesso) ?></div>
        <?php endif; ?>

        <form action="" method="post" id="formProduto">
            <div class="form-group">
                <label for="nome">Nome do Produto:</label>
                <input type="text" id="nome" name="nome" required maxlength="50" />
            </div>

            <div class="form-group">
                <label for="descricao">Descrição:</label>
                <input type="text" id="descricao" name="descricao" required maxlength="100" />
            </div>

            <div class="form-group">
                <label for="preco">Preço:</label>
                <input type="number" id="preco" name="preco" step="0.01" min="0.01" required />
            </div>

            <div class="form-group">
                <label for="qtdeEstoque">Quantidade em Estoque:</label>
                <input type="number" id="qtdeEstoque" name="qtdeEstoque" min="0" required />
            </div>

            <div class="form-group buttons">
                <button type="submit" class="btn-cadastrar">Cadastrar</button>
                <button type="reset" class="btn-limpar">Limpar</button>
            </div>
        </form>
    </main>

    <script>
        const toggleBtn = document.getElementById('toggle-theme');
        const body = document.body;

        window.onload = () => {
            const savedTheme = localStorage.getItem('theme') || 'light-theme';
            body.classList.add(savedTheme);
        }

        toggleBtn.addEventListener('click', () => {
            body.classList.toggle('dark-theme');
            body.classList.toggle('light-theme');
            const theme = body.classList.contains('dark-theme') ? 'dark-theme' : 'light-theme';
            localStorage.setItem('theme', theme);
        });
    </script>
</body>
</html>
