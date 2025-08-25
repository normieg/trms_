<?php
declare(strict_types=1);

// Database connection using environment variables with safe defaults
$servername = getenv('DB_HOST') ?: 'localhost';
$dbusername = getenv('DB_USER') ?: 'root';
$dbpassword = getenv('DB_PASS') ?: '';
$dbname     = getenv('DB_NAME') ?: 'trms';

// Throw exceptions on errors and use a secure charset
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    // Fail fast without exposing sensitive details
    http_response_code(500);
    exit('Database connection failed.');
}
