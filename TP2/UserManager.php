<?php
class UserManager {
    private PDO $db;

    public function __construct() {
        $dsn = "mysql:host=127.0.0.1;port=8889;dbname=user_management;charset=utf8;port=8889";
        $username = "root";
        $password = "root";

        $this->db = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }

    public function resetTable(): void {
        $stmt = $this->db->prepare("DELETE FROM users;ALTER TABLE users AUTO_INCREMENT = 1; ");
        $stmt->execute();
    }

    public function addUser(string $name, string $email): void {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Email invalide.");
        }

        $stmt = $this->db->prepare("INSERT INTO users (name, email) VALUES (:name, :email)");
        $stmt->execute(['name' => $name, 'email' => $email]);
    }

    public function removeUser(int $id): void {
        $user = $this->getUser($id);
        if($user) {
            $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
            $stmt->execute(['id' => $id]);
        }
        else{
            throw new InvalidArgumentException("User not found.");
        }

    }

    public function getUsers(): array {
        $stmt = $this->db->query("SELECT * FROM users");
        return $stmt->fetchAll();
    }

    public function getUser(int $id): array {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();
        if (!$user) throw new Exception("Utilisateur introuvable.");
        return $user;
    }

    public function updateUser(int $id, string $name, string $email): void {
        $user = $this->getUser($id);
        if($user) {
            $stmt = $this->db->prepare("UPDATE users SET name = :name, email = :email WHERE id = :id");
            $stmt->execute(['id' => $id, 'name' => $name, 'email' => $email]);
        }
        else {
            throw new InvalidArgumentException("User not found.");
        }

    }
}
?>
