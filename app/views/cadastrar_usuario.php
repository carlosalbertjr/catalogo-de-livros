<?php
// Iniciar sessão
session_start();

// Incluir configurações e modelos
require_once '../../config/config.php';
require_once '../models/Usuario.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome_usuario'];
    $usuario = $_POST['login'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Verificar se o usuário já existe
    $usuarioModel = new Usuario();
    if ($usuarioModel->usuarioExistente($usuario)) {
        echo "Usuário já existe.";
    } else {
        // Cadastrar usuário
        if ($usuarioModel->cadastrarUsuario($nome, $usuario, $senha, $email)) {
            echo "Usuário cadastrado com sucesso!";
            header('Location: ../login.php');
            exit;
        } else {
            echo "Erro ao cadastrar usuário.";
        }
    }
}
?>
<head>
    <link rel="stylesheet" href="../../public/assets/styles.css">
</head>
<?php require_once 'header.php'; ?>
<div class="body-login">
    <div">
        <h1>Cadastrar Usuario</h1>
        <form method="POST" action="../../public/index.php?action=cadastrar-usuario" class="form-cadastro-usuario">
            <label for="nome_usuario" class="form-label">Nome de Usuário</label>
            <input type="text" id="nome_usuario" name="nome_usuario" class="form-input" required>

            <label for="login" class="form-label">Login</label>
            <input type="text" id="login" name="login" class="form-input" required>

            <label for="senha" class="form-label">Senha</label>
            <input type="password" id="senha" name="senha" class="form-input" required>

            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-input" required>

            <button type="submit" class="form-button">Cadastrar</button>
        <p class="form-footer-text">
            Já tem uma conta? <a href="../../public/login.php" class="form-link">Faça login</a>
        </p>
        </form>
    </div>
</div>