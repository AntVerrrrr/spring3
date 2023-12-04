<?php
// MySQL 데이터베이스 연결 설정
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'spring';

$conn = new mysqli($servername, $username, $password, $dbname);

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 글 목록 가져오기
$sql = "SELECT title, content, image_filename FROM posts";
$result = $conn->query($sql);

$posts = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
}

// MySQL 연결 종료
$conn->close();

// JSON 형식으로 데이터 반환
header('Content-Type: application/json');
echo json_encode($posts);
?>