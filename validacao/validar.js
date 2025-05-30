// Função que limpa todas as mensagens de erro na tela
function limparErros() {
    const erros = document.querySelectorAll('.erro'); // Seleciona todos os elementos com a classe 'erro'
    erros.forEach(erro => erro.textContent = ''); // Limpa o conteúdo de texto de cada erro
}

// Função para validar CPF (com cálculo dos dígitos verificadores)
function validarCPF(cpf) {
    cpf = cpf.replace(/[^\d]+/g, ''); // Remove tudo que não for número
    if (cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) return false; // Verifica se tem 11 dígitos ou se todos são iguais

    // Cálculo dos dois últimos dígitos verificadores
    for (let t = 9; t < 11; t++) {
        let d = 0;
        for (let c = 0; c < t; c++) {
            d += parseInt(cpf.charAt(c)) * ((t + 1) - c);
        }
        d = (d * 10) % 11;
        if (d === 10 || d === 11) d = 0;
        if (d !== parseInt(cpf.charAt(t))) return false; // Se o dígito não bate, CPF inválido
    }

    return true; // CPF válido
}

// Função principal que valida o formulário antes do envio
function validarFormulario() {
    limparErros(); // Limpa mensagens de erro anteriores

    let valido = true; // Variável de controle: se for falsa, o formulário não será enviado

    // Pegando os valores dos campos do formulário
    const nome = document.getElementById("nome").value.trim();
    const cpf = document.getElementById("cpf").value.trim();
    const endereco = document.getElementById("endereco").value.trim();
    const estado = document.getElementById("estado").value;
    const dataNasc = document.getElementById("dtnasc").value;
    const sexo = document.getElementById("sexo").value;
    const login = document.getElementById("login").value.trim();
    const senha = document.getElementById("senha1").value;
    const senha2 = document.getElementById("senha2").value;

    // Interesses marcados
    const interesseCinema = document.getElementById("cinema").checked;
    const interesseMusica = document.getElementById("musica").checked;
    const interesseInfo = document.getElementById("info").checked;

    // Expressão regular para validar nomes (apenas letras e espaços)
    const regexNome = /^[A-Za-zÀ-ÿ\s]+$/;

    // Validação do nome
    if (nome === "") {
        document.getElementById("erro-nome").textContent = "Por favor, preencha o nome.";
        valido = false;
    } else if (!regexNome.test(nome)) {
        document.getElementById("erro-nome").textContent = "O nome deve conter apenas letras e espaços.";
        valido = false;
    }

    // Validação do endereço
    if (endereco === "") {
        document.getElementById("erro-endereco").textContent = "Por favor, preencha o endereço.";
        valido = false;
    } else if (!validarEndereco(endereco)) { // Aqui espera-se que exista a função validarEndereco()
        document.getElementById("erro-endereco").textContent = "Endereço inválido.";
        valido = false;
    }

    // Validação do estado (select)
    if (estado === "") {
        document.getElementById("erro-estado").textContent = "Selecione um estado.";
        valido = false;
    }

    // Validação do CPF
    if (cpf === "") {
        document.getElementById("erro-cpf").textContent = "Preencha o CPF.";
        valido = false;
    } else if (!validarCPF(cpf)) {
        document.getElementById("erro-cpf").textContent = "CPF inválido.";
        valido = false;
    }

    // Validação da data de nascimento
    if (!dataNasc) {
        document.getElementById("erro-dtnasc").textContent = "Preencha a data de nascimento.";
        valido = false;
    } else {
        const hoje = new Date();
        const dataInformada = new Date(dataNasc);
        if (dataInformada > hoje) {
            document.getElementById("erro-dtnasc").textContent = "Data de nascimento não pode ser no futuro.";
            valido = false;
        }
    }

    // Validação do sexo
    if (sexo === "") {
        document.getElementById("erro-sexo").textContent = "Selecione o sexo.";
        valido = false;
    }

    // Pelo menos um interesse deve estar selecionado
    if (!interesseCinema && !interesseMusica && !interesseInfo) {
        document.getElementById("erro-interesses").textContent = "Selecione pelo menos um interesse.";
        valido = false;
    }

    // Validação do login
    if (login === "") {
        document.getElementById("erro-login").textContent = "Informe o login.";
        valido = false;
    }

    // Validação das senhas
    if (senha === "" || senha2 === "") {
        document.getElementById("erro-senha").textContent = "Preencha os dois campos de senha.";
        valido = false;
    } else if (senha !== senha2) {
        document.getElementById("erro-senha").textContent = "As senhas não conferem.";
        valido = false;
    }

    return valido; // Se for falso, o envio do formulário será bloqueado
}
