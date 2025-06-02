<?php
  require_once "../includes/paginas/cadastro-produto/includes/cadastrar-produto.inc.php";
  require_once "../includes/paginas/cadastro-produto/includes/banco-de-dados-produto.inc.php";

  

  //criar o objeto banco de dados que, nesse momento, armazena a conexão com o servidor, inicializando o construtor da nossa classe
  $banco = new BancoDeDados("localhost", "root", "");

  //criar a conexão física com o servidor MySQL
  $conexao = $banco->criarConexao();

  //o próximo passo é criar o banco de dados, de fato, no servidor
  $banco->criarBanco($conexao);

  //agora, vamos abrir o banco de dados criado
  $banco->abrirBanco($conexao);

  //definindo o utf-8 como tabela de símbolos do MySQL
  $banco->definirCharset($conexao);

  //invocando o método para criar a tabela 
  $banco->criarTabela($conexao);

  //a partir desse ponto no arquivo principal, vamos criar o objeto Cadastrar Produtos e invocar os métodos da classe cadastrar-produto.inc.php
  $produto = new Produto();

  //para cadastrarmos o dados do objeto Cadastrar Produtos no banco, precisamos fazer com o PHP teste se o botão de cadastro foi acionado no formulário
if(isset($_POST["cadastrar"])) {
   $produto->receberDadosDoFormulario($conexao);
   $produto->cadastrar($conexao, $banco->produto);
   echo "<p> Produtos foram cadastrados com sucesso no banco de dados. </p>";
}

  //após finalizar toda a execução da nossa aplicação, "matamos" a conexão com o MySQL
  $banco->desconectar($conexao);
 ?>                       