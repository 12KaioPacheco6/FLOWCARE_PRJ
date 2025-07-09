<?php
require_once "../includes/banco-de-dados.inc.php";
require_once "../includes/cadastrar-produto.inc.php"; // Necessário para a classe Produto (embora não a usemos diretamente aqui, é um bom hábito incluir)

// Inclua a classe Comentario se você for criá-la (Recomendado!)
// Por enquanto, vamos fazer direto, mas idealmente teríamos uma classe Comentario
 require_once "../includes/cadastrar-comentario.inc.php";

$banco = new BancoDeDados(
    "localhost", "root", "", "flowcare", "categoria", "marca", "produto", "comentario", "cliente");
$conexao = $banco->criarConexao();
$banco->abrirBanco($conexao);
$banco->definirCharset($conexao);

// Verifica se os dados foram enviados via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Valida e sanitiza os inputs
    $id_produto = filter_input(INPUT_POST, 'id_produto', FILTER_VALIDATE_INT);
    $id_cliente = filter_input(INPUT_POST, 'id_cliente', FILTER_VALIDATE_INT); // IMPORTANT: Este 'id_cliente' está como "SEU_ID_DO_CLIENTE_AQUI". Você precisará implementar um sistema de login para obter o ID real do cliente logado. Por enquanto, pode usar um valor fixo para teste (ex: 1).
    $comentario_texto = trim(filter_input(INPUT_POST, 'comentario_texto', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

    // Validação básica
    if ($id_produto === false || $id_produto === null || $id_cliente === false || $id_cliente === null || empty($comentario_texto)) {
        echo "<script>alert('Erro: Dados do comentário inválidos ou incompletos.'); window.location.href='index.php';</script>";
        exit();
    }

    // Usar prepared statements para inserir o comentário
    $sql = "INSERT INTO " . $banco->nomeDaTabela4 . " (id_produto, id_cliente, comentario) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conexao, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "iis", $id_produto, $id_cliente, $comentario_texto);

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Comentário adicionado com sucesso!'); window.location.href='detalhes_produto.php?id=" . $id_produto . "';</script>";
        } else {
            echo "<script>alert('Erro ao adicionar comentário: " . mysqli_error($conexao) . "'); window.location.href='detalhes_produto.php?id=" . $id_produto . "';</script>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('Erro na preparação da declaração: " . mysqli_error($conexao) . "'); window.location.href='detalhes_produto.php?id=" . $id_produto . "';</script>";
    }
} else {
    // Redireciona se não for um POST request direto
    header("Location: index.php");
    exit();
}

$banco->fecharBanco($conexao);
?>