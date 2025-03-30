<?php

class User {
    private $conn;
    private $table = 'usuarios';

    public $id;
    public $nombre;
    public $email;
    public $password;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Método para registrar usuario
    public function create() {
        $query = "INSERT INTO " . $this->table . " (fullname, email, password) VALUES (:fullname, :email, :password)";
        $stmt = $this->conn->prepare($query);

        // Limpiar los datos
        $this->fullname = htmlspecialchars(strip_tags($this->fullname));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));

        // Hash a la contraseña
        $hashed_password = password_hash($this->password, PASSWORD_BCRYPT);

        // Enlazar parámetros
        $stmt->bindParam(':fullname', $this->fullname);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $hashed_password);

        if ($stmt->execute()) {
            session_start(); // inicio de la sesion
            $lastid = $this->conn->lastInsertId(); //obtener el id del usuario registrado
            $_SESSION['user_id'] = $lastid; //establecer id como variable de sesion
            $_SESSION['user_name'] = $this->fullname; //establecer username como variable de sesion
            return true;
        }
        return false;
    }

    public function login() {
        $query = "SELECT id, fullname, password FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $this->email = htmlspecialchars(strip_tags($this->email));
        $stmt->bindParam(':email', $this->email);

        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $hashed_password = $row['password'];

            // Verificar contraseña
            if (password_verify($this->password, $hashed_password)) {
                session_start(); //iniciar sesion
                $_SESSION['user_id'] = $row['id']; //establecer id como variable de sesion
                $_SESSION['user_name'] = $row['fullname']; //establecer user name como variable de sesion
                return true;
            }
        }
        return false;
    }
}

?>

