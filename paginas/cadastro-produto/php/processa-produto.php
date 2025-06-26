<?php

class Produto
{
    private $nome;
    private $marca;
    private $local;
    private $categoria;
    private $preco;
    private $descricao;

    function receberDadosFormularios($conexao)
    {
        $this->nome = trim($conexao->escape_string($_POST['nome']));
        $this->marca = trim($conexao->escape_string($_POST['marca-produto']));
        $this->local = trim($conexao->escape_string($_POST['local']));
        $this->categoria = trim($conexao->escape_string($_POST['categoria']));
        $this->preco = trim($conexao->escape_string($_POST['preco']));
        $this->descricao = trim($conexao->escape_string($_POST['descricao']));
    }

    function cadastrarProduto($conexao)
    {
        $sql = "INSERT INTO $produto VALUES (
                        '$this->nome',
                        '$this->marca',
                        '$this->local',
                        '$this->categoria',
                        $this->preco,
                        '$this->descricao')";
        $produto = $conexao->query($sql) or die ($conexao->error);
        $vetorProduto = $produto->fetch_arry()

        function mostrarProdutos($conexao){
            $nomeProduto = $vetorProduto[0];
            $marcaProduto = $vetorProduto[1];
            $localProduto = $vetorProduto[2];
            $categoriaProduto = $vetorProduto[3];
            $precoProduto = $vetorProduto[4];
            $descricaoProduto = $vetorProduto[5];

            echo "<p> Seu produto foi Cadastrado. </p>"
        }
    }

    function atualizarProduto($conexao)
    {
        $sql = "UPDATE $produto SET nome = '$this->nome', local = '$this->local', categoria = '$this->categoria', 
                 $this->preco = '$this->preco', $this->descricao = '$this->descricao' 
                 WHERE id_categoria = $this->categoria";
        $produto = $conexao->query($sql) or die ($conexao->error);
    }

    function mostrarComentarios($conexao)
    {
        $sql = "SELECT $descricao FROM $produto WHERE id_descricao = $this->descricao";
        $produto = $conexao->query($sql) or die ($conexao->error);

        $vetorComentarios = $produto->fetch_array();
        return $vetorComentarios;
    }
}
?>