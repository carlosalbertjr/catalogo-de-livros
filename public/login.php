<?php
// Iniciar sessão
session_start();

// Incluir configurações e modelos
require_once '../config/config.php';
require_once __DIR__ . '//..//app/models/Usuario.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    // Verificar credenciais
    $usuarioModel = new Usuario();
    $usuarioLogado = $usuarioModel->login($usuario, $senha);

    if ($usuarioLogado != false) {
        // Criar sessão do usuário
        $_SESSION['usuario_id'] = $usuarioLogado['id'];
        $_SESSION['usuario_nome'] = $usuarioLogado['nome'];
        // Redirecionar para a página principal
        header('Location: index.php?action=listar');
        exit;
    } else {
        echo "Usuário ou senha inválidos.";
    }
}
$BASE_URL = "http://" . $_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']. '?') . '/';
?>

<head>
    <link rel="stylesheet" href="<?php echo $BASE_URL ?>assets/styles.css">
</head>
<?php require_once '../app/views/header.php'; ?>
<body>
    <div class="body-login">
        <div class="container-login">
            <h1>Login</h1>
            <form action="login.php" method="POST">
                <label for="usuario">Usuário:</label>
                <input type="text" id="usuario" name="usuario" required>

                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required>

                <input type="submit" value="Entrar">
            </form>
            <p>Não tem uma conta? <a href="../app/views/cadastrar_usuario.php">Cadastre-se</a></p>
        </div>
    </div>
</body>