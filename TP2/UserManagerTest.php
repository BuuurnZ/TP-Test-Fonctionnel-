<?php

use PHPUnit\Framework\TestCase;

require_once "UserManager.php";

class UserManagerTest extends TestCase {
    private UserManager $userManager;
    private PDO $db;

    protected function setUp(): void {
        // Connexion à la base de test
        $dsn = "mysql:host=127.0.0.1;port=8889;dbname=user_management;charset=utf8;port=8889";
        $username = "root";
        $password = "root";

        try {
            $this->db = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de test : " . $e->getMessage());
        }

        $this->userManager = new UserManager();
    }

    // Enlever la suppression de la table
    // protected function tearDown(): void {
    //     // Suppression des données après chaque test
    //     $this->db->exec("DROP TABLE users");
    // }

    public function testAddUser() {
        $this->userManager->resetTable();
        $this->userManager->addUser("John Doe", "john@example.com");

        $stmt = $this->db->query("SELECT * FROM users WHERE email = 'john@example.com'");
        $user = $stmt->fetch();

        $this->assertNotEmpty($user);
        $this->assertEquals("John Doe", $user['name']);
        $this->assertEquals("john@example.com", $user['email']);
    }

    public function testAddUserEmailException() {
        $this->userManager->resetTable();
        $this->expectException(InvalidArgumentException::class);
        $this->userManager->addUser("Jane Doe", "invalid-email");
    }

    public function testUpdateUser() {
        $this->userManager->resetTable();
        $this->userManager->addUser("Old Name", "old@example.com");
        $stmt = $this->db->query("SELECT id FROM users WHERE email = 'old@example.com'");
        $id = $stmt->fetch()['id'];

        $this->userManager->updateUser($id, "New Name", "new@example.com");

        $stmt = $this->db->query("SELECT * FROM users WHERE id = $id");
        $user = $stmt->fetch();

        $this->assertEquals("New Name", $user['name']);
        $this->assertEquals("new@example.com", $user['email']);
    }

    public function testRemoveUser() {
        $this->userManager->resetTable();
        $this->userManager->addUser("To Delete", "delete@example.com");
        $stmt = $this->db->query("SELECT id FROM users WHERE email = 'delete@example.com'");
        $id = $stmt->fetch()['id'];

        $this->userManager->removeUser($id);

        $stmt = $this->db->query("SELECT * FROM users WHERE id = $id");
        $this->assertFalse($stmt->fetch());
    }

    public function testGetUsers() {
        $this->userManager->resetTable();
        $this->userManager->addUser("Alice", "alice@example.com");
        $this->userManager->addUser("Bob", "bob@example.com");

        $users = $this->userManager->getUsers();
        $this->assertCount(2, $users);
    }

    public function testInvalidUpdateThrowsException() {
        $this->userManager->resetTable();
        $this->expectException(Exception::class);
        $this->userManager->updateUser(9999, "Ghost", "ghost@example.com");
    }

    public function testInvalidDeleteThrowsException() {
        $this->userManager->resetTable();
        $this->expectException(Exception::class);
        $this->userManager->removeUser(9999);
    }
}
?>
