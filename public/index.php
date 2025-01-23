<?php
session_start();
// Configurações
include_once("../helpers/url.php");
require_once '../config/config.php';
require_once '../app/controllers/LivroController.php';
require_once '../app/controllers/UsuarioController.php';
require_once '../app/controllers/FavoritosController.php';

// Definir o controlador
$livroController = new LivroController();
$usuarioController = new UsuarioController();

// Verificar a ação e redirecionar
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    
    if ($action == 'cadastrar') {
        $livroController->cadastrarLivro();  // Processar o cadastro do livro
    } elseif ($action == 'listar') {
        $pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
        $livroController->listarLivros($pagina);  // Listar livros
    } elseif ($action == 'form-cadastrar') {
        $livroController->exibirFormularioCadastro();
    } elseif ($action == 'sair') {
        // Encerra a sessão e redireciona para a página de login
        session_destroy();
        header('Location: login.php');
        exit();
    } elseif ($action == 'adicionar_favorito') {
        $livroController->adicionarFavorito();
    } elseif ($action == 'usuario') {
        if (isset($_SESSION['usuario_id'])){
            $usuarioController->exibirUsuario();
        } else{
            header('Location: login.php');
            exit();
        }
    }elseif ($action == 'removerFavorito') {
        if (isset($_POST['id_livro'])) {
            $usuarioId = $_SESSION['usuario_id'];
            $idLivro = $_POST['id_livro'];
            $favoritosController = new Favorito();
            $favoritosController->removerFavorito($usuarioId, $idLivro);
            header("Location: ?action=usuario");
            exit;
        }
        
    } elseif ($action == 'editarUsuario') {
        $usuarioId = $_SESSION['usuario_id'];
        $usuarioController->exibirFormularioEdicao($usuarioId);

    } elseif ($action == 'salvarEdicao') {
        $usuarioId = $_SESSION['usuario_id'];
        $usuarioController->salvarEdicao($usuarioId, $_POST);

    } elseif ($action == 'descricao') {
        $livroId = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $livroController->exibirDescricaoLivro($livroId);
    }
    
    
    
} else {
    // Caso não haja ação definida, exibe o formulário de cadastro de livro
    header('Location: index.php?action=listar');
    //$livroController->exibirFormularioCadastro();
}
// testado meu primeiro commit
?>
