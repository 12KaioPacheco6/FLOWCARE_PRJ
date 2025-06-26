<?php

require_once "../includes/cadastrar-clientes.inc.php";
require_once "../includes/banco-de-dados-cliente.inc.php";

  $banco = new BancoDeDados("localhost", "root", "", "flowcare", "categoria", "marca", "produto", "comentario", "usuario");

  //criar a conexão física com o servidor MySQL
  $conexao = $banco->criarConexao();

  //o próximo passo é criar o banco de dados, de fato, no servidor
  $banco->criarBanco($conexao);

  //agora, vamos abrir o banco de dados criado
  $banco->abrirBanco($conexao);

  //definindo o utf-8 como tabela de símbolos do MySQL
  $banco->definirCharset($conexao);

  //invocando o método para criar a tabela 
  $banco->criarTabelaUsuario($conexao);

  //a partir desse ponto no arquivo principal, vamos criar o objeto Cadastrar Produtos e invocar os métodos da classe cadastrar-produto.inc.php
  $usuario = new Usuario();

  //para cadastrarmos o dados do objeto Cadastrar Produtos no banco, precisamos fazer com o PHP teste se o botão de cadastro foi acionado no formulário
  if(isset($_POST["cadastrar-cliente"])) {
      //se o botão foi acionado, vamos receber os dados do formulário e armazená-los no objeto Cliente
      $usuario->receberDadosDoFormulario($conexao);
      $usuario->cadastrar($conexao, $banco->nomeDaTabela5);
  }
  else {
      $mensagem = "Erro ao cadastrar o cliente. Por favor, tente novamente.";
  }

  //após finalizar toda a execução da nossa aplicação, "matamos" a conexão com o MySQL
  $banco->desconectar($conexao);

?> 