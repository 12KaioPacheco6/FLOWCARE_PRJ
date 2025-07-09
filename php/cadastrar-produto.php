<?php
  // Ajustei o caminho do arquivo para incluir corretamente
  require_once "../includes/cadastrar-produto.inc.php";
  require_once "../includes/banco-de-dados.inc.php";
  //criar o objeto banco de dados que, nesse momento, armazena a conexão com o servidor, inicializando o construtor da nossa classe
  //Passando o endereço do servidor, usuário, senha, nome do banco de dados e os nomes das tabelas que serão criadas
  $banco = new BancoDeDados("localhost", "root", "", "flowcare", "categoria", "marca", "produto", "comentario","cliente");

  //criar a conexão física com o servidor MySQL
  $conexao = $banco->criarConexao();

  //o próximo passo é criar o banco de dados, de fato, no servidor
  $banco->criarBanco($conexao);

  //agora, vamos abrir o banco de dados criado
  $banco->abrirBanco($conexao);

  //definindo o utf-8 como tabela de símbolos do MySQL
  $banco->definirCharset($conexao);

  //invocando o método para criar a tabela 
  $banco->criarTabelaProduto($conexao);

  //a partir desse ponto no arquivo principal, vamos criar o objeto Cadastrar Produtos e invocar os métodos da classe cadastrar-produto.inc.php
  $produto = new Produto();

  //para cadastrarmos o dados do objeto Cadastrar Produtos no banco, precisamos fazer com o PHP teste se o botão de cadastro foi acionado no formulário
  if(isset($_POST["cadastrar-produto"])) {
      $produto->receberDadosDoFormulario($conexao);
      $produto->cadastrar($conexao, $banco->nomeDaTabela3);

  }
  else {
      $mensagem = "Erro ao cadastrar o produto. Por favor, tente novamente.";
  }
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Flowcare - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/castro-produto.css">
</head>

<body>
<header>
    <nav class="navbar bg-body-tertiary fixed-top style=background-color: #e3f2fd; data-bs-theme=light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">FlowCare</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">

                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="../php/index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="../php/login.php">login</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Categorias
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Cabelo</a></li>
                                <li><a class="dropdown-item" href="#"></a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="#">Skincare</a></li>
                            </ul>
                        </li>
                    </ul>
                    <form class="d-flex mt-3" role="search">
                        <input class="form-control me-2" type="search" placeholder="Encontre seu produto" aria-label="Search"/>
                        <button class="btn btn-outline-success" type="submit">Buscar</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
</header>
<main class="content">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <div class="container py-4 mt-5 mx-auto p-2 style=width: 200px">
        <section class="cadastro-form">
            <h2>CADASTRAR PRODUTO – PREENCHA COM AS SUAS INFORMAÇÕES</h2>
            <div class="container-fluid">
                <form action="../php/cadastrar-produto.php" method="POST" class="form-produto" enctype="multipart/form-data">
      
                <label class="label-produto">Nome do produto: </label>
                <input name="nome">

                <label class="label-produto">Marca do Produto</label>
                <select name="marca">
                    <optgroup label="Beleza">
                        <option name="loreal" value="L'Oréal">L'Oréal</option>
                        <option name="natura" value="Natura">Natura</option>
                        <option name="avon" value="Avon">Avon</option>
                    </optgroup>
                    <optgroup label="Corpo">
                        <option name="granado" value="Granado">Granado</option>
                        <option name="bepantol" value="Bepantol">Bepantol</option>
                    </optgroup>
                    <optgroup label="Outros">
                        <option name="boticario" value="O Boticário">OBoticário</option>
                        <option name="eudora" value="Eudora">Eudora</option>
                    </optgroup>
                </select>
                <label class="label-produto">Local</label>
                <input type="text" name="local" required>

                <label class="label-produto">Categoria</label>
                <select name="categoria" required>
                    <optgroup label="Beleza">
                        <option name="cabelo" value="Cabelo">Cabelo</option>
                        <option name="skincare" value="SkinCare">SkinCare</option>
                    </optgroup>
                    <optgroup label="Corpo">
                        <option name="corporal" value="Corporal">Corporal</option>
                        <option name="cremes" value="Cremes">Cremes</option>
                    </optgroup>
                    <optgroup label="Outros">
                        <option name="perfume" value="Perfume">Perfume</option>
                    </optgroup>
                </select>

                <label class="label-produto-lado">Preço</label>
                <input type="number" step="0.01" min="0" name="preco" required>

                <label class="label-produto-lado">Descrição</label>
                <input type="text" name="descricao" required>
                <button name="cadastrar-produto" type="submit">Cadastrar Produto</button>
            </form>
            </div>
    
        </section>
    </div>
</main>
</body>
</html>
