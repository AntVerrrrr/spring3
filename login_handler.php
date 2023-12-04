<?php
session_start(); // 세션 시작

// 데이터베이스 연결 설정
$host = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'spring'; // 데이터베이스 이름

// MySQL 데이터베이스에 연결
$conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

// 연결 확인
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'error' => 'Database connection failed: ' . $conn->connect_error]));
}

// 폼 데이터 받기
$id = $_POST['id'];
$password = $_POST['pass'];

// 사용자 인증 쿼리 실행
$sql = "SELECT * FROM members WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();

// if ($row = $result->fetch_assoc()) {
//     // 비밀번호 검증
//     if ($password === $row['pass']) { // 'pass' 열 사용
//         // 인증 성공
//         $_SESSION['user_id'] = $row['id'];
//         echo json_encode(['success' => true]);
//     } else {
//         // 로그인 실패: 일반적인 에러 메시지
//         echo json_encode(['success' => false, 'error' => 'Invalid ID or password']);
//     }
// } else {
//     // 로그인 실패: 일반적인 에러 메시지
//     echo json_encode(['success' => false, 'error' => 'Invalid ID or password']);
// }
// 
if ($row = $result->fetch_assoc()) {
    if ($password === $row['pass']) {
        // 인증 성공
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['name']; // 사용자 이름을 세션에 저장

        echo json_encode(['success' => true, 'name' => $row['name']]); // 사용자 이름도 전송
    } else {
        // 로그인 실패
        echo json_encode(['success' => false, 'error' => 'Invalid ID or password']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid ID or password']);
}


$stmt->close();
$conn->close();
?>