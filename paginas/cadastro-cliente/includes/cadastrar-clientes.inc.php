<?php

 class Clientes
  {
  public $nome;
  public $email;
  public $telefone;
  public $senha;
  public $confirmarSenha;

  function receberDadosDoFormulario($conexao)
   {
  
   $this->nome               = trim($conexao->escape_string($_POST["cliente"]));
   $this->email              = trim($conexao->escape_string($_POST["email"]));
   $this->telefone           = trim($conexao->escape_string($_POST["celular"]));
   $this->senha              = trim($conexao->escape_string($_POST["senha"]));
    $this->confirmarSenha    = trim($conexao->escape_string($_POST["confirmarSenha"]));

   }

  function cadastrar($conexao, $nomeDaTabela)
   {
   $sql = "INSERT $nomeDaTabela VALUES(
             '$this->nome',
             '$this->email',
             '$this->telefone',
             '$this->login',
             '$this->senha',
             '$this->confirmarSenha')";

   $conexao->query($sql) or die($conexao->error);
   echo "<p> Cliente cadastrado com sucesso no banco de dados. </p>";
   }
   }