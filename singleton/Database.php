<?php
/**
 * Database.php
 * * A Singleton class to manage a single, secure PDO connection to MySQL.
 * This pattern ensures only one connection object is ever created, saving resources.
 */

class Database {
    // Stores the single instance of the Database class (the manager object).
    private static ?Database $instance = null;
    
    // Stores the actual PDO connection object (the execution tool).
    private ?PDO $connection = null;
    
    // --- Database Configuration (UPDATE THESE VALUES) ---
    private string $host = 'localhost';
    private string $db_name = 'test_db'; 
    private string $username = 'root';     
    private string $password = 'your_password'; // <<< IMPORTANT: Change this

    /**
     * The private constructor prevents direct instantiation of the class (The Singleton Rule).
     * This method is only called once, inside the getInstance() method.
     */
    private function __construct() {
        // Data Source Name (DSN) for MySQL
        $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4";
        
        try {
            // Create the PDO connection object
            $this->connection = new PDO($dsn, $this->username, $this->password);
            
            // Set error mode to throw exceptions on errors (BEST PRACTICE for PDO)
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            echo "Database connection established successfully.<br>";
            
        } catch (PDOException $e) {
            // In a real application, you would log this error and show a generic message.
            die("Database Connection Error: " . $e->getMessage());
        }
    }
    
    // Prevent cloning the instance
    private function __clone() { }

    /**
     * The static method that controls access to the single Database instance.
     * This is the Singleton Gatekeeper.
     * * @return Database The single instance of the Database class.
     */
    public static function getInstance(): Database {
        // If the instance does not exist, create it.
        if (self::$instance === null) {
            self::$instance = new self();
        }
        // Return the one and only existing instance.
        return self::$instance;
    }

    /**
     * Retrieves the underlying PDO connection object for executing queries.
     * * @return PDO The active PDO connection object.
     */
    public function getConnection(): PDO {
        return $this->connection;
    }
}

// EOF

?>
