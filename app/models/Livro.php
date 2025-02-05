<?php
require_once 'Database.php';

class Livro {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Método para cadastrar um livro
    public function cadastrarLivro($titulo, $autor, $genero, $data_lancamento, $resumo, $imagem_capa) {
        $stmt = $this->conn->prepare(
            "INSERT INTO livros (titulo, autor, genero, data_lancamento, resumo, imagem_capa)
            VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$titulo, $autor, $genero, $data_lancamento, $resumo, $imagem_capa])) {
            return true;
        }
        return false;
    }

    // Método para buscar todos os livros
    public function listarLivros($limite, $offset) {
        $stmt = $this->conn->prepare(
            "SELECT id, titulo, autor, genero, data_lancamento, imagem_capa FROM livros 
            ORDER BY criado_em DESC LIMIT :limite OFFSET :offset");
        $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function adicionarFavorito($idUsuario, $idLivro, $dataAdicionado) {
        try {
            $stmt = $this->conn->prepare(
                "INSERT INTO favoritos (id_usuario, id_livro, data_adicionado) 
                VALUES (?, ?, ?)");
            return $stmt->execute([$idUsuario, $idLivro, $dataAdicionado]);
        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage();
            return false;
        }
    }
    
    public function buscarLivroPorId($id) {
        $stmt = $this->conn->prepare(
            "SELECT id, titulo, autor, genero, data_lancamento, resumo, imagem_capa 
            FROM livros 
            WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function adicionarComentario($idLivro, $idUsuario, $comentario) {
        $stmt = $this->conn->prepare(
            "INSERT INTO comentarios (id_livro, id_usuario, comentario, data_comentario) 
            VALUES (?, ?, ?, NOW())");
        return $stmt->execute([$idLivro, $idUsuario, $comentario]);
    }
    
    public function buscarComentariosPorLivroId($idLivro) {
        $stmt = $this->conn->prepare(
            "SELECT c.id, c.comentario, c.data_comentario, c.id_usuario, u.nome_usuario AS nome_usuario 
            FROM comentarios c
            INNER JOIN usuarios u ON c.id_usuario = u.id
            WHERE c.id_livro = ?
            ORDER BY c.data_comentario DESC");
        $stmt->execute([$idLivro]);
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function verificarProprietarioComentario($idComentario, $idUsuario) {
        $stmt = $this->conn->prepare("SELECT id FROM comentarios WHERE id = ? AND id_usuario = ?");
        $stmt->execute([$idComentario, $idUsuario]);
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }
    
    public function excluirComentario($idComentario) {
        $sql = "DELETE FROM comentarios WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$idComentario]);
    }
  
}
?>
