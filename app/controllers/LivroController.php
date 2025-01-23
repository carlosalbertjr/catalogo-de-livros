<?php
require_once '../app/models/Livro.php';
$BASE_URL = "http://" . $_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']. '?') . '/';

class LivroController {
    
    // Método para exibir o formulário de cadastro de livro
    public function exibirFormularioCadastro() {
        require_once '../app/views/cadastrar_livro.php';
    }

    // Método para processar o cadastro de um livro
    public function cadastrarLivro() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $titulo = $_POST['titulo'];
            $autor = $_POST['autor'];
            $genero = $_POST['genero'];
            $data_lancamento = $_POST['data_lancamento'];
            $resumo = $_POST['resumo'];
            $imagem_capa = $_FILES['imagem_capa']['name'];

            // Validar campos obrigatórios
            if (empty($titulo) || empty($autor) || empty($data_lancamento)) {
                echo "Título, autor e data de lançamento são obrigatórios.";
                return;
            }

            // Validar formato da data
            $dataPartes = explode("-",$data_lancamento);
            $data = new DateTime();
            $data->setDate($dataPartes[0], $dataPartes[1], $dataPartes[2]);
            
            // Converter a data para o formato 'Y-m-d' (compatível com o banco)
            $data_lancamento_formatado = $data->format('Y-m-d');

            // Validar imagem (apenas .jpg e .png)
            $extensao = pathinfo($imagem_capa, PATHINFO_EXTENSION);
            if (!in_array($extensao, ['jpg', 'png'])) {
                echo "Imagem de capa deve ser no formato JPG ou PNG.";
                return;
            }

            // Mover a imagem para o diretório apropriado
            // Verificar e processar upload de imagem
            $livro = new Livro();
            if (isset($_FILES['imagem_capa']) && $_FILES['imagem_capa']['error'] === UPLOAD_ERR_OK) {
                $extensao = pathinfo($_FILES['imagem_capa']['name'], PATHINFO_EXTENSION);
                
                // Validar extensão
                if (in_array(strtolower($extensao), ['jpg', 'jpeg', 'png'])) {
                    $diretorio = '../../public/assets/uploads/';

                    if (!is_dir($diretorio)) {
                        mkdir($diretorio, 0755, true);
                    }

                    // Gerar nome único para o arquivo
                    $nomeArquivo = uniqid('livro_', true) . '.' . $extensao;
                    $caminhoArquivo = $BASE_URL . 'assets/uploads/'. $nomeArquivo;

                    // Mover arquivo para o diretório de upload
                    if (move_uploaded_file($_FILES['imagem_capa']['tmp_name'], $caminhoArquivo)) {
                        $diretorio2 = 'assets/uploads/';
                        $livro->imagem_capa = $diretorio2 . $nomeArquivo;
                    } else {
                        die("Erro ao salvar a imagem. Tente novamente.");
                    }
                } else {
                    die("Formato de imagem inválido. Use JPG ou PNG.");
                }
            } else {
                die("Erro no upload da imagem.");
            }


            // Chamar o modelo para salvar o livro
            var_dump($data_lancamento_formatado); // Exibe o valor que será passado para o banco
            if ($livro->cadastrarLivro($titulo, $autor, $genero, $data_lancamento_formatado, $resumo, $diretorio2 . $nomeArquivo)) {
                // Redirecionar para a página de listagem após o cadastro bem-sucedido
                header('Location: ' . $BASE_URL . 'index.php?action=listar');
                exit; // Sempre usar exit após redirecionar
            } else {
                echo "Erro ao cadastrar livro.";
            }
        }
    }

    // Método para listar os livros na página inicial (index)
    public function listarLivros($pagina = 1) {
        $livro = new Livro();
        $livrosPorPagina = 10;
        $offset = ($pagina - 1) * $livrosPorPagina;
        
        $livros = $livro->listarLivros($livrosPorPagina, $offset);
        require_once '../app/views/listar_livros.php';
    }

    // Método para exibir os detalhes do livro
    public function exibirDetalhes($id) {
        $livro = new Livro();
        $detalhes = $livro->buscarLivroPorId($id);
        
        // Buscando os comentários do livro
        $comentarios = $livro->buscarComentariosPorLivroId($id);
    
        require_once '../app/views/detalhes_livro.php';
    }

    // Adicionar Favoritos
    public function adicionarAosFavoritos($idLivro) {
        session_start();
        
        // Verificar se o usuário está logado
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?action=login');
            exit();
        }
    
        $idUsuario = $_SESSION['usuario_id'];
        $dataAdicionado = date('Y-m-d H:i:s');
    
        // Instanciar o modelo de Livro
        $livroModel = new Livro();
    
        // Chamar o método do modelo para adicionar aos favoritos
        $resultado = $livroModel->adicionarFavorito($idUsuario, $idLivro, $dataAdicionado);
    
        if ($resultado) {
            echo "Livro adicionado aos favoritos com sucesso!";
        } else {
            echo "Erro ao adicionar livro aos favoritos.";
        }
    
        // Redirecionar de volta para a lista de livros
        header('Location: index.php?action=listar');
        exit();
    }
    
    public function adicionarFavorito(){
        // Verificar se o usuário está logado
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ?action=login');
            exit;
        }

        // Verificar se os dados foram enviados via POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_livro'])) {
            $idUsuario = $_SESSION['usuario_id'];
            $idLivro = $_POST['id_livro'];
            $dataAdicionado = date('Y-m-d H:i:s');

            require_once '../app/controllers/FavoritosController.php';
            $favoritoModel = new Favorito();

            // Adicionar o favorito ao banco
            if ($favoritoModel->adicionar($idUsuario, $idLivro, $dataAdicionado)) {
                echo "Livro adicionado aos favoritos com sucesso!";
                header('Location: index.php?action=listar');
            } else {
                header('Location: index.php?action=listar');
            }
        } else {
            echo "Requisição inválida.";
        }
    }

    public function exibirDescricaoLivro($id) {
        $livroModel = new Livro();
    
        // Buscar detalhes do livro pelo ID
        $detalhesLivro = $livroModel->buscarLivroPorId($id);
        $comentarios = $livroModel->buscarComentariosPorLivroId($id);
        
        // Verificar se o livro foi encontrado
        if (!$detalhesLivro) {
            echo "Livro não encontrado.";
            return;
        }
    
        // Carregar a view de descrição do livro
        require_once __DIR__ . '/../views/descricao_livro.php';
    }
    
    public function comentar() {
    
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ?action=login');
            exit;
        }
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idLivro = $_POST['id_livro'];
            $comentario = $_POST['comentario'];
            $idUsuario = $_SESSION['usuario_id'];
    
            if (!empty($comentario)) {
                $livroModel = new Livro();
                if ($livroModel->adicionarComentario($idLivro, $idUsuario, $comentario)) {
                    header("Location: ?action=descricao&id=$idLivro");
                    exit;
                } else {
                    echo "Erro ao salvar o comentário.";
                }
            }
        }
    }
    
    public function excluirComentario() {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ?action=login');
            exit;
        }
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_comentario'])) {
            $idComentario = $_POST['id_comentario'];
            $idUsuario = $_SESSION['usuario_id'];
            $idLivro = $_POST['id_livro'];
    
            $livroModel = new Livro();
    
            // Verificar se o comentário pertence ao usuário logado
            if ($livroModel->verificarProprietarioComentario($idComentario, $idUsuario)) {
                if ($livroModel->excluirComentario($idComentario)) {
                    header("Location: ?action=descricao&id=$idLivro");
                    exit;
                }
            } else {
                echo "Você não tem permissão para excluir este comentário.";
                header("Location: ?action=descricao&id=$idLivro");
                exit;
            }
        }
    
        header("Location: ?action=descricaoLivro&id=" . $_GET['id']);
        exit;
    }
    
}
?>
