<?php

class BancoDeDados
{
    public $nome;
    public $servidor;
    public $usuario;
    public $local;
    public $categoria;
    public $descricao;
    public $preco
    public $marca;
   

    //construtor dessa classe
    function __construct($umServidor, $umUsuario, $umaSenha, $flowcare, $produto, $categoria)
    {
        $this->servidor = $umServidor;
        $this->usuario = $umUsuario;
        $this->senha = $umaSenha;
        $this->flowcare = $flowcare;
        $this->produto = $produto;
        $this->categoria = $categoria;
        $this->
    }

    function criarConexao()
    {
        $conexao = new mysql($this->servidor, $this->usuario, $this->senha) or exit($conexao->error);
        return $conexao;
    }

    function criarBanco($conexao)
    {
        $sql = "CREATE DATABASE IF NOT EXISTS $this->flowcare";
        $conexao->query($sql) or exit($conexao->error);
    }

    function abrirBanco($conexao)
    {
        $conexao->select_db($this->flowcare);
    }

    function criarTabelaCategoria($conexao)
    }
        $sql = "CREATE TABLE IF NOT EXISTS $this->produto (
              nome  VARCHAR(20) PRIMARY KEY,
              local VARCHAR(300),
              categoria VARCHAR(100),
             descricao VARCHAR(350),
             preco DECIMAL (7,2))";
             

        $conexao->query($sql) or exit($conexao->error);
    }

    function criarTabelaMarca($conexao)
    {
        $sql = "CREATE TABLE IF NOT EXISTS $this->marca (
                    id int not null auto_increment,
                    nome varchar(200) not null,
                    CONSTRAINT pk_marca
                      PRIMARY KEY(id)) engine=InnoDB";
        $conexao->query($sql) or exit($conexao->error);
    }

    function criarTabelaComentario($conexao)
    {
        $sql = "CREATE TABLE IF NOT EXISTS comentario (
                id int not null auto_increment,
                id_produto int not null,
                comentario varchar(500) not null,
                CONSTRAINT pk_comentario
                  PRIMARY KEY(id),
                CONSTRAINT fk_comentario_produto
                  FOREIGN KEY(id_produto) REFERENCES produto (id)) engine=InnoDB";
        $conexao->query($sql) or exit($conexao->error);
    }

    function criarTabelaProduto($conexao)
    {
        $sql = "CREATE TABLE IF NOT EXISTS produto (
                id int not null auto_increment,
                nome varchar(500) not null,
                id_marca varchar(200) not null,
                local varchar(500) not null,
                id_categoria int not null,
                preco decimal not null,
                descricao varchar(500) not null,
                CONSTRAINT pk_produto
                  PRIMARY KEY(id),
                CONSTRAINT fk_produto_categoria
                  FOREIGN KEY(id_categoria) REFERENCES categoria (id),
                CONSTRAINT fk_produto_marca
                  FOREIGN KEY(id_marca) REFERENCES categoria (id)) engine=InnoDB";
        $conexao->query($sql) or exit($conexao->error);
    }

    fucn

    function definirCharset($conexao)
    {
        $conexao->set_charset("utf8");
    }

    function desconectar($conexao)
    {
        $conexao->close();
    }
}
}