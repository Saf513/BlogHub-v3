<?php
class User {
    private $db;
    private $table = "users";
    
    public $id;
    public $username;
    public $email;
    public $password;
    public $role;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function register($username, $email, $password) {
        try {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            
            $query = "INSERT INTO " . $this->table . " (username, email, password_hash, role) 
                     VALUES (:username, :email, :password, 'user')";
            
            $stmt = $this->db->prepare($query);
            
            $stmt->bindParam(":username", $username);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":password", $hash);
            
            return $stmt->execute();
            
        } catch(PDOException $e) {
            echo "Erreur d'inscription : " . $e->getMessage();
            return false;
        }
    }

    public function login($email, $password) {
        try {
            $query = "SELECT * FROM " . $this->table . " WHERE email = :email";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":email", $email);
            $stmt->execute();

            if($stmt->rowCount() == 1) {
                $row = $stmt->fetch();
                if(password_verify($password, $row['password_hash'])) {
                    // Démarrer la session et stocker les infos utilisateur
                    session_start();
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['role'] = $row['role'];
                    return true;
                }
            }
            return false;
            
        } catch(PDOException $e) {
            echo "Erreur de connexion : " . $e->getMessage();
            return false;
        }
    }

    public function logout() {
        session_start();
        session_destroy();
        return true;
    }

    public function getById($id) {
        try {
            $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            
            return $stmt->fetch();
            
        } catch(PDOException $e) {
            echo "Erreur : " . $e->getMessage();
            return false;
        }
    }

    public function update($id, $data) {
        try {
            $fields = array();
            foreach($data as $key => $value) {
                $fields[] = "$key = :$key";
            }
            
            $query = "UPDATE " . $this->table . " SET " . implode(', ', $fields) . " WHERE id = :id";
            $stmt = $this->db->prepare($query);
            
            foreach($data as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            $stmt->bindValue(":id", $id);
            
            return $stmt->execute();
            
        } catch(PDOException $e) {
            echo "Erreur de mise à jour : " . $e->getMessage();
            return false;
        }
    }
}