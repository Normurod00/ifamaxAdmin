<?php
class AdminController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllAdministrators() {
        try {
            return $this->pdo->query("SELECT * FROM administrators");
        } catch (PDOException $e) {
            die("Не удалось подключиться: " . $e->getMessage());
        }
    }

    public function addAdministrator($data) {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO administrators (username, email, password, name, position, phone, branch_id, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $data['username'], 
                $data['email'], 
                password_hash($data['password'], PASSWORD_DEFAULT),
                $data['name'], 
                $data['position'], 
                $data['phone'], 
                $data['branch_id'], 
                $data['role']
            ]);
            return true;
        } catch (PDOException $e) {
            echo "Ошибка при добавлении администратора: " . $e->getMessage();
            return false;
        }
    }
}
?>
