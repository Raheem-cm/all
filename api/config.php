 <?php
// Database Configuration for RAHEEM-XMD-3
define('DB_HOST', 'localhost');
define('DB_NAME', 'raheem_xmd_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// Session configuration
define('SESSION_PREFIX', 'Raheem~');
define('SESSION_LENGTH', 6);
define('SESSION_EXPIRE_HOURS', 24);

// Security
define('API_KEY', 'RaheemXMD3_' . date('Ymd'));

// Create tables if not exists (run once)
function createTables($pdo) {
    $queries = [
        "CREATE TABLE IF NOT EXISTS sessions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            session_id VARCHAR(50) UNIQUE NOT NULL,
            phone VARCHAR(20) NOT NULL,
            pairing_code VARCHAR(10) NOT NULL,
            created_at DATETIME NOT NULL,
            expires_at DATETIME NOT NULL,
            status ENUM('active', 'used', 'expired') DEFAULT 'active',
            INDEX idx_session (session_id),
            INDEX idx_phone (phone),
            INDEX idx_expires (expires_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",
        
        "CREATE TABLE IF NOT EXISTS session_logs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            session_id VARCHAR(50) NOT NULL,
            phone VARCHAR(20) NOT NULL,
            action VARCHAR(50) NOT NULL,
            timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_session_action (session_id, action)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",
        
        "CREATE TABLE IF NOT EXISTS qr_codes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            qr_data TEXT NOT NULL,
            session_id VARCHAR(50),
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            scans INT DEFAULT 0,
            INDEX idx_session (session_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
    ];
    
    foreach ($queries as $query) {
        $pdo->exec($query);
    }
}

// Initialize database connection
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    // Create database if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE " . DB_NAME);
    
    // Create tables
    createTables($pdo);
    
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
}
?>
