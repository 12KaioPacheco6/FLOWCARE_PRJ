<?php
session_start(); // Inicia a sessão PHP no início de cada página que a usa

// Se o usuário já estiver logado, redireciona para a página principal
if (isset($_SESSION['id_cliente'])) {
    header("Location: index.php");
    exit();
}

// Inclui o arquivo do banco de dados (será necessário para o processamento do login)
require_once "../includes/banco-de-dados.inc.php";
require_once "../includes/cadastrar-produto.inc.php"; // Necessário para a classe Produto, se for usar algo dela no cabeçalho ou rodapé

$banco = new BancoDeDados("localhost", "root", "", "flowcare", "categoria", "marca", "produto", "comentario", "cliente");
$conexao = $banco->criarConexao();
$banco->abrirBanco($conexao);
$banco->definirCharset($conexao);

$mensagem_erro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim(filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL));
    $senha_digitada = trim(filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

    if (empty($email) || $email === false || empty($senha_digitada)) {
        $mensagem_erro = "Por favor, preencha todos os campos e use um e-mail válido.";
    } else {
        // Prepare a consulta para buscar o cliente pelo email
        $sql = "SELECT id, nome, email, senha FROM " . $banco->nomeDaTabela5 . " WHERE email = ?";
        $stmt = mysqli_prepare($conexao, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($result && mysqli_num_rows($result) > 0) {
                $cliente = mysqli_fetch_assoc($result);
                // ATENÇÃO: Em um sistema real, a senha armazenada deve ser HASHEADA (ex: password_hash())
                // e a verificação deve ser feita com password_verify().
                // Para simplificar o exemplo, vamos comparar a string literal por enquanto,
                // MAS VOCÊ DEVE MUDAR ISSO PARA HASHEAR AS SENHAS!
                if ($senha_digitada === $cliente['senha']) { // Esta comparação é INSEGURA para produção
                    // Login bem-sucedido! Armazena informações na sessão
                    $_SESSION['id_cliente'] = $cliente['id'];
                    $_SESSION['nome_cliente'] = $cliente['nome'];
                    $_SESSION['email_cliente'] = $cliente['email'];

                    // Redireciona para a página principal ou outra página após o login
                    header("Location: index.php");
                    exit();
                } else {
                    $mensagem_erro = "Senha incorreta.";
                }
            } else {
                $mensagem_erro = "E-mail não encontrado.";
            }
            mysqli_stmt_close($stmt);
        } else {
            $mensagem_erro = "Erro na preparação da consulta de login.";
        }
    }
}

$banco->fecharBanco($conexao); // Fechar a conexão após o processamento
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - FlowCare</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/formata-projeto.css">
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
                            <a class="nav-link active" aria-current="page" href="../php/cadastrar-produto.php">Cadastrar Produto</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="../php/cadastrar-cliente.php">login</a>
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
    <div class="container py-4 mt-5 mx-auto p-2" style="max-width: 500px;">
        <h1 class="text-center mb-4">Login</h1>

        <?php if (!empty($mensagem_erro)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $mensagem_erro; ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">E-mail:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="senha" class="form-label">Senha:</label>
                <input type="password" class="form-control" id="senha" name="senha" required>
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Entrar</button>
                <p class="text-center mt-3">Ainda não tem conta? <a href="cadastrar-cliente.php">Cadastre-se aqui</a></p>
            </div>
        </form>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
</body>
</html>