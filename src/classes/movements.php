<?php
class Movements {
    private $conn;
    private $table;

    public $id;
    public $type;
    public $amount;
    public $date;
    public $bill;
    public $user_id;

    public function __construct($db, $tipo = null) { //la funcion tendra tipo como parametro, dependiendo puede ser entrada o salidas
        $this->conn = $db;
        if ($tipo !== null) {
            $this->table = ($tipo === "entrada") ? "entradas" : "salidas"; //operador ternario, si el tipo es entrada, entonces se definira la tabla entradas
                                                                            // sino la tabla salidas
        }
    }

    //parametro para registrar 
    public function registrar() {
        $query = "INSERT INTO " . $this->table . " (type, amount, date, bill, user_id) 
                  VALUES (:type, :amount, :date, :bill, :user_id)";

        $stmt = $this->conn->prepare($query);

        //Limpiar los datos
        $this->type = htmlspecialchars(strip_tags($this->type));
        $this->amount = htmlspecialchars(strip_tags($this->amount));
        $this->date = htmlspecialchars(strip_tags($this->date));
        $this->bill = htmlspecialchars(strip_tags($this->bill));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));

        // Enlazar parámetros
        $stmt->bindParam(':type', $this->type);
        $stmt->bindParam(':amount', $this->amount);
        $stmt->bindParam(':date', $this->date);
        $stmt->bindParam(':bill', $this->bill);
        $stmt->bindParam(':user_id', $this->user_id);

        if ($stmt->execute()) {
            return true;
        }

        // Si algo sale mal, imprimir error
        printf("Error: %s.\n", $stmt->error);
        return false;
    }

    // metodo para obtener todas las entradas del ususario
    public function getEntradas($user_id) {
        $query = "SELECT * FROM entradas WHERE user_id = :user_id ORDER BY date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // metodo para obtener todas las entradas del ususario
    public function getSalidas($user_id) {
        $query = "SELECT * FROM salidas WHERE user_id = :user_id ORDER BY date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }    
    
}
?>
