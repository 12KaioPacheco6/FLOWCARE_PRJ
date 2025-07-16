<?php
session_start();
echo "ID do Cliente na Sessão: " . ($_SESSION['id_cliente'] ?? 'Não definido') . "<br>";
require_once "../includes/banco-de-dados.inc.php";
require_once "../includes/cadastrar-produto.inc.php"; // Sua classe Produto

$banco = new BancoDeDados("localhost", "root", "", "flowcare", "categoria", "marca", "produto", "comentario", "cliente");
$conexao = $banco->criarConexao();
// Não é necessário criar banco/tabelas aqui; isso deve ser feito uma única vez.
$banco->abrirBanco($conexao); // Abre a conexão com o banco de dados 'flowcare'
$banco->definirCharset($conexao);

$produto = null;
$produtoObj = new Produto();

if (isset($_GET['id'])) {
    $id_produto = htmlspecialchars($_GET['id']);
    // Chamada correta: obterProdutoPorId retorna a chave 'id'
    $produto = $produtoObj->obterProdutoPorId($conexao, $banco->nomeDaTabela3, $id_produto);
}

// Redireciona de volta para a index se nenhum ID for fornecido ou se o produto não for encontrado
if (!$produto) {
    header("Location: index.php");
    exit();
}

// NOVO CÓDIGO: Obter os comentários para este produto
$comentarios = $produtoObj->listarComentariosPorProduto(
    $conexao,
    $banco->nomeDaTabela4, // nome da tabela de comentários
    $banco->nomeDaTabela5, // nome da tabela de clientes
    $produto['id'] // Use a chave 'id' para o ID do produto
);

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Detalhes do Produto - <?php echo htmlspecialchars($produto["nome"]); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/formata-projeto.css">
</head>


<body>
    <header>
        <nav class="navbar bg-body-tertiary fixed-top" style="background-color: #e3f2fd;" data-bs-theme="light">
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
                                <a class="nav-link active" aria-current="page" href="../php/cadastrar-cliente.php">Cadastra-se</a>
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
    <div class="container py-4 mt-5 mx-auto p-2" style="width: 80%;">
        <h1>Detalhes do Produto: <?php echo htmlspecialchars($produto["nome"]); ?></h1>

        <div class="card h-80">
            <div class="card-body">
                <p><strong>Marca:</strong> <?php echo htmlspecialchars($produto["marca"]); ?></p>
                <p><strong>Local:</strong> <?php echo htmlspecialchars($produto["local"]); ?></p>
                <p><strong>Categoria:</strong> <?php echo htmlspecialchars($produto["categoria"]); ?></p>
                <p><strong>Preço:</strong> R$ <?php echo number_format($produto["preco"], 2, ',', '.'); ?></p>
                <p><strong>Descrição:</strong> <?php echo nl2br(htmlspecialchars($produto["descricao"])); ?></p>
            </div>
        </div>

        <h2 class="mt-4">Comentários</h2>
        <h3>Adicionar um Comentário</h3>
        <form action="../php/processa-comentario.php" method="POST">
            <input type="hidden" name="id_produto" value="<?php echo htmlspecialchars($produto['id']); ?>">
            <textarea name="comentario_texto" rows="5" class="form-control" placeholder="Digite seu comentário" required></textarea><br>
            <button type="submit" class="btn btn-primary">Enviar Comentário</button>
        </form>
        <div class="mt-4">
           <?php
                if ($comentarios && mysqli_num_rows($comentarios) > 0) {
                    while ($comentario_item = mysqli_fetch_assoc($comentarios)) {
                        ?>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($comentario_item['nome_cliente']); ?></h5>
                                <h6 class="card-subtitle mb-2 text-muted"><?php echo date('d/m/Y H:i', strtotime($comentario_item['data_comentario'])); ?></h6>
                                <p class="card-text"><?php echo nl2br(htmlspecialchars($comentario_item['comentario'])); ?></p>

                                <?php 

                                if (isset($_SESSION['id_cliente']) && $_SESSION['id_cliente'] == $comentario_item['id_cliente']): 
                                ?>
                                    <div class="d-flex justify-content-end">
                                        <button type="button" class="btn btn-sm btn-info me-2" data-bs-toggle="modal" data-bs-target="#editCommentModal_<?php echo $comentario_item['id_comentario']; ?>" data-comment-id="<?php echo $comentario_item['id_comentario']; ?>" data-comment-text="<?php echo htmlspecialchars($comentario_item['comentario']); ?>">Editar</button>
                                        
                                        <form action="../php/processa-comentario.php" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este comentário?');">
                                            <input type="hidden" name="acao" value="excluir">
                                            <input type="hidden" name="id_comentario" value="<?php echo htmlspecialchars($comentario_item['id_comentario']); ?>">
                                            <input type="hidden" name="id_produto" value="<?php echo htmlspecialchars($produto['id']); ?>">
                                            <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                                        </form>
                                    </div>

                                    <div class="modal fade" id="editCommentModal_<?php echo $comentario_item['id_comentario']; ?>" tabindex="-1" aria-labelledby="editCommentModalLabel_<?php echo $comentario_item['id_comentario']; ?>" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="../php/processa-comentario.php" method="POST">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editCommentModalLabel_<?php echo $comentario_item['id_comentario']; ?>">Editar Comentário</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <input type="hidden" name="acao" value="editar">
                                                        <input type="hidden" name="id_comentario" value="<?php echo htmlspecialchars($comentario_item['id_comentario']); ?>">
                                                        <input type="hidden" name="id_produto" value="<?php echo htmlspecialchars($produto['id']); ?>">
                                                        <div class="mb-3">
                                                            <label for="comentarioTexto_<?php echo $comentario_item['id_comentario']; ?>" class="form-label">Seu Comentário</label>
                                                            <textarea class="form-control" id="comentarioTexto_<?php echo $comentario_item['id_comentario']; ?>" name="comentario_texto" rows="5" required><?php echo htmlspecialchars($comentario_item['comentario']); ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo '<p>Nenhum comentário para este produto ainda. Seja o primeiro a comentar!</p>';
                }
                ?>
        </div>

        <p class="mt-3" href="index.php">Voltar para a lista de produtos</p>
    </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
</body>
</html>
<?php
$banco->fecharBanco($conexao);
?>