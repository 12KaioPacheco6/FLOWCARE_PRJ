<?php
require_once "../includes/banco-de-dados.inc.php";
require_once "../includes/cadastrar-clientes.inc.php"; // Certifique-se de que sua classe Cliente está aqui

$banco = new BancoDeDados("localhost", "root", "", "flowcare", "categoria", "marca", "produto", "comentario", "cliente");
$conexao = $banco->criarConexao();
$banco->abrirBanco($conexao);
$banco->definirCharset($conexao);

$clienteObj = new Usuario(); // Assumindo que você tem uma classe Cliente

$mensagem_cadastro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pegar dados do formulário
    $nome = trim(filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $telefone = trim(filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $email = trim(filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL));
    $senha = $_POST['senha']; // Senha pura do formulário
    $confirmar_senha = $_POST['confirmar_senha'];

    if (empty($nome) || empty($email) || $email === false || empty($senha) || empty($confirmar_senha)) {
        $mensagem_cadastro = "<div class='alert alert-danger'>Por favor, preencha todos os campos obrigatórios.</div>";
    } elseif ($senha !== $confirmar_senha) {
        $mensagem_cadastro = "<div class='alert alert-danger'>As senhas não coincidem.</div>";
    } else {
        // Hashear a senha antes de armazenar no banco de dados
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        // Chamar o método de cadastro da classe Cliente
        // Adapte isso para o seu método de cadastro de cliente.
        // Se você não tiver um método na classe Cliente, pode fazer a inserção aqui.
        $sql = "INSERT INTO " . $banco->nomeDaTabela5 . " (nome, telefone, email, senha) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conexao, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssss", $nome, $telefone, $email, $senha_hash);

            if (mysqli_stmt_execute($stmt)) {
                $mensagem_cadastro = "<div class='alert alert-success'>Cadastro realizado com sucesso! Você já pode <a href='login.php'>fazer login</a>.</div>";
            } else {
                // Verificar se o erro é de duplicidade de email
                if (mysqli_errno($conexao) == 1062) { // 1062 é o código de erro para chave duplicada (UNIQUE constraint)
                    $mensagem_cadastro = "<div class='alert alert-danger'>Este e-mail já está cadastrado.</div>";
                } else {
                    $mensagem_cadastro = "<div class='alert alert-danger'>Erro ao cadastrar: " . mysqli_error($conexao) . "</div>";
                }
            }
            mysqli_stmt_close($stmt);
        } else {
            $mensagem_cadastro = "<div class='alert alert-danger'>Erro na preparação da consulta de cadastro.</div>";
        }
    }
}

$banco->fecharBanco($conexao);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Cliente - FlowCare</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/cadastro-cliente.css">
</head>
<body>
<header>
    <nav class="navbar bg-body-tertiary fixed-top" style="background-color: #e3f2fd;" data-bs-theme="light">
<div class="container-fluid">
            <a class="navbar-brand" href="#">FlowCare</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">

                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="../php/index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="../php/login.php">login</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Categorias
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Cabelo</a></li>
                                <li><a class="dropdown-item" href="#"></a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="#">Skincare</a></li>
                            </ul>
                        </li>
                    </ul>
                    <form class="d-flex mt-3" role="search">
                        <input class="form-control me-2" type="search" placeholder="Encontre seu produto" aria-label="Search"/>
                        <button class="btn btn-outline-success" type="submit">Buscar</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
</header>

<main class="content">
    <div class="container py-4 mt-5 mx-auto p-2" style="max-width: 600px;">
        <h1 class="text-center mb-4">Cadastro de Cliente</h1>

        <?php echo $mensagem_cadastro; ?>

        <form action="cadastrar-cliente.php" method="POST">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome:</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
            </div>
            <div class="mb-3">
                <label for="telefone" class="form-label">Telefone (opcional):</label>
                <input type="text" class="form-control" id="telefone" name="telefone">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">E-mail:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="senha" class="form-label">Senha:</label>
                <input type="password" class="form-control" id="senha" name="senha" required>
            </div>
            <div class="mb-3">
                <label for="confirmar_senha" class="form-label">Confirmar Senha:</label>
                <input type="password" class="form-control" id="confirmar_senha" name="confirmar_senha" required>
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-success">Cadastrar</button>
                <p class="text-center mt-3">Já tem conta? <a href="login.php">Faça login aqui</a></p>
            </div>
        </form>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
</body>
</html>