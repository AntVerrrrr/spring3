<?php
// 데이터베이스 연결 설정
$host = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'spring'; // 데이터베이스 이름

// MySQL 데이터베이스에 연결
$conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 아이디 받기
$id = isset($_GET['id']) ? $_GET['id'] : '';

// 아이디 중복 검사 쿼리 실행
$sql = "SELECT id FROM members WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // 아이디가 이미 존재하는 경우
    echo "이미 존재하는 아이디입니다.";
} else {
    // 사용 가능한 아이디
    echo "사용 가능한 아이디입니다.";
}

$stmt->close();
$conn->close();
?>