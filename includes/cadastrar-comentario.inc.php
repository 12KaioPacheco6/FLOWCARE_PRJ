<?php
class Comentario
{
    private $id;    
    // Atributos para armazenar os dados do comentário
    private $id_produto;
    private $comentario;

    function receberDadosDoFormulario($conexao)
    {
        $this->id_produto = trim($conexao->escape_string($_POST["produto-comentario"]));
        $this->comentario = trim($conexao->escape_string($_POST["comentario"]));
    }

    function cadastrarComentario($conexao, $nomeDaTabela4)
    {
        $sql = "INSERT INTO $nomeDaTabela4 VALUES (
            NULL,
            '$this->id_produto',
            '$this->comentario',
            FOREIGN KEY(id_produto) REFERENCES produto (id)
        )";
        $conexao->query($sql) or die("Erro ao cadastrar comentário: " . $conexao->error);

        if ($conexao->affected_rows > 0) {
            echo "<script>alert('Comentário cadastrado com sucesso!');</script>";
        } else {
            echo "<script>alert('Erro ao cadastrar comentário.');</script>";
        }
    }


}
