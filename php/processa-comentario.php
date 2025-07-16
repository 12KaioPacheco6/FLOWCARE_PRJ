<?php
session_start();
require_once "../includes/banco-de-dados.inc.php";
require_once "../includes/cadastrar-produto.inc.php"; // Necessário para a classe Produto (embora não a usemos diretamente aqui, é um bom hábito incluir)

$banco = new BancoDeDados(
    "localhost", "root", "", "flowcare", "categoria", "marca", "produto", "comentario", "cliente");
$conexao = $banco->criarConexao();
$banco->abrirBanco($conexao);
$banco->definirCharset($conexao);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $acao = isset($_POST['acao']) ? $_POST['acao'] : 'adicionar'; // Define uma ação padrão 'adicionar' se não for especificada

    // Redireciona para o produto atual ou index se o ID do produto não for válido
    $id_produto = filter_input(INPUT_POST, 'id_produto', FILTER_VALIDATE_INT);
    if ($id_produto === false || $id_produto === null) {
        header("Location: index.php");
        exit();
    }

    // Lógica para Adicionar Comentário
if ($acao == 'adicionar') {
    $id_cliente = isset($_SESSION['id_cliente']) ? $_SESSION['id_cliente'] : null;
    $comentario_texto = trim($_POST['comentario_texto']);

    if ($id_cliente === null || empty($comentario_texto)) { // <--- A CONDIÇÃO QUE ESTÁ SENDO ATIVADA
        echo "<script>alert('Erro: Dados do comentário inválidos ou incompletos.'); window.location.href='informacao-produto.php?id=" . $id_produto . "';</script>";
        exit();
    }

        $sql = "INSERT INTO " . $banco->nomeDaTabela4 . " (id_produto, id_cliente, comentario) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conexao, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "iis", $id_produto, $id_cliente, $comentario_texto);

            if (mysqli_stmt_execute($stmt)) {
                echo "<script>alert('Comentário adicionado com sucesso!'); window.location.href='informacao-produto.php?id=" . $id_produto . "';</script>";
            } else {
                echo "<script>alert('Erro ao adicionar comentário: " . mysqli_error($conexao) . "'); window.location.href='informacao-produto.php?id=" . $id_produto . "';</script>";
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "<script>alert('Erro na preparação da declaração para adicionar: " . mysqli_error($conexao) . "'); window.location.href='informacao-produto.php?id=" . $id_produto . "';</script>";
        }
    } 
    // Lógica para Editar Comentário
    elseif ($acao == 'editar') {
        $id_comentario = filter_input(INPUT_POST, 'id_comentario', FILTER_VALIDATE_INT);
        $id_cliente_sessao = isset($_SESSION['id_cliente']) ? $_SESSION['id_cliente'] : null;
        $novo_comentario_texto = trim($_POST['comentario_texto']);

        if ($id_comentario === false || $id_comentario === null || $id_cliente_sessao === null || empty($novo_comentario_texto)) {
            echo "<script>alert('Erro: Dados para edição inválidos ou incompletos.'); window.location.href='informacao-produto.php?id=" . $id_produto . "';</script>";
            exit();
        }

        // Primeiro, verifique se o comentário pertence ao cliente logado para evitar que editem comentários de outros
        $sql_check = "SELECT id_cliente FROM " . $banco->nomeDaTabela4 . " WHERE id = ? AND id_produto = ?";
        $stmt_check = mysqli_prepare($conexao, $sql_check);
        mysqli_stmt_bind_param($stmt_check, "ii", $id_comentario, $id_produto);
        mysqli_stmt_execute($stmt_check);
        $resultado_check = mysqli_stmt_get_result($stmt_check);
        $comentario_existente = mysqli_fetch_assoc($resultado_check);
        mysqli_stmt_close($stmt_check);

        if ($comentario_existente && $comentario_existente['id_cliente'] == $id_cliente_sessao) {
            $sql_update = "UPDATE " . $banco->nomeDaTabela4 . " SET comentario = ? WHERE id = ?";
            $stmt_update = mysqli_prepare($conexao, $sql_update);

            if ($stmt_update) {
                mysqli_stmt_bind_param($stmt_update, "si", $novo_comentario_texto, $id_comentario);

                if (mysqli_stmt_execute($stmt_update)) {
                    echo "<script>alert('Comentário atualizado com sucesso!'); window.location.href='informacao-produto.php?id=" . $id_produto . "';</script>";
                } else {
                    echo "<script>alert('Erro ao atualizar comentário: " . mysqli_error($conexao) . "'); window.location.href='informacao-produto.php?id=" . $id_produto . "';</script>";
                }
                mysqli_stmt_close($stmt_update);
            } else {
                echo "<script>alert('Erro na preparação da declaração para edição: " . mysqli_error($conexao) . "'); window.location.href='informacao-produto.php?id=" . $id_produto . "';</script>";
            }
        } else {
            echo "<script>alert('Você não tem permissão para editar este comentário ou o comentário não existe.'); window.location.href='informacao-produto.php?id=" . $id_produto . "';</script>";
        }
    }
    // Lógica para Excluir Comentário
    elseif ($acao == 'excluir') {
        $id_comentario = filter_input(INPUT_POST, 'id_comentario', FILTER_VALIDATE_INT);
        $id_cliente_sessao = isset($_SESSION['id_cliente']) ? $_SESSION['id_cliente'] : null;

        if ($id_comentario === false || $id_comentario === null || $id_cliente_sessao === null) {
            echo "<script>alert('Erro: Dados para exclusão inválidos ou incompletos.'); window.location.href='informacao-produto.php?id=" . $id_produto . "';</script>";
            exit();
        }

        // Primeiro, verifique se o comentário pertence ao cliente logado
        $sql_check = "SELECT id_cliente FROM " . $banco->nomeDaTabela4 . " WHERE id = ? AND id_produto = ?";
        $stmt_check = mysqli_prepare($conexao, $sql_check);
        mysqli_stmt_bind_param($stmt_check, "ii", $id_comentario, $id_produto);
        mysqli_stmt_execute($stmt_check);
        $resultado_check = mysqli_stmt_get_result($stmt_check);
        $comentario_existente = mysqli_fetch_assoc($resultado_check);
        mysqli_stmt_close($stmt_check);

        if ($comentario_existente && $comentario_existente['id_cliente'] == $id_cliente_sessao) {
            $sql_delete = "DELETE FROM " . $banco->nomeDaTabela4 . " WHERE id = ?";
            $stmt_delete = mysqli_prepare($conexao, $sql_delete);

            if ($stmt_delete) {
                mysqli_stmt_bind_param($stmt_delete, "i", $id_comentario);

                if (mysqli_stmt_execute($stmt_delete)) {
                    echo "<script>alert('Comentário excluído com sucesso!'); window.location.href='informacao-produto.php?id=" . $id_produto . "';</script>";
                } else {
                    echo "<script>alert('Erro ao excluir comentário: " . mysqli_error($conexao) . "'); window.location.href='informacao-produto.php?id=" . $id_produto . "';</script>";
                }
                mysqli_stmt_close($stmt_delete);
            } else {
                echo "<script>alert('Erro na preparação da declaração para exclusão: " . mysqli_error($conexao) . "'); window.location.href='informacao-produto.php?id=" . $id_produto . "';</script>";
            }
        } else {
            echo "<script>alert('Você não tem permissão para excluir este comentário ou o comentário não existe.'); window.location.href='informacao-produto.php?id=" . $id_produto . "';</script>";
        }
    }
} else {
    // Redireciona se não for um POST request direto
    header("Location: index.php");
    exit();
}

$banco->fecharBanco($conexao);
?>