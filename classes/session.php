<?php
class Post {
    private $db;
    private $table = "posts";
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($userId, $title, $content, $image = null) {
        try {
            $query = "INSERT INTO " . $this->table . " (user_id, title, content, image_path) 
                     VALUES (:user_id, :title, :content, :image)";
            
            $stmt = $this->db->prepare($query);
            
            $stmt->bindParam(":user_id", $userId);
            $stmt->bindParam(":title", $title);
            $stmt->bindParam(":content", $content);
            $stmt->bindParam(":image", $image);
            
            return $stmt->execute();
            
        } catch(PDOException $e) {
            echo "Erreur de création : " . $e->getMessage();
            return false;
        }
    }

    public function update($id, $title, $content, $image = null) {
        try {
            $query = "UPDATE " . $this->table . " 
                     SET title = :title, content = :content, image_path = :image 
                     WHERE id = :id";
            
            $stmt = $this->db->prepare($query);
            
            $stmt->bindParam(":title", $title);
            $stmt->bindParam(":content", $content);
            $stmt->bindParam(":image", $image);
            $stmt->bindParam(":id", $id);
            
            return $stmt->execute();
            
        } catch(PDOException $e) {
            echo "Erreur de mise à jour : " . $e->getMessage();
            return false;
        }
    }

    public function getById($id) {
        try {
            $query = "SELECT p.*, u.username FROM " . $this->table . " p 
                     JOIN users u ON p.user_id = u.id 
                     WHERE p.id = :id";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            
            return $stmt->fetch();
            
        } catch(PDOException $e) {
            echo "Erreur : " . $e->getMessage();
            return false;
        }
    }

    public function getAll($limit = 10, $offset = 0) {
        try {
            $query = "SELECT p.*, u.username FROM " . $this->table . " p 
                     JOIN users u ON p.user_id = u.id 
                     ORDER BY p.created_at DESC 
                     LIMIT :limit OFFSET :offset";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
            $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll();
            
        } catch(PDOException $e) {
            echo "Erreur : " . $e->getMessage();
            return false;
        }
    }

    public function delete($id) {
        try {
            $query = "DELETE FROM " . $this->table . " WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":id", $id);
            return $stmt->execute();
            
        } catch(PDOException $e) {
            echo "Erreur de suppression : " . $e->getMessage();
            return false;
        }
    }

    public function getByCategory($categoryId, $limit = 10, $offset = 0) {
        try {
            $query = "SELECT p.*, u.username FROM " . $this->table . " p 
                     JOIN users u ON p.user_id = u.id 
                     JOIN post_categories pc ON p.id = pc.post_id 
                     WHERE pc.category_id = :category_id 
                     ORDER BY p.created_at DESC 
                     LIMIT :limit OFFSET :offset";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":category_id", $categoryId);
            $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
            $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll();
            
        } catch(PDOException $e) {
            echo "Erreur : " . $e->getMessage();
            return false;
        }
    }

    public function search($keyword) {
        try {
            $keyword = "%$keyword%";
            $query = "SELECT p.*, u.username FROM " . $this->table . " p 
                     JOIN users u ON p.user_id = u.id 
                     WHERE p.title LIKE :keyword OR p.content LIKE :keyword 
                     ORDER BY p.created_at DESC";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":keyword", $keyword);
            $stmt->execute();
            
            return $stmt->fetchAll();
            
        } catch(PDOException $e) {
            echo "Erreur de recherche : " . $e->getMessage();
            return false;
        }
    }
}