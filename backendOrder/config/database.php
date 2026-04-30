
<?php
/**
 * Gaza Order - Database Configuration
 * Handles MySQL database connection using PDO
 */

class Database {
    // Database credentials - UPDATE THESE FOR YOUR HOSTING
    private $host = "127.0.0.1";
    private $port = "3306";
    private $db_name = "u595508539_orderdb";
    private $username = "u595508539_orderuser";
    private $password = "M1p9lbf^d2";  // UPDATE THIS
    private $conn;

    /**
     * Get database connection
     * @return PDO Database connection object
     */
    public function connect(): PDO {
        $this->conn = null;
        
        try {
            $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->db_name};charset=utf8mb4";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            
            // Set error mode to exceptions
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Set default fetch mode to associative array
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            
            // Emulate prepares for better compatibility
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            
            // Set session timezone to Gaza (Palestine) so CURTIME() returns correct local time.
            // Gaza uses Asia/Gaza (UTC+2 winter / UTC+3 summer with DST).
            // Using numeric offset as fallback in case named timezone isn't loaded on the server.
            try {
                $this->conn->exec("SET time_zone = 'Asia/Gaza'");
            } catch (PDOException $tzEx) {
                // Fallback: use fixed offset (UTC+3 for summer / UTC+2 for winter)
                // Determine current offset based on PHP's timezone awareness
                $gazaNow = new \DateTime('now', new \DateTimeZone('Asia/Gaza'));
                $offset = $gazaNow->format('P'); // e.g. "+03:00" or "+02:00"
                $this->conn->exec("SET time_zone = '$offset'");
            }
            
            // Ensure UTF-8 for Arabic/Unicode text in queries and results
            $this->conn->exec("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");
            $this->conn->exec("SET CHARACTER SET utf8mb4");
            
        } catch(PDOException $e) {
            // Log error (in production, log to file instead of displaying)
            error_log("Database Connection Error: " . $e->getMessage());
            die(json_encode([
                "status" => "error",
                "message" => "Database connection failed. Please try again later."
            ]));
        }
        
        return $this->conn;
    }

    /**
     * Close database connection
     */
    public function disconnect() {
        $this->conn = null;
    }

    /**
     * Get last inserted ID
     */
    public function lastInsertId() {
        return $this->conn->lastInsertId();
    }

    /**
     * Begin transaction
     */
    public function beginTransaction() {
        return $this->conn->beginTransaction();
    }

    /**
     * Commit transaction
     */
    public function commit() {
        return $this->conn->commit();
    }

    /**
     * Rollback transaction
     */
    public function rollback() {
        return $this->conn->rollBack();
    }
}
?>