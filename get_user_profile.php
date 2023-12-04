<?php
session_start();
header('Content-Type: application/json');

// 데이터베이스 연결 설정
$host = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'spring';

$conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $sql = "SELECT id, name, email FROM members WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        echo json_encode($row);
    } else {
        echo json_encode(["error" => "User not found"]);
    }
} else {
    echo json_encode(["error" => "Not logged in"]);
}

$conn->close();
?>