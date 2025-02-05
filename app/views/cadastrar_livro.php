<?php
// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Livro</title>
    <link rel="stylesheet" href="<?php __DIR__ ?>assets/styles.css">
    
</head>
<body>
<?php require_once 'header.php'; ?>
<div class="container-cadastro-livro">
    <h1>Cadastro de Livro</h1>
    <form class="formulario-cadastro" action="index.php?action=cadastrar" method="POST" enctype="multipart/form-data">
        <div class="avisos">
            <!-- Aqui você pode mostrar mensagens de erro -->
        </div>    
        <label for="titulo">Título:</label>
        <input type="text" name="titulo" id="titulo" required><br>

        <label for="autor">Autor:</label>
        <input type="text" name="autor" id="autor" required><br>

        <label for="genero">Gênero:</label>
        <input type="text" name="genero" id="genero" required><br>

        <label for="data_lancamento">Data de Lançamento:</label>
        <input type="date" name="data_lancamento" id="data_lancamento" required><br>

        <label for="resumo">Resumo:</label>
        <textarea id="resumo" name="resumo" rows="5"></textarea><br>

        <label for="imagem_capa">Imagem de Capa:</label>
        <input type="file" name="imagem_capa" id="imagem_capa" accept="image/png, image/jpeg" required><br>

        <button class="botao-cadastrar-link"type="submit">Cadastrar Livro</button>
    </form>
    <div class="botao-voltar">
        <a href="?action=listar">Voltar para a Lista de Livros</a>
    </div>
</div>
</body>
</html>
