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

// 게시글 불러오기 쿼리
$sql = "SELECT * FROM posts"; // 'posts'는 게시글 정보가 저장된 테이블
$result = $conn->query($sql);

$posts = [];

if ($result->num_rows > 0) {
    // 결과를 배열로 변환
    while($row = $result->fetch_assoc()) {
        array_push($posts, $row);
    }
}

echo json_encode($posts); // 게시글 목록을 JSON 형식으로 출력

$conn->close();
?>