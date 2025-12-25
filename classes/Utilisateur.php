<?php

class Utilisateur {

     protected $id; 
     protected $nom; 
     protected $email; 
     protected $telephone;
     protected $role;
     protected $passwordHash; 
     
     public function __construct($id, $nom, $email, $telephone, $role, $passwordHash = null) { 
        $this->id = $id; 
        $this->nom = $nom; 
        $this->email = $email; 
        $this->telephone= $telephone;
        $this->role= $role;
        $this->passwordHash = $passwordHash;
     } 

    public function getId() {
        return $this->id;
    }
    public function getNom() {
        return $this->nom;
    }
    public function getEmail() {
        return $this->email;
    }
    public function getTelephone() {
        return $this->telephone;
    }
    public function getRole() {
        return $this->role;
    }
    public function getPasswordHash() {
        return $this->passwordHash;
    }
     
    public function __toString() { 
        return "nom : ".$this->nom . " " ."email : ". $this->email;     
    }

    public function save() {
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("
                INSERT INTO users (nom, email, telephone, role, password)
                VALUES (:nom, :email, :telephone, :role, :password)
            ");
            $stmt->execute([
                'nom' => $this->nom,
                'email' => $this->email,
                'telephone' => $this->telephone,
                'role' => $this->role,
                'password' => $this->passwordHash
            ]);
            $this->id = (int)$pdo->lastInsertId();
            return true;
        } catch (PDOException $e) {
            error_log("Erreur save Utilisateur: " . $e->getMessage());
            return false;
        }
    }

    public static function login(string $email, string $password): ?Utilisateur {
        try {

            $pdo = Database::getConnection();

            
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$data) {
                return null; 
            }

            if (password_verify($password, $data['password'])) {
                return new Utilisateur(
                    $data['id_user'],
                    $data['nom'],
                    $data['email'],
                    $data['telephone'],
                    $data['role'],
                    $data['password']
                );
            } else {
                return null; 
            }

        } catch (PDOException $e) {
            error_log("Erreur login: " . $e->getMessage());
            return null;
        }
    }

}
?>


