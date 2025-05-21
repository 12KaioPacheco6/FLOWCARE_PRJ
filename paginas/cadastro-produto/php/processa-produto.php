<?php

class Produto {

    private $nome;
    private $local;
    private $categoria;
    private $preco;
    private $descricao;

    function receberDadosFormularios($conexao){
        $this->nome = trim($conexao->escape_string($_POST['nome']));
        $this->local = trim($conexao->escape_string($_POST['local']));
        $this->categoria = trim($conexao->escape_string($_POST['categoria']));
        $this->preco = trim($conexao->escape_string($_POST['preco']));
        $this->descricao = trim($conexao->escape_string($_POST['descricao']));

    }

    function cadastrarProduto($conexao){
        $sql = "INSERT INTO $produto VALUES (
                        '$this->nome',
                        '$this->local',
                        '$this->categoria',
                        '$this->preco',
                        '$this->descricao')";
        $produto = $conexao->query($sql) or die ($conexao->error);
    }

    function atualizarProduto($conexao){
        $sql = "UPDATE $produto SET $this->nome = '$this->nome', local = '$this->local', categoria = '$this->categoria', 
                 $this->preco = '$this->preco', $this->descricao = '$this->descricao' 
                 WHERE nome = '$this->nome'";
        $produto = $conexao->query($sql) or die ($conexao->error);
    }

    function mostrarComentarios($conexao){
        $sql = "SELECT $descricao FROM $produto WHERE nome = '$this->nome'";
        $produto = $conexao->query($sql) or die ($conexao->error);
    }

}

?>