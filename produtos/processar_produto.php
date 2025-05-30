<?php
// Função para validar os dados do produto
function validarProduto($descricao, $preco, $qtdeEstoque, $dataValidade) {
    $erros = [];
    
    // Validar descrição
    if (empty($descricao)) {
        $erros[] = "A descrição do produto é obrigatória.";
    } elseif (strlen($descricao) > 100) {
        $erros[] = "A descrição deve ter no máximo 100 caracteres.";
    }
    
    // Validar preço
    if (!is_numeric($preco) || $preco <= 0) {
        $erros[] = "O preço deve ser um valor numérico positivo.";
    }
    
    // Validar quantidade em estoque
    if (!is_numeric($qtdeEstoque) || $qtdeEstoque < 0 || !is_int((int)$qtdeEstoque)) {
        $erros[] = "A quantidade em estoque deve ser um número inteiro não negativo.";
    }
    
    // Validar data de validade
    $dataAtual = date('Y-m-d');
    if (empty($dataValidade)) {
        $erros[] = "A data de validade é obrigatória.";
    } elseif ($dataValidade < $dataAtual) {
        $erros[] = "A data de validade não pode ser anterior à data atual.";
    }
    
    return $erros;
}

// Inicializar variáveis
$descricao = $preco = $qtdeEstoque = $dataValidade = "";
$mensagem = "";
$tipoMensagem = "";
$erros = [];

// Processar o formulário quando enviado via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obter e limpar os dados do formulário
    $descricao = trim(htmlspecialchars($_POST["descricao"] ?? ''));
    $preco = filter_var($_POST["preco"] ?? 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $qtdeEstoque = filter_var($_POST["qtdeEstoque"] ?? 0, FILTER_SANITIZE_NUMBER_INT);
    $dataValidade = $_POST["dataValidade"] ?? '';
    
    // Validar os dados
    $erros = validarProduto($descricao, $preco, $qtdeEstoque, $dataValidade);
    
    // Se não houver erros, inserir no banco de dados
    if (empty($erros)) {
        try {
            // Criar conexão usando o arquivo MySQL.php
            $conn = require_once '../MySQL.php';
            
            // Verificar conexão
            if ($conn->connect_error) {
                throw new Exception("Falha na conexão com o banco de dados: " . $conn->connect_error);
            }
            
            // Preparar e executar a query
            $stmt = $conn->prepare("INSERT INTO produtos (descricao, preco, qtdeEstoque, dataValidade) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sdis", $descricao, $preco, $qtdeEstoque, $dataValidade);
            
            if ($stmt->execute()) {
                $mensagem = "Produto cadastrado com sucesso!";
                $tipoMensagem = "sucesso";
                
                // Limpar os campos após o cadastro bem-sucedido
                $descricao = $preco = $qtdeEstoque = $dataValidade = "";
            } else {
                throw new Exception("Erro ao cadastrar produto: " . $stmt->error);
            }
            
            $stmt->close();
            $conn->close();
            
        } catch (Exception $e) {
            $mensagem = $e->getMessage();
            $tipoMensagem = "erro";
        }
    }
}
?>
<?php

include_once '../config.php'; 

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Processamento de Cadastro de Produto</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/processar_produto.css" />
</head>
<body class="light-theme">
    <main class="container">
        <?php include_once(__DIR__ . '/../header.php');
 ?>

        <h1>Processamento de Cadastro de Produto</h1>
        
        <?php if (!empty($mensagem)): ?>
            <div class="mensagem <?php echo $tipoMensagem; ?>">
                <?php echo $mensagem; ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($erros)): ?>
            <div class="erros">
                <h3>Foram encontrados os seguintes erros:</h3>
                <ul>
                    <?php foreach ($erros as $erro): ?>
                        <li><?php echo $erro; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <div class="links">
            <a href="cadastro_produto.php" class="btn-voltar">Voltar ao Cadastro</a>
            <a href="pesquisar_produtos.php" class="btn-pesquisar">Pesquisar Produtos</a>
        </div>
    </main>

    <script>
        // Script para alternar entre temas claro e escuro
        const toggleBtn = document.getElementById('toggle-theme');
        const body = document.body;

        window.onload = () => {
            const savedTheme = localStorage.getItem('theme') || 'light-theme';
            body.classList.add(savedTheme);
        }

        toggleBtn.addEventListener('click', () => {
            if (body.classList.contains('dark-theme')) {
                body.classList.replace('dark-theme', 'light-theme');
                localStorage.setItem('theme', 'light-theme');
            } else {
                body.classList.replace('light-theme', 'dark-theme');
                localStorage.setItem('theme', 'dark-theme');
            }
        });
    </script>
</body>
</html>
