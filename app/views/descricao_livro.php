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
</body>
</html>
