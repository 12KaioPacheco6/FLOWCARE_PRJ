<?php

class Produto
{
    private $id; // Adicionei um ID para seguir o formato do banco de dados
    private $nome;
    private $marca;
    private $local;
    private $categoria;
    private $preco;
    private $descricao;

    //construtor da classe Produto
    // function __construct($nome = "", $marca = "", $local = "", $categoria = "", $preco = 0.0, $descricao = "")
    // {
    //     $this->nome       = $nome;
    //     $this->marca      = $marca;
    //     $this->local      = $local;
    //     $this->categoria  = $categoria;
    //     $this->preco      = $preco;
    //     $this->descricao  = $descricao;
    // }

    //implementar o método que recebe os dados do formulário e insere esses dados em cada atributo do objeto
    function receberDadosDoFormulario($conexao)
    {
        //AVISO: cuidado ao receber dados de um formulário e enviá-los ao banco de dados. Se o seu código não contiver os comandos apropriados, o servidor estará sujeito ao tipo de invasão conhecido como INJEÇÃO DE SQL
        $this->nome = trim($conexao->escape_string($_POST["nome"]));
        $this->marca = trim($conexao->escape_string($_POST["marca"]));
        $this->local = trim($conexao->escape_string($_POST["local"]));
        $this->categoria = trim($conexao->escape_string($_POST["categoria"]));
        $this->preco = trim($conexao->escape_string($_POST["preco"]));
        $this->descricao = trim($conexao->escape_string($_POST["descricao"]));
    }

    //método que cadastra os dados do objeto aluno no banco de dados
    function cadastrar($conexao, $nomeDaTabela3)
    {
        // Coloquei o id como NULL, pois ele é auto-incremento no banco de dados
        $sql = "INSERT $nomeDaTabela3 VALUES(
                null,
                '$this->nome',
                '$this->marca',
                '$this->local',
                '$this->categoria',
                '$this->preco',
                '$this->descricao'
                )";
        $conexao->query($sql) or die($conexao->error);
    }
}
?>