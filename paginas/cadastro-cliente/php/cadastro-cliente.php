<?php
    require_once "../includes/banco-de-dados-clientes.inc.php";
    require_once "../includes/cadastrar-clientes.inc.php";

    $banco = new BancoDeDados("localhost", "root", "","flowcare","clientes");

    $conexao = $banco->criarConexao();
    $banco->criarBanco($conexao);
    $banco->abrirBanco($conexao);
    $banco->definirCharset($conexao);
    //$banco->criarTabelaProdutos($conexao);
    $banco->criarTabelaClientes($conexao);


    //criando objeto cliente
    $objCliente = new Clientes();
    //testar se o botão de cadastro foi acionado
    if(isset($_POST["cadastrar-cliente"]))
    {
        $objCliente->receberDadosDoFormulario($conexao);
        $objCliente->cadastrar($conexao,$banco->nomeDaTabelaClientes);
    }

    $banco->desconectar($conexao);

   ?>