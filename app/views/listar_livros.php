<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listagem de Livros</title>
    <link rel="stylesheet" href="../public/assets/styles.css">
</head>
<body>
<?php require_once 'header.php'; ?>
<div class="container">
    <h1>Listagem de Livros</h1>
    <div class="grade-livros">
        <?php foreach ($livros as $livro): ?>
            <div class="cartao-livro">
                <img src="<?php echo $BASE_URL . $livro['imagem_capa'] ?>" alt="Capa do Livro">
                <h2><?= $livro['titulo']; ?></h2>
                <p><strong>Autor:</strong> <?= $livro['autor']; ?></p>
                <p><strong>Data de Lançamento:</strong> <?= $livro['data_lancamento']; ?></p>
                <a href="<?php echo $BASE_URL ?>index.php?action=descricao&id=<?= $livro['id']; ?>">Ver Detalhes</a>
            
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <form action="?action=adicionar_favorito" method="POST" class="form-favorito">
                        <input type="hidden" name="id_livro" value="<?php echo $livro['id']; ?>">
                        <button type="submit" class="botao-favorito">Adicionar aos Favoritos</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <br>
    <div class="paginacao">
        <a href="<?php echo $BASE_URL ?>index.php?action=listar&pagina=1">Primeira</a>
        <a href="<?php echo $BASE_URL ?>index.php?action=listar&pagina=<?= $pagina - 1; ?>">Anterior</a>
        <a href="<?php echo $BASE_URL ?>index.php?action=listar&pagina=<?= $pagina + 1; ?>">Próxima</a>
        <a href="<?php echo $BASE_URL ?>index.php?action=listar&pagina=<?= $pagina; ?>">Última</a>
    </div>
</div>
</body>
</html>
