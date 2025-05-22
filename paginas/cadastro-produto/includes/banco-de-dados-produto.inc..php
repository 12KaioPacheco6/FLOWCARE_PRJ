<?php

class BancoDeDados
{
    public $flowcare;
    public $produto;
    public $servidor;
    public $usuario;
    public $senha;

    //construtor dessa classe
    function __construct($umServidor, $umUsuario, $umaSenha, $flowcare, $produto)
    {
        $this->servidor = $umServidor;
        $this->usuario = $umUsuario;
        $this->senha = $umaSenha;
        $this->flowcare = $flowcare;
        $this->produto = $produto;
    }

    //método que cria a ligação do código em PHP com o MySQL no servidor
    function criarConexao()
    {
        $conexao = new mysqli($this->servidor, $this->usuario, $this->senha) or exit($conexao->error);
        return $conexao;
    }

    //criar o banco de dados físico no servidor - este método é opcional
    function criarBanco($conexao)
    {
        $sql = "CREATE DATABASE IF NOT EXISTS $this->flowcare";
        $conexao->query($sql) or exit($conexao->error);
    }

    //método para abrir o banco de dados
    function abrirBanco($conexao)
    {
        /*$sql = "USE $this->flowcare";
        $conexao->query($sql) or $conexao->error;*/
        $conexao->select_db($this->flowcare);
    }

    //método para criar a tabela no banco de dados
    function criarTabela($conexao)
    {
        $sql = "CREATE TABLE IF NOT EXISTS $this->produto (
              matricula VARCHAR(20) PRIMARY KEY,
              aluno VARCHAR(300),
              media DECIMAL(3,1))";
        $conexao->query($sql) or exit($conexao->error);
    }

    //método para padronizar a tabela de símbolos para toda a aplicação
    function definirCharset($conexao)
    {
        $conexao->set_charset("utf8");
    }

    //fechar a conexão com o banco
    function desconectar($conexao)
    {
        $conexao->close();
    }
}