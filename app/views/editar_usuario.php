<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php __DIR__ ?>assets/styles.css">
    <title>Editar Usuário</title>
</head>
<?php require_once 'header.php'; ?>
<body>
    <div class="body-login">
        <div class="container-login">
            <h2>Editar Dados do Usuário</h2>
            <form method="POST" action="index.php?action=salvarEdicao">
                <input type="hidden" name="id" value="<?= $dadosUsuario['id'] ?>">
                <label for="nome">Nome</label>
                <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($dadosUsuario['nome_usuario']); ?>" required>

                <label for="login">Login</label>
                <input type="text" id="login" name="login" value="<?= htmlspecialchars($dadosUsuario['login']); ?>" required>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($dadosUsuario['email']); ?>" required>

                <label for="senha">Nova Senha (opcional)</label>
                <input type="password" id="senha" name="senha">

                <button type="submit">Salvar Alterações</button>
            </form>
        </div>
    </div>
</body>
</html>
