<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Cliente</title>
    <link rel="stylesheet" href="css/edicao.css">
    
</head>
<body class="light-theme">

<main class="container">
    <?php include_once __DIR__ . '/header.php';
 ?>
    
    <h1>Cadastrar Cliente</h1>

    <form method="POST" name="formCadastro" action="clientes/cliente_sql.php">

        <!-- Dados Pessoais -->
        <fieldset class="form-section">
            <legend>Dados Pessoais</legend>

            <div class="form-group">
                <label for="nome">Nome Completo</label>
            <input type="text" name="txtNome" id="nome" placeholder="Nome completo" required>
                <span class="erro" id="erro-nome"></span>
            </div>

            <div class="form-group">
                <label for="cpf">CPF</label>
                <input type="text" name="txtCPF" id="cpf" maxlength="14" placeholder="000.000.000-00" required pattern="\d{3}\.\d{3}\.\d{3}-\d{2}">
                <span class="erro" id="erro-cpf"></span>
            </div>

            <div class="form-group">
                <label for="endereco">Endereço</label>
                <textarea name="txtEndereco" id="endereco" rows="3" placeholder="Rua, número, bairro, cidade, etc." required></textarea>
                <span class="erro" id="erro-endereco"></span>
                
            </div>

            <div class="form-group">
                <label for="estado">Estado</label>
                <select name="listEstados" id="estado" required>
                    <option value="" disabled selected>Selecione um estado</option>
                    <option value="BA">Bahia</option>
                    <option value="ES">Espírito Santo</option>
                    <option value="MG">Minas Gerais</option>
                    <option value="RJ">Rio de Janeiro</option>
                    <option value="SP">São Paulo</option>
                </select>
                <span class="erro" id="erro-estado"></span>
            </div>

            <div class="form-group">
                <label for="dtnasc">Data de Nascimento</label>
                <input type="date" name="cxcData" id="dtnasc" required>
                <span class="erro" id="erro-dtnasc"></span>
            </div>

            <div class="form-group">
                <label for="sexo">Sexo</label>
                <select name="sexo" id="sexo" required>
                    <option value="" disabled selected>Selecione o sexo</option>
                    <option value="Masc">Masculino</option>
                    <option value="Fem">Feminino</option>
                </select>
                <span class="erro" id="erro-sexo"></span>
            </div>
        </fieldset>

        <!-- Interesses -->
       <fieldset class="form-section">
            <legend>Áreas de Interesse</legend>

        <div class="form-checkbox">
            <input type="checkbox" name="checkMusica" id="musica" value="true">
            <label for="musica">Música</label>
        </div>
        <div class="form-checkbox">
            <input type="checkbox" name="checkCinema" id="cinema" value="true">
            <label for="cinema">Cinema</label>
        </div>
        
        <div class="form-checkbox">
            <input type="checkbox" name="checkInfo" id="info" value="true">
            <label for="info">Informática</label>
        </div>

        <span class="erro" id="erro-interesses"></span>
        </fieldset>

        <!-- Conta de Acesso -->
        <fieldset class="form-section">
            <legend>Informações de Acesso</legend>

            <div class="form-group">
                <label for="login">Login</label>
                <input type="text" name="cxtLogin" id="login" required>
                <span class="erro" id="erro-login"></span>
            </div>

            <div class="form-group">
                <label for="senha1">Senha</label>
                <input type="password" name="cxcSenha" id="senha1" required>
            </div>

            <div class="form-group">
                <label for="senha2">Confirme a Senha</label>
                <input type="password" name="cxcSenha2" id="senha2" required>
                <span class="erro" id="erro-senha"></span>
            </div>
        </fieldset>

        <!-- Ações -->
        <div class="form-actions">
            <input type="submit" name="btnEnviar" value="Enviar">
            <input type="reset" name="btnLimpar" value="Limpar">
        </div>

    </form>
</main>

<script>
    // Máscara de CPF
    const cpfInput = document.querySelector('input[name="txtCPF"]');
    cpfInput.addEventListener('input', function (e) {
        let value = this.value.replace(/\D/g, '');

        if (value.length > 11) value = value.slice(0, 11);

        value = value
            .replace(/^(\d{3})(\d)/, '$1.$2')
            .replace(/^(\d{3})\.(\d{3})(\d)/, '$1.$2.$3')
            .replace(/\.(\d{3})(\d)/, '.$1-$2');

        this.value = value;
    });

    // Validação de senhas
    function validarFormulario() {
    const senha1 = document.getElementById('senha1').value;
    const senha2 = document.getElementById('senha2').value;
    if (senha1 !== senha2) {
        document.getElementById('erro-senha').textContent = 'As senhas não conferem.';
        return false;
    } else {
        document.getElementById('erro-senha').textContent = '';
    }
    return true;
}
</script>

    <script src="validacao/validar.js"></script>

</body>
</html>
