<?php
require_once __DIR__ . '/../models/Usuario.php';
require_once 'FavoritosController.php';

class UsuarioController {

    // Método para exibir o formulário de cadastro
    public function exibirFormularioCadastro() {
        require_once __DIR__ . '/../views/cadastrar_usuario.php';
    }

    // Método para cadastrar o usuário
    public function cadastrarUsuario() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nome_usuario = $_POST['nome_usuario'];
            $login = $_POST['login'];
            $senha = $_POST['senha'];
            $email = $_POST['email'];

            // Validar campos obrigatórios
            if (empty($nome_usuario) || empty($login) || empty($senha) || empty($email)) {
                echo "Todos os campos são obrigatórios!";
                return;
            }

            // Validar email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "Email inválido!";
                return;
            }

            // Criptografar senha
            $senha_hash = password_hash($senha, PASSWORD_BCRYPT);

            // Criar um novo objeto de usuário
            $usuario = new Usuario();
            if ($usuario->cadastrar($nome_usuario, $login, $senha_hash, $email)) {
                echo "Usuário cadastrado com sucesso!";
            } else {
                echo "Erro ao cadastrar usuário!";
            }
        }
    }

        public function exibirUsuario() {

        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?action=login");
            exit;
        }

        $usuarioId = $_SESSION['usuario_id'];
        $usuarioModel = new Usuario();
        $favoritoModel = new Favorito();

        $dadosUsuario = $usuarioModel->buscarPorId($usuarioId);
        $livrosFavoritos = $favoritoModel->listarPorUsuario($usuarioId);

        require_once __DIR__ . '/../views/usuario.php';
    }

    public function exibirFormularioEdicao($usuarioId) {
        // Instanciar o modelo de Usuário
        $usuarioModel = new Usuario();
    
        // Buscar os dados do usuário pelo ID
        $dadosUsuario = $usuarioModel->buscarPorId($usuarioId);
    
        // Verificar se os dados foram encontrados
        if ($dadosUsuario) {
            // Exibir a página de edição
            require_once __DIR__ . '/../views/editar_usuario.php';
        } else {
            // Caso o usuário não seja encontrado, exibir uma mensagem de erro
            echo "Erro: Usuário não encontrado.";
        }
    }

    public function salvarEdicao() {
        // Verificar se o formulário foi enviado
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Recuperar os dados enviados
            $usuarioId = $_POST['id'];
            $nome = trim($_POST['nome']);
            $login = trim($_POST['login']);
            $email = trim($_POST['email']);
            $senha = trim($_POST['senha']);
    
            // Validações básicas
            if (empty($nome) || empty($login) || empty($email)) {
                echo "Erro: Todos os campos obrigatórios devem ser preenchidos.";
                return;
            }
    
            // Validar formato do e-mail
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "Erro: E-mail inválido.";
                return;
            }
    
            // Instanciar o modelo de Usuário
            $usuarioModel = new Usuario();
    
            // Atualizar os dados no banco de dados
            $resultado = $usuarioModel->atualizar($usuarioId, $nome, $login, $email, $senha);
    
            if ($resultado) {
                echo "Dados atualizados com sucesso!";
                header("Location: index.php?action=usuario"); // Redirecionar para a tela de usuário
                exit;
            } else {
                echo "Erro ao atualizar os dados. Tente novamente.";
            }
        }
    }
    
}
?>
