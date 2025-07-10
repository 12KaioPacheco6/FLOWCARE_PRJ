<?php

class BancoDeDados
{
    // Variaveis de instancia para a construção do banco de dados
    public $nomeDoBanco;
    public $nomeDaTabela1;
    public $nomeDaTabela2;
    public $nomeDaTabela3;
    public $nomeDaTabela4;
    public $nomeDaTabela5;
    public $servidor;
    public $usuario;
    public $senha;

    /* 
        public $nome;
        public $servidor;
        public $usuario;
        public $local;
        public $categoria;
        public $descricao;
        public $preco;
        public $marca;
    */ 

    //construtor dessa classe 
    function __construct($umServidor, $umUsuario, $umaSenha, $umBanco, $umaTabela1, $umaTabela2, $umaTabela3, $umaTabela4, $umaTabela5){
            $this->servidor     = $umServidor;  // localhost
            $this->usuario      = $umUsuario;   // root
            $this->senha        = $umaSenha;    // ""
            $this->nomeDoBanco  = $umBanco;     // flowcare
            $this->nomeDaTabela1 = $umaTabela1;   // categoria
            $this->nomeDaTabela2 = $umaTabela2;   // marca
            $this->nomeDaTabela3 = $umaTabela3;   // produto
            $this->nomeDaTabela4 = $umaTabela4;   // comentario
            $this->nomeDaTabela5 = $umaTabela5;   // cliente
    }

    // Falar ao sistema o endereço, nome do adm e a senha do adm para acessar SGBD
    function criarConexao()
    {   
        // Esse mesmo procedimento ocorre ao entra no MySQL Workbench
        $conexao = new mysqli($this->servidor, $this->usuario, $this->senha) or exit($conexao->error);
        return $conexao;
    }

    // Após realizar a conexão, pode se criar o banco de dados, caso não exista
    function criarBanco($conexao)
    {
        $sql = "CREATE DATABASE IF NOT EXISTS $this->nomeDoBanco";
        $conexao->query($sql) or exit($conexao->error);
    }

    // Você vai ter criado o banco, mas ainda não está direcionando os comandos SQL para ele.
    // Geralmente usamos o "USE nome_do_banco" para isso.
    function abrirBanco($conexao)
    {
        $conexao->select_db($this->nomeDoBanco);
    }

    // Criar as tabelas do banco de dados, caso não existam
    function criarTabelaCategoria($conexao)
    {
        // local é uma palavra reservada do MySQL, então é necessário usar crase - Vou mudar para endereco
        // $this->produto troquei para $this->nomeDaTabela1 - nome será inserido durante a instancia do construtor
        $sql = "CREATE TABLE IF NOT EXISTS $this->nomeDaTabela1 (
            nome  VARCHAR(20) PRIMARY KEY,
            endereco VARCHAR(300),
            categoria VARCHAR(100),
            descricao VARCHAR(350),
            preco DECIMAL (7,2)) engine=InnoDB";
             
        $conexao->query($sql) or exit($conexao->error);
    }

    // Criar a tabela de marcas, caso não exista
    // $this->marca troquei para $this->nomeDaTabela2
    function criarTabelaMarca($conexao)
    {
        $sql = "CREATE TABLE IF NOT EXISTS $this->nomeDaTabela2 (
                    id int not null auto_increment,
                    nome varchar(200) not null,
                    CONSTRAINT pk_marca
                      PRIMARY KEY(id)) engine=InnoDB";
        $conexao->query($sql) or exit($conexao->error);
    }

    // Tabela com a associação de produtos com categorias e marcas
    // Criar a tabela de produtos, caso não exista
    // "produto" troquei para $this->nomeDaTabela3
    // function criarTabelaProduto($conexao)
    // {
    //     $sql = "CREATE TABLE IF NOT EXISTS $this->nomeDaTabela3 (
    //             id int not null auto_increment,
    //             nome varchar(500) not null,
    //             id_marca int not null,
    //             'local' varchar(500) not null,
    //             id_categoria int not null,
    //             preco decimal not null,
    //             descricao varchar(500) not null,
    //             CONSTRAINT pk_produto
    //               PRIMARY KEY(id),
    //             CONSTRAINT fk_produto_categoria
    //               FOREIGN KEY(id_categoria) REFERENCES categoria (id),
    //             CONSTRAINT fk_produto_marca
    //               FOREIGN KEY(id_marca) REFERENCES categoria (id)) engine=InnoDB";
    //     $conexao->query($sql) or exit($conexao->error);
    // }

    // Para o cadastro atual vamos manter sem a associação de produtos com categorias e marcas
    // Tabela temporaria para o cadastro de produtos
    function criarTabelaProduto($conexao)
    {
        $sql = "CREATE TABLE IF NOT EXISTS $this->nomeDaTabela3 (
                id int not null auto_increment,
                nome varchar(500) not null,
                marca varchar(200) not null,
                local varchar(500) not null,
                categoria varchar(200) not null,
                preco decimal not null,
                descricao varchar(500) not null,
                CONSTRAINT pk_produto
                  PRIMARY KEY(id)
                ) engine=InnoDB";
        $conexao->query($sql) or exit($conexao->error);
    }

    // Criar a tabela de comentários, caso não exista
    // $this->comentario troquei para $this->nomeDaTabela4
    function criarTabelaComentario($conexao) // Coloquei depois de produto, pois recebe o id do produto
    {
        $sql = "CREATE TABLE IF NOT EXISTS $this->nomeDaTabela4 (
                id int not null auto_increment,
                id_produto int not null,
                comentario varchar(500) not null,
                CONSTRAINT pk_comentario
                  PRIMARY KEY(id),
                CONSTRAINT fk_comentario_produto
                  FOREIGN KEY(id_produto) REFERENCES produto (id)) engine=InnoDB";
        $conexao->query($sql) or exit($conexao->error);
    }

    function criarTabelaUsuario($conexao)
    {
        $sql = "CREATE TABLE IF NOT EXISTS $this->nomeDaTabela5 (
                id int not null auto_increment,
                nome varchar(500) not null,
                telefone varchar(200) not null,
                email varchar(500) not null,
                senha varchar(200) not null,
                confirmaSenha varchar(200) not null,
                CONSTRAINT pk_produto
                  PRIMARY KEY(id)
                ) engine=InnoDB";
        $conexao->query($sql) or exit($conexao->error);
    }

    function definirCharset($conexao)
    {
        $conexao->set_charset("utf8");
    }

    function desconectar($conexao)
    {
        $conexao->close();
    }
}
