<?php

class Produto
{
    private $nome;
    private $marca;
    private $local;
    private $categoria;
    private $preco;
    private $descricao;

    function receberDadosDoFormulario($conexao){
        $this->nome = trim($conexao->escape_string($_POST["nome"]));
        $this->marca = trim($conexao->escape_string($_POST["marca"]));
        $this->local = trim($conexao->escape_string($_POST["local"]));
        $this->categoria = trim($conexao->escape_string($_POST["categoria"]));
        $this->preco = trim($conexao->escape_string($_POST["preco"]));
        $this->descricao = trim($conexao->escape_string($_POST["descricao"]));
    }

    function cadastrar($conexao, $nomeDaTabela3)
    {
        // Usando prepared statements para segurança e boas práticas
        $sql = "INSERT INTO " . $nomeDaTabela3 . " (nome, marca, local, categoria, preco, descricao) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conexao, $sql);

        // **CORREÇÃO AQUI:** Faça o cast para float ANTES de passar para bind_param
        $preco_float = (float)$this->preco; // Crie uma variável temporária com o valor float

        // 's' para string, 'd' para double (decimal). Se 'f' era para float, 'd' é o correto para DECIMAL no MySQL.
        // Se a coluna preco no seu DB é DECIMAL(10,2), 'd' é o tipo correto no bind_param.
        mysqli_stmt_bind_param($stmt, "ssssds", // Use 'd' para DECIMAL/DOUBLE, não 'f'
            $this->nome,
            $this->marca,
            $this->local,
            $this->categoria,
            $preco_float, // Passe a variável $preco_float
            $this->descricao
        );

        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo "<script>alert('Produto cadastrado com sucesso!');</script>";
        } else {
            echo "<script>alert('Erro ao cadastrar produto: " . mysqli_error($conexao) . "');</script>"; // Adiciona o erro do MySQL
        }
        mysqli_stmt_close($stmt); // Fechar o statement
    }

    // ... (restante da classe Produto, sem alterações) ...
    public function obterProdutoPorId($conexao, $tabelaProduto, $id) {
        // Seleciona 'id' (sem alias), pois é o nome da coluna no DB
        $query = "SELECT id, nome, marca, local, categoria, preco, descricao FROM " . $tabelaProduto . " WHERE id = ?";
        $stmt = mysqli_prepare($conexao, $query);
        mysqli_stmt_bind_param($stmt, "i", $id); // 'i' para inteiro
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($result && mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result); // Retorna array com chave 'id'
        }
        return null; // Retorna null se o produto não for encontrado
    }

    // Método para listar todos os produtos (usado em index.php)
    public function listarTodosProdutos($conexao, $tabelaProduto) {
        // Seleciona todas as colunas necessárias para o index e usa alias 'id_produto'
        $query = "SELECT id AS id_produto, nome, preco, marca, local, categoria, descricao FROM " . $tabelaProduto;
        $result = mysqli_query($conexao, $query);
        if ($result) {
            return $result; // Retorna o objeto mysqli_result
        }
        return null;
    }
    
    // NOVO MÉTODO: Listar comentários para um produto específico
    public function listarComentariosPorProduto($conexao, $tabelaComentario, $tabelaCliente, $id_produto) {
        $sql = "SELECT c.comentario, c.data_comentario, cli.nome AS nome_cliente
                FROM " . $tabelaComentario . " c
                JOIN " . $tabelaCliente . " cli ON c.id_cliente = cli.id
                WHERE c.id_produto = ?
                ORDER BY c.data_comentario DESC"; // Ordenar pelos mais recentes

        $stmt = mysqli_prepare($conexao, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $id_produto);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            return $result; // Retorna o objeto mysqli_result
        }
        return null;
    }
}