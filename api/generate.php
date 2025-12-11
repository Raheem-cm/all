 <?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Database configuration
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);
$phoneNumber = $data['phone'] ?? '';
$countryCode = $data['country_code'] ?? '255';

// Validate phone number
if (empty($phoneNumber) || !preg_match('/^[0-9]{9,15}$/', $phoneNumber)) {
    echo json_encode(['error' => 'Invalid phone number']);
    exit;
}

// Generate RAHEEM session ID
function generateRaheemSessionId() {
    $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'; // No 0,1,O,I to avoid confusion
    $length = 6;
    $randomString = '';
    
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    
    return 'Raheem~' . $randomString; // Format: Raheem~ABC123
}

// Generate pairing code
function generatePairingCode() {
    $characters = '0123456789';
    $length = 6;
    $code = '';
    
    for ($i = 0; $i < $length; $i++) {
        $code .= $characters[rand(0, strlen($characters) - 1)];
    }
    
    return $code;
}

try {
    // Connect to database
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    // Generate session data
    $sessionId = generateRaheemSessionId();
    $pairingCode = generatePairingCode();
    $fullPhone = '+' . $countryCode . $phoneNumber;
    $createdAt = date('Y-m-d H:i:s');
    $expiresAt = date('Y-m-d H:i:s', strtotime('+24 hours'));
    
    // Check if phone already has active session
    $stmt = $pdo->prepare("SELECT session_id FROM sessions WHERE phone = ? AND expires_at > NOW() AND status = 'active'");
    $stmt->execute([$fullPhone]);
    $existing = $stmt->fetch();
    
    if ($existing) {
        // Return existing session
        echo json_encode([
            'success' => true,
            'session_id' => $existing['session_id'],
            'message' => 'Existing active session found'
        ]);
        exit;
    }
    
    // Insert new session
    $stmt = $pdo->prepare("INSERT INTO sessions (session_id, phone, pairing_code, created_at, expires_at, status) VALUES (?, ?, ?, ?, ?, 'active')");
    $stmt->execute([$sessionId, $fullPhone, $pairingCode, $createdAt, $expiresAt]);
    
    // Log the generation
    $logStmt = $pdo->prepare("INSERT INTO session_logs (session_id, phone, action) VALUES (?, ?, 'generated')");
    $logStmt->execute([$sessionId, $fullPhone]);
    
    // Return success
    echo json_encode([
        'success' => true,
        'session_id' => $sessionId,
        'pairing_code' => $pairingCode,
        'phone' => $fullPhone,
        'expires_at' => $expiresAt,
        'message' => 'Session created successfully'
    ]);
    
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}
?>
