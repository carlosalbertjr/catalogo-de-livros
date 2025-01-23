<?php
require_once '../app/models/Database.php';

class Favorito
{
    private $pdo;

    public function __construct(){
        $database = new Database();
        $this->pdo = $database->getConnection();
    }

    public function adicionar($idUsuario, $idLivro, $dataAdicionado){

        if ($this->jaFavoritado($idUsuario, $idLivro)) {
            return false; // Retorna falso se o livro já está nos favoritos
        }

        $sql = "INSERT INTO favoritos (id_usuario, id_livro, data_adicionado) VALUES (:id_usuario, :id_livro, :data_adicionado)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id_usuario', $idUsuario);
        $stmt->bindParam(':id_livro', $idLivro);
        $stmt->bindParam(':data_adicionado', $dataAdicionado);
        return $stmt->execute();
    }
    
    public function jaFavoritado($idUsuario, $idLivro){
        $sql = "SELECT COUNT(*) FROM favoritos WHERE id_usuario = :id_usuario AND id_livro = :id_livro";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id_usuario', $idUsuario);
        $stmt->bindParam(':id_livro', $idLivro);
        $stmt->execute();
        return $stmt->fetchColumn() > 0; // Retorna true se o livro já estiver favoritado
    }

    public function listarPorUsuario($idUsuario) {
        $sql = "SELECT l.id, l.titulo, l.autor, l.imagem_capa 
                FROM favoritos f 
                JOIN livros l ON f.id_livro = l.id 
                WHERE f.id_usuario = :id_usuario";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id_usuario', $idUsuario);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function removerFavorito($idUsuario, $idLivro) {
        $stmt = $this->pdo->prepare("DELETE FROM favoritos WHERE id_usuario = ? AND id_livro = ?");
        return $stmt->execute([$idUsuario, $idLivro]);
    }
    
}
?>
