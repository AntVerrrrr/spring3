<?php
// 데이터베이스 연결 설정
$host = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'spring'; // 데이터베이스 이름을 'spring'으로 설정

// MySQL 데이터베이스에 연결
$conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

// 연결 확인
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'error' => 'Database connection failed: ' . $conn->connect_error]));
}

// 폼 데이터 받기
$id = $_POST['id'];
$pass = $_POST['pass']; // 비밀번호는 해싱하는 것이 좋을 듯
$name = $_POST['name'];
$email = $_POST['email'];
$regist_day = date("Y-m-d H:i:s"); // 현재 날짜와 시간
$level = 1; // 기본 레벨 설정
$point = 0; // 기본 포인트 설정

// 입력값 검증
if (empty($id) || empty($pass) || empty($name)) {
    echo json_encode(['success' => false, 'error' => 'Please fill all the required fields.']);
    exit;
}

// SQL 쿼리 실행
$sql = "INSERT INTO members (id, pass, name, email, regist_day, level, point) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssii", $id, $pass, $name, $email, $regist_day, $level, $point);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Error while inserting data: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
   
