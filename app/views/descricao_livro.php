<?php
$BASE_URL = "http://" . $_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']. '?') . '/';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php $BASE_URL ?>assets/styles.css">
    <title><?php echo htmlspecialchars($detalhesLivro['titulo']); ?></title>
</head>
<?php require_once 'header.php'; ?>
<body>
    <div class="descricao-container">
        <div class="imagem-livro">
            <img src="<?php echo htmlspecialchars($detalhesLivro['imagem_capa']); ?>" alt="Capa do Livro">
        </div>
        <div class="detalhes-livro">
            <h1><?php echo htmlspecialchars($detalhesLivro['titulo']); ?></h1>
            <p><strong>Autor:</strong> <?php echo htmlspecialchars($detalhesLivro['autor']); ?></p>
            <p><strong>Gênero:</strong> <?php echo htmlspecialchars($detalhesLivro['genero']); ?></p>
            <p><strong>Data de Lançamento:</strong> <?php echo htmlspecialchars($detalhesLivro['data_lancamento']); ?></p>
            <p><strong>Resumo:</strong></p>
            <p><?php echo nl2br(htmlspecialchars($detalhesLivro['resumo'])); ?></p>
        </div>
    </div>
    
    <!-- Trecho para Adicionar Comentários no Livro -->
    <?php if (isset($_SESSION['usuario_id'])): ?>
        <div class="adicionar-comentario">
            <h3>Adicionar Comentário</h3>
            <form action="?action=comentar" method="POST">
                <input type="hidden" name="id_livro" value="<?php echo $_GET['id']; ?>">
                <textarea name="comentario" placeholder="Escreva seu comentário aqui..." required></textarea>
                <button type="submit">Enviar Comentário</button>
            </form>
        </div>
    <?php else: ?>
        <p class="login-mensagem">Faça login para adicionar um comentário.</p>
    <?php endif; ?>
    
    <!-- Trecho para Exibir comentarios -->
    <h3>Comentários</h3>
    <div class="lista-comentarios">
        <?php if (!empty($comentarios)): ?>
            <?php foreach ($comentarios as $comentario): ?>
                <div class="comentario">
                    <p><strong><?php echo htmlspecialchars($comentario['nome_usuario']); ?></strong></p>
                    <p><?php echo htmlspecialchars($comentario['comentario']); ?></p>
                    <!-- Botão para excluir comentário (somente se for do usuário logado) -->
                    <?php if (isset($_SESSION['usuario_id'])):?>
                        <?php if ($_SESSION['usuario_id'] === $comentario['id_usuario']): ?>
                            <form method="POST" action="?action=excluirComentario">
                                <input type="hidden" name="id_livro" value="<?php echo $_GET['id']; ?>">
                                <input type="hidden" name="id_comentario" value="<?= $comentario['id'] ?>">
                                <button type="submit" class="btn-excluir">Excluir</button>
                            </form>
                        <?php endif; ?>
                    <?php endif; ?>
                    <p class="data-comentario"><?php echo date("d/m/Y H:i", strtotime($comentario['data_comentario'])); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Não há comentários para este livro ainda.</p>
        <?php endif; ?>
    </div>

</body>
</html>
