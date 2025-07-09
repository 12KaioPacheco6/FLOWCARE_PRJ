<?php

class BancoDeDados
{
    public $nomeDoBanco;
    public $nomeDaTabela1; // Categoria
    public $nomeDaTabela2; // Marca
    public $nomeDaTabela3; // Produto
    public $nomeDaTabela4; // Comentario
    public $nomeDaTabela5; // Cliente
    public $servidor;
    public $usuario;
    public $senha;
    public $conexao; // Propriedade para armazenar a conexão

    function __construct($umServidor, $umUsuario, $umaSenha, $umBanco, $umaTabelaCategoria, $umaTabelaMarca, $umaTabelaProduto, $umaTabelaComentario, $umaTabelaCliente){
        $this->servidor          = $umServidor;
        $this->usuario           = $umUsuario;
        $this->senha             = $umaSenha;
        $this->nomeDoBanco       = $umBanco;
        $this->nomeDaTabela1     = $umaTabelaCategoria;
        $this->nomeDaTabela2     = $umaTabelaMarca;
        $this->nomeDaTabela3     = $umaTabelaProduto;
        $this->nomeDaTabela4     = $umaTabelaComentario;
        $this->nomeDaTabela5     = $umaTabelaCliente;
    }

    function criarConexao()
    {
        $this->conexao = new mysqli($this->servidor, $this->usuario, $this->senha);
        if ($this->conexao->connect_error) {
            die("Erro na conexão: " . $this->conexao->connect_error);
        }
        return $this->conexao;
    }

    function criarBanco($conexao)
    {
        $sql = "CREATE DATABASE IF NOT EXISTS " . $this->nomeDoBanco . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
        $conexao->query($sql) or die("Erro ao criar banco de dados: " . $conexao->error);
    }

    function abrirBanco($conexao)
    {
        $conexao->select_db($this->nomeDoBanco) or die("Erro ao selecionar banco de dados: " . $conexao->error);
    }

    // TABELA CATEGORIA (Removi as colunas que pareciam pertencer a Produto)
    function criarTabelaCategoria($conexao)
    {
        $sql = "CREATE TABLE IF NOT EXISTS " . $this->nomeDaTabela1 . " (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(20) NOT NULL UNIQUE
            ) engine=InnoDB";
        $conexao->query($sql) or die("Erro ao criar tabela categoria: " . $conexao->error);
    }

    // TABELA MARCA
    function criarTabelaMarca($conexao)
    {
        $sql = "CREATE TABLE IF NOT EXISTS " . $this->nomeDaTabela2 . " (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(200) NOT NULL UNIQUE
            ) engine=InnoDB";
        $conexao->query($sql) or die("Erro ao criar tabela marca: " . $conexao->error);
    }

    // TABELA PRODUTO
    function criarTabelaProduto($conexao)
    {
        $sql = "CREATE TABLE IF NOT EXISTS " . $this->nomeDaTabela3 . " (
                id INT NOT NULL AUTO_INCREMENT,
                nome VARCHAR(500) NOT NULL,
                marca VARCHAR(200) NOT NULL,
                local VARCHAR(500) NOT NULL,
                categoria VARCHAR(200) NOT NULL,
                preco DECIMAL(10,2) NOT NULL,
                descricao VARCHAR(500) NOT NULL,
                CONSTRAINT pk_produto
                  PRIMARY KEY(id)
                ) engine=InnoDB";
        $conexao->query($sql) or die("Erro ao criar tabela produto: " . $conexao->error);
    }

    // TABELA CLIENTE
    function criarTabelaCliente($conexao)
    {
        $sql = "CREATE TABLE IF NOT EXISTS " . $this->nomeDaTabela5 . " (
                id INT NOT NULL AUTO_INCREMENT,
                nome VARCHAR(500) NOT NULL,
                telefone VARCHAR(200),
                email VARCHAR(500) NOT NULL UNIQUE,
                senha VARCHAR(255) NOT NULL,
                CONSTRAINT pk_cliente
                  PRIMARY KEY(id)
                ) engine=InnoDB";
        $conexao->query($sql) or die("Erro ao criar tabela cliente: " . $conexao->error);
    }

    // TABELA COMENTÁRIO
    function criarTabelaComentario($conexao)
    {
        $sql = "CREATE TABLE IF NOT EXISTS " . $this->nomeDaTabela4 . " (
                id INT NOT NULL AUTO_INCREMENT,
                id_produto INT NOT NULL,
                id_cliente INT NOT NULL,
                comentario TEXT NOT NULL,
                data_comentario TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                CONSTRAINT pk_comentario
                  PRIMARY KEY(id),
                CONSTRAINT fk_comentario_produto
                  FOREIGN KEY(id_produto) REFERENCES " . $this->nomeDaTabela3 . " (id) ON DELETE CASCADE,
                CONSTRAINT fk_comentario_cliente
                  FOREIGN KEY(id_cliente) REFERENCES " . $this->nomeDaTabela5 . " (id) ON DELETE CASCADE
                ) engine=InnoDB";
        $conexao->query($sql) or die("Erro ao criar tabela comentário: " . $conexao->error);
    }

    function definirCharset($conexao)
    {
        $conexao->set_charset("utf8mb4"); // Use utf8mb4 para melhor compatibilidade com emojis etc.
    }

    function fecharBanco($conexao)
    {
        if ($conexao) {
            $conexao->close();
        }
    }
}   