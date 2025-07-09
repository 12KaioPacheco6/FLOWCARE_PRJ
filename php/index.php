<?php
    require_once "../includes/banco-de-dados.inc.php";
    require_once "../includes/cadastrar-produto.inc.php"; // Sua classe Produto

    $banco = new BancoDeDados("localhost", "root", "", "flowcare", "categoria", "marca", "produto", "comentario", "cliente");
    $conexao = $banco->criarConexao();
    $banco->criarBanco($conexao); // Crie o banco de dados se não existir
    $banco->abrirBanco($conexao);
    $banco->definirCharset($conexao);
    $banco->criarTabelaCategoria($conexao); // Crie as tabelas
    $banco->criarTabelaMarca($conexao);
    $banco->criarTabelaProduto($conexao);
    $banco->criarTabelaCliente($conexao); // Cliente deve ser criado antes de Comentario
    $banco->criarTabelaComentario($conexao); // Crie a tabela de comentários

    $buscar = isset($_GET['buscar']) ? $_GET['buscar'] : '';

    if (!empty ($buscar)) {
        // Se não houver busca, redireciona para a página inicial
        echo "<script>alert('Por favor, digite algo para buscar.');</script>";
        echo "<script>window.location.href = '../index.php';</script>";
        exit();
    }

    $termo = mysqli_real_escape_string($conexao, $buscar);
    $query = "SELECT * FROM {$banco->nomeDaTabela3} WHERE nome LIKE '%$termo%' OR marca LIKE '%$termo%' OR local LIKE '%$termo%' OR categoria LIKE '%$termo%'";
    $resultado = mysqli_query($conexao, $query);

    if (!$resultado) {
        echo "<script>alert('Erro ao buscar produtos: " . mysqli_error($conexao) . "');</script>";
        exit();
    }

    $produtoObj = new Produto(); 
    $lista_produtos = $produtoObj->listarTodosProdutos($conexao, $banco->nomeDaTabela3);
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Flowcare - Produtos Avaliados!</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/formata-projeto.css">
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
                  <a class="nav-link active" aria-current="page" href="../php/cadastrar-produto.php">Cadastrar Produto</a>
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
              <form class="d-flex mt-3" role="search" action="../php/informacao-produto.php" method="GET">
                <input name="buscar" class="form-control me-2" type="search" placeholder="Encontre seu produto" aria-label="Search"/>
                <button class="btn btn-outline-success" type="submit">Buscar</button>
              </form>
            </div>
          </div>
        </div>
      </nav>
  </header>
<main class="content">
    <div class="container py-4 mt-5 mx-auto p-2" style="width: 80%;"> <h1>Produtos Avaliados!</h1>

        <div class="row">
            <?php
            if ($lista_produtos && mysqli_num_rows($lista_produtos) > 0) {
                while($item = mysqli_fetch_assoc($lista_produtos)) {
                    echo '<div class="col-md-4 mb-4">'; // Colunas para exibir produtos em grade
                    echo '<div class="card h-100">';
                    // O link agora aponta para detalhes_produto.php e passa o ID
                    echo '<a href="informacao-produto.php?id=' . htmlspecialchars($item["id_produto"]) . '" class="card-body">';
                    echo '<h5 class="card-title">' . htmlspecialchars($item["nome"]) . '</h5>';
                    echo '<p class="card-text"><strong>Marca:</strong> ' . htmlspecialchars($item["marca"]) . '</p>';
                    echo '<p class="card-text"><strong>Local:</strong> ' . htmlspecialchars($item["local"]) . '</p>';
                    echo '<p class="card-text"><strong>Categoria:</strong> ' . htmlspecialchars($item["categoria"]) . '</p>';
                    echo '<p class="card-text"><strong> Preço:</strong> R$' . number_format($item["preco"], 2, ',', '.') . '</p>';

                    echo '</a>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p>Nenhum produto disponível para avaliação ainda.</p>';
            }
            ?>
        </div>
    </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
</body>
</html>
<?php
$banco->fecharBanco($conexao); // Feche a conexão quando não precisar mais dela
?>