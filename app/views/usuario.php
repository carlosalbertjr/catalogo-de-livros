<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Livro</title>
    <link rel="stylesheet" href="<?php __DIR__ ?>assets/styles.css">
</head>
<?php require_once 'header.php'; ?>
<div class="container-usuario">
    <div class="dados-usuario">
        <h2>Perfil do Usuário</h2>
        <p><strong>Nome:</strong> <?= htmlspecialchars($dadosUsuario['nome_usuario']); ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($dadosUsuario['email']); ?></p>
        <a href="index.php?action=editarUsuario" class="botao">Editar Dados</a>
    </div>

    <div class="livros-favoritos">
        <h2>Livros Favoritos</h2>
        <?php if (count($livrosFavoritos) > 0): ?>
            <div class="lista-favoritos">
                <?php foreach ($livrosFavoritos as $livro): ?>
                    <div class="livro">
                        <img src="<?= htmlspecialchars($livro['imagem_capa']); ?>" alt="Capa do livro <?= htmlspecialchars($livro['titulo']); ?>">
                        <h3><?= htmlspecialchars($livro['titulo']); ?></h3>
                        <p><?= htmlspecialchars($livro['autor']); ?></p>
                        <form method="POST" action="?action=removerFavorito">
                            <input type="hidden" name="id_livro" value="<?php echo $livro['id']; ?>">
                            <button type="submit" class="btn-remover">Remover</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Você ainda não tem livros favoritos.</p>
        <?php endif; ?>
    </div>
</div>
