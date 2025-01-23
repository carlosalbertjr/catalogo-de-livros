<?php
// Iniciar sessão
session_start();

// Incluir configurações e modelos
require_once '../config/config.php';
require_once '../app/models/Usuario.php';

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
            header('Location: login.php');
            exit;
        } else {
            echo "Erro ao cadastrar usuário.";
        }
    }
}

?>

<form method="POST" action="cadastrar_usuario.php">
    <label for="nome_usuario">Nome de Usuário</label>
    <input type="text" id="nome_usuario" name="nome_usuario" required>

    <label for="login">Login</label>
    <input type="text" id="login" name="login" required>

    <label for="senha">Senha</label>
    <input type="password" id="senha" name="senha" required>

    <label for="email">Email</label>
    <input type="email" id="email" name="email" required>

    <button type="submit">Cadastrar</button>
</form>


<p>Já tem uma conta? <a href="login.php">Faça login</a></p>
